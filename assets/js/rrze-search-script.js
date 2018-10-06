jQuery(document).ready(function ($) {
    var keyHandle;
    var tabHandle;
    var disabledHandle;
    var hiddenHandle;
    var focusedElementBeforeDialogOpened;
    var dialog = document.getElementById('search-header');
    var panel = document.getElementById('search-panel');
    var toggle = document.getElementById('search-toggle');
    var searchinput = document.getElementById('headsearchinput');
    var backdrop;
    var $body = $('body');

    /**
     * Open the search modal
     *
     * @param {Function} callback Callback function (optional)
     */
    function openDialog(callback) {
        // focusedElementBeforeDialogOpened = document.activeElement;
        ally.when.visibleArea({ context: dialog, callback: callback || null });

        // Make all elements outside the dialog non-interactive, hide them from the accessibility tree and trap the tab focus
        disabledHandle = ally.maintain.disabled({ filter: [dialog, toggle, backdrop] });
        hiddenHandle = ally.maintain.hidden({ filter: dialog });
        tabHandle = ally.maintain.tabFocus({ context: dialog });
        keyHandle = ally.when.key({ escape: closeDialogByKey/*, enter: closeDialogByKey*/ });

        // Show the search panel
        $(panel).prop('hidden', false);
    }

    /**
     * Close the search modal by key (<ESC>)
     */
    function closeDialogByKey() {
        searchinput.blur();
        setTimeout(collapseSearch);
    }

    /**
     * Close the search
     *
     * @param {Event} e Event
     * @param {bool} toggleBtn Via toggle button
     */
    function closeDialog(e, toggleBtn) {
        keyHandle.disengage(); // undo listening to keyboard
        tabHandle.disengage(); // undo trapping Tab key focus
        hiddenHandle.disengage(); // undo hiding elements outside of the dialog
        disabledHandle.disengage(); // undo disabling elements outside of the dialog

        // If the dialog wasn't closed via the toggle button: hide the search dialog option panel
        if (!toggleBtn) {
            $(panel).prop('hidden', true);
        }
    }

    /**
     * Keyboard-enable the privacy policy / instruction for the current search engine (and disable all others)
     */
    function toggleDisclaimer() {
        $this = $(this);
        $this.closest('[role=radiogroup]').find('[tabindex]').attr('tabindex', -1);
        $this.closest('label').find('a').add(this).attr('tabindex', this.checked ? 1 : -1);
    }

    /**
     * Callback to focus the first tabbable form element inside a context
     *
     * @param {Element} context Context element
     */
    var focusOnVisible = function (context) {
        var element = ally.query.firstTabbable({
            context: context, // context === dialog
            defaultToContext: true,
        });
        if (element) {
            element.focus();
            element.select();
        }
    };

    /**
     * Override the default search toggle method to open / close the search modal
     *
     * @param {Boolean} onOff Enable / disable the search panel
     * @private
     */
    toggle._toggleSearch = function (onOff) {
        this._expanded = onOff;
        $body.toggleClass('search-toggled', this._expanded);
        this.setAttribute('aria-expanded', this._expanded ? 'true' : 'false');
        if (this._expanded) {
            openDialog(focusOnVisible);
        } else {
            closeDialog(null, this);
        }
    }

    /**
     * Collapse the search modal
     */
    function collapseSearch() {
        toggle._toggleSearch(false);
    }

    // $(searchinput).focus(openDialog);
    $(dialog).submit(collapseSearch);
    $('.search-engine').click(toggleDisclaimer);

    // Create and enable the dialog backdrop
    var $backdrop = $('<div id="search-backdrop"/>').click(collapseSearch);
    $('#meta').before($backdrop);
    backdrop = $backdrop[0];
});