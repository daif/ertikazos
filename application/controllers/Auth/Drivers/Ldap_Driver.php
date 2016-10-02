<?php
/**
 * LDAP Auth driver class
 *
 * This class is LDAP Auth driver .
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class Ldap_Driver implements Auth {
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
        //remove Special Characters + domain
        $username   = str_replace(array('*','(', ')', '\\', 'NUL', '/','@'.get_setting('ldap/domain')), '', $username);
        if(strpos($username, '@')) {
            list($username, ) = explode('@', $username, 2);
        }
        $conn       = ldap_connect(get_setting('ldap/host'), get_setting('ldap/port'));
        $bind       = ldap_bind($conn, $username.'@'.get_setting('ldap/domain'), $password);
        
        if($bind) {
            $ldap_allowed_ous       = get_setting('ldap/allowed_ous');
            $ldap_base_dn           = get_setting('ldap/base_dn');
            $ldap_sam_account_name  = get_setting('ldap/sam_account_name');
            $filter                 = "(|($ldap_sam_account_name=$username)(mail=$username@*))";
            $result                 = ldap_search($conn, $ldap_base_dn, $filter, array('memberof','distinguishedname'));
            $entries                = ldap_get_entries($conn, $result);
            if($entries['count']>0) {
                if(empty($ldap_allowed_ous)) {
                    return TRUE;
                }
                // check if user in allowed OUs 
                if(isset($entries['0']['memberof']) && is_array($entries['0']['memberof'])) {
                    foreach ($entries['0']['memberof'] as $key => $memberof) {
                        if(preg_match('/'.preg_quote($ldap_allowed_ous).'/i', $memberof)) {
                            return TRUE;
                        }
                    }
                }
                // check if user in allowed OUs 
                if(isset($entries['0']['distinguishedname'])) {
                    if(preg_match('/'.preg_quote($ldap_allowed_ous).'/i', $entries['0']['distinguishedname'])) {
                        return TRUE;
                    }
                }
                set_message('Sorry, your account is not allowed to access', 'error');
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

/* End of file Ldap_Driver.php */
/* Location: ./application/Auth/Drivers/Ldap_Driver.php */