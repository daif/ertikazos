<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class {class_name} extends ER_Migration {
    
    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct(){
        parent::__construct();
    }

    /**
     * prepare the migration array
     *
     * @return  void
     */
    public function setup()
    {
        // migration array
        // $this->migration[''] = [];
    }

    // migrating
    public function up()
    {
        // prepare migration array
        $this->setup();
        // migrating the above array
        $this->migrating();
    }

    // un-migrating
    public function down()
    {
        // prepare migration array
        $this->setup();
        // remove migration
        foreach ($this->migration as $table => $fields)
        {
            if(get_instance()->db->count_all($table) == 0)
            {
                get_instance()->dbforge->drop_table($table, TRUE);
            }
        }
    }
}
