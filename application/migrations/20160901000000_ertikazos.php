<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Ertikazos extends ER_Migration {
    
    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct(){
        parent::__construct();
    }

    public function up()
    {
        // er_settings table
        $this->migration['er_settings'] = array(
            'name'  =>  'er_settings',
            'key'   =>  'id',
            'field' =>  array(
                'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '300',
                    'key' => TRUE,
                ),
                'value' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '300',
                    'null' => TRUE,
                ),
                'rules' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '300',
                    'null' => TRUE,
                ),
                'sort' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE,
                ),
            ),
            'attributes' => array('ENGINE' => 'MyISAM'),
        );

        // er_apps table
        $this->migration['er_apps'] = array(
            'name'  =>  'er_apps',
            'key'   =>  'app_id',
            'field' =>  array(
                'app_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'app_path' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '45',
                    'key' => TRUE,
                ),
                'app_icon' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '45',
                ),
                'app_sort' => array(
                    'type' => 'INT',
                    'constraint' => 3,
                    'default' => 0,
                    'null' => TRUE,
                ),
                'app_menu' => array(
                    'type' => 'INT',
                    'constraint' => 1,
                ),
                'app_access' => array(
                    'type' => 'INT',
                    'constraint' => 1,
                ),
                //table suffix
                'app_create_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => '0'
                ),
                'app_update_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => '0'
                ),
                'app_create_at' => array(
                    'type' => 'datetime',
                    'null' => TRUE
                ),
                'app_update_at' => array(
                    'type' => 'datetime',
                    'null' => TRUE
                ),
                'app_status' => array(
                    'type' => 'INT',
                    'constraint' => 2,
                ),
            ),
            'attributes' => array('ENGINE' => 'MyISAM'),
        );

        // er_groups table
        $this->migration['er_groups'] = array(
            'name'  =>  'er_groups',
            'key'   =>  'group_id',
            'field' =>  array(
                'group_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
               'group_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '64',
                ),
                //table suffix
                'group_create_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => '0'
                ),
                'group_update_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => '0'
                ),
                'group_create_at' => array(
                    'type' => 'datetime',
                    'null' => TRUE
                ),
                'group_update_at' => array(
                    'type' => 'datetime',
                    'null' => TRUE
                ),
                'group_status' => array(
                    'type' => 'INT',
                    'constraint' => 2,
                    'default' => '0'
                ),
            ),
            'attributes' => array('ENGINE' => 'MyISAM'),
        );

        // er_users table
        $this->migration['er_users'] = array(
            'name'  =>  'er_users',
            'key'   =>  'user_id',
            'field' =>  array(
                'user_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'user_type' => array(
                    'type' => 'INT',
                    'constraint' => 2,
                ),
               'user_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '64',
                    'key' => TRUE,
                ),
                'user_email' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '64',
                    'key' => TRUE,
                ),
                'user_pass' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '64',
                ),
                'user_mobile' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '15',
                    'default' => '0'
                ),
                'user_avatar' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                    'default' => '0'
                ),
                'user_options' => array(
                    'type' => 'TEXT',
                    'null' => TRUE
                ),
                'user_code' => array(
                    'type' => 'INT',
                    'constraint' => '11',
                    'default' => '0'
                ),
                //table suffix
                'user_create_by' => array(
                    'type' => 'INT',
		    'constraint' => 11,
                    'default' => '0'
                ),
                'user_update_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => '0'
                ),
                'user_create_at' => array(
                    'type' => 'datetime',
                    'null' => TRUE
                ),
                'user_update_at' => array(
                    'type' => 'datetime',
                    'null' => TRUE
                ),
                'user_status' => array(
                    'type' => 'INT',
                    'constraint' => 2,
                    'default' => '0'
                ),
            ),
            'attributes' => array('ENGINE' => 'MyISAM'),
        );

        // er_users_rels table
        $this->migration['er_users_rels'] = array(
            'name'  =>  'er_users_rels',
            'key'   =>  'rel_id',
            'field' =>  array(
                'rel_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'rel_user_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'key' => TRUE,
                ),
                'rel_group_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'key' => TRUE,
                ),
                //table suffix
                'rel_create_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => '0'
                ),
                'rel_create_at' => array(
                    'type' => 'datetime',
                    'null' => TRUE
                ),
            ),
            'attributes' => array('ENGINE' => 'MyISAM'),
        );

        // er_users_ses table
        $this->migration['er_users_ses'] = array(
            'name'  =>  'er_users_ses',
            'key'   =>  'id',
            'field' =>  array(
                'id' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 40,
                    'key' => TRUE,
                ),
                'timestamp' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'ip_address' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 45,
                ),
                'data' => array(
                    'type' => 'BLOB',
                )
            ),
            'attributes' => array('ENGINE' => 'MyISAM'),
        );

        // er_users_notify table
        $this->migration['er_users_notify'] = array(
            'name'  =>  'er_users_notify',
            'key'   =>  'notify_id',
            'field' =>  array(
                'notify_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'notify_user_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'key' => TRUE,
                ),
                'notify_title' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 32,
                ),
                'notify_body' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 160,
                ),
                //table suffix
                'notify_create_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => '0'
                ),
                'notify_update_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => '0'
                ),
                'notify_create_at' => array(
                    'type' => 'datetime',
                    'null' => TRUE
                ),
                'notify_update_at' => array(
                    'type' => 'datetime',
                    'null' => TRUE
                ),
                'notify_status' => array(
                    'type' => 'INT',
                    'constraint' => 2,
                ),
            ),
            'attributes' => array('ENGINE' => 'MyISAM'),
        );

        // er_permissions table
        $this->migration['er_permissions'] = array(
            'name'  =>  'er_permissions',
            'key'   =>  'perm_id',
            'field' =>  array(
                'perm_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'perm_app_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'key' => TRUE,
                ),
                'perm_group_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'key' => TRUE,
                    'null' => TRUE
                ),
                'perm_user_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'key' => TRUE,
                    'null' => TRUE
                ),
                //table suffix
                'perm_create_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => '0'
                ),
                'perm_create_at' => array(
                    'type' => 'datetime',
                    'null' => TRUE
                ),
            ),
            'attributes' => array('ENGINE' => 'MyISAM'),
        );

        // er_logs table
        $this->migration['er_logs'] = array(
            'name'  =>  'er_logs',
            'key'   =>  'log_id',
            'field' =>  array(
                'log_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'log_date' => array(
                    'type' => 'datetime',
                ),
                'log_type' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 8,
                    'key' => TRUE,
                ),
                'log_ip' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 15,
                ),
                'log_app_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'key' => TRUE,
                ),
                'log_user_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'key' => TRUE,
                ),
                'log_variables' => array(
                    'type' => 'TEXT',
                ),
                'log_time' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'key' => TRUE,
                ),
            ),
            'attributes' => array('ENGINE' => 'MyISAM'),
        );

        // do the migration
        $this->migrating();

    }

    public function down()
    {
        $this->dbforge->drop_table('er_settings', TRUE);
        $this->dbforge->drop_table('er_apps', TRUE);
        $this->dbforge->drop_table('er_groups', TRUE);
        $this->dbforge->drop_table('er_users', TRUE);
        $this->dbforge->drop_table('er_users_ses', TRUE);
        $this->dbforge->drop_table('er_users_rels', TRUE);
        $this->dbforge->drop_table('er_permissions', TRUE);
        $this->dbforge->drop_table('er_logs', TRUE);
    }
}
?>