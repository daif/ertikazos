<?php
/**
 * Database Auth driver class
 *
 * This class is Database Auth driver .
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class Database_Driver implements Auth {
    /**
     * Config array
     *
     * @var array
     */
    public $config = array();

    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct(){
        get_instance()->load->model('Admin/User_model', 'user');
    }

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
        if(get_instance()->user->login($username, $password, $remember)) {
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
/* End of file Database_Driver.php */
/* Location: ./application/Auth/Drivers/Database_Driver.php */