<?php
/**
 * Ertikaz User model class
 *
 * This class object is the User model class
 *
 * @package     Ertikaz
 * @subpackage  model
 * @category    model
 */

class User_model extends ER_Model {
    // constants
    const STATUS_INACTIVE = 0;
    const STATUS_NEW = 1;
    const STATUS_ACTIVE = 2;

    const TYPE_ADMIN = 1;
    const TYPE_USER = 2;

    /**
     * The table name for the model.
     *
     * @var string
     */
    public $table = 'er_users';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    public $primaryKey = 'user_id';

    /**
     * The primary Label for the model.
     *
     * @var string
     */
    public $primaryLabel = 'user_name';

    /**
     * The create by field name.
     *
     * @var string
     */
    public $createBy = 'user_create_by';

    /**
     * The create at field name.
     *
     * @var string
     */
    public $createAt = 'user_create_at';

    /**
     * The update by field name.
     *
     * @var string
     */
    public $updateBy = 'user_update_by';

    /**
     * The update at field name.
     *
     * @var string
     */
    public $updateAt = 'user_update_at';

    /**
     * The row permission.
     * @var integer
     */
    public $permission = 777;

    /**
     * The array of Form Validation rules.
     *
     * @var array
     */
    public $rules = array(
        'user_id'      => 'integer',
        'user_type'    => 'required|integer',
        'user_name'    => 'required|min_length[3]',
        'user_email'   => 'required|valid_email',
        'user_pass'    => 'min_length[8]|max_length[16]',
        'user_pconf'   => 'required|matches[user_pass]',
        'user_mobile'  => 'required|min_length[10]',
        'user_avatar'  => '',
        'user_code'    => 'required|integer',
        'user_status'  => 'required|integer',
    );

    /**
     * The array of the row action buttons.
     *
     * @var array
     */
    public $action_buttons = [
        '*' => [
            'url' => 'Admin/User',
            'class' => '',
            'method' => 'get',
        ],
        'show' => [
            'class' => 'glyphicon glyphicon-eye-open',
        ],
        'edit' => [
            'class' => 'glyphicon glyphicon-edit',
        ], 

        'delete' => [
            'class' => 'glyphicon glyphicon-remove',
            'method' => 'post',
        ],
    ];

    /**
     * The array of the forms input fields.
     *
     * @var array
     */
    public $forms = array(
        '*' => array(
            'user_id'    => array(
                'field' => 'user_id'
            ),
            'user_type'  => array(
                'field' => 'user_type',
                'type'  => 'select:hasOne[Admin/Setting][value][name^=user_type]'
            ),
            'user_name'  => array(
                'field' => 'user_name'
            ),
            'user_email' => array(
                'field' => 'user_email'
            ),
            'user_pass' => array(
                'field' => 'user_pass',
                'type'  => 'password'
            ),
            'user_pconf' => array(
                'field'  => 'user_pconf',
                'type'   => 'password'
            ),
            'user_mobile'  => array(
                'field' => 'user_mobile',
                'type'  => 'mobile'
            ),
            'user_avatar'  => array(
                'field' => 'user_avatar',
                'type'  => 'select:hasOne[Admin/User::avatars][avatar]'
            ),
            'user_code'  => array(
                'field' => 'user_code'
            ),
            'user_status'  => array(
                'field' => 'user_status',
                'type'  => 'select:hasOne[Admin/Setting][value][name^=user_status]'
            ),
        ),
        'list' => array(
            'user_id'    => array(),
            'user_type'  => array(),
            'user_name'  => array(),
            'user_email' => array(),
            'user_mobile'  => array(),
            'user_status'  => array(),
        ),
        'search' => array(
            'user_name'  => array(
                'rules'     => 'min_length[3]'
            ),
            'user_email' => array(
                'rules'     => 'valid_email'
            ),
        ),
        'create' => array(
            'user_type'  => array(),
            'user_name'  => array(),
            'user_email' => array(),
            'user_pass'  => array(),
            'user_mobile'  => array(),
            'user_status'  => array(),
        ),
        'edit' => array(
            'user_id'    => array(
                'type'  =>'hidden'
            ),
            'user_type'  => array(),
            'user_name'  => array(),
            'user_email' => array(),
            'user_pass'  => array(),
            'user_mobile'  => array(),
            'user_status'  => array(),
        ),
        'show' => array(
            'user_id'    => array(),
            'user_type'  => array(),
            'user_name'  => array(),
            'user_email' => array(),
            'user_mobile'  => array(),
            'user_status'  => array(),
        ),
        'edit_account' => array(
            'user_name'  => array(),
            'user_email' => array(),
            'user_pass'  => array(),
            'user_mobile'  => array(),
            'user_avatar'  => array(),
        ),
        'register' => array(
            'user_name'  => array(),
            'user_email' => array(
                'rules'   =>'required|valid_email|is_unique[er_users.user_email]'
            ),
            'user_pass'  => array(),
            'user_pconf' => array(),
        ),
        'lost' => array(
            'user_email' => array(),
        ),
        'reset' => array(
            'user_email' => array(),
            'user_code' => array(),
            'user_pass'  => array(),
        ),
        'delete' => array(
            'user_id'   => array(
                'type'  => 'hidden'
            ),
        ),
    );

