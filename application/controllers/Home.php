<?php
/**
 * Home Controller
 *
 * Provide Home functions.
 *
 */

class Home extends ER_Controller {
    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct(){
        parent::__construct();
    }

    /**
     *
     * Index Page for this controller.
     *
     */
    public function index()
    {
        
    }

    /**
     *
     * Switch user language.
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
            redirect('/');
        }
    }
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */