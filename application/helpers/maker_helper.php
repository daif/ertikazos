<?php
/**
 *
 * Ertikaz Form Helpers
 *
 * @package     Ertikaz
 * @subpackage  Helpers
 * @category    Helpers
 */
defined('BASEPATH') OR exit('No direct script access allowed');

// ------------------------------------------------------------------------

if ( ! function_exists('make_form_open'))
{
    /**
     * make form opening tag
     *
     * @param   string  $action Form action/target URI string
     * @param   array   $attributes HTML attributes
     * @param   array   $hidden An array of hidden fields’ definitions
     * @return  string  An HTML form opening tag
     */
    function make_form_open($action='', $attributes = '', $hidden = array())
    {
        //auto role attribute to form
        if(is_array($attributes)) {
            $attributes['role'] =  'form';
        } else {
            $attributes .= ' role="form" ';
        }
        //auto add csrf token
        if(is_array($hidden))
        {
            $hidden[get_instance()->security->get_csrf_token_name()] = get_instance()->security->get_csrf_hash();
        }
        else
        {
            $hidden = array(get_instance()->security->get_csrf_token_name()=>get_instance()->security->get_csrf_hash());
        }
        return form_open_multipart($action, $attributes, $hidden);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('make_form_actions'))
{
    /**
     * make form actions buttons
     *
     * @param   object  $row object of the record
     * @param   array  $buttons array of submit buttons
     * @param   boolean $append array of submit buttons
     * @return  string An HTML buttons tag
     */
    function make_form_actions($row, $buttons='', $append = FALSE)
    {
        $buttons_list = $row->action_buttons();

        if(is_array($buttons))
        {
            if($append)
            {
                $buttons_list = array_merge($buttons_list, $buttons);
            }
            else
            {
                $buttons_list = $buttons;
            }
        }
        // remove unaccessible buttons 
        foreach ($buttons_list as $name => $button) {
            //if(!isset($row->forms[$name]) || !$row->hasPermission($name))
            if(!$row->hasPermission($name))
            {
                unset($buttons_list[$name]);
            }
        }

        $return = '';
        $return .= '<div class="btn-group">'."\n";
        $return .= '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.lang('action').' <span class="caret"></span></button>'."\n";
        $return .= '<ul role="menu" class="dropdown-menu">'."\n";
        foreach ($buttons_list as $name => $button) {
            //if(isset($row->forms[$name]) && $row->hasPermission($name))
            if($row->hasPermission($name))
            {
                if($button['method'] == 'post')
                {
                    $return .= make_form_open($button['url'].'/'.$name);
                    $return .= '<input type="hidden" name="'.$row->getKeyName().'" value="'.$row->getKey().'" />'."\n";
                    $return .= ' <li><a href="'.base_url($button['url']).'" class="submit"><i class="'.$button['class'].'"></i> '.lang(strtolower($name)).'</a></li> '."\n";
                    $return .= make_form_close();
                }
                else
                {
                    $return .= ' <li><a href="'.base_url($button['url'].'/'.$row->getKey()).'"><i class="'.$button['class'].'"></i> '.lang(strtolower($name)).'</a></li> '."\n";
                }
            }
        }
        $return .= '</ul>'."\n";
        $return .= '</div>'."\n";
        return $return;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('make_search_form'))
{
    /**
     * make search form
     *
     * @return  string An HTML form
     */
    function make_search_form($path='', $input)
    {
        
        $return  = '<div class="panel panel-default searchBox" style="display:none">'."\n";
        $return .= '<div class="panel-body">'."\n";
        $return .= make_form_open($path);
        foreach ($input as $key => $input)
        {
            $return .= '<div class="form-group">'.make_input($input).'</div>'."\n";
        }
        $return .= '<button class="btn btn-default" name="search" type="submit">'.lang('search').'</button>'."\n";
        $return .= make_form_close();
        $return .= '</div>'."\n";
        $return .= '</div>'."\n";
        
        return $return;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('make_form_submit'))
{
    /**
     * make form submit and reset buttons
     *
     * @param   array  $buttons array of submit buttons
     * @param   boolean $append array of submit buttons
     * @return  string An HTML buttons tag
     */
    function make_form_submit($buttons=NULL, $append=FALSE)
    {
        $buttons_list = array(
            array('type' => 'Submit', 'name'=>'Submit', 'class'=>'btn-primary'),
            array('type' => 'Reset', 'name'=>'Reset', 'class'=>'btn-warning'),
        );
        if(is_array($buttons))
        {
            if($append)
            {
                $buttons_list = array_merge($buttons_list, $buttons);
            }
            else
            {
                $buttons_list = $buttons;
            }
        }
        $return = '';
        foreach ($buttons_list as $key => $button) {
            $return .= '<button class="btn '.$button['class'].'" type="'.$button['type'].'">'.lang(strtolower($button['name'])).'</button> ';
        }
        return $return;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('make_form_close'))
{
    /**
     * make form closing tag
     *
     * @param   string  $extra Anything to append after the closing tag, as is
     * @return  string  An HTML form closing tag
     */
    function make_form_close($extra='')
    {
        return form_close($extra);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('make_input'))
{
    /**
     * Make Input Field
     *
     * Generates input fields.
     *
     * @param   array   $input      Field input array
     * @param   array   $row        Field row array
     * @param   boolean $suffix     field name suffix
     * @return  string
     */
    function make_input($input, $row = NULL, $suffix = FALSE)
    {
        $return = '';
        if( ! isset($input['rules']))    $input['rules']  = '';
        if( ! isset($input['type']))     $input['type']   = (preg_match('/in_list\[.*\]/', $input['rules']))?'select':'text';
        if( ! isset($input['class']))    $input['class']  = '';
        if( ! isset($input['extra']))    $input['extra']  = 'class="form-control '.$input['class'].'" data-rules="'.$input['rules'].'"';

        // convert $row to array
        if(!is_array($row)) 
        {
            $row = (array)$row;
        }

        // set field array
        $field  = array(
            'id'     => $input['field'],
            'name'   => $input['field'],
        );

        // if $suffix is available append it to the name
        if($suffix)
        {
            $field['name'] .= $suffix; 
        }

        // set default value
        if(!isset($row[$input['field']]) || $row[$input['field']] === NULL)
        {
            $row[$input['field']] = get_input_value($input['field']);
        }

        // hidden input
        if(preg_match('/hidden/i', $input['type']))
        {
            $return = form_hidden($input['field'], $row[$input['field']], $input['extra']);

        // password input
        }
        elseif(preg_match('/password/i', $input['type']))
        {
            $return = form_password($field, '', $input['extra']);
            $value  = '';

        // file input
        }
        elseif(preg_match('/upload|file/i',$input['type']))
        {
            $return = form_upload($field, $row[$input['field']], $input['extra']);

        // textarea input
        }
        elseif(preg_match('/textarea/i', $input['type']))
        {
            $return = form_textarea($field, $row[$input['field']], $input['extra']);

        // select input, accept query, hasOne, hasMany
        }
        elseif(preg_match('/select:/i', $input['type']))
        {
            if(!preg_match('/required/i', $input['rules']))
            {
                $options  = array(''=>'');
            }
            else
            {
                $options  = array();
            }

            // query[fields name *][table name][where condition][order by]
            if(preg_match('/select:query\[(.+)\]\[(.+)\](\[.+\]|)(\[.+\]|)/Ui', $input['type'], $match))
            {
            }

            // hasOne[model_name][field_name][where condition]
            if(preg_match('/select:hasOne\[(.+)\]\[(.+)\](\[.+\]|)/Ui', $input['type'], $match))
            {
                // set default model method 
                $model_method = 'rows';
                $where        = array();
                $model        = $match[1];
                $model_field  = $match[2];

                if(isset($match[3]))
                {
                  $where_list = explode(',', str_replace(array('[',']'), '', $match[3]));
                    foreach ($where_list as $key => $value)
                    {

                        // We will use jQuery selector syntax:
                        // [name^=value] name like value%
                        // [name$=value] name like %value 
                        // [name*=value] name like %value% 
                        // [name=value] name = value

                        if(strpos($value, '^=') !== FALSE)
                        {
                            list($where_key, $where_val) =  explode('^=', $value, 2);
                            $where['like_after'][$where_key] = replace_vars($where_val);
                        }
                        else if(strpos($value, '$=') !== FALSE)
                        {
                            list($where_key, $where_val) =  explode('$=', $value, 2);
                            $where['like_before'][$where_key] = replace_vars($where_val);
                        }
                        else if(strpos($value, '*=') !== FALSE)
                        {
                            list($where_key, $where_val) =  explode('*=', $value, 2);
                            $where['like_before'][$where_key] = replace_vars($where_val);
                        }
                        else if(strpos($value, '=') !== FALSE)
                        {
                            list($where_key, $where_val) =  explode('=', $value, 2);
                            $where[$where_key] = replace_vars($where_val);
                        }
                    }
                }

                // try to find method name or use the default method 'rows'
                if(preg_match('/(.+)::(.+)/i', $model, $model_match))
                {
                    $model          = $model_match[1];
                    $model_method   = $model_match[2];
                }

                $hasone_model = load_model($model);

                foreach ($hasone_model->{$model_method}($where) as $result_row)
                {
                    if(is_object($result_row))
                    {
                        $options[$result_row->$model_field] = lang($result_row->{$hasone_model->getLabelName()});
                    }
                    else
                    {
                        $options[$result_row] = $result_row;
                    }
                }
            }

            // hasMany[model_name][field_name][where condition]
            if(preg_match('/select:hasMany\[(.+)\]\[(.+)\](\[.+\]|)/Ui', $input['type'], $match))
            {
            }

            $return = form_dropdown($field, $options, $row[$input['field']], $input['extra']);

        }
        elseif(preg_match('/range/i', $input['type']))
        {
            if( ! preg_match('/required/i', $input['rules']))
            {
                $options  = array(''=>'');
            }
            else
            {
                $options  = array();
            }
            if(preg_match('/range:\[\s*(\\d+)\s*,\s*(\\d+)\\s*\]/', $input['type'], $match))
            {
                if(isset($match[1]) && isset($match[2]))
                {
                    for ($i=$match[1]; $i <= $match[2]; $i++)
                    { 
                        $options[$i] = $i;
                    }
                }
            }
            $return = form_dropdown($field, $options, $row[$input['field']], $input['extra']);
        }
        elseif(preg_match('/select|dropdown/i', $input['type']))
        {
            if( ! preg_match('/required/i', $input['rules']))
            {
                $options  = array(''=>'');
            }
            else
            {
                $options  = array();
            }
            // if rules is set an has in_list rule 
            // in_list[red,blue,green]
            if(preg_match('/in_list\[(.+)\]/', $input['rules'], $match))
            {
                $in_list = explode(',', $match[1]);
                foreach ($in_list as $list_key => $list_value)
                {
                    $options[$list_value] = $list_value;
                }
            }
            $return = form_dropdown($field, $options, $row[$input['field']], $input['extra']);
        }
        else
        {
            $return = form_input($field, $row[$input['field']], $input['extra']);
        }

        return $return;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('make_input_value'))
{
    /**
     * Make Input field value
     *
     * Generates input field value.
     *
     * @param   array   $input      Field input array
     * @param   array   $row        Field row object
     * @return  string
     */
    function make_input_value($input, $row)
    {
        if($input['type'] == 'password') {
            return '';
        }

        if(isset($input['alias']) && isset($row->{$input['alias']}))
        {
            $value_label = $row->$input['alias'];
        }

        // hasOne[model_name][field_name][where condition]
        if(preg_match('/select:hasOne\[(.+)\]\[(.+)\](\[.+\]|)/Ui', $input['type'], $match))
        {
            // set default model method 
            $model_method = 'rows';
            $where        = array();
            $model        = $match[1];
            $model_field  = $match[2];

            if(isset($match[3]))
            {
              $where_list = explode(',', str_replace(array('[',']'), '', $match[3]));
                foreach ($where_list as $key => $value)
                {
                    // We will use jQuery selector syntax:
                    // [name^=value] name like value%
                    // [name$=value] name like %value 
                    // [name*=value] name like %value% 
                    // [name=value] name = value

                    if(strpos($value, '^=') !== FALSE)
                    {
                        list($where_key, $where_val) =  explode('^=', $value, 2);
                        $where['like_after'][$where_key] = replace_vars($where_val);
                    }
                    else if(strpos($value, '$=') !== FALSE)
                    {
                        list($where_key, $where_val) =  explode('$=', $value, 2);
                        $where['like_before'][$where_key] = replace_vars($where_val);
                    }
                    else if(strpos($value, '*=') !== FALSE)
                    {
                        list($where_key, $where_val) =  explode('*=', $value, 2);
                        $where['like_before'][$where_key] = replace_vars($where_val);
                    }
                    else if(strpos($value, '=') !== FALSE)
                    {
                        list($where_key, $where_val) =  explode('=', $value, 2);
                        $where[$where_key] = replace_vars($where_val);
                    }
                }
            }

            // try to find method name or use the default method 'rows'
            if(preg_match('/(.+)::(.+)/i', $model, $model_match))
            {
                $model          = $model_match[1];
                $model_method   = $model_match[2];
            }

            $hasone_model = load_model($model);
            $where[$model_field] = $row->{$input['field']};
            $value_label  = $hasone_model->{$model_method}($where);

            // if the return is array get the first element of the array 
            if(is_array($value_label))
            {
                if(isset($value_label[0]))
                {
                    $value_label = $value_label[0];
                }
                else
                {
                    unset($value_label);
                }
            }

            if(isset($value_label))
            {
                if(is_object($value_label))
                {
                    $value_label  = $value_label->{$hasone_model->getLabelName()};
                }
            }
        }

        return isset($value_label)?lang($value_label):$row->{$input['field']};
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('replace_vars'))
{
    /**
     * replace variables name with value  
     *
     * @param   string  string value 
     * @return  object  query object
     */
    function replace_vars($value, $row = FALSE)
    {
        preg_match_all('/{(get|post|user|row)\.([a-z0-9\-_]+)}/Ui', $value, $matches);
        if(is_array($matches))
        {
            foreach ($matches[1] as $key => $value)
            {
                $search     = '{'.$matches[1][$key].'.'.$matches[2][$key].'}';
                $replace    = '';
                if(strtolower($matches[1][$key]) == 'get')
                {
                    $replace = @$_GET[$matches[2][$key]];
                }
                if(strtolower($matches[1][$key]) == 'post')
                {
                    $replace = @$_POST[$matches[2][$key]];
                }
                if(strtolower($matches[1][$key]) == 'user')
                {
                    $replace = get_instance()->userdata($matches[2][$key]);
                }
                if(strtolower($matches[1][$key]) == 'row')
                {
                    if(is_object($row) && isset($row->{$matches[2][$key]}))
                    {
                        $replace = $row->$matches[2][$key];
                    }
                }
                $value = str_replace($search, $replace, $value);
            }
        }
        return $value;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('set_input_value'))
{
    /**
     * set Input default value
     *
     * @param   string  field name
     * @param   string  field value
     * @return  void
     */
    function set_input_value($field, $value)
    {
        get_instance()->session->set_flashdata('input_'.$field, $value);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_input_value'))
{
    /**
     * get Input value
     *
     * @param   string  field name
     * @return  string  field value
     */
    function get_input_value($field)
    {
        $post = get_instance()->session->flashdata('old_post_data');
        if(is_array($post) && isset($post[$field]))
        {
            return $post[$field];
        }
        if($value = get_instance()->session->flashdata('input_'.$field))
        {
            return $value;
        }
        return get_instance()->input->post($field);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('make_label'))
{
    /**
     * Make Input Label Field
     *
     * @param   array   $input      Field input array
     * @param   string  $attrs      Additional attributes array
     * @return  string
     */
    function make_label($input, $attrs = array())
    {
        if( ! isset($input['field']))    $input['field'] = '';
        if( ! isset($input['type']))     $input['type']  = 'text';
        if( ! isset($input['label']))    $input['label'] = $input['field'];
        if( ! isset($input['class']))    $input['class'] = '';
        if( ! is_array($attrs))          $attrs          = array(); 

        if(preg_match('/hidden/i', $input['type']))
        {
            return '';
        }

        // try to find the translations for the input 
        if(get_instance()->lang->has_line($input['field']))
        {
            $input['label'] = get_instance()->lang->line($input['field']);
        }
        elseif(get_instance()->lang->has_line($input['label']))
        {
            $input['label'] = get_instance()->lang->line($input['label']);
        }

        $attrs['class'] = 'control-label ' .$input['class'];
        $label = form_label($input['label'], $input['field'], $attrs);
        return $label;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('set_message'))
{
    /**
     * Set messages string
     *
     * @param   string  type of messages
     * @return  string
     */
    function set_message($message, $type = 'success')
    {
        $message_array = (array) get_instance()->session->userdata($type.'_message_list');
        if(!is_array($message))
        {
            $message = (array)lang($message);
        }
        $message_array = array_merge($message_array, $message);
        get_instance()->session->set_userdata($type.'_message_list', $message_array);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('make_message'))
{
    /**
     * Make messages string
     *
     * @param   string  type of messages
     * @return  string
     */
    function make_message($type = NULL)
    {
        $message = '';

        if($type == NULL || $type == 'error')
        {
            $message_array      = get_instance()->session->userdata('error_message_list');
            if(is_array($message_array) && count($message_array)>0)
            {
                $message .= '<div role="alert" class="alert alert-danger alert-dismissible"><button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>';
                foreach ($message_array as $key => $message_row)
                {
                    $message .= '<p>'.$message_row.'</p>'; 
                }
                $message .= '</div>';
            }
            get_instance()->session->unset_userdata('error_message_list');
        }

        if($type == NULL || $type == 'success')
        {
            $message_array      = get_instance()->session->userdata('success_message_list');
            if(is_array($message_array) && count($message_array)>0)
            {
                $message .= '<div role="alert" class="alert alert-success alert-dismissible"><button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>';
                foreach ($message_array as $key => $message_row)
                {
                    $message .= '<p>'.$message_row.'</p>'; 
                }
                $message .= '</div>';
            }
            get_instance()->session->unset_userdata('success_message_list');
        }

        return $message;
    }
}

/* End of file maker_helper.php */
/* Location: ./application/helpers/maker_helper.php */