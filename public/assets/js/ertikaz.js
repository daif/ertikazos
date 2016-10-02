
function convertSelectToSelect2(elem) {
    $options = {
        language: $locale,
        dir: ($locale == 'en') ? 'ltr':'rtl'
    };
    if($.isFunction(window['template_'+$(elem).attr('id')])) {
        $options.templateSelection  = window['template_'+$(elem).attr('id')];
        $options.templateResult     = window['template_'+$(elem).attr('id')];
    }
    $(elem).select2($options);
}

function convertSelectToSelect2Ajax(elem) {
    $options = {
        ajax: {
            url: $base_url + $router_dir + $router_class + '/ajax/' + $(elem).attr('id'),
            delay: 250,
            data: function (params) {
              return {
                q: params.term
              };
            },
            processResults: function (data) {
                return {
                    results: data.items
                };
            }
        },
        language: $locale,
        dir: ($locale == 'en') ? 'ltr':'rtl',
        minimumInputLength: 2
    };
    if($.isFunction(window['template_'+$(elem).attr('id')])) {
        $options.templateSelection  = window['template_'+$(elem).attr('id')];
        $options.templateResult     = window['template_'+$(elem).attr('id')];
    }
    $(elem).select2($options);
}

function convertInputToPicker(elem) {
    $options = {
        locale: $locale,
        format: 'YYYY-MM-DD'
    };
    if($(elem).hasClass('datetime')) {
        $options.format = 'YYYY-MM-DD hh:mm:ss';
    }
    if($(elem).hasClass('time')) {
        $options.format = 'hh:mm:ss';
    }
    if(typeof $(elem).data('format') !== 'undefined') {
        $options.format = $(elem).data('format');
    }
    
    $elem = $(elem).clone();
    $picker = $('<div class="input-group date" id="'+$elem.attr('id')+'_picker"></div>');
    $(elem).after($picker);
    $(elem).remove();
    $picker.append($elem);
    $picker.append('<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>');
    $('#'+$elem.attr('id')+'_picker').datetimepicker($options);

}

$(document).ready(function(){
    
    /*
     * confirm message 
     */

    $('body').on('mousedown', 'a[href*=delete],.btn-danger,.confirm', function(e) {
        $confirmTarget = $(this);
        if(!$confirmTarget.hasClass('isClickConfirmed')) {
            e.preventDefault();
            bootbox.confirm($msg_are_yousure, function(result) {
                if(result == true) {
                    //add isClickConfirmed to continue execution on click again. 
                    $confirmTarget.addClass('isClickConfirmed');
                    //click the target again
                    $confirmTarget.trigger("click");
                }
            });
        }
    });

    /*
     * apps checkbox
     */
    $('body').on('click', '.app>.checkbox label', function(e){
        if($(e.target).prop("tagName") == 'INPUT'){
            $('.children input', $(this).parents('.app')).prop('checked', $('.parent input',$(this).parents('.app')).prop('checked'));
        } else {
            if($('.children input', $(this).parents('.app')).length >0 ) {
                e.preventDefault();
                $('.children', $(this).parents('.app')).slideToggle();
            }
        }
    });
    $('body').on('click', '.app>.children label', function(e){
        if($(e.target).prop("tagName") == 'INPUT'){
            $('.parent input', $(this).parents('.app')).prop('checked', $('.children input', $(this).parents('.app')).is(':checked'));
        }
    });

    /*
     * link buttons
     */
    $('body').on('click', '.dropdown-menu li>a.submit', function(e){
        e.preventDefault();
        $(this).parents('form').submit();
    });

    /*
     * show/hide search box
     */ 
    if($('.searchBox').length == 0) {
        $('#btn-search').hide();
    }
    $('body').on('click', '#btn-search', function(e){
        e.preventDefault();
        $('.searchBox').slideToggle();
    });

    /*
     * rePOST for pagination
     */ 
    if($('.pagination input').length > 0) {
        $('body').on('click', '.pagination a[href]', function(e){
            e.preventDefault();
            $($(this)).parents('form').attr('action', $(this).attr('href'));
            $($(this)).parents('form').submit();
        });
    }

    /*
     * by default click first  in tabs
     */
    $('.tabpanel ul.nav li:first a').click();

    /*
     * convert any select to searchable select 
     */
     $('select:not(.ajax)').each(function(){
        convertSelectToSelect2(this);
     });

    /*
     * convert any select with ajax searchable select 
     */ 
     $('select.ajax').each(function(){
        convertSelectToSelect2Ajax(this);
     });

    /*
     * convert any date input to datepicker
     */ 
     $('input.date').each(function(){
        convertInputToPicker(this);
     });
     $('input.time').each(function(){
        convertInputToPicker(this);
     });
     $('input.datetime').each(function(){
        convertInputToPicker(this);
     });

    /*
     * convert textarea with editor class to  trumbowyg editor
     */ 
     //$('textarea.editor').each(function(){
     $('textarea').each(function(){
        $(this).trumbowyg({lang: $locale});
     });

});

