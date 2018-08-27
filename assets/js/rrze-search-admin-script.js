// TODO: Complete Plugin JavaScript File
console.log('rrze-search-admin-script initiated!');

function rrze_resource_removal(resource_id) {
    let data = {
        'action': 'resourceRemoval',
        'resource_id': resource_id
    };

    jQuery.post(ajaxurl, data, function(success){
        if (success){ location.reload(); }
    });
}

jQuery(document).ready(function ($) {
    $('#rrze_search_add_resource_form').bind('click', function (e) {

        let template = document.getElementsByTagName("template")[0];

        let count = $('#rrze_search_resource_count').val();
        let partial = template.content.cloneNode(true);

        console.log(partial.querySelectorAll());

        $('#rrze_search_resource_form').append(partial);
        // $('#rrze_search_resource_form').append('<tr>' +
        //     '<td><input type="text" id="rrze_search_resources" name="rrze_search_settings[rrze_search_resources][' + count + '][resource_name]" value=""></td>' +
        //     '<td><input type="text" id="rrze_search_resources" name="rrze_search_settings[rrze_search_resources][' + count + '][resource_uri]" value=""></td>' +
        //     '<td><input type="text" id="rrze_search_resources" name="rrze_search_settings[rrze_search_resources][' + count + '][resource_key]" value=""></td>' +
        //     '<td>&nbsp;</td>' +
        //     '</tr>');
        $('#rrze_search_resource_count').val(parseInt(count) +1);
    });
});