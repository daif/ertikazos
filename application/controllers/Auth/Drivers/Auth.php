<?php
/**
 * Auth interface class
 *
 * This class is Auth interface
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

interface Auth {

    /**
     * Logs a user in.
     *
     * @param   string   $username  Username
     * @param   string   $password  Password
     * @param   boolean  $remember  Enable autologin
     * @return  boolean
     */
    public function login($username, $password, $remember);

    /**
     * Logs a user out.
     *
     * @return  boolean
     */
    public function logout();
}

/* End of file Auth.php */
/* Location: ./application/Auth/Drivers/Auth.php */