    public function __construct($id=NULL)
    {
        parent::__construct($id);
    }

    public function login($user_email, $user_pass)
    {
        $user = $this->row(array('user_email'=>$user_email));
        if(is_object($user) && $user->password_verify($user_pass))
        {
            return $user;
        }
        return FALSE;
    }

    /**
     * override insert function
     *
     * @return  $this->insert
     */
    public function insert() {
        if(strlen($this->user_pass) >=4 && strlen($this->user_pass) <=16)
        {
            $this->user_pass = $this->password_hash($this->user_pass);
        }
        return parent::insert();
    }

    /**
     * override update function
     *
     * @return  $this->update
     */
    public function update() {
        if(strlen($this->user_pass) >=4 && strlen($this->user_pass) <=16 )
        {
            $this->user_pass = $this->password_hash($this->user_pass);
        }
        return parent::update();
    }

    public function password_hash($user_pass)
    {
        return password_hash($user_pass, PASSWORD_DEFAULT) ;
    }

    public function password_verify($user_pass)
    {
        return password_verify($user_pass, $this->user_pass);
    }

    public function groups()
    {
        $this->load->model('Admin/Group_model', 'group');
        $this->db->reset_query();
        $this->db->select('er_groups.*');
        $this->db->from('er_groups');
        $this->db->join('er_users_rels','er_users_rels.rel_group_id=er_groups.group_id','inner');
        $this->db->where('er_users_rels.rel_user_id', $this->user_id);
        return $this->db->get()->result('Group_model');
    }

    public function permissions()
    {
        $this->load->model('Admin/Permission_model', 'permission');
        $this->db->reset_query();
        $this->db->select('er_permissions.*,er_apps.*');
        $this->db->from('er_apps');
        $this->db->join('er_permissions','er_permissions.perm_app_id=er_apps.app_id','inner');
        $this->db->where('er_permissions.perm_user_id', $this->user_id);
        return $this->db->get()->result('Permission_model');
    }

    public function rels()
    {
        $this->load->model('Admin/Userrel_model', 'userrel');
        $this->db->reset_query();
        $this->db->select('er_users_rels.*,er_groups.*');
        $this->db->from('er_groups');
        $this->db->join('er_users_rels','er_users_rels.rel_group_id=er_groups.group_id','inner');
        $this->db->where('er_users_rels.rel_user_id', $this->user_id);
        return $this->db->get()->result('Userrel_model');
    }

    public function hasgroup($group)
    {
        $groups = $this->groups();
        foreach ($groups as $key => $group_obj) {
            if($group_obj->group_id == $group->group_id)
            {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function avatars()
    {
        $this->load->helper('directory');
        return directory_map(FCPATH.'assets/avatars/');
    }

    public function user_avatar()
    {
        if(strlen($this->user_avatar)>1)
        {
            return $this->user_avatar;
        }
        if(!file_exists(FCPATH.'assets/avatars/'.$this->user_id.'.jpg'))
        {
            $user_photo = get_user_photo($this->user_email);
            if(strlen($user_photo)>100)
            {
                file_put_contents(FCPATH.'assets/avatars/'.$this->user_id.'.jpg', $user_photo);
            }
            else
            {
                copy(FCPATH.'assets/avatars/default.jpg',FCPATH.'assets/avatars/'.$this->user_id.'.jpg');
            }
        }
        return $this->user_id.'.jpg';
    }

    public function notifications_count($status = 0)
    {
        $this->load->model('User/Notify_model', 'notify');
        $this->db->reset_query();
        $this->db->select('er_users_notify.*');
        $this->db->from('er_users_notify');
        $this->db->where('er_users_notify.notify_status', $status);
        $this->db->where('er_users_notify.notify_user_id', $this->user_id);
        return $this->db->count_all_results();
    }
    public function notifications($status = 0)
    {
        $this->load->model('User/Notify_model', 'notify');
        $this->db->reset_query();
        $this->db->select('er_users_notify.*');
        $this->db->from('er_users_notify');
        $this->db->where('er_users_notify.notify_status', $status);
        $this->db->where('er_users_notify.notify_user_id', $this->user_id);
        return $this->db->get()->result('Notify_model');
    }

}
