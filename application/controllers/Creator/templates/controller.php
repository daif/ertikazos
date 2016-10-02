<?php
/**
 * {class_name} Controller
 *
 * Provide {class_name} functions.
 *
 */

class {class_name} extends ER_Controller {
    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('{app_name}/{class_name}_model', 'model');
    }

    /**
     *
     * Index Page for this controller.
     *
     */
    public function index()
    {
        redirect('/{app_name}/{class_name}/list');
    }

    /**
     *
     * List Page for this controller.
     *
     */
    public function getList()
    {
        $this->create_button = true;
        $limit  = 10;
        $offset = $this->input->get('page');
        $count  = $this->model->count();
        $this->data['paging'] = make_paging($count, $limit);
        $this->data['rows_list'] = $this->model->rows();
    }

    /**
     *
     * Show Page for this controller.
     *
     */
    public function getShow($id)
    {
        $this->data['row'] = $this->model->find($id);
        if(!is_object($this->data['row']))
        {
            set_message('row_is_not_found', 'error');
            redirect('/{app_name}/{class_name}/list');
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
        $input = $this->input->post(array_keys($this->model->forms['create']));
        $this->form_validation->set_rules($this->model->rules('create'));

        if ($this->form_validation->run() === TRUE)
        {
            $model = new $this->model;
            foreach ($input as $key => $value) {
                $model->$key = $value;
            }
            $model = $model->insert();
            set_message('row_has_been_created', 'success');
            redirect('/{app_name}/{class_name}/list');
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
            redirect('/{app_name}/{class_name}/create');
        }
    }

    /**
     *
     * getEdit Page for this controller.
     *
     */
    public function getEdit($id)
    {
        $this->data['row'] = $this->model->find($id);
        if(!is_object($this->data['row']))
        {
            set_message('row_is_not_found', 'error');
            redirect('/{app_name}/{class_name}/list');
        }
    }

    /**
     *
     * postEdit Page for this controller.
     *
     */
    public function postEdit($id)
    {
        $input = $this->input->post(array_keys($this->model->forms['edit']));
        $this->form_validation->set_rules($this->model->rules('edit'));

        if ($this->form_validation->run() === TRUE)
        {
            $model = $this->model->find($id);
            if(is_object($model))
            {
                foreach ($input as $key => $value) {
                    $model->$key = $value;
                }
                $model->update();
                set_message('row_has_been_updated', 'success');
            }
            else
            {
                set_message('row_is_not_found', 'error');
                redirect('/{app_name}/{class_name}/list');
            }
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
        }
        redirect('/{app_name}/{class_name}/edit/'.$id);
    }

    /**
     *
     * postDelete Page for this controller.
     *
     */
    public function postDelete()
    {
        $input = $this->input->post(array_keys($this->model->forms['delete']));
        $model = $this->model->user_find($input[$this->model->primaryKey]);
        if(is_object($model))
        {
            $model->delete();
            set_message('row_has_been_deleted', 'success');
        }
        else
        {
            set_message('row_is_not_found', 'error');
        }
        redirect('/{app_name}/{class_name}/list');
    }

}

/* End of file {class_name}.php */
/* Location: ./application/controllers/{app_name}/{class_name}.php */