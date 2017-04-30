<?php
/**
 * Ertikaz Group model class
 *
 * This class object is the Group model class
 *
 * @package     Ertikaz
 * @subpackage  model
 * @category    model
 */

class Group_model extends ER_Model {
    // constants
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * The table name for the model.
     *
     * @var string
     */
    public $table = 'er_groups';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    public $primaryKey = 'group_id';

    /**
     * The primary Label for the model.
     *
     * @var string
     */
    public $primaryLabel = 'group_name';

    /**
     * The row permission.
     * @var integer
     */
    public $permission = 777;

    /**
     * The array of the row action buttons.
     *
     * @var array
     */
    public $action_buttons = [
        '*' => [
            'url' => 'Admin/Group',
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
    public $forms = [
        '*' => [
            'group_id'    => [
                'field' => 'group_id',
                'rules'     => 'integer'
            ],
            'group_name'  => [
                'field' => 'group_name',
                'rules'     => 'required'
            ],
            'group_status'  => [
                'field' => 'group_status',
                'rules'     => 'integer',
                'type'  => 'select:hasOne[Admin/Setting][value][name^=status]'
            ],
        ],
        'list' => [
            'group_id'      => [],
            'group_name'    => [],
            'group_status'  => [],
        ],
        'search' => [
            'group_name'    => [],
        ],
        'create' => [
            'group_name'    => [],
            'group_status'  => [],
        ],
        'edit' => [
            'group_id'      => [
                'type'      =>'hidden'
            ],
            'group_name'    => [],
            'group_status'  => [],
        ], 
        'show' => [
            'group_id'      => [],
            'group_name'    => [],
            'group_status'  => [],
        ], 
        'delete' => [
            'group_id'  => [
                'type'  => 'hidden'
            ],
        ],
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function users($group_id=0)
    {
        $this->load->model('Admin/User_model', 'user');
        $this->db->reset_query();
        $this->db->select('er_users.*');
        $this->db->from('er_users');
        $this->db->join('er_users_rels','er_users_rels.rel_user_id=er_users.user_id','inner');
        $this->db->where('er_users_rels.rel_group_id', $this->group_id);
        return($this->db->get()->result('User_model'));
    }

    public function permissions($group_id=0)
    {
        $this->load->model('Admin/Permission_model', 'permission');
        $this->db->reset_query();
        $this->db->select('er_permissions.*,er_apps.*');
        $this->db->from('er_apps');
        $this->db->join('er_permissions','er_permissions.perm_app_id=er_apps.app_id','inner');
        $this->db->where('er_permissions.perm_group_id', $this->group_id);
        return($this->db->get()->result('Permission_model'));
    }
}
