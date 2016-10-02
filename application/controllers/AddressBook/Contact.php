<?php
/**
 * Contact Controller
 *
 * Provide Contact functions.
 *
 */

class Contact extends ER_Controller {
    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('AddressBook/Contact_model', 'contact');
        $this->load->model('AddressBook/Group_model', 'group');
    }

    /**
     *
     * Index Page for this controller.
     *
     */
    public function index()
    {
        redirect('/AddressBook/Contact/list');
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
        $this->data['rows_list'] = $this->contact->user_rows();
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
        
        $input = $this->input->post(array_keys($this->contact->forms['search']));
        $this->form_validation->set_rules($this->contact->rules('search'));

        if ($this->form_validation->run() === TRUE)
        {
            $limit  = 10;
            $offset = $this->input->get('page');
            $count  = $this->contact->user_count_search($input);
            $this->data['paging'] = make_paging($count, $limit);
            $this->data['rows_list'] = $this->contact->user_search($input, $limit, $offset);
        }
        else
        {
            set_message(get_instance()->form_validation->error_array(), 'error');
            redirect('/AddressBook/Contact/list');
        }
    }

    /**
     *
     * Show Page for this controller.
     *
     */
    public function getShow($id)
    {
        $this->data['row'] = $this->contact->user_find($id);
        if(!is_object($this->data['row']) || !$this->data['row']->hasPermission('show'))
        {
            set_message('row_is_not_found', 'error');
            redirect('/AddressBook/Contact/list');
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
        $input = $this->input->post(array_keys($this->contact->forms['create']));
        $this->form_validation->set_rules($this->contact->rules('create'));

        if ($this->form_validation->run() === TRUE)
        {
            $contact = new $this->contact;
            foreach ($input as $key => $value) {
                $contact->$key = $value;
            }
            $contact = $contact->insert();
            set_message('row_has_been_created', 'success');
            redirect('/AddressBook/Contact/list');
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
            redirect('/AddressBook/Contact/create');
        }
    }

    /**
     *
     * getEdit Page for this controller.
     *
     */
    public function getEdit($id)
    {
        $this->data['row'] = $this->contact->user_find($id);
        if(!is_object($this->data['row']) || !$this->data['row']->hasPermission('edit'))
        {
            set_message('row_is_not_found', 'error');
            redirect('/AddressBook/Contact/list');
        }
    }

    /**
     *
     * postEdit Page for this controller.
     *
     */
    public function postEdit($id)
    {
        $input = $this->input->post(array_keys($this->contact->forms['edit']));
        $this->form_validation->set_rules($this->contact->rules('edit'));

        if ($this->form_validation->run() === TRUE)
        {
            $contact = $this->contact->user_find($id);
            if(is_object($contact) && $contact->hasPermission('edit'))
            {
                foreach ($input as $key => $value) {
                    $contact->$key = $value;
                }
                $contact->update();
                set_message('row_has_been_updated', 'success');
            }
            else
            {
                set_message('row_is_not_found', 'error');
                redirect('/AddressBook/Contact/list');
            }
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
        }
        redirect('/AddressBook/Contact/edit/'.$id);
    }

    /**
     *
     * postDelete Page for this controller.
     *
     */
    public function postDelete()
    {
        $input = $this->input->post(array_keys($this->contact->forms['delete']));
        $contact = $this->contact->user_find($input[$this->contact->primaryKey]);
        if(is_object($contact) && $contact->hasPermission('delete'))
        {
            $contact->delete();
            set_message('row_has_been_deleted', 'success');
        }
        else
        {
            set_message('row_is_not_found', 'error');
        }
        redirect('/AddressBook/Contact/list');
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
                            <a href="'.base_url('AddressBook/Contact/list').'">
                                <div class="h2 text-purple">'.lang('addressbook').'</div>
                            </a>
                            <span class="text-muted">'.lang('view').' '.lang('addressbook/contact').'</span>
                            <div class="text-right">
                              <i class="fa fa-user fa-2x text-purple"></i>
                            </div>
                        </div>'
                    );
        return($widget);
    }

}

/* End of file Contact.php */
/* Location: ./application/controllers/AddressBook/Contact.php */