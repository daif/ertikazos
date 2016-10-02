<?php
/**
 * ertikaz migration class
 *
 * This class object is migration class 
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class ER_Migration extends CI_Migration{
    /**
     * The migration array of tables
     *
     * @var array
     */
    public $migration = array();

    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct(){
        parent::__construct();
    }


    /**
     * migrating the database
     *
     * @return  boolean 
     */
    function migrating() {
        foreach ($this->migration as $key => $table) {
            $this->dbforge->add_field($table['field']);
            $this->dbforge->add_key($table['key'], TRUE);
            foreach ($table['field'] as $field_name => $field)
            {
                if(isset($field['key']))
                {
                    $this->dbforge->add_key($field_name);
                }
            }
            if($this->dbforge->create_table($table['name'], TRUE, $table['attributes'])) {
                print $table['name']." Table has been created\n";
            } else {
                print $table['name']." Table already existed\n";
            }
        }
    }

}

/* End of file ER_Migration.php */
/* Location: ./application/core/ER_Migration.php */
