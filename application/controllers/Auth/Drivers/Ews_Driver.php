<?php
/**
 * EWS Auth driver class
 *
 * This class is Exchange Web Services Auth driver .
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class Ews_Driver implements Auth {
    /**
     * Config array
     *
     * @var array
     */
    public $config = array();

    /**
     * Logs a user in.
     *
     * @param   string   $username  Username
     * @param   string   $password  Password
     * @param   boolean  $remember  Enable autologin
     * @return  boolean
     */
    public function login($username, $password, $remember)
    {
        $ews_url     = get_setting('ews/url');
        $ews_domain  = parse_url($ews_url, PHP_URL_HOST);
        $ews_domain  = str_ireplace(array('mail.','www.'), '', $ews_domain);
        $user_domain = '';
        if(strpos($username, '@')) {
            list($username, $user_domain) = explode('@', $username, 2);
        }

        if(strtolower($user_domain) == strtolower($ews_domain))
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $ews_url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
            curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);
            $data = curl_exec($ch);
            if($data && curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200')
            {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * Logs a user out.
     *
     * @return  boolean
     */
    public function logout()
    {
        
    }
}

/* End of file Ews_Driver.php */
/* Location: ./application/Auth/Drivers/Ews_Driver.php */