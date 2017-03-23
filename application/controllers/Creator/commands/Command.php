<?php
/**
 * 
 * Command class
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class Command {

    /**
     * The creator object 
     *
     * @var object
     */
    public $creator = NULL;

    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct()
    {
        $this->creator = &get_instance();
    }

    /**
     * Overloading variables
     */
    public function __get($name)
    {
        return (isset($this->creator->$name))?$this->creator->$name:NULL;
    }

    /**
     * Overloading functions
     */
    public function __call($name, $arguments)
    {
        return (method_exists($this->creator, $name))?call_user_func_array(array(&$this->creator,$name), $arguments):NULL;
    }
}

/* End of file Command.php */
/* Location: ./application/Creator/commands/Command.php */