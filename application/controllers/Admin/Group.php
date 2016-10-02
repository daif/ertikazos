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
        $this->load->model('Admin/Group_model', 'group');
        $this->load->model('Admin/Permission_model', 'permission');
    }

    /**
     *
     * Index Page for this controller.
     *
     */
    public function index()
    {
        redirect('/Admin/Group/list');
    }

    /**
     *
     * List Page for this controller.
     *
     */
    public function getList()
    {
        $this->create_button = true;
        $this->data['rows_list'] = $this->group->rows();
    }

    /**
     *
     * Show Page for this controller.
     *
     */
    public function getShow($id)
    {
        $this->data['row'] = $this->group->find($id);
        if(!is_object($this->data['row']))
        {
            set_message('row_is_not_found', 'error');
            redirect('/Admin/Group/list');
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
            //set new permissions
            $perms = $this->input->post('apps[]');
            if(is_array($perms))
            {
                foreach ($perms as $key => $perm_app_id)
                {
                    $permission = new $this->permission;
                    $permission->perm_group_id = $group->group_id;
                    $permission->perm_app_id = $perm_app_id;
                    $permission->insert();
                }
            }
            set_message('row_has_been_created', 'success');
            redirect('/Admin/Group/list');
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
            redirect('/Admin/Group/create');
        }
    }

    /**
     *
     * getEdit Page for this controller.
     *
     */
    public function getEdit($id)
    {
        $this->data['row'] = $this->group->find($id);
        if(!is_object($this->data['row']))
        {
            set_message('row_is_not_found', 'error');
            redirect('/Admin/Group/list');
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
            $group = $this->group->find($id);
            if(is_object($group))
            {
                foreach ($input as $key => $value) {
                    $group->$key = $value;
                }
                $group->update();
                // delete old permissions
                $permissions = $group->permissions();
                foreach ($permissions as $key => $permission)
                {
                    $permission->delete();
                }
                // set new permissions
                $perms = $this->input->post('apps[]');
                if(is_array($perms))
                {
                    foreach ($perms as $key => $perm_app_id)
                    {
                        $permission = new $this->permission;
                        $permission->perm_group_id = $group->group_id;
                        $permission->perm_app_id = $perm_app_id;
                        $permission->insert();
                    }
                }
                set_message('row_has_been_updated', 'success');
            }
            else
            {
                set_message('row_is_not_found', 'error');
                redirect('/Admin/Group/list');
            }
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
        }
        redirect('/Admin/Group/edit/'.$id);
    }

    /**
     *
     * postDelete Page for this controller.
     *
     */
    public function postDelete()
    {
        $input = $this->input->post(array_keys($this->group->forms['delete']));
        $group = $this->group->find($input[$this->group->primaryKey]);
        if(is_object($group))
        {
            $group->delete();
            set_message('row_has_been_deleted', 'success');
        }
        else
        {
            set_message('row_is_not_found', 'error');
        }
        redirect('/Admin/Group/list');
    }

}

/* End of file Group.php */
/* Location: ./application/controllers/Admin/Group.php */