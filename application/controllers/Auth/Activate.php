<?php
/**
 * Activate Controller
 *
 * Provide Activate functions.
 *
 */

class Activate extends ER_Controller {

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
    public function index($email='', $code='')
    {
        if($this->session->has_userdata('userdata')) {
            redirect('/User/Dashboard');
        }
        $user = $this->user->row(array('user_email'=>$email, 'user_code'=>$code));
        if(is_object($user))
        {
            $user->user_code   = '';
            $user->user_status = User_model::STATUS_ACTIVE;
            if($user->update()) {
                set_message('account_activated', 'success');
                redirect('/Auth/Login');
            } else {
                set_message('cannot_activate', 'error');
                redirect('/Auth/Activate');
            }
        } else {
            set_message('wrong_activation_code', 'error');
            redirect('/Auth/Lost');
        }
    }

}

/* End of file Activate.php */
/* Location: ./application/controllers/Auth/Activate.php */