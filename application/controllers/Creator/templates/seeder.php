<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class {class_name} extends ER_Seeder {

    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct(){
        parent::__construct();
    }

    /**
     * prepare the seeds array
     *
     * @return  void
     */
    public function setup()
    {
        // seeds array
        // $this->seeds[''] = [];
    }

    // seeding 
    public function up()
    {
        // prepare seeds array
        $this->setup();
        // seeding the seeds
        $this->seeding();
    }

    // un-seeding 
    public function down()
    {
        // prepare seeds array
        $this->setup();
        // delete the seeded data
        foreach ($this->seeds as $table => $rows)
        {
            foreach ($rows as $key => $where)
            {
                get_instance()->db->delete($table, $where);
            }            
        }
    }
}
