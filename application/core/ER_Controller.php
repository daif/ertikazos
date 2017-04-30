<?php
/**
 * ertikaz super class
 *
 * This class object is the super class 
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class ER_Controller extends CI_Controller{
    /**
     * The current version of ErtikazOS
     */
    const ER_VERSION = '1.4.0';

    /**
    * The variables array
    */
    public $data = array();

    /**
    * The default layout name.
    */
    public $layout = '';

    /**
    * The default view name.
    */
    public $viewname = '';

    /**
    * if this value is true we will auto load view after call method
    */
    public $autoloadview = TRUE;

    /**
    * The userdata object
    */
    public $userdata = NULL;

    /**
    * The log object
    */
    public $watchlog = NULL;

    /**
    * if this value is true we will display breadcrumb
    */
    public $breadcrumb = TRUE;

    /**
    * if this value is true we will display create button
    */
    public $create_button = FALSE;

    /**
    * if this value is true we will display search button
    */
    public $search_button = FALSE;

    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct(){
        parent::__construct();

        // load session
        $this->load->driver('session');

        // auto save post vars
        if($this->input->server('REQUEST_METHOD') == 'POST')
        {
            $this->session->set_flashdata('old_post_data',$this->input->post());
        }

        // auto assign current user to userdata var
        if($this->session->has_userdata('userdata'))
        {
            $this->userdata = $this->session->userdata('userdata');
        }

        // auto set language var if not set  
        if( ! $this->session->has_userdata('lang'))
        {
            $this->session->set_userdata('lang', 'english');
        }

        // auto load language file for global and current app
        $this->lang->load('global', $this->session->userdata('lang'));
        if(file_exists(APPPATH.'language/'.$this->session->userdata('lang').'/'.strtolower(trim($this->router->directory,'/')).'_lang.php'))
        {
            $this->lang->load(strtolower(trim($this->router->directory,'/')), $this->session->userdata('lang'));
        }

        // check user permissions
        if($this->router->directory != 'Auth/' && $this->uri->rsegment(1) != 'Home' && !is_cli() )
        {
            if($this->uri->rsegment(3))
            {
                $app = $this->app->getByPath($this->router->directory.$this->uri->rsegment(1).'/'.$this->uri->rsegment(2));
            }
            if(!isset($app) || !is_object($app))
            {
                $app = $this->app->getByPath($this->router->directory.$this->uri->rsegment(1));
            }
            if(!isset($app) || !is_object($app))
            {
                $app = $this->app->getByPath($this->router->directory);
            }
            if(!user_hasaccess($this->userdata, $app))
            {
                redirect('/Auth/Login');
            }
        }

        if(isset($app))
        {
            $this->watchlog = watchdog('ACCESS', $app->app_id, $_REQUEST);
        }
    }
    
    //remapping the method calls
    public function _remap($method, $params = []) {
        $_method = @strtolower($_SERVER['REQUEST_METHOD']).ucfirst($method);
        // if postMethod or getMethod exists call it
        if (method_exists($this, $_method))
        {
            $data = call_user_func_array(array($this, $_method), array_slice($this->uri->rsegments, 2));
        }
        // if method exists call it
        elseif (method_exists($this, $method))
        {
            $data = call_user_func_array(array($this, $method), array_slice($this->uri->rsegments, 2));
        }
        // if _404 method exists call it
        elseif (method_exists($this, '_404'))
        {
            $data = call_user_func_array(array($this, '_404'), array($method, array_slice($this->uri->rsegments, 2)));
        }
        // else call show_404
        else
        {
            show_404();
        }

        //set router vars
        $this->data['router_dir']    = $this->router->directory;
        $this->data['router_class']  = $this->router->class;
        $this->data['router_method'] = $this->router->method;

        //by default load the view 
        if($this->autoloadview)
        {
            $this->autoLoadView($data);
        }
        if($this->watchlog)
        {
            update_watchdog($this->watchlog);
        }
    }

    //Automatically load the nearest view 
    public function autoLoadView($data=false) {
        // if $data is array add it to variables array
        if(is_array($data))
        {
            $this->data +=$data;
        }

        // if $data is object convert it to array and add it to variables array
        elseif(is_object($data))
        {
            $this->data +=(array)$data;
        }

        // set views path
        $views = 'controllers/'.$this->router->directory.'views';

        // try to find the layout view
        if(file_exists(APPPATH.'views/'.strtolower($this->router->directory.$this->router->class).'/'.strtolower($this->router->method).'-layout.php'))
        {
            $layout = strtolower($this->router->directory.$this->router->class).'/'.strtolower($this->router->method).'-layout';
        }
        elseif(file_exists(APPPATH.'views/'.strtolower($this->router->directory.$this->router->class).'/layout.php'))
        {
            $layout = strtolower($this->router->directory.$this->router->class).'/layout';
        }
        elseif(file_exists(APPPATH.'views/'.strtolower($this->router->directory).'layout.php'))
        {
            $layout = strtolower($this->router->directory).'layout';
        }
        elseif(file_exists(APPPATH.'views/layout.php'))
        {
            $layout = 'layout';
        }

        if(!$this->viewname)
        {
            // try to find the view 
            if(file_exists(APPPATH.'views/'.strtolower($this->router->directory.$this->router->class).'/'.strtolower($this->router->method).'.php'))
            {
                $this->data['content'] = $this->load->view(strtolower($this->router->directory.$this->router->class).'/'.strtolower($this->router->method), $this->data, isset($layout));
            }
            elseif(file_exists(APPPATH.'views/'.strtolower($this->router->directory.$this->router->class).'/index.php'))
            {
                $this->data['content'] = $this->load->view(strtolower($this->router->directory.$this->router->class.'/index'), $this->data, isset($layout));
            }
            elseif(file_exists(APPPATH.'views/'.strtolower($this->router->directory.$this->router->class).'.php'))
            {
                $this->data['content'] = $this->load->view(strtolower($this->router->directory.$this->router->class), $this->data, isset($layout));
            }
        }
        else 
        {
            $this->data['content'] = $this->load->view($this->viewname, $this->data, isset($layout));
        }
        
        // if layout set load it 
        if(isset($layout))
        {
            $this->load->view($layout, $this->data);
        }
    }

    // parsing @section and @yield in output
    public function _output($output) {
        $pattern = '#\@section\(([a-z_\-\'\"]+)\)(.+)@endsection#Uis';
        preg_match_all($pattern, $output, $matches);
        if(count($matches[1]) > 0)
        {
            $sections = array();
            foreach ($matches[1] as $key => $var)
            {
                $section_name = strtolower(trim($matches[1][$key],"'\""));
                if(!isset($sections[$section_name]))
                {
                    $sections[$section_name] = array();
                }
                $sections[$section_name][] = $matches[2][$key];
                // remove section and added to it's yield
                $output = str_replace($matches[0][$key], '', $output);
            }
            // append sections to it's yield
            foreach ($sections as $section_name => $section)
            {
                $output = str_ireplace('@yield(\''.$section_name.'\')', implode("\n", $section), $output);
            }
        }
        // remove any unused yield
        $output = preg_replace('#@yield\(([a-z_\-\'\"]+)\)#i', '', $output);
        echo $output;
    }

    // get user data
    public function userdata($attr=NULL) {
        if($attr !== NULL)
        {
            if(isset($this->userdata->$attr))
            {
                return $this->userdata->$attr;
            }
        }
        else
        {
            return $this->userdata;
        }
        return FALSE;
    }
}

/* End of file ER_Controller.php */
/* Location: ./application/core/ER_Controller.php */