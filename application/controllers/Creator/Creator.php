<?php
/**
 * Creator Controller
 *
 * Provide create new application.
 *
 */

class Creator extends ER_Controller {
    /**
     * The commands array
     *
     * @var array
     */
    public $commands = array();

    /**
     * The commands class array
     *
     * @var array
     */
    public $commands_class = array();

    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct()
    {
        parent::__construct();
        if(!is_cli())
        {
            $this->_print("Run via the command-line", 'error');
            return false;
        }
        $this->autoloadview = FALSE;
        $this->load->library('migration');
        $this->load->library('directory');
        $this->load->helper('directory');
        $this->config->load('migration');
        // auto-load Migration and Seeder
        include_once(APPPATH . 'core/ER_Migration.php');
        include_once(APPPATH . 'core/ER_Seeder.php');
        // call register_commands
        $this->register_commands();
    }

    /**
     *
     * Index by default print help.
     *
     */
    public function index()
    {
        $command_name_padding = 0;
        $command_vars_padding = 0;
        foreach ($this->commands as $key => $command) {
            if(isset($command['vars']) && is_array($command['vars']))
            {
                if(strlen($command['name'])+2 > $command_name_padding)
                {
                    $command_name_padding = strlen($command['name'])+2;
                }

                foreach ($command['vars'] as $key => $var)
                {
                    if(strlen($var['name'])+5 > $command_vars_padding)
                    {
                        $command_vars_padding = strlen($var['name'])+5;
                    }
                }
            }
        }
        $this->_print("", "", "\n");
        $this->_print("Usage:", "warning", "\n");

        foreach ($this->commands as $key => $command) {
            $this->_print(" creator ", "warning", "");
            $this->_print(str_pad($command['name'], $command_vars_padding+3 ), "warning", "");
            $this->_print($command['desc'], "", "\n");
            if(isset($command['vars']) && is_array($command['vars']))
            {
                foreach ($command['vars'] as $key => $var)
                {
                    $this->_print("         ".str_pad($var['name'], $command_vars_padding+3), "success", "");
                    $this->_print($var['desc'], "", "\n");
                }
            }
            
        }
        $this->_print("", "", "\n");
    }

    /**
     *
     * register_commands function .
     *
     */
    public function register_commands()
    {
        $cmd_path  = APPPATH .'controllers/Creator/commands/';
        $cmd_files = directory_map($cmd_path, 1);

        foreach ($cmd_files as $key => $cmd_file) {
            if(preg_match('/([a-z]+)_Command/i', $cmd_file, $match)) {
                $cmd_class = $match[0];
                $cmd_name  = strtolower($match[1]);
                include_once($cmd_path.$cmd_file);
                $this->class[$cmd_name]     = new $cmd_class;
                $this->class[$cmd_name]->CI = &$this;
                $this->commands[$cmd_name]  = $this->class[$cmd_name]->commands();
            }
        }
    }

    /**
     *
     * remap the command.
     *
     */
    public function _remap($method, $params = [])
    {
        //if method exists call it
        if (array_key_exists($method, $this->commands))
        {
            call_user_func_array(array($this->class[$method], $method), array_slice($this->uri->rsegments, 2));
        }
        else
        {
            if($method != 'help' && $method != 'index' && $method != '?')
            {
                $this->_print("", "", "\n");
                $this->_print("Error: command $method is not found.", 'error');
            }
            call_user_func_array(array($this, 'index'), array_slice($this->uri->rsegments, 2));
        }
    }


    /**
     *
     * _print command.
     *
     */
    public function _print($message, $type = '', $append="\n")
    {
        echo $this->_color($message, $type).$append;
    }

    /**
     *
     * color the text.
     *
     */
    public function _color($text, $type = '')
    {
        if($type == 'error')
        {
            return "\033[31m".$text."\033[0m";
        }
        elseif($type == 'warning')
        {
            return "\033[33m".$text."\033[0m";
        }
        elseif($type == 'success')
        {
            return "\033[32m".$text."\033[0m";
        }
        else
        {
            return $text;
        }
    }

    /**
     *
     * _prompt command.
     *
     */
    public function _prompt($message, $type = '')
    {
        $this->print($this->_color($message, $type));
        $stdin      = fopen('php://stdin', 'r');
        $response   = trim(fgetc($stdin));
        return($response);
    }

}

/* End of file Creator.php */
/* Location: ./application/controllers/Creator/Creator.php */