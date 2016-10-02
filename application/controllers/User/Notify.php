<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 */

class Notify extends ER_Controller {
    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct(){
        parent::__construct();
        $this->load->model('User/Notify_model', 'notify');
        $this->create_button = false;
    }

    /**
     *
     * Index Page for this controller.
     *
     */
    public function index()
    {
        redirect('/User/Notify/list');
    }

    /**
     *
     * List Page for this controller.
     *
     */
    public function getList($id = NULL)
    {
        if($id !== NULL) {
            $notify  = $this->notify->find($id);
            if(!is_object($notify))
            {
                set_message('row_is_not_found', 'error');
            }
            else
            {
                if($notify->notify_user_id == $this->userdata('user_id'))
                {
                    $notify->notify_status = 1;
                    $notify->update();
                }
                else
                {
                    set_message('row_is_not_found', 'error');
                }
            }
            redirect('/User/Notify/list');
        }
        else
        {
            $this->data['rows_list'] = $this->session->userdata('userdata')->notifications();
        }
    }
}

/* End of file Notify.php */
/* Location: ./application/controllers/User/Notify.php */