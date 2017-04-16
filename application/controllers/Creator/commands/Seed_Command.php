<?php
/**
 *
 * Seed command class
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class Seed_Command extends Command {

    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct()
    {
        parent::__construct();
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
            'name' => 'seed', 
            'desc' => 'Seed database with last seeder file.', 
            'vars' => [
                [
                    'name' => '$version', 
                    'desc' => 'Seed this $version number.',
                ],
            ],
        ];
    }

    /**
     *
     * seed command.
     *
     */
    public function seed($target_version = FALSE)
    {
        $target_version  = $this->version_by_name($target_version);
        if(!$this->db->field_exists('seed_version', config_item('migration_table')))
        {
            $fields = array('seed_version' =>  array('type' => 'BIGINT', 'constraint' => 20));
            $this->dbforge->add_column(config_item('migration_table'), $fields);
        }
        $seed_version = $this->db->select('seed_version')->get(config_item('migration_table'))->row();
        // if $target_version is not set, use migration version 
        if($target_version === FALSE)
        {
            $target_version = $this->db->select('version')->get(config_item('migration_table'))->row();
            $target_version = $target_version->version;
        }
        // if $target_version bigger than old one 
        if($target_version > $seed_version->seed_version)
        {
            $seed_files = directory_map(config_item('migration_path').'seeds/', 1);
            if(is_array($seed_files))
            {
                foreach ($seed_files as $key => $seed_file)
                {
                    list($timestamp, $filename) = explode('_', basename($seed_file));
                    // $timestamp must be less than $target_version and bigger than old target version 
                    if($timestamp <= $target_version && $timestamp > $seed_version->seed_version)
                    {
                        require_once(config_item('migration_path').'seeds/'.$seed_file);
                        $seeder = ucfirst(explode('.', $filename)[0]).'_Seeder';
                        if(class_exists($seeder))
                        {
                            $seeder = new $seeder;
                            $seeder->up();
                            $this->_print('seeds/'.$seed_file." Seeded.", 'success');
                            $this->db->update(config_item('migration_table'), array('seed_version' => $target_version));
                        }
                        else
                        {
                            $this->_print("seed class '$seeder' is not found", 'error');
                        }
                    }
                }
            }
        }
        else
        {
            $this->_print("Nothing to seed", 'error');
        }
    }

    /**
     *
     *Get target version by name.
     *
     */
    public function version_by_name($target_version)
    {
        if(!preg_match('/[0-9]{14}/', $target_version) && $target_version !== FALSE)
        {
            $version = glob(config_item('migration_path').'seeds/*_'.$target_version.'.php');
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