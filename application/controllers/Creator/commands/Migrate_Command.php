<?php
/**
 * Migrate command class
 *
 * Migrate command class
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class Migrate_Command {

    /**
     * The CodeIgniter instance 
     *
     * @var object
     */
    public $CI = NULL;

    /**
     * Overloading variables
     */
    public function __get($name)
    {
        return (isset($this->CI->$name))?$this->CI->$name:NULL;
    }

    /**
     * Overloading functions
     */
    public function __call($name, $arguments)
    {
        return (method_exists($this->CI, $name))?call_user_func_array(array(&$this->CI,$name), $arguments):NULL;
    }

    /**
     * commands function.
     *
     * return command list as array.
     *
     * @access public
     * @return array
     */
    public function commands()
    {
        return [
            'name' => 'migrate', 
            'desc' => 'Migrate the database to last migration version.', 
        ];
    }

    /**
     *
     * migrate command.
     *
     */
    public function migrate($target_version = FALSE)
    {
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

}

?>