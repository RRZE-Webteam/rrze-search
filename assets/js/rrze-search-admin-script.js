function rrze_resource_removal(resource_id) {
    var data = {
        'action': 'resourceRemoval',
        'resource_id': resource_id
    };

    jQuery.post(ajaxurl, data, function (success) {
        if (success) {
            location.reload();
        }
    });
}

jQuery(document).ready(function ($) {
    $('#rrze_search_add_resource_form').bind('click', function (e) {
        /** define resource count */
        var count = $('#rrze_search_resource_count').val();
        /** define unique Id */
        var uId = 'rrze_'+Math.random();

        /** selected template content */
        var template = document.getElementsByTagName("template")[0];

        /** replace `index` with current count */
        /** replace `uid` with unique Id */
        var partial = template.innerHTML.replace(/index/g, count).replace(/uid/g, uId);

        /** Append the partial */
        $('#rrze_search_resource_form tbody').append(partial);

        /** increment the count */
        $('#rrze_search_resource_count').val(parseInt(count) + 1);
    });
});