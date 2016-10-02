<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Addressbook extends ER_Migration {
    
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
        //address_groups table
        $this->migration['address_groups'] = array(
            'name'  =>  'address_groups',
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
                    'constraint' => '100',
                ),
                //table suffix
                'group_create_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'group_update_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'group_create_at' => array(
                    'type' => 'datetime',
                ),
                'group_update_at' => array(
                    'type' => 'datetime',
                ),
            ),
            'attributes' => array('ENGINE' => 'MyISAM'),
        );

        //address_contacts table
        $this->migration['address_contacts'] = array(
            'name'  =>  'address_contacts',
            'key'   =>  'contact_id',
            'field' =>  array(
                'contact_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'contact_group_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
               'contact_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                ),
               'contact_email' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                ),
               'contact_mobile' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                ),
                //table suffix
                'contact_create_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'contact_update_by' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                ),
                'contact_create_at' => array(
                    'type' => 'datetime',
                ),
                'contact_update_at' => array(
                    'type' => 'datetime',
                ),
            ),
            'attributes' => array('ENGINE' => 'MyISAM'),
        );

        // do the migration
        $this->migrating();

    }

    public function down()
    {
        $this->dbforge->drop_table('address_groups', TRUE);
        $this->dbforge->drop_table('address_contacts', TRUE);
    }
}
?>