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

    public function up()
    {
        // migration array
        // $this->migration[''] = [];
        // migrating the above array
        $this->migrating();
    }

    public function down()
    {

    }
}
