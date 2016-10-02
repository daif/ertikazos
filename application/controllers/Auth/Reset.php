<?php
/**
 * Reset Controller
 *
 * Provide Reset functions.
 *
 */

class Reset extends ER_Controller {

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
    public function index($email='', $code='')
    {
        if($this->session->has_userdata('userdata')) {
            redirect('/User/Dashboard');
        }

        $row = new stdClass;
        if($email && $code)
        {
            $row->user_code    = $code;
            $row->user_email   = urldecode($email);
        } else {
            $row->user_code    = '';
            $row->user_email   = '';
        }
        $this->data['row']     = $row;
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

        $input = $this->input->post(array_keys($this->user->forms['reset']));
        $this->form_validation->set_rules($this->user->rules('reset'));

        if ($this->form_validation->run() === TRUE)
        {
            $user = $this->user->row(array('user_email'=>$input['user_email'], 'user_code'=>$input['user_code']));
            if(is_object($user))
            {
                $user->user_code = '';
                $user->user_pass = $input['user_pass'];
                if($user->update()) {
                    set_message('password_set', 'success');
                    redirect('/Auth/Login');
                } else {
                    set_message('cannot_set_password', 'error');
                    redirect('/Auth/Reset');
                }
            } else {
                set_message('invalid_code_or_email', 'error');
                redirect('/Auth/Reset');
            }
        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
            redirect('/Auth/Lost');
        }
    }

}

/* End of file Reset.php */
/* Location: ./application/controllers/Auth/Reset.php */