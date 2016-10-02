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
     * The array of Form Validation rules.
     *
     * @var array
     */
    public $rules = [
        'app_id'     => 'integer',
        'app_path'   => 'required',
        'app_icon'   => 'required',
        'app_sort'   => 'required|integer',
        'app_menu'   => 'required|integer',
        'app_access' => 'required|integer',
        'app_status' => 'required|integer',
    ];

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
    public $forms = array(
        '*' => array(
            'app_id'    => array(
                'field'     => 'app_id'
            ),
            'app_path'  => array(
                'field'     => 'app_path'
            ),
            'app_icon'  => array(
                'field'     => 'app_icon'
            ),
            'app_sort'  => array(
                'field'     => 'app_sort',
                'type'      => 'range:[1,10]'
            ),
            'app_menu'  => array(
                'field'     => 'app_menu',
                'type'      => 'select:hasOne[Admin/Setting][value][name^=app_menu]'
            ),
            'app_access'=> array(
                'field'     => 'app_access',
                'type'      => 'select:hasOne[Admin/Setting][value][name^=app_access]'
            ),
            'app_status'=> array(
                'field'     => 'app_status',
                'type'      => 'select:hasOne[Admin/Setting][value][name^=status]'
            ),
        ),
        'list' => array(
            'app_id'    => array(),
            'app_path'  => array(),
            'app_icon'  => array(),
            'app_sort'  => array(),
            'app_menu'  => array(),
            'app_access'=> array(),
            'app_status'=> array(),
        ),
        'search' => array(
            'app_path'  => array(
                'rules' => ''
            ),
        ),
        'create' => array(
            'app_path'  => array(),
            'app_icon'  => array(),
            'app_sort'  => array(),
            'app_menu'  => array(),
            'app_access'=> array(),
            'app_status'=> array(),
        ),
        'edit' => array(
            'app_id'    => array(
                'type'  =>'hidden'
            ),
            'app_path'  => array(),
            'app_icon'  => array(),
            'app_sort'  => array(),
            'app_menu'  => array(),
            'app_access'=> array(),
            'app_status'=> array(),
        ), 
        'show' => array(
            'app_id'    => array(),
            'app_path'  => array(),
            'app_icon'  => array(),
            'app_sort'  => array(),
            'app_menu'  => array(),
            'app_access'=> array(),
            'app_status'=> array(),
        ), 
        'delete' => array(
            'app_id'    => array(
                'type'  =>'hidden'
            ),
        ),
    );

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
        $query = $this->db->get_where($this->table, array('app_path' => trim($app_path, '/')));
        return $query->row(0, get_called_class());
    }
}
