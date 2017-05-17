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
        // auto create file_path to track files
        if(!$this->db->field_exists('file_path', config_item('migration_table')))
        {
            $fields = array('file_path' =>  array('type' => 'varchar', 'constraint' => 64));
            $this->dbforge->add_column(config_item('migration_table'), $fields);
            $this->db->query('ALTER TABLE `'.config_item('migration_table').'` ADD UNIQUE (`version`, `file_path`)');
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
            'name' => 'migrate', 
            'desc' => 'Migrate all file to database or passed $version or $name.', 
            'vars' => [
                [
                    'name' => '$version or $name', 
                    'desc' => 'Migrating file by its [success]$version[/success] or [success]$name[/success].',
                ],
                [
                    'name' => '$direction', 
                    'desc' => 'Migrating direction [success]up[/success] or [success]down[/success].',
                ],
            ],
        ];
    }

    /**
     *
     * migrate command.
     *
     */
    public function migrate($target_version = FALSE, $direction = 'up')
    {
        $this->_print('', '');
        if($target_version === FALSE)
        {
            $migration_path = '*_*.php';
        }
        elseif(preg_match('/[0-9]{14}/', $target_version))
        {
            $migration_path = ''.$target_version.'_*.php';
        } else
        {
            $migration_path = '*_'.strtolower($target_version).'.php';
        }

        // find migration files 
        foreach (glob(config_item('migration_path').$migration_path) as $key => $migration_file)
        {
            list($timestamp, $filename) = explode('_', basename($migration_file));
            // search for the migration file in database
            $migration_row = $this->db->select('file_path')->get_where(config_item('migration_table'), array('file_path' => basename($migration_file)))->row();

            require_once(config_item('migration_path').''.basename($migration_file));
            $migration = 'Migration_'.ucfirst(explode('.', $filename)[0]);

            if(!class_exists($migration))
            {
                $this->_print("migration class '$migration' is not found", 'error');
                continue;
            }
            // new migration instance
            $migration = new $migration;
            if(strtolower($direction) == 'down')
            {
                if($migration_row)
                {
                    $migration->down();

                    $this->db->delete(config_item('migration_table'), array('file_path' => basename($migration_file)));
                    $this->_print(basename($migration_file).' Un-Migrated.', 'success');
                }
                else
                {
                    // migration file is not migrated yet
                    $this->_print(basename($migration_file).' is not migrated yet.', 'success');
                    continue;
                }
            }
            else
            {
                if($migration_row)
                {
                    // migrated file is already migrated
                    $this->_print(basename($migration_file).' is already migrated.', 'warning');
                    continue;
                }
                else
                {
                    $migration->up();
                    $this->db->insert(config_item('migration_table'), array('file_path' => basename($migration_file)));
                    $this->_print(basename($migration_file).' Migrated.', 'success');
                }
            }
        }
        if(!isset($migration_file))
        {
            $this->_print("Migrating target '$target_version' is not found.", 'error');
        }
        $this->_print('', '');
    }
}

?>