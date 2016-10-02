<?php
/**
 * Setting Controller
 *
 * Provide Setting functions.
 *
 */

class Setting extends ER_Controller {
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
        redirect('/Admin/Setting/list');
    }

    /**
     *
     * List Page for this controller.
     *
     */
    public function getList()
    {
        $this->create_button = true;
        $this->data['rows_list'] = $this->setting->rows();
    }

    /**
     *
     * Show Page for this controller.
     *
     */
    public function getShow($id)
    {
        $this->data['row'] = $this->setting->find($id);
        if(!is_object($this->data['row']))
        {
            set_message('row_is_not_found', 'error');
            redirect('/Admin/Setting/list');
        }
    }

    /**
     *
     * getCreate Page for this controller.
     *
     */
    public function getCreate()
    {
    }

    /**
     *
     * getCreate Page for this controller.
     *
     */
    public function postCreate()
    {
        $input = $this->input->post(array_keys($this->setting->forms['create']));
        $this->form_validation->set_rules($this->setting->rules('create'));

        if ($this->form_validation->run() === TRUE)
        {
            $setting = new $this->setting;
            foreach ($input as $key => $value) {
                $setting->$key = $value;
            }
            $setting = $setting->insert();
            set_message('row_has_been_created', 'success');
            redirect('/Admin/Setting/list');
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
            redirect('/Admin/Setting/create');
        }
    }

    /**
     *
     * getEdit Page for this controller.
     *
     */
    public function getEdit($id)
    {
        $this->data['row'] = $this->setting->find($id);
        if(!is_object($this->data['row']))
        {
            set_message('row_is_not_found', 'error');
            redirect('/Admin/Setting/list');
        }
    }

    /**
     *
     * postEdit Page for this controller.
     *
     */
    public function postEdit($id)
    {
        $input = $this->input->post(array_keys($this->setting->forms['edit']));
        $this->form_validation->set_rules($this->setting->rules('edit'));

        if ($this->form_validation->run() === TRUE)
        {
            $setting = $this->setting->find($id);
            if(is_object($setting))
            {
                foreach ($input as $key => $value) {
                    $setting->$key = $value;
                }
                $setting->update();
                set_message('row_has_been_updated', 'success');
            }
            else
            {
                set_message('row_is_not_found', 'error');
                redirect('/Admin/Setting/list');
            }
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
        }
        redirect('/Admin/Setting/edit/'.$id);
    }

    /**
     *
     * postDelete Page for this controller.
     *
     */
    public function postDelete()
    {
        $input = $this->input->post(array_keys($this->setting->forms['delete']));
        $setting = $this->setting->find($input[$this->setting->primaryKey]);
        if(is_object($setting))
        {
            $setting->delete();
            set_message('row_has_been_deleted', 'success');
        }
        else
        {
            set_message('row_is_not_found', 'error');
        }
        redirect('/Admin/Setting/list');
    }

}

/* End of file Setting.php */
/* Location: ./application/controllers/Admin/Setting.php */