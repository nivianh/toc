<?php

namespace Botble\ToC;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ToCHelper
{

    /**
     * array
     */
    protected $options;

    /**
     * @var array
     */
    protected $collisionCollector;

    /**
     * ToC constructor.
     */
    public function __construct()
    {
        $this->options = config('plugins.toc.general', []);
        $this->theTag = '<!--ToC-->';
    }

    /**
     * @param string $content
     */
    public function theContent($content)
    {
        $customToCPosition = strpos($content, $this->theTag);
        $find = [];
        $replace = [];
        $items = $this->extractHeadings($find, $replace, $content);

        if ($items) {

            $cssClasses = trim(Arr::get($this->options, 'css_container_class'));

            // Add container, toc title and list items
            $html = view('plugins/toc::container', compact('cssClasses', 'items'))->render();

            if ($customToCPosition !== false) {
                $find[] = $this->theTag;
                $replace[] = $html;
                $content = $this->mbFindReplace($find, $replace, $content);
            } else {
                if (count($find) > 0) {
                    switch (Arr::get($this->options, 'option')) {
                        case 'top':
                            $content = $html . $this->mbFindReplace($find, $replace, $content);
                            break;
                        case 'bottom':
                            $content = $this->mbFindReplace($find, $replace, $content) . $html;
                            break;
                        case 'after-first-heading':
                            $replace[0] = $replace[0] . $html;
                            $content = $this->mbFindReplace($find, $replace, $content);
                            break;
                        case 'before-first-heading':
                        default:
                            $replace[0] = $html . $replace[0];
                            $content = $this->mbFindReplace($find, $replace, $content);
                    }
                }
            }
        }

        return $content;
    }

    /**
     * Returns a string with all items from the $find array replaced with their matching
     * items in the $replace array.  This does a one to one replacement (rather than
     * globally).
     *
     * This function is multibyte safe.
     *
     * $find and $replace are arrays, $string is the haystack.  All variables are
     * passed by reference.
     */
    private function mbFindReplace(&$find = false, &$replace = false, &$string = '')
    {
        if (is_array($find) && is_array($replace) && $string) {
            // check if multibyte strings are supported
            if (function_exists('mb_strpos')) {
                for ($i = 0; $i < count($find); $i++) {
                    $string =
                        mb_substr($string, 0, mb_strpos($string, $find[$i])) . // everything before $find
                        $replace[$i] . // its replacement
                        mb_substr($string, mb_strpos($string, $find[$i]) + mb_strlen($find[$i])) // everything after $find
                    ;
                }
            } else {
                for ($i = 0; $i < count($find); $i++) {
                    $string = substr_replace(
                        $string,
                        $replace[$i],
                        strpos($string, $find[$i]),
                        strlen($find[$i])
                    );
                }
            }
        }

        return $string;
    }

