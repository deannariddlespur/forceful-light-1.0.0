/*
* Post rating for dynamically generate rating fields in post meta box
* version 1.0 - 24/09/2013
*/

(function(){
    jQuery('#kopa-rating-add').on('click', function(e){
        e.preventDefault();
        var fieldWrapperList = jQuery('#kopa-rating-wrapper .kopa-field-wrapper'),
            intId = fieldWrapperList.length ? fieldWrapperList.last().data('id') + 1 : 0,
            fieldWrapper = jQuery('<p id="kopa-rating-field-'+intId+'" class="kopa-field-wrapper" data-id="'+intId+'">'),
            fieldLabel = jQuery('<label for="">Rating Name: </label>'),
            fieldInput = jQuery('<input name="kopa_editor_post_rating['+intId+'][name]" type="text">'),
            fieldSelect = jQuery('<select name="kopa_editor_post_rating['+intId+'][value]">'+
                '<option value="1">1 Star(s)</option>'+
                '<option value="2">2 Star(s)</option>'+
                '<option value="3">3 Star(s)</option>'+
                '<option value="4">4 Star(s)</option>'+
                '<option value="5">5 Star(s)</option>'+
                '</select>'),
            removeButton = jQuery('<button class="button kopa-remove-rating">Remove</button>');
        
        // remove event for dynamic added content
        removeButton.on('click', function(e){
            e.preventDefault();

            if ( window.confirm('Are you sure you want to remove this?') ) {
                jQuery(this).parent().remove();
            }
        });
        
        fieldWrapper.append(fieldLabel)
            .append(fieldInput)
            .append(fieldSelect)
            .append(removeButton);
        
        jQuery('#kopa-rating-wrapper').append(fieldWrapper);
        
    });
    
    jQuery('#kopa-rating-remove-all').on('click', function(e){
        e.preventDefault();
        if ( window.confirm('Are you sure you want to remove all ratings?') ) {
            jQuery('#kopa-rating-wrapper').children().remove();
        }
    });
    
    jQuery('.kopa-remove-rating').on('click', function(e){
        e.preventDefault();
        if ( window.confirm('Are you sure you want to remove this?') ) {
            jQuery(this).parent().remove();
        }
    });
}());