<?php
/**
 * Account Controller
 *
 * Provide Account functions.
 *
 */

class Account extends ER_Controller {

    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct(){
        parent::__construct();
        $this->load->model('Admin/User_model', 'user');
        $this->create_button = false;
    }

    /**
     *
     * Index Page for this controller.
     *
     */
    public function index()
    {
        $this->data['row'] = $this->user->find($this->userdata->user_id);
    }

    /**
     *
     * getUpdate for this controller.
     *
     */
    public function getUpdate()
    {
        $this->data['row'] = $this->user->find($this->userdata->user_id);
    }

    /**
     *
     * postUpdate for this controller.
     *
     */
    public function postUpdate()
    {
        $input = $this->input->post(array_keys($this->user->forms['edit_account']));
        $this->form_validation->set_rules($this->user->rules('edit_account'));

        // upload file and get public path
        $user_avatar = upload_file('user_avatar');
        if(is_array($user_avatar))
        {
            $input['user_avatar'] = substr($user_avatar['full_path'], strlen(FCPATH));
        }

        if ($this->form_validation->run() === TRUE)
        {
            $user = $this->user->find($this->userdata->user_id);
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
                $this->session->set_userdata('userdata', $this->user->find($this->userdata->user_id));
                set_message('row_has_been_updated', 'success');
            }
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
        }
        //edit_account
        redirect('/User/Account/update');
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
                            <a href="'.base_url('User/Account').'">
                                <div class="h2 text-purple">'.lang('account').'</div>
                            </a>
                            <span class="text-muted">'.lang('show_account').'</span>
                            <div class="text-right">
                              <i class="fa fa-user fa-2x text-purple"></i>
                            </div>
                        </div>'
                    );
        $widget[] = array(
                        'class'     => 'col-sm-6 col-md-3',
                        'content'   => '<div class="panel bg-primary">
                            <a href="'.base_url('User/Account/update').'">
                                <div class="h2 text-white">'.lang('update').'</div>
                            </a>
                            <span class="text-white">'.lang('update_account').'</span>
                            <div class="text-right">
                              <i class="fa fa-user fa-2x text-white"></i>
                            </div>
                        </div>'
                    );
        return($widget);
    }

}

/* End of file Account.php */
/* Location: ./application/controllers/User/Account.php */