    /**
     * This function extracts headings from the html formatted $content.  It will pull out
     * only the required headings as specified in the options.  For all qualifying headings,
     * this function populates the $find and $replace arrays (both passed by reference)
     * with what to search and replace with.
     *
     * Returns a html formatted string of list items for each qualifying heading.  This
     * is everything between and NOT including <ul> and </ul>
     */
    public function extractHeadings(&$find, &$replace, $content = '')
    {
        $matches = [];
        $items = false;

        // reset the internal collision collection as the_content may have been triggered elsewhere
        // eg by themes or other plugins that need to read in content such as metadata fields in
        // the head html tag, or to provide descriptions to twitter/facebook
        $this->collisionCollector = [];

        if (is_array($find) && is_array($replace) && $content) {
            // get all headings
            // the html spec allows for a maximum of 6 heading depths
            if (preg_match_all('/(<h([1-6]{1})[^>]*>).*<\/h\2>/msuU', $content, $matches, PREG_SET_ORDER)) {
                // remove undesired headings (if any) as defined by heading_levels
                if (count(Arr::get($this->options, 'heading_levels')) != 6) {
                    $newMatches = [];
                    for ($i = 0; $i < count($matches); $i++) {
                        if (in_array($matches[$i][2], Arr::get($this->options, 'heading_levels'))) {
                            $newMatches[] = $matches[$i];
                        }

                    }
                    $matches = $newMatches;
                }

                // remove specific headings if provided via the 'exclude' property
                if (Arr::get($this->options, 'exclude')) {
                    $excludedHeadings = explode('|', Arr::get($this->options, 'exclude'));
                    if (count($excludedHeadings) > 0) {
                        for ($j = 0; $j < count($excludedHeadings); $j++) {
                            // escape some regular expression characters
                            // others: http://www.php.net/manual/en/regexp.reference.meta.php
                            $excludedHeadings[$j] = str_replace(
                                ['*'],
                                ['.*'],
                                trim($excludedHeadings[$j])
                            );
                        }

                        $newMatches = [];
                        for ($i = 0; $i < count($matches); $i++) {
                            $found = false;
                            for ($j = 0; $j < count($excludedHeadings); $j++) {
                                if (@preg_match('/^' . $excludedHeadings[$j] . '$/imU', strip_tags($matches[$i][0]))) {
                                    $found = true;
                                    break;
                                }
                            }
                            if (!$found) {
                                $newMatches[] = $matches[$i];
                            }
                        }
                        if (count($matches) != count($newMatches)) {
                            $matches = $newMatches;
                        }

                    }
                }

                // remove empty headings
                $newMatches = [];
                for ($i = 0; $i < count($matches); $i++) {
                    if (trim(strip_tags($matches[$i][0])) != false) {
                        $newMatches[] = $matches[$i];
                    }

                }
                if (count($matches) != count($newMatches)) {
                    $matches = $newMatches;
                }

                // check minimum number of headings
                if (count($matches) >= Arr::get($this->options, 'start')) {
                    for ($i = 0; $i < count($matches); $i++) {
                        // get anchor and add to find and replace arrays
                        $anchor = $this->urlAnchorTarget($matches[$i][0]);
                        $matches[$i][3] = $anchor;

                        $find[] = $matches[$i][0];
                        $replace[] = str_replace(
                            [
                                $matches[$i][1], // start of heading
                                '</h' . $matches[$i][2] . '>', // end of heading
                            ],
                            [
                                $matches[$i][1] . '<span class="' . $anchor . '">',
                                '</span></h' . $matches[$i][2] . '>',
                            ],
                            $matches[$i][0]
                        );

                        // assemble flat list
                        if (!Arr::get($this->options, 'show_heirarchy')) {
                            $items .= '<li><a href="#' . $anchor . '">';
                            if (Arr::get($this->options, 'ordered_list')) {
                                $items .= count($replace) . ' ';
                            }

                            $items .= strip_tags($matches[$i][0]) . '</a></li>';
                        }
                    }

                    // build a hierarchical toc?
                    // we could have tested for $items but that var can be quite large in some cases
                    if (Arr::get($this->options, 'show_heirarchy')) {
                        $items = $this->buildHierarchy($matches);
                    }

                }
            }
        }

        return $items;
    }
    /**
     * @param array $matches
     * @return string
     */
    private function buildHierarchy(&$matches)
    {
        $currentDepth = 100; // headings can't be larger than h6 but 100 as a default to be sure
        $numberedItems = [];

        // find the minimum heading to establish our baseline
        for ($i = 0; $i < count($matches); $i++) {
            if ($currentDepth > $matches[$i][2]) {
                $currentDepth = (int) $matches[$i][2];
            }
        }

        $numberedItems[$currentDepth] = 0;
        $numberedItemsMin = $currentDepth;
        $options = $this->options;

        $compact = compact('currentDepth', 'numberedItems', 'numberedItemsMin', 'options', 'matches');
        $html = view('plugins/toc::toc', $compact)->render();

        return $html;
    }

    /**
     * Returns a clean url to be used as the destination anchor target
     */
    protected function urlAnchorTarget($title)
    {
        $return = false;

        if ($title) {
            $return = html_entity_decode(strip_tags($title));
            $return = Str::slug($return);

            // lowercase everything?
            if (Arr::get($this->options, 'lowercase')) {
                $return = strtolower($return);
            }

            // if blank, then prepend with the fragment prefix
            // blank anchors normally appear on sites that don't use the latin charset
            if (!$return) {
                $return = Arr::get($this->options, 'fragment_prefix') ?: '_';
            }

            // hyphenate?
            if (Arr::get($this->options, 'hyphenate')) {
                $return = str_replace('_', '-', $return);
                $return = str_replace('--', '-', $return);
            }
        }

        if (array_key_exists($return, $this->collisionCollector)) {
            $this->collisionCollector[$return]++;
            $return .= '-' . $this->collisionCollector[$return];
        } else {
            $this->collisionCollector[$return] = 1;
        }

        return $return;
    }

    /**
     * @param string | array $model
     * @return $this
     */
    public function registerModule($model)
    {
        if (!is_array($model)) {
            $model = [$model];
        }

        $supported = array_merge($this->supportedModels(), $model);

        config(['plugins.toc.general.supported' => $supported]);

        $this->options['supported'] = $supported;

        return $this;
    }

    /**
     * @return array
     */
    public function supportedModels(): array
    {
        return config('plugins.toc.general.supported', []);
    }

    /**
     * @return bool
     */
    public function isSupportedModel(string $model): bool
    {
        return in_array($model, $this->supportedModels());
    }

    /**
     * @param string $model
     * @return $this
     */
    public function unregisterModule(string $model)
    {
        $supported = $this->supportedModels();

        if (($key = array_search($model, $supported)) !== false) {
            unset($supported[$key]);
        }

        config(['plugins.toc.general.supported' => $supported]);

        $this->options['supported'] = $supported;

        return $this;
    }

    /**
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config)
    {
        $options = array_merge($this->options, $config);

        config(['plugins.toc.general' => $options]);

        $this->options = $options;

        return $this;
    }

    /**
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function config($key = null, $default = null)
    {
        $options = $this->options;

        if ($key) {
            $options = Arr::get($options, $key, $default);
        }

        return $options;
    }
}
