<?php
/**
 * Creator Controller
 *
 * Provide create new application.
 *
 */

class Creator extends ER_Controller {
    /**
     * The commands path
     *
     * @var String
     */
    public $commands_path = APPPATH.'controllers/Creator/commands/';

    /**
     * The commands files array
     *
     * @var array
     */
    public $commands_files = array();

    /**
     * The commands array
     *
     * @var array
     */
    public $commands = array();

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
        $this->load->library('directory');
        $this->load->helper('directory');
        include_once(APPPATH . 'controllers/Creator/commands/Command.php');
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
        $commands_path_files = directory_map($this->commands_path, 1);
        foreach ($commands_path_files as $key => $command_file) {
            if(preg_match('/([a-z]+)_Command/i', $command_file, $match)) {
                // include command file
                include_once($this->commands_path.$command_file);
                $cmd_class = $match[0];
                $cmd_name  = strtolower($match[1]);
                $this->commands[$cmd_name]  = $cmd_class::commands();
                $this->commands_files[$cmd_name] = $command_file;
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
            $command_class = ucfirst(strtolower($method)).'_Command';
            $command_object = new $command_class;
            $command_object->creator = &$this;
            call_user_func_array(array($command_object, $method), array_slice($this->uri->rsegments, 2));
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
        $search = ['[error]','[/error]','[warning]','[/warning]','[success]','[/success]'];
        $replace = ["\033[31m","\033[0m","\033[33m","\033[0m","\033[32m","\033[0m"];

        if(in_array('['.$type.']', $search))
        {
            $text = '['.$type.']'.$text.'[/'.$type.']';
        }

        return str_replace($search, $replace, $text);
    }

    /**
     *
     * _prompt command.
     *
     */
    public function _prompt($message, $type = '')
    {
        $this->print($message, $type);
        $stdin      = fopen('php://stdin', 'r');
        $response   = trim(fgetc($stdin));
        return($response);
    }

}

/* End of file Creator.php */
/* Location: ./application/controllers/Creator/Creator.php */