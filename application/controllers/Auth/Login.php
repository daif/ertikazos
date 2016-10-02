<?php
/**
 * Login Controller
 *
 * Provide Login functions.
 *
 */

class Login extends ER_Controller {

    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct(){
        parent::__construct();
        $this->load->model('Admin/User_model', 'user');
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
     * getLogout for this controller.
     *
     */
    public function getLogout()
    {
        $this->session->unset_userdata('userdata');
        set_message('just_logged_out', 'success');
        redirect('/Auth/Login');
    }

    /**
     *
     * postIndex for this controller.
     *
     */
    public function postIndex()
    {
        // default driver is database
        $input          = $this->input->post(array('email', 'password', 'remember'));
        $auth_driver    = ucfirst(strtolower(get_setting('auth/driver')));
        $auth_class     = $auth_driver.'_Driver';
        $auth_file      = APPPATH.'controllers/Auth/Drivers/'.$auth_driver.'_Driver.php';
        require_once(APPPATH.'controllers/Auth/Drivers/Auth.php');
        // if driver is not existed failback to database driver 
        if(file_exists($auth_file)) {
            require_once($auth_file);
        } else {

            require_once(APPPATH.'controllers/Auth/Drivers/Database_Driver.php');
        }
        $auth_driver    = new $auth_class;

        if($auth_driver->login($input['email'], $input['password'], $input['remember']))
        {
            $userdata = $this->user->row(array('user_email'=>$input['email']));
            // if user is not existed, create it
            if (!is_object($userdata)) {
                // create the user and make it active
                $newuser = new User_model;
                $newuser->user_type     = User_model::TYPE_USER;
                $newuser->user_name     = $input['email'];
                $newuser->user_email    = $input['email'];
                $newuser->user_pass     = $input['password'];
                $newuser->user_mobile   = '';
                $newuser->user_avatar   = 'avatar01.png';
                $newuser->user_status   = User_model::STATUS_ACTIVE;
                $newuser->save();
                // load the user info
                $userdata = $this->user->row(array('user_email'=>$input['email']));
            }
        }


        if(isset($userdata) && is_object($userdata) && isset($userdata->user_id)) {
            // check if the account is active 
            if($userdata->user_status == User_model::STATUS_ACTIVE) {
                $this->session->set_userdata('userdata', $userdata);
                redirect('/User/Dashboard');
            } else {
                unset($userdata);
                set_message('activate_your_account', 'error');
                redirect('/Auth/Activate');
            }
        } else {
            set_message('wrong_email_or_password', 'error');
        }
        redirect('/Auth/Login');
    }

    /**
     *
     * Lang Page for this controller.
     *
     */
    public function getLang($lang)
    {
        $this->load->library('user_agent');
        if($lang == 'arabic' || $lang == 'english') {
            $this->session->set_userdata('lang', $lang);
            set_message('language_switched','success');
        }
        if(base_url() == substr($this->agent->referrer(), 0, strlen(base_url()))) {
            redirect($this->agent->referrer());
        } else {
            redirect('/Auth/Login');
        }
    }
}

/* End of file Login.php */
/* Location: ./application/controllers/Auth/Login.php */