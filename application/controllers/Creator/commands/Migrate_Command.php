<?php
/**
 *
 * Migrate command class
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class Migrate_Command extends Command {

    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->library('migration');
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
            'name' => 'migrate', 
            'desc' => 'Migrate the database to last migration version.', 
            'vars' => [
                [
                    'name' => '$version', 
                    'desc' => 'Migrate this $version number.',
                ],
            ],
        ];
    }

    /**
     *
     * migrate command.
     *
     */
    public function migrate($target_version = FALSE)
    {
        $target_version  = $this->version_by_name($target_version);
        $current_version = $this->db->select('version')->get(config_item('migration_table'))->row();
        if($target_version !== FALSE)
        {
            if($target_version > $current_version->version)
            {
                if ($this->migration->version($target_version) === FALSE)
                {
                    show_error($this->migration->error_string());
                }
                else
                {
                    $this->_print("Migrate has been executed", 'success');
                }
            }
            else
            {
                $this->_print("Nothing to migrate", 'error');
                return;
            }
        }
        else
        {
            if ($this->migration->current() === FALSE)
            {
                show_error($this->migration->error_string());
            }
            else
            {
                $this->_print("Migrate has been executed", 'success');
            }
        }
    }

    /**
     *
     * Get target version by name.
     *
     */
    public function version_by_name($target_version)
    {
        if(!preg_match('/[0-9]{14}/', $target_version) && $target_version !== FALSE)
        {
            $version = glob(config_item('migration_path').'*_'.$target_version.'.php');
            if(isset($version[0]))
            {
                $version = explode('_', basename($version[0]));
                if(isset($version[0]))
                {
                    return $version[0];
                }
            }
        }
        return $target_version;
    }
}

?>