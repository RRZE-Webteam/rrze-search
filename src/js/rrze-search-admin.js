/**
 * Removes a multisearch resource via AJAX and reloads the settings page.
 *
 * @param {number} resourceId Index of the resource to remove.
 * @return {void}
 */
const rrzeResourceRemoval = (resourceId) => {
    const data = {
        action: 'resourceRemoval',
        resource_id: resourceId,
    };

    jQuery.post(ajaxurl, data, (success) => {
        if (success) {
            window.location.reload();
        }
    });
};

window.rrze_resource_removal = rrzeResourceRemoval;

jQuery(document).ready(function ($) {
    $('#rrze_search_add_resource_form').bind('click', function (e) {
        /** define resource count */
        var count = $('#rrze_search_resource_count').val();
        /** define unique Id */
        var uId = 'rrze_' + Math.random();

        /** selected template content */
        var template = document.getElementsByTagName('template')[0];

        /** replace `index` with current count */
        /** replace `uid` with unique Id */
        var partial = template.innerHTML.replace(/index/g, count).replace(/uid/g, uId);

        /** Append the partial */
        $('#rrze_search_resource_form tbody').append(partial);

        /** increment the count */
        $('#rrze_search_resource_count').val(parseInt(count) + 1);
    });
});
