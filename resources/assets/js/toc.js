$(() => {
    if (window.location.hash && $('.toc-container ul').length) {
        scrollToElement(window.location.hash)
    }
    $(document).on('click', '.toc-container ul a[href^="#"]', (e) => {
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
            window.location.hash = hash;
        }
    }

    const $tocTitle = $('.toc-container p.toc_title');

    function showToCContainer() {
        $tocTitle.find('a').html($tocTitle.data('hide-text'));
        $(".toc-container").addClass("contracted");
        $(".toc-container ul:first").show("fast");
    }

    function hideToCContainer() {
        $tocTitle.find('a').html($tocTitle.data('show-text'));
        $(".toc-container ul:first").hide("fast");
        $(".toc-container").removeClass("contracted");
    }

    // Default is Close
    let isVisibilityToC = localStorage.getItem('visibilityTextToC');
    if (isVisibilityToC == '1') {
        showToCContainer();
    }

    $(document).on('click', 'span.toc_toggle a', function (e) {
        e.preventDefault();
        const $this = $(e.currentTarget);
        const isOpen = $this.closest('.toc-container').hasClass('contracted');
        if (isOpen) {
            localStorage.setItem('visibilityTextToC', '0');
            hideToCContainer();
        } else {
            localStorage.setItem('visibilityTextToC', '1');
            showToCContainer();
        }
    });
});
