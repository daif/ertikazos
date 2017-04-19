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
        $this->load->model('{app_name}/{class_name}_model', '{model_name}');
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
        $count  = $this->{model_name}->count();
        $this->data['paging'] = make_paging($count, $limit);
        $this->data['rows_list'] = $this->{model_name}->rows();
    }

    /**
     *
     * Show Page for this controller.
     *
     */
    public function getShow($id)
    {
        $this->data['row'] = $this->{model_name}->find($id);
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
        $input = $this->input->post(array_keys($this->{model_name}->forms['create']));
        $this->form_validation->set_rules($this->{model_name}->rules('create'));

        if ($this->form_validation->run() === TRUE)
        {
            ${model_name} = new $this->{model_name};
            foreach ($input as $key => $value) {
                ${model_name}->$key = $value;
            }
            ${model_name} = ${model_name}->insert();
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
        $this->data['row'] = $this->{model_name}->find($id);
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
        $input = $this->input->post(array_keys($this->{model_name}->forms['edit']));
        $this->form_validation->set_rules($this->{model_name}->rules('edit'));

        if ($this->form_validation->run() === TRUE)
        {
            ${model_name} = $this->{model_name}->find($id);
            if(is_object(${model_name}))
            {
                foreach ($input as $key => $value) {
                    ${model_name}->$key = $value;
                }
                ${model_name}->update();
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
        $input = $this->input->post(array_keys($this->{model_name}->forms['delete']));
        ${model_name} = $this->{model_name}->user_find($input[$this->{model_name}->primaryKey]);
        if(is_object(${model_name}))
        {
            ${model_name}->delete();
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