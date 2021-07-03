@for ($i = 0; $i < count($matches); $i++)
    {{-- // start lists --}}
    @if ($currentDepth != (int) $matches[$i][2])
        @for ($currentDepth; $currentDepth < (int) $matches[$i][2]; $currentDepth++)
            @php $numberedItems[$currentDepth + 1] = 0; @endphp
            <ul>
                <li>
        @endfor
    @else
        <li>
    @endif
    {{-- // list item --}}
    @if (in_array($matches[$i][2], $options['heading_levels']))
        <a href="#{{ $matches[$i][3] }}">
            @if ($options['ordered_list'])
                {{-- // attach leading numbers when lower in hierarchy --}}
                <span class="toc_number toc_depth_{{ $currentDepth - $numberedItemsMin + 1 }}">
                @for ($j = $numberedItemsMin; $j < $currentDepth; $j++)
                    {{ ($numberedItems[$j] ?: 0) . '.' }}
                @endfor
                {{ $numberedItems[$currentDepth] + 1 }} </span>
                @php
                    $numberedItems[$currentDepth]++;
                @endphp
            @endif
            {!! clean(strip_tags($matches[$i][0])) !!}
        </a>
    @endif
    {{-- // end lists --}}
    @if ($i != count($matches) - 1)
        @if ($currentDepth > (int) $matches[$i + 1][2])
            @for ($currentDepth; $currentDepth > (int) $matches[$i + 1][2]; $currentDepth--)
                </li>
                    </ul>
                @php
                    $numberedItems[$currentDepth] = 0;
                @endphp
            @endfor
        @endif
        @if ($currentDepth == (int) @$matches[$i + 1][2])
            </li>
        @endif
    @else
        {{-- // this is the last item, make sure we close off all tags --}}
        @for ($currentDepth; $currentDepth >= $numberedItemsMin; $currentDepth--)
            </li>
            @if ($currentDepth != $numberedItemsMin) {
                </ul>
            @endif
        @endfor
    @endif
@endfor
