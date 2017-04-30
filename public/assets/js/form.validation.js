/*
 * Form Validation 0.3.1
 * Applying CodeIgniter rules in JavaScript 
 * http://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
 * 
 * Author: Daif Alotaibi 
 * Email: daif@daif.net
 * Website: http://daif.net/
 *  
 * Licensed under the MIT license. 
 * http://www.opensource.org/licenses/mit-license.php 
 */

$(document).ready(function(){
    $("form").submit(function(event){
        $form = $(this);
        $form.attr("validation", "true");
        UnSetFormInputErrorMessage($form);
        $('input[data-rules],textarea[data-rules],select[data-rules]', this).each(function(){
            $rules = $(this).data('rules').split('|');
            if($(this).data('rules').length > 0 && $rules.length > 0) {
                for(var $i=0; $i<$rules.length; $i++) {
                    $attr = [];
                    $func = 'CI_'+$rules[$i];
                    if(/.+\[(.+)\]/.test($rules[$i])) {
                        $attr = $rules[$i].match(/(.+)\[(.+)\]/);
                        $func = 'CI_'+$attr[1];
                    }
                    if (typeof window[$func] !== "undefined") {
                        if(!window[$func].call(undefined, this, $attr) && (/required/.test($(this).data('rules')) || $.trim($(this).val()) != '' )) {
                            $form.attr("validation", "false");
                            if($attr.length > 0) {
                                $message = ErrorMessageList($attr[1], $(this), $attr[2]);
                            } else {
                                $message = ErrorMessageList($rules[$i], $(this), '');
                            }
                            SetFormInputErrorMessage($(this), $message);
                        }
                    }
                }
            }
        });
        if($form.attr("validation") != "true") {
            event.preventDefault();
        }
    });
});

