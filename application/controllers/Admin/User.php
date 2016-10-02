<?php
/**
 * User Controller
 *
 * Provide User functions.
 *
 */

class User extends ER_Controller {
    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('Admin/User_model', 'user');
        $this->load->model('Admin/Group_model', 'group');
        $this->load->model('Admin/Userrel_model', 'userrel');
        $this->load->model('Admin/Permission_model', 'permission');
    }

    /**
     *
     * Index Page for this controller.
     *
     */
    public function index()
    {
        redirect('/Admin/User/list');
    }

    /**
     *
     * List Page for this controller.
     *
     */
    public function getList()
    {
        $this->search_button = true;
        $this->create_button = true;

        $limit  = 10;
        $offset = $this->input->get('page');
        $count  = $this->user->count();
        $this->data['paging'] = make_paging($count, $limit);
        $this->data['rows_list'] = $this->user->rows(NULL,$limit, $offset);
        
    }

    public function postList()
    {
        $this->create_button = true;
        $this->search_button = true;

        $input = $this->input->post(array_keys($this->user->forms['search']));
        $this->form_validation->set_rules($this->user->rules('search'));

        if ($this->form_validation->run() === TRUE)
        {
            $limit  = 10;
            $offset = $this->input->get('page');
            $count  = $this->user->count_search($input);
            $this->data['paging'] = make_paging($count, $limit);
            $this->data['rows_list'] = $this->user->search($input);
        }
        else
        {
            set_message(get_instance()->form_validation->error_array(), 'error');
            redirect('/Admin/User/list');
        }
    }

    /**
     *
     * Show Page for this controller.
     *
     */
    public function getShow($id)
    {
        $this->data['row'] = $this->user->find($id);
        if(!is_object($this->data['row']))
        {
            set_message('row_is_not_found', 'error');
            redirect('/Admin/User/list');
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
        $input = $this->input->post(array_keys($this->user->forms['create']));
        $this->form_validation->set_rules($this->user->rules('create'));

        if ($this->form_validation->run() === TRUE)
        {
            $user = new $this->user;
            foreach ($input as $key => $value) {
                $user->$key = $value;
            }
            $user = $user->insert();
            //set new permissions
            $perms = $this->input->post('apps[]');
            if(is_array($perms))
            {
                foreach ($perms as $key => $perm_app_id)
                {
                    $permission = new $this->permission;
                    $permission->perm_user_id = $user->user_id;
                    $permission->perm_app_id = $perm_app_id;
                    $permission->insert();
                }
            }
            //set new groups relations
            $rels = $this->input->post('groups[]');
            if(is_array($rels))
            {
                foreach ($rels as $key => $rel_group_id)
                {
                    $userrel = new $this->userrel;
                    $userrel->rel_user_id  = $user->user_id;
                    $userrel->rel_group_id = $rel_group_id;
                    $userrel->insert();
                }
            }
            set_message('row_has_been_created', 'success');
            redirect('/Admin/User/list');
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
            redirect('/Admin/User/create');
        }
    }

    /**
     *
     * getEdit Page for this controller.
     *
     */
    public function getEdit($id)
    {
        $this->data['row'] = $this->user->find($id);
        if(!is_object($this->data['row']))
        {
            set_message('row_is_not_found', 'error');
            redirect('/Admin/User/list');
        }
    }

    /**
     *
     * postEdit Page for this controller.
     *
     */
    public function postEdit($id)
    {
        $input = $this->input->post(array_keys($this->user->forms['edit']));
        $this->form_validation->set_rules($this->user->rules('edit'));

        if ($this->form_validation->run() === TRUE)
        {
            $user = $this->user->find($id);
            if(is_object($user))
            {
                foreach ($input as $key => $value) {
                    if($key == 'user_pass')
                    {
                        if($value != '')
                        {
                            $user->$key = $value;
                        }
                    }
                    else
                    {
                        $user->$key = $value;
                    }
                }
                $user->update();
                //delete old permissions
                $permissions = $user->permissions();
                foreach ($permissions as $key => $permission)
                {
                    $permission->delete();
                }
                //set new permissions
                $perms = $this->input->post('apps[]');
                if(is_array($perms))
                {
                    foreach ($perms as $key => $perm_app_id)
                    {
                        $permission = new $this->permission;
                        $permission->perm_user_id = $user->user_id;
                        $permission->perm_app_id = $perm_app_id;
                        $permission->insert();
                    }
                }

                //delete old group relations
                $rels = $user->rels();
                foreach ($rels as $key => $rel)
                {
                    $rel->delete();
                }
                //set new groups relations
                $rels = $this->input->post('groups[]');
                if(is_array($rels))
                {
                    foreach ($rels as $key => $rel_group_id)
                    {
                        $userrel = new $this->userrel;
                        $userrel->rel_user_id  = $user->user_id;
                        $userrel->rel_group_id = $rel_group_id;
                        $userrel->insert();
                    }
                }
                set_message('row_has_been_updated', 'success');
            }
            else
            {
                set_message('row_is_not_found', 'error');
                redirect('/Admin/User/list');
            }
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
        }
        redirect('/Admin/User/edit/'.$id);
    }

    /**
     *
     * postDelete Page for this controller.
     *
     */
    public function postDelete()
    {
        $input = $this->input->post(array_keys($this->user->forms['delete']));
        $user = $this->user->find($input[$this->user->primaryKey]);
        if(is_object($user))
        {
            $user->delete();
            set_message('row_has_been_deleted', 'success');
        }
        else
        {
            set_message('row_is_not_found', 'error');
        }
        redirect('/Admin/User/list');
    }

}

/* End of file Setting.php */
/* Location: ./application/controllers/Admin/User.php */