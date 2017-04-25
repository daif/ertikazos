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
        $this->config->load('migration');
        // auto-load Migration and Seeder
        include_once(APPPATH . 'core/ER_Migration.php');
        include_once(APPPATH . 'core/ER_Seeder.php');
        // auto create file_path to track files
        if(!$this->db->field_exists('file_path', config_item('migration_table')))
        {
            $fields = array('file_path' =>  array('type' => 'varchar', 'constraint' => 64));
            $this->dbforge->add_column(config_item('migration_table'), $fields);
        }
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
            'desc' => 'Seed all file to database or the passed $version or $name..', 
            'vars' => [
                [
                    'name' => '$version or $name', 
                    'desc' => 'Seeding file by its [success]$version[/success] or [success]$name[/success].',
                ],
                [
                    'name' => '$direction', 
                    'desc' => 'Seeding direction [success]up[/success] or [success]down[/success].',
                ],
            ],
        ];
    }

    /**
     *
     * seed command.
     *
     */
    public function seed($target_version = FALSE, $direction = 'up')
    {
        $this->_print('', '');
        if($target_version === FALSE)
        {
            $seed_path = 'seeds/*_*.php';
        }
        elseif(preg_match('/[0-9]{14}/', $target_version))
        {
            $seed_path = 'seeds/'.$target_version.'_*.php';
        } else
        {
            $seed_path = 'seeds/*_'.strtolower($target_version).'.php';
        }

        // find seeds files 
        foreach (glob(config_item('migration_path').$seed_path) as $key => $seed_file)
        {
            list($timestamp, $filename) = explode('_', basename($seed_file));
            // search for the seed file in database
            $seed_row = $this->db->select('file_path')->get_where(config_item('migration_table'), array('file_path' => 'seeds/'.basename($seed_file)))->row();

            require_once(config_item('migration_path').'seeds/'.basename($seed_file));
            $seeder = ucfirst(explode('.', $filename)[0]).'_Seeder';

            if(!class_exists($seeder))
            {
                $this->_print("seed class '$seeder' is not found", 'error');
                continue;
            }
            // new seed instance
            $seeder = new $seeder;
            if(strtolower($direction) == 'down')
            {
                if($seed_row)
                {
                    $seeder->down();

                    $this->db->delete(config_item('migration_table'), array('file_path' => 'seeds/'.basename($seed_file)));
                    $this->_print('seeds/'.basename($seed_file).' Un-Seeded.', 'success');
                }
                else
                {
                    // seed is not seeded yet
                    $this->_print('seeds/'.basename($seed_file).' is not seeded yet.', 'success');
                    continue;
                }
            }
            else
            {
                if($seed_row)
                {
                    // seed is already seeded
                    $this->_print('seeds/'.basename($seed_file).' is already seeded.', 'warning');
                    continue;
                }
                else
                {
                    $seeder->up();
                    $this->db->insert(config_item('migration_table'), array('file_path' => 'seeds/'.basename($seed_file)));
                    $this->_print('seeds/'.basename($seed_file).' Seeded.', 'success');
                }
            }
        }
        if(!isset($seed_file))
        {
            $this->_print("Seeding target '$target_version' is not found.", 'error');
        }
        $this->_print('', '');
    }
}

?>