<?php
/**
 * Group Controller
 *
 * Provide Group functions.
 *
 */

class Group extends ER_Controller {
    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('AddressBook/Group_model', 'group');
        $this->load->model('AddressBook/Contact_model', 'contact');
    }

    /**
     *
     * Index Page for this controller.
     *
     */
    public function index()
    {
        redirect('/AddressBook/Group/list');
    }

    /**
     *
     * List Page for this controller.
     *
     */
    public function getList()
    {
        $this->create_button = true;
        $this->data['rows_list'] = $this->group->user_rows();
    }

    /**
     *
     * Show Page for this controller.
     *
     */
    public function getShow($id)
    {
        $this->data['row'] = $this->group->user_find($id);
        if(!is_object($this->data['row']))
        {
            set_message('row_is_not_found', 'error');
            redirect('/AddressBook/Group/list');
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
        $input = $this->input->post(array_keys($this->group->forms['create']));
        $this->form_validation->set_rules($this->group->rules('create'));

        if ($this->form_validation->run() === TRUE)
        {
            $group = new $this->group;
            foreach ($input as $key => $value) {
                $group->$key = $value;
            }
            $group = $group->insert();
            set_message('row_has_been_created', 'success');
            redirect('/AddressBook/Group/list');
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
            redirect('/AddressBook/Group/create');
        }
    }

    /**
     *
     * getEdit Page for this controller.
     *
     */
    public function getEdit($id)
    {
        $this->data['row'] = $this->group->user_find($id);
        if(!is_object($this->data['row']))
        {
            set_message('row_is_not_found', 'error');
            redirect('/AddressBook/Group/list');
        }
    }

    /**
     *
     * postEdit Page for this controller.
     *
     */
    public function postEdit($id)
    {
        $input = $this->input->post(array_keys($this->group->forms['edit']));
        $this->form_validation->set_rules($this->group->rules('edit'));

        if ($this->form_validation->run() === TRUE)
        {
            $group = $this->group->user_find($id);
            if(is_object($group))
            {
                foreach ($input as $key => $value) {
                    $group->$key = $value;
                }
                $group->update();
                set_message('row_has_been_updated', 'success');
            }
            else
            {
                set_message('row_is_not_found', 'error');
                redirect('/AddressBook/Group/list');
            }
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
        }
        redirect('/AddressBook/Group/edit/'.$id);
    }

    /**
     *
     * postDelete Page for this controller.
     *
     */
    public function postDelete()
    {
        $input = $this->input->post(array_keys($this->group->forms['delete']));
        $group = $this->group->user_find($input[$this->group->primaryKey]);
        if(is_object($group))
        {
            $group->delete();
            set_message('row_has_been_deleted', 'success');
        }
        else
        {
            set_message('row_is_not_found', 'error');
        }
        redirect('/AddressBook/Group/list');
    }

    /**
     *
     * widgets for this controller.
     *
     */
    public static function widgets()
    {
        $widget   = array();
        $widget[] = array(
                        'class'     => 'col-sm-6 col-md-3',
                        'content'   => '<div class="panel">
                            <a href="'.base_url('AddressBook/Group/list').'">
                                <div class="h2 text-purple">'.lang('view').'</div>
                            </a>
                            <span class="text-muted">'.lang('View all groups').'</span>
                            <div class="text-right">
                              <i class="fa fa-user fa-2x text-purple"></i>
                            </div>
                        </div>'
                    );
        return($widget);
    }

}

/* End of file Group.php */
/* Location: ./application/controllers/AddressBook/Group.php */