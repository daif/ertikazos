<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ertikazos_Seeder extends ER_Seeder {
    public function up()
    {
        get_instance()->load->model('Admin/App_model');
        get_instance()->load->model('Admin/User_model');
        get_instance()->load->model('Admin/Group_model');
        get_instance()->load->model('User/Notify_model');

        $this->seeds['er_apps'] = array(
            // User application
            array(
                'app_path'      => 'User',
                'app_icon'      => 'fa fa-user',
                'app_sort'      => '-99',
                'app_menu'      => App_model::MENU_HIDE,
                'app_access'    => App_model::ACCESS_AUTHENTICATED,
                'app_status'    => App_model::STATUS_ACTIVE
            ),
            array(
                'app_path'      => 'User/Dashboard',
                'app_icon'      => 'fa fa-dashboard',
                'app_menu'      => App_model::MENU_SHOW,
                'app_access'    => App_model::ACCESS_AUTHENTICATED,
                'app_status'    => App_model::STATUS_ACTIVE
            ),
            array(
                'app_path'      => 'User/Account',
                'app_icon'      => 'fa fa-info',
                'app_menu'      => App_model::MENU_HIDE,
                'app_access'    => App_model::ACCESS_AUTHENTICATED,
                'app_status'    => App_model::STATUS_ACTIVE
            ),
            array(
                'app_path'      => 'User/Notify',
                'app_icon'      => 'fa fa-info',
                'app_menu'      => App_model::MENU_HIDE,
                'app_access'    => App_model::ACCESS_AUTHENTICATED,
                'app_status'    => App_model::STATUS_ACTIVE
            ),
            // Admin application
            array(
                'app_path'      => 'Admin',
                'app_icon'      => 'fa fa-wrench',
                'app_sort'      => '99',
                'app_menu'      => App_model::MENU_SHOW,
                'app_access'    => App_model::ACCESS_AUTHORIZED,
                'app_status'    => App_model::STATUS_ACTIVE
            ),
            array(
                'app_path'      => 'Admin/Config',
                'app_icon'      => 'fa fa-gears',
                'app_menu'      => App_model::MENU_SHOW,
                'app_access'    => App_model::ACCESS_AUTHORIZED,
                'app_status'    => App_model::STATUS_ACTIVE
            ),
            array(
                'app_path'      => 'Admin/Setting',
                'app_icon'      => 'fa fa-gears',
                'app_menu'      => App_model::MENU_SHOW,
                'app_access'    => App_model::ACCESS_AUTHORIZED,
                'app_status'    => App_model::STATUS_ACTIVE
            ),
            array(
                'app_path'      => 'Admin/App',
                'app_icon'      => 'fa fa-gears',
                'app_menu'      => App_model::MENU_SHOW,
                'app_access'    => App_model::ACCESS_AUTHORIZED,
                'app_status'    => App_model::STATUS_ACTIVE
            ),
            array(
                'app_path'      => 'Admin/Group',
                'app_icon'      => 'fa fa-gears',
                'app_menu'      => App_model::MENU_SHOW,
                'app_access'    => App_model::ACCESS_AUTHORIZED,
                'app_status'    => App_model::STATUS_ACTIVE
            ),
            array(
                'app_path'      => 'Admin/User',
                'app_icon'      => 'fa fa-gears',
                'app_menu'      => App_model::MENU_SHOW,
                'app_access'    => App_model::ACCESS_AUTHORIZED,
                'app_status'    => App_model::STATUS_ACTIVE
            ),
            array(
                'app_path'      => 'Admin/Watchdog',
                'app_icon'      => 'fa fa-gears',
                'app_menu'      => App_model::MENU_SHOW,
                'app_access'    => App_model::ACCESS_AUTHORIZED,
                'app_status'    => App_model::STATUS_ACTIVE
            ),
        );


        $this->seeds['er_settings'] = array(
            // Site setting
            array(
                'name'  => 'site',
                'value' => 'Site',
                'rules' => 'required'
            ),
            array(
                'name'  => 'site/name',
                'value' => 'ErtikazOS',
                'rules' => 'required'
            ),
            array(
                'name'  => 'site/desc',
                'value' => 'A complete platform for building web applications.',
                'rules' => 'required'
            ),
            array(
                'name'  => 'site/email',
                'value' => 'admin@ertikazos.dev',
                'rules' => 'required|valid_email'
            ),
            array(
                'name'  => 'site/domain',
                'value' => 'ertikazos.dev',
                'rules' => 'required'
            ),
            // LDAP setting
            array(
                'name'  => 'ldap',
                'value' => 'LDAP',
                'rules' => 'required'
            ),
            array(
                'name'  => 'ldap/host',
                'value' => '',
                'rules' => ''
            ),
            array(
                'name'  => 'ldap/port',
                'value' => '',
                'rules' => 'integer'
            ),
            array(
                'name'  => 'ldap/domain',
                'value' => '',
                'rules' => ''
            ),
            array(
                'name'  => 'ldap/base_dn',
                'value' => '',
                'rules' => ''
            ),
            array(
                'name'  => 'ldap/sam_account_name',
                'value' => 'sAMAccountName',
                'rules' => 'required'
            ),
            array(
                'name'  => 'ldap/allowed_ous',
                'value' => '',
                'rules' => ''
            ),
            array(
                'name'  => 'ldap/disallowed_ous',
                'value' => '',
                'rules' => ''
            ),
            array(
                'name'  => 'ldap/user',
                'value' => '',
                'rules' => ''
            ),
            array(
                'name'  => 'ldap/pass',
                'value' => '',
                'rules' => ''
            ),
            // SMTP setting
            array(
                'name'  => 'smtp',
                'value' => 'SMTP',
                'rules' => 'required'
            ),
            array(
                'name'  => 'smtp/host',
                'value' => 'ssl://smtp.domain.com',
                'rules' => 'required'
            ),
            array(
                'name'  => 'smtp/port',
                'value' => '465',
                'rules' => 'required|integer'
            ),
            array(
                'name'  => 'smtp/user',
                'value' => ''
            ),
            array(
                'name'  => 'smtp/pass',
                'value' => ''
            ),
            // IMAP setting
            array(
                'name'  => 'imap',
                'value' => 'IMAP',
                'rules' => 'required'
            ),
            array(
                'name'  => 'imap/host',
                'value' => '',
                'rules' => ''
            ),
            array(
                'name'  => 'imap/port',
                'value' => '',
                'rules' => 'integer'
            ),
            array(
                'name'  => 'imap/flags',
                'value' => '',
                'rules' => ''
            ),
            // Exchange server setting
            array(
                'name'  => 'ews',
                'value' => 'Exchange',
                'rules' => 'required'
            ),
            array(
                'name'  => 'ews/url',
                'value' => 'https://mail.domain.com/EWS/exchange.asmx',
                'rules' => ''
            ),
            array(
                'name'  => 'ews/user',
                'value' => '',
                'rules' => ''
            ),
            array(
                'name'  => 'ews/pass',
                'value' => '',
                'rules' => ''
            ),
            // SMS gateway setting
            array(
                'name'  => 'sms',
                'value' => 'SMS',
                'rules' => 'required'
            ),
            array(
                'name'  => 'sms/url',
                'value' => 'http://api.domain.com/SendSMS',
                'rules' => ''
            ),
            array(
                'name'  => 'sms/user',
                'value' => '',
                'rules' => ''
            ),
            array(
                'name'  => 'sms/pass',
                'value' => '',
                'rules' => ''
            ),
            array(
                'name'  => 'sms/params',
                'value' => 'strUserName={username}&strPassword={password}&strTagName=SMS&strRecepientNumbers={numbers}&strMessage={message}',
                'rules' => ''
            ),
            array(
                'name'  => 'sms/post',
                'value' => 'POST',
                'rules' => 'required|in_list[POST,GET]' // POST or GET
            ),
            // Authentication setting
            array(
                'name'  => 'auth',
                'value' => 'Auth',
                'rules' => 'required'
            ),
            array(
                'name'  => 'auth/driver',
                'value' => 'Database',
                'rules' => 'required|in_list[Database,LDAP,EWS,IMAP]' // Database or LDAP or EWS or IMAP 
            ),
            array(
                'name'  => 'auth/registration',
                'value' => 'Allowed',
                'rules' => 'required|in_list[Allowed,Not Allowed]' // Allowed or No tAllowed
            ),
            array(
                'name'  => 'auth/allowed_domains',
                'value' => '',
                'rules' => 'required' // a comma-separated list of domains addresses
            ),
            // status
            array(
                'name'  => 'status/active',
                'value' => Setting_model::STATUS_ACTIVE
            ),
            array(
                'name'  => 'status/inactive',
                'value' => Setting_model::STATUS_INACTIVE
            ),
            // app_access
            array(
                'name'  => 'app_access/authorized',
                'value' => App_model::ACCESS_AUTHORIZED
            ),
            array(
                'name'  => 'app_access/authenticated',
                'value' => App_model::ACCESS_AUTHENTICATED
            ),
            array(
                'name'  => 'app_access/anonymous',
                'value' => App_model::ACCESS_ANONYMOUS
            ),
            // app_menu
            array(
                'name'  => 'app_menu/show',
                'value' => App_model::MENU_SHOW
            ),
            array(
                'name'  => 'app_menu/hide',
                'value' => App_model::MENU_HIDE
            ),
            // user_type
            array(
                'name'  => 'user_type/user',
                'value' => User_model::TYPE_USER
            ),
            array(
                'name'  => 'user_type/admin',
                'value' => User_model::TYPE_ADMIN
            ),
            // user_status
            array(
                'name'  => 'user_status/active',
                'value' => User_model::STATUS_ACTIVE
            ),
            array(
                'name'  => 'user_status/inactive',
                'value' => User_model::STATUS_INACTIVE
            ),
            // notify_status
            array(
                'name'  => 'notify_status/read',
                'value' => Notify_model::STATUS_READ
            ),
            array(
                'name'  => 'notify_status/unread',
                'value' => Notify_model::STATUS_UNREAD
            ),
        );

        // Default group
        $this->seeds['er_groups'] = array(
            array(
                'group_name'    => 'Users',
                'group_status'  => Group_model::STATUS_ACTIVE
            ),
        );

        // Default users
        $this->seeds['er_users'] = array(
            array(
                'user_type'     => User_model::TYPE_ADMIN,
                'user_name'     => 'Admin User',
                'user_email'    => 'admin@ertikazos.dev',
                'user_pass'     => password_hash('12345678', PASSWORD_DEFAULT),
                'user_avatar'   => 'avatar01.png',
                'user_status'   => User_model::STATUS_ACTIVE
            ),
            array(
                'user_type'     => User_model::TYPE_USER,
                'user_name'     => 'Normal User',
                'user_email'    => 'user@ertikazos.dev',
                'user_pass'     => password_hash('12345678', PASSWORD_DEFAULT),
                'user_avatar'   => 'avatar01.png',
                'user_status'   => User_model::STATUS_ACTIVE
            ),
        );

        // seeding the seeds
        $this->seeding();

    }

    public function down()
    {
        get_instance()->db->delete('er_settings');
        get_instance()->db->delete('er_apps');
        get_instance()->db->delete('er_groups');
        get_instance()->db->delete('er_users');
        get_instance()->db->delete('er_users_ses');
        get_instance()->db->delete('er_users_rels');
        get_instance()->db->delete('er_permissions');
    }
}
?>