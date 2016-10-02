<?php
/**
 * App Controller
 *
 * Provide App functions.
 *
 */

class App extends ER_Controller {
    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('Admin/App_model', 'app');
    }

    /**
     *
     * Index Page for this controller.
     *
     */
    public function index()
    {
        redirect('/Admin/App/list');
    }

    /**
     *
     * List Page for this controller.
     *
     */
    public function getList()
    {
        $this->create_button = true;
        $this->search_button = true;
        $limit  = 10;
        $offset = $this->input->get('page');
        $count  = $this->app->count();
        $this->data['paging'] = make_paging($count, $limit);
        $this->data['rows_list'] = $this->app->rows(NULL, $limit, $offset);
    }

    /**
     *
     * List Page for this controller.
     *
     */
    public function postList()
    {
        $this->create_button = true;
        $this->search_button = true;
        $input = $this->input->post(array_keys($this->app->forms['search']));
        $this->form_validation->set_rules($this->app->rules('search'));

        if ($this->form_validation->run() === TRUE)
        {
            $limit  = 10;
            $offset = $this->input->get('page');
            $count  = $this->app->count_search($input);
            $this->data['paging'] = make_paging($count, $limit);
            $this->data['rows_list'] = $this->app->search($input, $limit, $offset);
        }
        else
        {
            set_message(get_instance()->form_validation->error_array(), 'error');
            redirect('/Admin/App/list');
        }
    }

    /**
     *
     * Show Page for this controller.
     *
     */
    public function getShow($id)
    {
        $this->data['row'] = $this->app->find($id);
        if(!is_object($this->data['row']))
        {
            set_message('row_is_not_found', 'error');
            redirect('/Admin/App/list');
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
        $input = $this->input->post(array_keys($this->app->forms['create']));
        $this->form_validation->set_rules($this->app->rules('create'));

        if ($this->form_validation->run() === TRUE)
        {
            $app = new $this->app;
            foreach ($input as $key => $value) {
                $app->$key = $value;
            }
            $app = $app->insert();
            set_message('row_has_been_created', 'success');
            redirect('/Admin/App/list');
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
            redirect('/Admin/App/create');
        }
    }

    /**
     *
     * getEdit Page for this controller.
     *
     */
    public function getEdit($id)
    {
        $this->data['row'] = $this->app->find($id);
        if(!is_object($this->data['row']))
        {
            set_message('row_is_not_found', 'error');
            redirect('/Admin/App/list');
        }
    }

    /**
     *
     * postEdit Page for this controller.
     *
     */
    public function postEdit($id)
    {
        $input = $this->input->post(array_keys($this->app->forms['edit']));
        $this->form_validation->set_rules($this->app->rules('edit'));

        if ($this->form_validation->run() === TRUE)
        {
            $app = $this->app->find($id);
            if(is_object($app))
            {
                foreach ($input as $key => $value) {
                    $app->$key = $value;
                }
                $app->update();
                set_message('row_has_been_updated', 'success');
            }
            else
            {
                set_message('row_is_not_found', 'error');
                redirect('/Admin/App/list');
            }
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
        }
        redirect('/Admin/App/edit/'.$id);
    }

    /**
     *
     * postDelete Page for this controller.
     *
     */
    public function postDelete()
    {
        $input = $this->input->post(array_keys($this->app->forms['delete']));
        $app = $this->app->find($input[$this->app->primaryKey]);
        if(is_object($app))
        {
            $app->delete();
            set_message('row_has_been_deleted', 'success');
        }
        else
        {
            set_message('row_is_not_found', 'error');
        }
        redirect('/Admin/App/list');
    }

}

/* End of file App.php */
/* Location: ./application/controllers/Admin/App.php */