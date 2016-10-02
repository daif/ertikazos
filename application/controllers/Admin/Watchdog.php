<?php
/**
 * Watchdog Controller
 *
 * Provide Watchdog functions.
 *
 */

class Watchdog extends ER_Controller {
    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('Admin/Watchdog_model', 'watchdog');
    }

    /**
     *
     * Index Page for this controller.
     *
     */
    public function index()
    {
        redirect('/Admin/Watchdog/list');
    }

    /**
     *
     * List Page for this controller.
     *
     */
    public function getList()
    {
        $this->search_button = true;
        $limit  = 30;
        $offset = $this->input->get('page');
        $count  = $this->watchdog->count();
        $this->data['paging'] = make_paging($count, $limit);
        $this->data['rows_list'] = $this->watchdog->rows(NULL, $limit, $offset, 'log_id DESC');
    }

    /**
     *
     * List Page for this controller.
     *
     */
    public function postList()
    {
        $this->search_button = true;
        $input = $this->input->post(array_keys($this->watchdog->forms['search']));
        $this->form_validation->set_rules($this->watchdog->rules('search'));

        if ($this->form_validation->run() === TRUE)
        {
            $limit  = 30;
            $offset = $this->input->get('page');
            $count  = $this->watchdog->count_search($input);
            $this->data['paging'] = make_paging($count, $limit);
            $this->data['rows_list'] = $this->watchdog->search($input, $limit, $offset, 'log_id DESC');
        }
        else
        {
            set_message(get_instance()->form_validation->error_array(), 'error');
            redirect('/Admin/Watchdog/list');
        }
    }

    /**
     *
     * Show Page for this controller.
     *
     */
    public function getShow($id)
    {
        $this->data['row'] = $this->watchdog->find($id);
        if(!is_object($this->data['row']))
        {
            set_message('row_is_not_found', 'error');
            redirect('/Admin/Watchdog/list');
        }
    }


}

/* End of file Watchdog.php */
/* Location: ./application/controllers/Admin/Watchdog.php */