// Escapes special characters and returns a valid jQuery selector
function jqSelector(str) {
    return str.replace(/([;&,\.\+\*\~':"\!\^#$%@\[\]\(\)=>\|])/g, '\\$1');
}
function SetFormInputErrorMessage($input, $message) {
    $input.parent().addClass('has-error');
    $input.parent().append('<small class="help-block inerrmsg">'+ $message +'</small>');
}
function UnSetFormInputErrorMessage($form) {
    $('input,textarea,select', $form).each(function(){
        $('.inerrmsg', $(this).parent()).remove();
    });
    $('.has-error', $form).removeClass('has-error')
}

function SetFormInputErrorMessage2($input, $message) {
    if($input.parent().next('small.help-block').length == 0) {
        $input.parent().after('<small class="help-block">'+ $message +'</small>');
    } else {
        $input.parent().next('small.help-block').html($message);
    }
    $input.parent().parent().addClass('has-error');
}
function UnSetFormInputErrorMessage2($input) {
    $input.parent().next('small').remove();
}

function ErrorMessageList($id, $field, $param) {
    if(typeof $locales[$locale][$id] != "undefined") {
        $message = $locales[$locale][$id];
    } else if(typeof $locales['en'][$id] != "undefined"){
        $message = $locales['en'][$id];
    } else {
        $message = $locales['en']["error_message_not_set"];
    }
    // try to find input label 
    if($('[for='+jqSelector($field.attr('name').toString())+']').length >= 1) {
        $message = $message.replace('{field}', $('[for='+jqSelector($field.attr('name').toString())+']').html().toString());
    } else {
        $message = $message.replace('{field}', $field.attr('name').toString());
    }
    // try to find param input label 
    if($param.toString() != '' && $('[for='+jqSelector($param.toString())+']').length >= 1) {
        $message = $message.replace('{param}', $('[for='+jqSelector($param.toString())+']').html().toString());
    } else {
        $message = $message.replace('{param}', $param.toString());
    }
    return ($message);
}
//rule check functions 
function CI_required($el, $attr) {
    if($($el).is(':checkbox')) {
        return($($el).is(':checked'));
    } else if($($el).is(':radio')) {
        return($('[name="'+$($el).attr('name')+'"]:radio',$($el).parents("form")).is(':checked'));
    } else {
        return($.trim($($el).val()) != '');
    }
}
function CI_matches($el, $attr) {
    return($($el).val() == $('[name='+$attr[2]+']', $($el).parents('form')).val());
}
function CI_differs($el, $attr) {
    return($($el).val() != $('[name='+$attr[2]+']', $($el).parents('form')).val());
}
function CI_is_unique($el, $attr) {
    return(true);
}
function CI_min_length($el, $attr) {
    return($($el).val().length >= $attr[2]);
}
function CI_max_length($el, $attr) {
    return($($el).val().length < $attr[2]);
}
function CI_exact_length($el, $attr) {
    return($($el).val().length == $attr[2]);
}
function CI_greater_than($el, $attr) {
    return($($el).val() > $attr[2]);
}
function CI_greater_than_equal_to($el, $attr) {
    return($($el).val() >= $attr[2]);
}
function CI_less_than($el, $attr) {
    return($($el).val() < $attr[2]);
}
function CI_less_than_equal_to($el, $attr) {
    return($($el).val() <= $attr[2]);
}
function CI_in_list($el, $attr) {
    var $list = $attr[2].split(',');
    for(var $i=0; $i<$list.length; $i++) {
        if($($el).val() == $list[$i]) {
            return(true);
        }
    }
    return(false);
}
function CI_alpha($el, $attr) {
    return(/^[a-zA-Z]+$/.test($($el).val()));
}
function CI_alpha_numeric($el, $attr) {
    return(/^[a-zA-Z0-9]+$/.test($($el).val()));
}
function CI_alpha_numeric_spaces($el, $attr) {
    return(/^[a-zA-Z0-9-\s]+$/.test($($el).val()));
}
function CI_alpha_dash($el, $attr) {
    return(/^[a-zA-Z0-9-\-]+$/.test($($el).val()));
}
function CI_integer($el, $attr) {
    return(/^[0-9]+$/.test($($el).val()));
}
function CI_numeric($el, $attr) {
    return(/^[0-9]*\.?[0-9]+$/.test($($el).val()));
}
function CI_decimal($el, $attr) {
    return(/^(\d+|\d+,\d{1,2})$/.test($($el).val()));
}
function CI_is_natural($el, $attr) {
    return(/^(0|([1-9]\d*))$/.test($($el).val()));
}
function CI_is_natural_no_zero($el, $attr) {    
    return(/^([1-9]\d*)$/.test($($el).val()));
}
function CI_url($el, $attr) {
    return(/^(http|https)?:\/\/[a-zA-Z0-9-\.]+\.[a-z]{2,4}/.test($($el).val()));
}
function CI_valid_email($el, $attr) {
    return(/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test($($el).val()));
}
function CI_valid_emails($el, $attr) {
    var $list = $attr[2].split(',');
    for(var $i=0; $i<$list.length; $i++) {
        if(!CI_email($list[$i])) {
            return(false);
        }
    }
    return(true);
}
function CI_ip($el, $attr) {
    return(/^(?:(?:25[0-5]2[0-4][0-9][01]?[0-9][0-9]?)\.){3}(?:25[0-5]2[0-4][0-9][01]?[0-9][0-9]?)$/.test($($el).val()));
}
function CI_base64($el, $attr) {
    return(/^(?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=|[A-Za-z0-9+/]{4})$/.test($($el).val()));
}

var $locales = {
    ar : {
        required:'الحقل {field} إجباري.',
        isset:'الحقل {field} يجب أن يحتوى على قيمة.',
        valid_email:'الحقل {field} يجب ان يحتوى على عنوان بريد الكتروني صحيح.',
        valid_emails:'الحقل {field} يجب ان يحتوى على قائمة صحيحة بعناوين البريد الالكتروني.',
        url:'الحقل {field} يجب ان يحتوى على رابط صحيح.',
        ip:'الحقل {field} يجب ان يحتوى على رقم أي بي صحيح.',
        base64:'الحقل {field} يجب ان يحتوى على قيمة من نوع الترميز base64 .',
        min_length:'الحقل {field} يجب ان يحتوى على قيمة لايقل طولها عن {param} حرف.',
        max_length:'الحقل {field} يجب ان يحتوى على قيمة لايتجاوز طولها {param} حرف .',
        exact_length:'الحقل {field} يجب ان يحتوى على قيمة بطول {param} حرف.',
        alpha:'الحقل {field} field يجب ان يحتوى على حروف فقط.',
        alpha_numeric:'الحقل {field} يجب ان يحتوى على حروف وارقام فقط.',
        alpha_numeric_spaces:'الحقل {field} يحتوى على حروف و ارقام وفراغات فقط.',
        alpha_dash:'الحقل {field} يجب عن يحتوى على حروف او شرطة او شرطة سفلية فقط.',
        numeric:'الحقل {field} يجب ان يحتوى على ارقام فقط.',
        is_numeric:'الحقل {field} يجب ان يحتوى عر ارقام.',
        integer:'الحقل {field} يجب ان يحتوى على عدد.',
        regex_match:'الحقل {field} يحتوى على قيمة غير صحيحة.',
        matches:'الحقل {field} يجب ان يحتوى على قيمة مثل قيمة الحقل {param}.',
        differs:'الحقل {field} يجب ان يحتوى على قيمة مختلفة عن قيمة الحقل {param}.',
        is_unique:'الحقل {field} يجب ان يحتوى على قيمة غير موجودة مسبقا.',
        is_natural:'الحقل {field} يجب ان يحتوى على ارقام فقط.',
        is_natural_no_zero:'الحقل {field} يجب ان يحتوى على ارقام وأكبر من صفر.',
        decimal:'الحقل {field} يجب ان يحتوى على عدد عشري.',
        less_than:'الحقل {field} يجب ان يحتوى على قيمة اقل من {param}.',
        less_than_equal_to:'الحقل {field} يجب ان يحتوى على قيمة اقل من او يساوي {param}.',
        greater_than:'الحقل {field} يجب ان يحتوى على قيمة اكبر من {param}.',
        greater_than_equal_to:'الحقل {field} يجب ان يحتوى على قيمة اكبر من او يساوي {param}.',
        in_list:'الحقل {field} يجب ان يحتوى على احد القيم التالية: {param}.',
        error_message_not_set:'يوجد خطأ غير محدد في {field}.'
    },
    en : {
        required:'The {field} field is required.',
        isset:'The {field} field must have a value.',
        valid_email:'The {field} field must contain a valid email address.',
        valid_emails:'The {field} field must contain all valid email addresses.',
        url:'The {field} field must contain a valid URL.',
        ip:'The {field} field must contain a valid IP.',
        base64:'The {field} field must contain a valid base64 encode.',
        min_length:'The {field} field must be at least {param} characters in length.',
        max_length:'The {field} field cannot exceed {param} characters in length.',
        exact_length:'The {field} field must be exactly {param} characters in length.',
        alpha:'The {field} field may only contain alphabetical characters.',
        alpha_numeric:'The {field} field may only contain alpha-numeric characters.',
        alpha_numeric_spaces:'The {field} field may only contain alpha-numeric characters and spaces.',
        alpha_dash:'The {field} field may only contain alpha-numeric characters, underscores, and dashes.',
        numeric:'The {field} field must contain only numbers.',
        is_numeric:'The {field} field must contain only numeric characters.',
        integer:'The {field} field must contain an integer.',
        regex_match:'The {field} field is not in the correct format.',
        matches:'The {field} field does not match the {param} field.',
        differs:'The {field} field must differ from the {param} field.',
        is_unique:'The {field} field must contain a unique value.',
        is_natural:'The {field} field must only contain digits.',
        is_natural_no_zero:'The {field} field must only contain digits and must be greater than zero.',
        decimal:'The {field} field must contain a decimal number.',
        less_than:'The {field} field must contain a number less than {param}.',
        less_than_equal_to:'The {field} field must contain a number less than or equal to {param}.',
        greater_than:'The {field} field must contain a number greater than {param}.',
        greater_than_equal_to:'The {field} field must contain a number greater than or equal to {param}.',
        in_list:'The {field} field must be one of: {param}.',
        error_message_not_set:'Unable to access an error message corresponding to your field name {field}.'
    },
};