<?php
/**
 *
 * Rollback command class
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class Rollback_Command extends Command {

    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct()
    {
        $this->load->library('migration');
        $this->load->library('directory');
        $this->load->helper('directory');
        $this->config->load('migration');
        // auto-load Migration and Seeder
        include_once(APPPATH . 'core/ER_Migration.php');
        include_once(APPPATH . 'core/ER_Seeder.php');
    }

    /**
     * commands function.
     *
     * return command list as array.
     *
     * @access public
     * @return array
     */
    public static function commands()
    {
        return [
            'name' => 'rollback', 
            'desc' => 'Rollback the database to the perverse migration version.', 
        ];
    }

    /**
     *
     * rollback command.
     *
     */
    public function rollback($target_version = FALSE)
    {
        $migration_files = $this->migration->find_migrations();
        $current_version = $this->db->select('version')->get(config_item('migration_table'))->row();
        // rollback the last migration file
        if($target_version === FALSE)
        {
            foreach ($migration_files as $migration_version => $migration_file)
            {
                if($migration_version < $current_version->version)
                {
                    $target_version = $migration_version;
                }
            }
        }
        // rollback to the target version
        if($target_version > 0 && $target_version < $current_version->version)
        {
            if(!array_key_exists($target_version, $migration_files))
            {
                $this->_print("Rollback version '" . $target_version . "' is not found", 'error');
                return;
            }
        }
        else
        {
            $this->_print("Nothing to rollback", 'error');
            return; 
        }

        if ($this->migration->version($target_version) === FALSE)
        {
            show_error($this->migration->error_string());
        }
        else
        {
            $this->_print("Rollback to migration version '" . $target_version . "' done", 'success');
        }
    }

}

?>