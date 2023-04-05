$(function () {
    const tocSelector = '.toc-container';
    const $tocContainer = $(tocSelector);

    const $tocTitle = $tocContainer.find('.toc_title');

    setTimeout(() => {
        if (window.location.hash && $tocContainer.find('ul a[href="' + window.location.hash + '"]').length) {
            if ($('html')[0].scrollTop == 0) {
                scrollToElement(window.location.hash);
            }
        }
    }, 500)

    $(document).on('click', tocSelector + ' ul a[href^="#"]', (e) => {
        e.preventDefault();
        const $this = $(e.currentTarget);
        scrollToElement($this.attr('href'))
    });

    function scrollToElement(hash) {
        const $target = $('span.' + hash.replace('#', ''));
        if ($target.length) {
            let offset = 20;

            let $adminBar = $('#admin_bar');

            if ($adminBar.length > 0 && $adminBar.is(':visible')) {
                offset += $adminBar.height();
            }

            $([document.documentElement, document.body]).animate({
                scrollTop: $target.offset().top - offset
            }, 1000);

            if (hash !== window.location.hash) {
                window.location.hash = hash;
            }
        }
    }

    function showToCContainer() {
        $tocTitle.find('.toc_toggle.hide-text').removeClass('d-none');
        $tocTitle.find('.toc_toggle.show-text').addClass('d-none');
        $tocContainer.addClass('contracted');
        $tocContainer.find('ul:first').show('fast');
    }

    function hideToCContainer() {
        $tocTitle.find('.toc_toggle.show-text').removeClass('d-none');
        $tocTitle.find('.toc_toggle.hide-text').addClass('d-none');
        $tocContainer.find('ul:first').hide('fast');
        $tocContainer.removeClass('contracted');
    }

    // Default is Close
    let isVisibilityToC = localStorage.getItem('visibilityTextToC');
    if (isVisibilityToC == '1') {
        showToCContainer();
    } else {
        hideToCContainer();
    }

    $(document).on('click', tocSelector + ' span.toc_toggle a', function (e) {
        e.preventDefault();
        const $this = $(e.currentTarget);
        const isOpen = $this.closest(tocSelector).hasClass('contracted');
        if (isOpen) {
            localStorage.setItem('visibilityTextToC', '0');
            hideToCContainer();
        } else {
            localStorage.setItem('visibilityTextToC', '1');
            showToCContainer();
        }
    });
});
