<?php
/**
 * Ertikaz App model class
 *
 * This class object is the App model class
 *
 * @package     Ertikaz
 * @subpackage  model
 * @category    model
 */

class App_model extends ER_Model {
    // constants
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const ACCESS_AUTHORIZED = 0;
    const ACCESS_AUTHENTICATED = 1;
    const ACCESS_ANONYMOUS = 2;

    const MENU_SHOW = 1;
    const MENU_HIDE = 0;

    /**
     * The table name for the model.
     *
     * @var string
     */
    public $table = 'er_apps';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    public $primaryKey = 'app_id';

    /**
     * The primary Label for the model.
     *
     * @var string
     */
    public $primaryLabel = 'app_path';

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
            'url' => 'Admin/App',
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
            'app_id'    => [
                'field'     => 'app_id',
                'rules'     => 'integer'
            ],
            'app_path'  => [
                'field'     => 'app_path',
                'rules'     => 'required'
            ],
            'app_icon'  => [
                'field'     => 'app_icon',
                'rules'     => 'required'
            ],
            'app_sort'  => [
                'field'     => 'app_sort',
                'rules'     => 'required|integer',
                'type'      => 'range:[1,10]'
            ],
            'app_menu'  => [
                'field'     => 'app_menu',
                'rules'     => 'required|integer',
                'type'      => 'select:hasOne[Admin/Setting][value][name^=app_menu]'
            ],
            'app_access'=> [
                'field'     => 'app_access',
                'rules'     => 'required|integer',
                'type'      => 'select:hasOne[Admin/Setting][value][name^=app_access]'
            ],
            'app_status'=> [
                'field'     => 'app_status',
                'rules'     => 'required|integer',
                'type'      => 'select:hasOne[Admin/Setting][value][name^=status]'
            ],
        ],
        'list' => [
            'app_id'    => [],
            'app_path'  => [],
            'app_icon'  => [],
            'app_sort'  => [],
            'app_menu'  => [],
            'app_access'=> [],
            'app_status'=> [],
        ],
        'search' => [
            'app_path'  => [
                'rules' => ''
            ],
        ],
        'create' => [
            'app_path'  => [],
            'app_icon'  => [],
            'app_sort'  => [],
            'app_menu'  => [],
            'app_access'=> [],
            'app_status'=> [],
        ],
        'edit' => [
            'app_id'    => [
                'type'  =>'hidden'
            ],
            'app_path'  => [],
            'app_icon'  => [],
            'app_sort'  => [],
            'app_menu'  => [],
            'app_access'=> [],
            'app_status'=> [],
        ], 
        'show' => [
            'app_id'    => [],
            'app_path'  => [],
            'app_icon'  => [],
            'app_sort'  => [],
            'app_menu'  => [],
            'app_access'=> [],
            'app_status'=> [],
        ], 
        'delete' => [
            'app_id'    => [
                'type'  =>'hidden'
            ],
        ],
    ];

    /**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * getMenu function
     *
     * @return  object
     */
    public function getMenu($app_path = '')
    {
        if($app_path)
        {
            $menu = $this->db->select('*')
                ->from($this->table)
                ->where('app_path', $app_path)
                ->order_by('app_sort ASC, app_id ASC')
                ->get()
                ->result(get_called_class());
        }
        else
        {
            $menu = $this->db->select('*')
                ->from($this->table)
                ->not_like('app_path', '/')
                ->order_by('app_sort ASC, app_id ASC')
                ->get()
                ->result(get_called_class());
        }

        foreach ($menu as $key => $app)
        {
            $menu[$key]->sub = 
                $this->db->select('*')
                    ->from($this->table)
                    ->like('app_path', $app->app_path.'/', 'after')
                    ->order_by('app_sort ASC, app_id ASC')
                    ->get()
                    ->result(get_called_class());
        }
        return $menu;
    }

    /**
     * getByPath function
     *
     * @return  object
     */
    public function getByPath($app_path)
    {
        $query = $this->db->get_where($this->table, ['app_path' => trim($app_path, '/')]);
        return $query->row(0, get_called_class());
    }
}
