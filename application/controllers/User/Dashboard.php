<?php
/**
 * Dashboard Controller
 *
 * Provide Dashboard functions.
 *
 */

class Dashboard extends ER_Controller {
    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct(){
        parent::__construct();
        $this->breadcrumb = false;
        $this->create_button = false;
    }

    /**
     *
     * Index Page for this controller.
     *
     */
    public function index()
    {
        redirect('/User/Dashboard/list');
    }

    /**
     *
     * List Page for this controller.
     *
     */
    public function getList()
    {
        $this->data['widgets'] = array();
        $this->load->helper('directory');
        $apps = directory_map(APPPATH . 'controllers', 1);
        foreach ($apps as $key => $app)
        {
            $controller_files = directory_map(APPPATH . 'controllers/' . $app, 1);
            if(is_array($controller_files))
            {
                foreach ($controller_files as $key => $controller_file)
                {
                    if(user_hasaccess($this->userdata(), $this->app->getByPath($app . str_replace('.php', '', $controller_file))))
                    {
                        if(is_file(APPPATH . 'controllers/' . $app . $controller_file))
                        {
                            $class = pathinfo(APPPATH . 'controllers/' . $app . $controller_file)['filename'];
                            if(!class_exists($class))
                            {
                                include_once(APPPATH . 'controllers/' . $app . $controller_file);
                            }
                            if(class_exists($class))
                            {
                                if(method_exists($class, 'widgets'))
                                {
                                    $widgets = call_user_func_array(array($class, 'widgets'), array());
                                    $this->data['widgets'] = array_merge($this->data['widgets'], $widgets);
                                }
                            }
                        }
                    }

                }
            }
        }
    }
}

/* End of file Dashboard.php */
/* Location: ./application/controllers/User/Dashboard.php */