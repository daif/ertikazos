<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Addressbook_Seeder extends ER_Seeder {
    public function up()
    {
        get_instance()->load->model('Admin/App_model');
        get_instance()->load->model('Admin/Group_model');
        get_instance()->load->model('Admin/User_model');

        // AddressBook Application
        $this->seeds['er_apps'] = array(
            array(
                'app_path'      => 'AddressBook',
                'app_icon'      => 'fa fa-list',
                'app_menu'      => App_model::MENU_SHOW,
                'app_access'    => App_model::ACCESS_AUTHORIZED,
                'app_status'    => App_model::STATUS_ACTIVE,
            ),
            array(
                'app_path'      => 'AddressBook/Group',
                'app_icon'      => 'fa fa-group',
                'app_menu'      => App_model::MENU_SHOW,
                'app_access'    => App_model::ACCESS_AUTHORIZED,
                'app_status'    => App_model::STATUS_ACTIVE
            ),
            array(
                'app_path'      => 'AddressBook/Contact',
                'app_icon'      => 'fa fa-user',
                'app_menu'      => App_model::MENU_SHOW,
                'app_access'    => App_model::ACCESS_AUTHORIZED,
                'app_status'    => App_model::STATUS_ACTIVE
            ),
        );

        // seeding the seeds
        $this->seeding();

    }

    public function down()
    {
        get_instance()->db->delete('address_groups');
        get_instance()->db->delete('address_contacts');
    }
}
?>