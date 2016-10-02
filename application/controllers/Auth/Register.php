<?php
/**
 * Register Controller
 *
 * Provide Register functions.
 *
 */

class Register extends ER_Controller {

    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct(){
        parent::__construct();
        $this->load->model('Admin/User_model', 'user');
        if(get_setting('auth/registration') != 'Allowed')
        {
            set_message('registration_is_not_allowed', 'error');
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

        $input = $this->input->post(array_keys($this->user->forms['register']));
        $this->form_validation->set_rules($this->user->rules('register'));

        if ($this->form_validation->run() === TRUE)
        {
            $user_email_accepted = FALSE;
            $allowed_domains = get_setting('auth/allowed_domains');
            if(trim($allowed_domains) && trim($allowed_domains) != '*')
            {
                $user_domain            = explode('@', $input['user_email'])[1];
                $allowed_domains_list   = explode(',', trim($allowed_domains));
                foreach ($allowed_domains_list as $key => $domain_name)
                {
                    if(strtolower($domain_name) == strtolower($user_domain))
                    {
                        $user_email_accepted = TRUE;
                        break;
                    }
                }
            }
            else
            {
                $user_email_accepted = TRUE;
            }
            // if user email domain is in allowed domains list continue the registration process 
            if($user_email_accepted == TRUE)
            {
                $user_code = rand(111111, 999999);
                $user = new User_model;
                foreach ($input as $key => $value) {
                    $user->$key = $value;
                }
                $user->user_avatar = 'avatar01.png';
                $user->user_code = $user_code;
                $user->user_type = User_model::TYPE_USER;
                $user->user_status = User_model::STATUS_INACTIVE;
                if($user->insert())
                {
                    set_message('account_created', 'success');
                    // send activation link
                    $to       = $user->user_email;
                    $subject  = 'Account Activation';
                    $message  = array(
                        'name'  =>$user->user_name,
                        'email' =>$user->user_email,
                        'code'  =>$user->user_code,
                    );
                    if(send_email($to, $subject, $message, 'user_activation'))
                    {
                        set_message('activation_link_sent', 'success');
                    }
                    else
                    {
                        set_message('cannot_send_activation', 'error');
                    }
                }
                else
                {
                    set_message('cannot_create_account', 'error');
                }
            }
            else
            {
                set_message('email_domain_is_not_allowed', 'error');
            }

        }
        else
        {
            set_message($this->form_validation->error_array(), 'error');
            redirect('/Auth/Register');
        }
        redirect('/Auth/Login');
    }

}

/* End of file Register.php */
/* Location: ./application/controllers/Auth/Register.php */