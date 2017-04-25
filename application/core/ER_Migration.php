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
     * Retrieves current schema version, override CI_Migration class
     *
     * @return  string  Current migration version
     */
    protected function _get_version()
    {
        $row = $this->db->select('version')->get($this->_migration_table)->row();
        return $row ? $row->version : '0';
    }

    // --------------------------------------------------------------------

    /**
     * Stores the current schema version, override CI_Migration class
     *
     * @param   string  $migration  Migration reached
     * @return  void
     */
    protected function _update_version($migration)
    {
        $this->db->update($this->_migration_table, array(
            'version' => $migration
        ));
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
