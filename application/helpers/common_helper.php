<?php
/**
 *
 * Ertikaz common Helpers
 *
 * @package     Ertikaz
 * @subpackage  Helpers
 * @category    Helpers
 */
defined('BASEPATH') OR exit('No direct script access allowed');

// ------------------------------------------------------------------------

if ( ! function_exists('user_hasaccess'))
{
	/**
	 * Check User permission
	 *
	 * Check if user has access to app.
	 *
	 * @param	object	$user		User Object
	 * @param	object	$app	    app Object
	 * @return	boolean $return
	 */
	function user_hasaccess($user, $app)
	{
        // if app is not object return FALSE
        if(!is_object($app))
        {
            return FALSE;
        }
        // anonymous user can access this app
        if($app->app_access == App_model::ACCESS_ANONYMOUS)
        {
            return TRUE;
        }
        // if user is not object return FALSE
        if(!is_object($user))
        {
            return FALSE;
        }
        // if user status is not active
        if($user->user_status == User_model::STATUS_INACTIVE)
        {
            return FALSE;
        }
        // if app_status is not active return FALSE
        if($app->app_status == App_model::STATUS_INACTIVE)
        {
            return FALSE;
        }
        // if user type is admin he can access to any apps
        if($user->user_type == User_model::TYPE_ADMIN)
        {
            return TRUE;
        }
        // any logged in user can access this app 
        if($app->app_access == App_model::ACCESS_AUTHENTICATED && $user->user_status == User_model::STATUS_ACTIVE)
        {
            return TRUE;
        }
        // User need permission to access this app
        if($app->app_access == App_model::ACCESS_AUTHORIZED)
        {
            // check user permissions
            $permissions = $user->permissions();
            foreach ($permissions as $key => $permission)
            {
                if($app->app_id == $permission->perm_app_id)
                {
                    return TRUE;
                }
            }
            // check user groups
            $groups = $user->groups();
            foreach ($groups as $key => $group)
            {
                if(group_hasaccess($group, $app))
                {
                    return TRUE;
                }
            }
        }
		return FALSE;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('group_hasaccess'))
{
    /**
     * Check Group permission
     *
     * Check if group has access to app.
     *
     * @param   object  $group       group Object
     * @param   object  $app    app Object
     * @return  boolean $return
     */
    function group_hasaccess($group, $app)
    {
        if(!is_object($group) || !is_object($app))
        {
            return FALSE;
        }
        if($app->app_access == App_model::ACCESS_AUTHENTICATED || $app->app_access == App_model::ACCESS_ANONYMOUS)
        {
            return TRUE;
        }
        $permissions = $group->permissions();
        foreach ($permissions as $key => $permission)
        {
            if($app->app_id == $permission->perm_app_id)
            {
                return TRUE;
            }
        }
        return FALSE;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_setting'))
{
    /**
     * Get settings rows
     *
     * find setting value by name.
     *
     * @param   string  $name       setting name as path
     * @return  mixed   $setting    object of result or setting value
     */
    function get_setting($name)
    {
        $setting = get_instance()->setting->rows(['name'=>$name]);
        if(count($setting) == 1)
        {
            if(count($setting) == 1)
            {
                return $setting[0]->value;
            }
            else
            {
                return $setting;
            }
        }
        return FALSE;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('set_setting'))
{
    /**
     * Set setting row
     *
     * create or update setting value.
     *
     * @param   string  $name       setting name as path
     * @param   string  $value      setting name value
     * @return  mixed   $setting    object of result or setting value
     */
    function set_setting($name, $value)
    {
        $setting = get_instance()->setting->row(['name'=>$name]);
        if(is_object($setting))
        {
            $setting->value = $value;
            $setting->update();
        }
        else
        {
            $setting = get_instance()->setting;
            $setting->name = $name;
            $setting->value = $value;
            return $setting->insert();
        }
        return get_setting($name);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('send_notify'))
{
    /**
     * send notify
     *
     * send message to user.
     *
     * @param   integer     $user_id     user id
     * @param   string      $title      notify title
     * @param   string      $body       notify body
     * @return  boolean     TRUE id success FALSE if fail
     */
    function send_notify($user_id, $title, $body)
    {
        get_instance()->load->model('User/Notify_model', 'notify');
        $notify = get_instance()->notify;
        $notify->notify_user_id = $user_id;
        $notify->notify_title   = $title;
        $notify->notify_body    = $body;
        $notify->notify_status  = Notify_model::STATUS_UNREAD;
        return $notify->insert();
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('send_email'))
{
    /**
     * send email
     *
     * send email message.
     *
     * @param   mixed       $to         can be email or array of emails
     * @param   string      $subject    email subject
     * @param   string      $message    email message
     * @param   string      $template   template file or html
     * @param   mixed       $from       can be email, user object, system 
     * @return  boolean     TRUE id success FALSE if fail
     */
    function send_email($to, $subject, $message, $template='default', $from = FALSE)
    {
        get_instance()->load->library('email');
        $site_name      = get_setting('site/name');
        $site_email     = get_setting('site/email');
        $site_url       = site_url();
        $config         = array(
            'protocol'  => 'mail',
            'mailtype'  => 'html',
            'charset'   => 'utf8',
        );
        // if smtp host is set and not the default use it
        if(get_setting('smtp/host') && get_setting('smtp/host') != 'ssl://smtp.domain.com')
        {
            $config['protocol']     = 'smtp';
            $config['smtp_host']    = get_setting('smtp/host');
            $config['smtp_port']    = get_setting('smtp/port');
            $config['smtp_user']    = get_setting('smtp/user');
            $config['smtp_pass']    = get_setting('smtp/pass');
        }

        get_instance()->email->initialize($config);
        get_instance()->email->clear(TRUE);

        if(is_object($from)) {
            $from_name      = $from->user_name;
            $from_email     = $from->user_email;
        } else {
            $from_name      = $site_name;
            $from_email     = $site_email;
        }

        if(file_exists(VIEWPATH.'templates/email/'.$template.'.html'))
        {
            $template = file_get_contents(VIEWPATH.'templates/email/'.$template.'.html');
            $template = str_replace(
                array('{site_url}', '{site_name}', '{subject}'), 
                array($site_url, $site_name, $subject),
                $template
            );
            if(is_array($message))
            {
                $search     = array_map(function($str){return '{'.$str.'}';},array_keys($message));
                $message    = str_replace($search, array_values($message), $template);
            }
            else
            {
                $message    = str_replace('{message}', $message, $template);
            }
        }

        get_instance()->email->from($from_email, $from_name);
        get_instance()->email->to($to);
        get_instance()->email->subject($subject);
        get_instance()->email->message($message);
        if(get_instance()->email->send())
        {
            return TRUE;
        }
        return FALSE;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('send_email_ews'))
{
    /**
     * send email
     *
     * send email message using exchange web service.
     *
     * @param   mixed       $to         can be email or array of emails
     * @param   string      $subject    email subject
     * @param   string      $message    email message
     * @param   string      $template   template file or html
     * @return  boolean     TRUE id success FALSE if fail
     */
    function send_email_ews($to, $subject, $message, $template='default')
    {
        $ews_url = get_setting('ews/url');
        $ews_usr = get_setting('ews/user');
        $ews_pwd = get_setting('ews/pass');
        if(strpos($ews_usr, '@')) {
            list($ews_usr, ) = explode('@', $ews_usr, 2);
        }
        $site_name      = get_setting('site/name');
        $site_email     = get_setting('site/email');
        $site_url       = site_url();

        if(file_exists(VIEWPATH.'templates/email/'.$template.'.html'))
        {
            $template = file_get_contents(VIEWPATH.'templates/email/'.$template.'.html');
            $template = str_replace(
                array('{site_url}', '{site_name}', '{subject}'), 
                array($site_url, $site_name, $subject),
                $template
            );
            if(is_array($message))
            {
                $search     = array_map(function($str){return '{'.$str.'}';},array_keys($message));
                $message    = str_replace($search, array_values($message), $template);
            }
            else
            {
                $message    = str_replace('{message}', $message, $template);
            }
        }

        // create soap request
        $envelope  = '<?xml version="1.0" encoding="utf-8"?>';
        $envelope .= '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $envelope .= '               xmlns:m="http://schemas.microsoft.com/exchange/services/2006/messages" ';
        $envelope .= '               xmlns:t="http://schemas.microsoft.com/exchange/services/2006/types" ';
        $envelope .= '               xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">';
        $envelope .= '  <soap:Header>';
        $envelope .= '    <t:RequestServerVersion Version="Exchange2013" />';
        $envelope .= '  </soap:Header>';
        $envelope .= '  <soap:Body>';
        $envelope .= '    <m:CreateItem MessageDisposition="SendAndSaveCopy">';
        $envelope .= '      <m:SavedItemFolderId>';
        $envelope .= '        <t:DistinguishedFolderId Id="sentitems" />';
        $envelope .= '      </m:SavedItemFolderId>';
        $envelope .= '      <m:Items>';
        $envelope .= '        <t:Message>';
        $envelope .= '          <t:Subject>'.trim(strip_tags($subject)).'</t:Subject>';
        $envelope .= '          <t:Body BodyType="HTML">'.htmlentities($message, ENT_QUOTES | ENT_IGNORE, "UTF-8").'</t:Body>';
        $envelope .= '          <t:ToRecipients>';
        $envelope .= '            <t:Mailbox>';
        $envelope .= '              <t:EmailAddress>'.$to.'</t:EmailAddress>';
        $envelope .= '              </t:Mailbox>';
        $envelope .= '          </t:ToRecipients>';
        $envelope .= '        </t:Message>';
        $envelope .= '      </m:Items>';
        $envelope .= '    </m:CreateItem>';
        $envelope .= '  </soap:Body>';
        $envelope .= '</soap:Envelope>';
        // header
        $header[] = "Content-type: text/xml"; 
        $header[] = "Connection: KEEP-Alive";
        $header[] = "User-Agent: PHP-SOAP-CURL";
        $header[] = "Method: POST";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ews_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 900); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        curl_setopt($ch, CURLOPT_USERPWD, $ews_usr.":".$ews_pwd);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $envelope);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);
        curl_close($ch);
        if($response) {
            if(preg_match('/NoError/', $response)) {
                return TRUE;
            }
        }
        return FALSE;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('ews_get_userid'))
{
    /**
     * get user id from EWS
     *
     * get user id from exchange web service.
     *
     * @param   string      $email      user email
     * @return  mixed       user id as string if success or FALSE if fail
     */
    function ews_get_userid($email)
    {
        $ews_url = get_setting('ews/url');
        $ews_usr = get_setting('ews/user');
        $ews_pwd = get_setting('ews/pass');
        if(strpos($ews_usr, '@')) {
            list($ews_usr, ) = explode('@', $ews_usr, 2);
        }
        // create soap request
        $envelope  = '<?xml version="1.0" encoding="utf-8"?>';
        $envelope .= '<soap:Envelope xmlns="http://schemas.microsoft.com/exchange/services/2006/types" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" ';
        $envelope .= 'xmlns:t="http://schemas.microsoft.com/exchange/services/2006/types" xmlns:m="http://schemas.microsoft.com/exchange/services/2006/messages">';
        $envelope .= '  <soap:Header>';
        $envelope .= '    <t:RequestServerVersion Version="Exchange2013" />';
        $envelope .= '  </soap:Header>';
        $envelope .= '  <soap:Body>';
        $envelope .= '    <m:FindPeople>';
        $envelope .= '      <m:PersonaShape>';
        $envelope .= '        <t:BaseShape>IdOnly</t:BaseShape>';
        $envelope .= '      </m:PersonaShape>';
        $envelope .= '      <m:IndexedPageItemView BasePoint="Beginning" MaxEntriesReturned="1" Offset="0" />';
        $envelope .= '      <m:ParentFolderId>';
        $envelope .= '        <t:DistinguishedFolderId Id="directory" />';
        $envelope .= '      </m:ParentFolderId>';
        $envelope .= '      <m:QueryString>'.trim(strip_tags($email)).'</m:QueryString>';
        $envelope .= '    </m:FindPeople>';
        $envelope .= '  </soap:Body>';
        $envelope .= '</soap:Envelope>';
        // header
        $header[] = "Content-type: text/xml"; 
        $header[] = "Connection: KEEP-Alive";
        $header[] = "User-Agent: PHP-SOAP-CURL";
        $header[] = "Method: POST";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ews_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 900); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        curl_setopt($ch, CURLOPT_USERPWD, $ews_usr.":".$ews_pwd);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $envelope);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);
        curl_close($ch);
        if($response) {
            if(preg_match('#<PersonaId Id="(.+)"/>#Uis', $response, $persona_id)) {
                return $persona_id[1];
            }
        }
        return FALSE;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('ews_get_userinfo'))
{
    /**
     * get user info from EWS
     *
     * get user information using exchange web service.
     *
     * @param   string      $email      user email
     * @return  array       user info array if success or FALSE if fail
     */
    function ews_get_userinfo($email)
    {
        $ews_url = get_setting('ews/url');
        $ews_usr = get_setting('ews/user');
        $ews_pwd = get_setting('ews/pass');
        if(strpos($ews_usr, '@')) {
            list($ews_usr, ) = explode('@', $ews_usr, 2);
        }
        $persona_id = ews_get_userid($email);
        if($persona_id)
        {
            // create soap request
            $envelope  = '<?xml version="1.0" encoding="utf-8"?>';
            $envelope .= '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:t="http://schemas.microsoft.com/exchange/services/2006/types">';
            $envelope .= '  <soap:Header>';
            $envelope .= '    <t:RequestServerVersion Version="Exchange2013" />';
            $envelope .= '  </soap:Header>';
            $envelope .= '  <soap:Body xmlns="http://schemas.microsoft.com/exchange/services/2006/messages">';
            $envelope .= '    <GetPersona>';
            $envelope .= '      <PersonaId Id="'.$persona_id.'" />';
            $envelope .= '    </GetPersona>';
            $envelope .= '  </soap:Body>';
            $envelope .= '</soap:Envelope>';
            // header
            $header[] = "Content-type: text/xml"; 
            $header[] = "Connection: KEEP-Alive";
            $header[] = "User-Agent: PHP-SOAP-CURL";
            $header[] = "Method: POST";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $ews_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 900); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
            curl_setopt($ch, CURLOPT_USERPWD, $ews_usr.":".$ews_pwd);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $envelope);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);  
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, false);
            $response = curl_exec($ch);
            curl_close($ch);
            if($response) {
                if(preg_match('#(<persona>.+</persona>)#Uis', $response, $persona)) {
                    $response = preg_replace('# xmlns="http://(.+)"#Uis', '', $persona[1]);
                    $response = simplexml_load_string($response);
                    $response = json_encode($response);
                    $response = json_decode($response, TRUE);
                    return array(
                        'Title' => @$response['Title'],
                        'Surname' => @$response['Surname'],
                        'GivenName' => @$response['GivenName'],
                        'DisplayName' => @$response['DisplayName'],
                        'Department' => @$response['Department'],
                        'PersonaType' => @$response['PersonaType'],
                        'PhoneNumber' => @$response['BusinessPhoneNumbers']['PhoneNumberAttributedValue']['Value']['Number'],
                        'DisplayNameFirstLast' => @$response['DisplayNameFirstLast'],
                    );
                }
            }
        }
        return FALSE;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_user_photo'))
{
    /**
     * get user photo
     *
     * get user photo by email from exchange web service.
     *
     * @param   string      $email    user email
     * @param   string      $size     photo size can be, HR48x48,HR64x64,HR96x96,HR120x120 
     * @return  string      photo content if success or FALSE if fail
     */
    function get_user_photo($email, $size='HR120x120')
    {
        $ews_url = get_setting('ews/url');
        $ews_usr = get_setting('ews/user');
        $ews_pwd = get_setting('ews/pass');
        $ews_url = $ews_url . '/s/GetUserPhoto?email='.$email.'&size='.$size;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ews_url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        curl_setopt($ch, CURLOPT_USERPWD, $ews_usr.":".$ews_pwd);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);
        curl_close($ch);
        if($response) {
            return $response;
        }
        return FALSE;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('send_sms'))
{
    /**
     * send_sms
     *
     * send SMS message.
     *
     * @param   mixed       $mobile     can be one mobile or array of mobiles
     * @param   string      $message    SMS message
     * @return  mixed       return the API output or FALSE
     */
    function send_sms($mobile, $message)
    {
        // SMS settings 
        $sms_url    = get_setting('sms/url');
        $sms_user   = get_setting('sms/user');
        $sms_pass   = get_setting('sms/pass');
        $sms_params = get_setting('sms/params');
        $sms_method = get_setting('sms/method');
        // if is array convert to string 
        if(is_array($mobile))
        {
            $mobile = implode(',', $mobile);
        }
        // replace variables with it's values
        $sms_params = str_replace(
            array( '{username}','{password}','{numbers}','{message}'), 
            array( $sms_user, $sms_pass, $mobile, $message),
            $sms_params);
        // parse the parameters 
        $sms_params = parse_str($sms_params);
        // initial cURL
        $ch = curl_init();
        if(strtoupper($sms_method) == 'GET')
        {
            $sms_url = $sms_url .'?'. http_build_query($sms_params);
            curl_setopt($ch, CURLOPT_URL, $sms_url);
        }
        else
        {
            curl_setopt($ch, CURLOPT_URL, $sms_url);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $sms_params);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        $data = curl_exec($ch);
        if($data)
        {
            return $data;
        }
        return FALSE;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('make_paging'))
{
    /**
     * make paging links
     *
     * generate paging links
     *
     * @param   integer  $total_rows    the total number of rows
     * @param   integer  $per_page      how many rows per page 
     * @return  mixed    $pagination    HTML code of paging links
     */
    function make_paging($total_rows, $per_page=5)
    {
        $config['base_url']             = current_url();
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $per_page;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'page';
        if(strtolower(@$_SERVER['REQUEST_METHOD']) == 'post')
        {
            $config['full_tag_open']    = '<div>'.make_form_open().'<ul class="pagination">';
            foreach($_POST as $var=>$val)
            {
              $config['full_tag_open'] .= form_hidden($var, $val);
            }
            $config['full_tag_close']   = '</ul>'.make_form_close().'</div>';
        }
        else
        {
            $config['full_tag_open']    = '<div><ul class="pagination">';
            $config['full_tag_close']   = '</ul></div>';
        }
        $config['next_tag_open']        = '<li>';
        $config['next_tag_close']       = '</li>';
        $config['prev_tag_open']        = '<li>';
        $config['prev_tag_close']       = '</li>';
        $config['first_tag_open']       = '<li>';
        $config['first_tag_close']      = '</li>';
        $config['last_tag_open']        = '<li>';
        $config['last_tag_close']       = '</li>';
        $config['num_tag_open']         = '<li>';
        $config['num_tag_close']        = '</li>';
        $config['cur_tag_open']         = '<li class="active"><a>';
        $config['cur_tag_close']        = '</a></li>';
        get_instance()->load->library('pagination');
        get_instance()->pagination->initialize($config);
        return get_instance()->pagination->create_links();
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_from_array'))
{
    /**
     * Fetch from array
     *
     * Used to retrieve values from arrays.
     *
     * @param   array   $array  The array.
     * @param   array   $index  Index of items to be fetched from $array
     * @return  mixed 
     */
    function get_from_array($array, $index)
    {
        if(is_array($array))
        {
            if(!is_array($index))
            {
                $index = [$index];
            }
            return array_intersect_key($array, array_combine($index, array_keys($index)));
        }
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('upload_file'))
{
    /**
     * uploading file
     *
     * Performs the upload based on the preferences you have set.
     *
     * @param   string  $field_name    Name of the form field.
     * @return  string  $file_name     rename the uploaded file to this name.
     * @return  mixed   Information about the uploaded file or FALSE on fail
     */
    function upload_file($field_name, $file_name='')
    {
        $config = array(
            'upload_path'   => FCPATH.'uploads/',
            'allowed_types' => 'gif|jpg|jpeg|png|pdf|doc|docx|zip|rar',
            'file_name'     => time(),
        );
        if(trim($file_name) != '')
        {
            $config['file_name'] = trim($file_name);
        }
        get_instance()->load->library('upload');
        get_instance()->upload->initialize($config);
        if(get_instance()->upload->do_upload($field_name))
        {
            return get_instance()->upload->data();
        }
        return FALSE;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('image_lib_resize'))
{
     /**
     * resizer image
     *
     * Performs the resize based on the preferences you have set.
     *
     * @param   string  $source_image  source image name/path. The path must be a relative or absolute server path, not a URL.
     * @return  string  $width     the new width of image .
     * @return  string  $height    the new height of image .
     */
    function image_lib_resize($source_image, $width, $height)
    {
        $config['image_library']    = 'gd2';
        $config['source_image']     = $source_image;
        $config['maintain_ratio']   = TRUE;
        $config['width']            = $width;
        $config['height']           = $height;
        get_instance()->load->library('image_lib', $config);
        return get_instance()->image_lib->resize();
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('load_model'))
{
    /**
     * load model class.
     *
     * @param  string  $model class name
     * @return object of $model class
     */
    function load_model($model)
    {
        $model_namespace = '';
        $model_model     = '';
        if(strpos($model, '/') !== FALSE)
        {
            list($model_namespace, $model_model) = explode('/', $model);
            $model_namespace = $model_namespace . '/';
        }
        else
        {
            $model_model = $model;
        }
        $model_model = strtolower($model_model);
        if(get_instance()->load->model(ucfirst($model_namespace) . ucfirst($model_model) . '_model', $model_model))
        {
            return get_instance()->$model_model;
        }
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_user_id'))
{
    /**
     * get current user id.
     *
     * @return integer  user id or 0
     */
    function get_user_id()
    {
        if(get_instance()->session->has_userdata('userdata'))
        {
            if(get_instance()->userdata('user_id') > 0)
            {
                return get_instance()->userdata('user_id');
            }
        }
        return 0;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('watchdog'))
{
    /**
     * watchdog logging messages.
     *
     * @param  string   $type log type
     * @param  integer  $app_id application id
     * @param  mix      $variables log this data
     * @return object of $model class
     */
    function watchdog($log_type, $log_app_id, $log_variables = NULL)
    {
        // load model
        get_instance()->load->model('Admin/Watchdog_model', 'watchdog');

        if( ! is_array($log_variables))
        {
            $log_variables = (array) $log_variables;
        }

        $watchlog = get_instance()->watchdog;
        $watchlog->log_type      = $log_type;
        $watchlog->log_date      = date('Y-m-d H:i:s');
        $watchlog->log_app_id    = $log_app_id;
        $watchlog->log_ip        = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        // append REFERER to variables array
        $log_variables['HTTP_REFERER'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        $watchlog->log_variables = serialize($log_variables);
        $watchlog->log_user_id   = get_user_id();
        $watchlog->log_time      = '0';
        return $watchlog->insert();
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('update_watchdog'))
{
    /**
     * update watchdog time
     *
     * @param  integer  $watchlog object
     * @return boolean TRUE on success or FALSE on fail
     */
    function update_watchdog($watchlog)
    {
        if(is_object($watchlog))
        {
            $watchlog->log_time  = time() - strtotime($watchlog->log_date);
            return $watchlog->update();
        }
        return FALSE;
    }
}

/* End of file common_helper.php */
/* Location: ./application/helpers/common_helper.php */