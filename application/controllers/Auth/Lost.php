<?php
/**
 * Lost Controller
 *
 * Provide Lost functions.
 *
 */

class Lost extends ER_Controller {

    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct(){
        parent::__construct();
        $this->load->model('Admin/User_model', 'user');
        if(get_setting('auth/driver') != 'Database')
        {
            set_message('reset_is_not_available', 'error');
            redirect('/Auth/Login');
        }
    }

    /**
     *
     * Index Page for this controller.
     *
     */
    public function index()
    {
        if($this->session->has_userdata('userdata')) {
            redirect('/User/Dashboard');
        }
    }

    /**
     *
     * postIndex for this controller.
     *
     */
    public function postIndex()
    {
        if($this->session->has_userdata('userdata')) {
            set_message('already_logged_in', 'error');
            redirect('/User/Dashboard');
        }

        $input = $this->input->post(array_keys($this->user->forms['lost']));
        $this->form_validation->set_rules($this->user->rules('lost'));

        if ($this->form_validation->run() === TRUE)
        {
            $user = $this->user->row(array('user_email'=>$input['user_email']));
            if(is_object($user))
            {
                if($user->user_status == User_model::STATUS_ACTIVE) {
                    $user_code = rand(111111, 999999);
                    $user->user_code = $user_code;
                    if($user->update()) {
                        // send reset link
                        $to       = $user->user_email;
                        $subject  = 'Password Reset';
                        $message  = array(
                            'name'  =>$user->user_name,
                            'email' =>$user->user_email,
                            'code'  =>$user->user_code,
                        );

                        if(send_email($to, $subject, $message, 'user_reset')){
                            set_message('reset_link_sent', 'success');
                        } else {
                            set_message('cannot_send_reset_link', 'error');
                            redirect('/Auth/Lost');
                        }

                    } else {
                        set_message('cannot_reset_account', 'error');
                        redirect('/Auth/Lost');
                    }
                } else {
                    set_message('not_active_yet', 'error');
                    redirect('/Auth/Lost');
                }
                
            } else {
                set_message('email_not_found', 'error');
                redirect('/Auth/Lost');
            }
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
            redirect('/Auth/Lost');
        }
    }

}

/* End of file Lost.php */
/* Location: ./application/controllers/Auth/Lost.php */