/**
 * Title to Sidebar plugin for Craft CMS
 *
 * @author    Josh Smith
 * @copyright Copyright (c) 2019 Josh Smith
 * @link      https://www.joshsmith.dev
 * @package   Title to Sidebar
 * @since     1.1.0
 */
;(function($){

    if( window.titleToSidebar == null || window.titleToSidebar.hasTitleField == null || window.titleToSidebar.hasTitleField === false ) return;

    var target = '#settings';

    if( window.titleToSidebar.entryType == 'product' ){
        $('#title-field').remove();
        var target = '#details .meta:first-of-type';
    }

    $(target).prepend(
        '<div class="field" id="title-sidebar">' +
            '<div class="heading">' +
                '<label id="title-label" for="title">'+(window.titleToSidebar.titleLabel || '')+'</label>' +
            '</div>' +
            '<div class="input ltr">' +
                '<input class="text fullwidth" type="text" id="title" name="title" value="'+(window.titleToSidebar.titleName || '')+'" autocomplete="off" autocorrect="off" autocapitalize="off" placeholder="Enter title">' +
            '</div>' +
        '</div>'
    );

    if( window.titleToSidebar.hasTitleError ){
        Craft.ui.addErrorsToField($('#title-sidebar'), [window.titleToSidebar.titleError]);
    }

    if( window.titleToSidebar.errors ){
        $.each(window.titleToSidebar.errors, function(attribute, errors){
            var $fieldInput = $('[name="fields['+attribute+']"]');
            if( $fieldInput.length ){
                var $field = $fieldInput.parents('.field').eq(0);
                Craft.ui.addErrorsToField($field, errors);
            }
        });
    }









})(jQuery);

