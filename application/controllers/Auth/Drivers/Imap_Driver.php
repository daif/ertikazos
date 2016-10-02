<?php
/**
 * IMAP Auth driver class
 *
 * This class is IMAP Auth driver .
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class Imap_Driver implements Auth {
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
        $host = '{'.get_setting('imap/host').':'.get_setting('imap/port').get_setting('imap/flags').'}';
        $mbox = imap_open($host, $username.'@'.get_setting('imap/domain'), $password);
        if($mbox) {
            return TRUE;
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

/* End of file Imap_Driver.php */
/* Location: ./application/Auth/Drivers/Imap_Driver.php */