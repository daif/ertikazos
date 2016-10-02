<?php
/**
 * Config Controller
 *
 * Provide Config functions.
 *
 */

class Config extends ER_Controller {
    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('Admin/Setting_model', 'setting');
    }

    /**
     *
     * Index Page for this controller.
     *
     */
    public function index()
    {
        redirect('/Admin/Config/list');
    }

    /**
     *
     * List Page for this controller.
     *
     */
    public function getList()
    {
        $this->data['setting_list'] = $this->setting->mainSettings();
    }

    /**
     *
     * postList Page for this controller.
     *
     */
    public function postList()
    {
        $input = $this->input->post();
        foreach ($input as $name => $value)
        {
            $config = $this->setting->row(array('name' => $name));
            if(is_object($config))
            {
                $config->value = $value;
                $config->update();
            }
        }
        redirect('/Admin/Config/list');
    }

}

/* End of file Config.php */
/* Location: ./application/controllers/Admin/Config.php */