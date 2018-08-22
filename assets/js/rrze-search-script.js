jQuery(document).ready(function ($) {
    var keyHandle;
    var tabHandle;
    var disabledHandle;
    var hiddenHandle;
    var focusedElementBeforeDialogOpened;
    var dialog = document.getElementById('search-header');
    var panel = document.getElementById('search-panel');
    var toggle = document.getElementById('search-toggle');
    var backdrop;

    function openDialog() {
        // Remember the focused element before we opened the dialog
        // so we can return focus to it once we close the dialog.
        // focusedElementBeforeDialogOpened = document.activeElement;

        // We're using a transition to reveal the dialog,
        // so wait until the element is visible, before
        // finding the first keyboard focusable element
        // and passing focus to it, otherwise the browser
        // might scroll the document to reveal the element
        // receiving focus
        ally.when.visibleArea({
            context: dialog,
            callback: function (context) {
                // the dialog is visible on screen, so find the first
                // keyboard focusable element (giving any element with
                // autofocus attribute precendence). If the dialog does
                // not contain any keyboard focusabe elements, focus will
                // be given to the dialog itself.
                var element = ally.query.firstTabbable({
                    context: context, // context === dialog
                    defaultToContext: true,
                });
                element.focus();
            },
        });

        // Make sure that no element outside of the dialog
        // can be interacted with while the dialog is visible.
        // This means we don't have to handle Tab and Shift+Tab,
        // but can defer that to the browser's internal handling.
        disabledHandle = ally.maintain.disabled({
            filter: [dialog, toggle, backdrop],
        });

        // Make sure that no element outside of the dialog
        // is exposed via the Accessibility Tree, to prevent
        // screen readers from navigating to content it shouldn't
        // be seeing while the dialog is open. See example:
        // https://marcysutton.com/slides/mobile-a11y-seattlejs/#/36
        hiddenHandle = ally.maintain.hidden({
            filter: dialog,
        });

        // Make sure that Tab key controlled focus is trapped within
        // the tabsequence of the dialog and does not reach the
        // browser's UI, e.g. the location bar.
        tabHandle = ally.maintain.tabFocus({
            context: dialog,
        });

        // React to enter and escape keys as mandated by ARIA Practices
        keyHandle = ally.when.key({
            escape: closeDialogByKey,
            // Note: in non-interactive content you would also bind the enter
            // key to close the dialog. In our example the form's submit event
            // will cover that instead. The enter handler would be executed
            // for *every* press of the enter key, even if the focused element
            // is a button (which would invoke the default action). Try using
            // a <form> for interactive content to get around that problem.
            // enter: closeDialogByKey,
        });

        // Show the search panel
        $(panel).prop('hidden', false);
        $('body').addClass('search-toggled');
    }

    function closeDialogByKey() {
        // we need to let the keyboard event handlers finish,
        // before actually closing the dialog. Otherwise the
        // keydown of <kbd>enter</kbd> will close the dialog,
        // focus is passed back to the open-dialog-button and
        // the keyup of <kbd>enter</kbd> will open the dialog
        // again.
        setTimeout(closeDialog);
        // alternatively we could've called event.preventDefault()
        // and then run closeDialog() synchronously
    }

    /**
     * Close the dialog
     *
     * @param {Event} e Event
     * @param {bool} toggleBtn Via toggle button
     */
    function closeDialog(e, toggleBtn) {
        // undo listening to keyboard
        keyHandle.disengage();
        // undo trapping Tab key focus
        tabHandle.disengage();
        // undo hiding elements outside of the dialog
        hiddenHandle.disengage();
        // undo disabling elements outside of the dialog
        disabledHandle.disengage();

        if (!toggleBtn) {
            // return focus to where it was before we opened the dialog
            // if (focusedElementBeforeDialogOpened) {
            //     focusedElementBeforeDialogOpened.focus();
            // } else if (document.activeElement) {
            //     document.activeElement.blur();
            // }

            // Hide the dialog
            $(panel).prop('hidden', true);
            $('body').removeClass('search-toggled');
        }

        $('#search-toggle').attr('aria-expanded', 'false');
        $('body').removeClass('search-toggled');
    }

    // function saveDialog(event) {
    //     // do not submit the form
    //     // event.preventDefault();
    //
    //     // do something with the entered data
    //     var name = dialog.querySelector('#headsearchinput').value;
    //     console.log('entered terms', name);
    //
    //     closeDialog();
    // }

    function toggleDisclaimer() {
        $this = $(this);
        $this.closest('[role=radiogroup]').find('[tabindex]').attr('tabindex', -1);
        $this.closest('label').find('a').add(this).attr('tabindex', this.checked ? 1 : -1);
    }

    $('#search-toggle').bind('click', function (e) {
        if ($('body').hasClass('search-toggled')) {
            closeDialog(e, true);
        }
    });

    $('#headsearchinput').focus(openDialog);
    $(dialog).submit(closeDialog, true);
    $('.search-engine').click(toggleDisclaimer); //.blur(disableDisclaimer);
    var $backdrop = $('<div id="search-backdrop"/>').click(closeDialog);
    $('#meta').before($backdrop);
    backdrop = $backdrop[0];
});