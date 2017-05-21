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
                'type'      => 'select:hasOne[Admin/Setting][value][name^=app_menu]',
                'alias'     => 'app_menu_name'
            ],
            'app_access'=> [
                'field'     => 'app_access',
                'rules'     => 'required|integer',
                'type'      => 'select:hasOne[Admin/Setting][value][name^=app_access]',
            ],
            'app_status'=> [
                'field'     => 'app_status',
                'rules'     => 'required|integer',
                'type'      => 'select:hasOne[Admin/Setting][value][name^=status]',
                'alias'     => 'app_status_name'
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
     * All Apps array.
     * @var array
     */
    public $apps = [];

    /**
     * Apps Menu array.
     * @var array
     */
    public $apps_menu = [];

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
     * load all Apps from DB function
     *
     * @return  object
     */
    public function loadApps()
    {
        if(count($this->apps) == 0)
        {
            $this->db->reset_query();
            $this->db->select('*');
            $this->db->from($this->table);
            $this->db->order_by('app_sort ASC, app_id ASC');
            $rows = $this->db->get()->result(get_called_class());
            foreach ($rows as $key => $row)
            {
                $row->sub = [];
                $this->apps[$row->app_path] = $row;
            }
        }
    }

    /**
     * getMenu function
     *
     * @return  object
     */
    public function getMenu()
    {
        if(count($this->apps_menu) == 0)
        {
            $this->loadApps();

            $app_menu = array_filter($this->apps, function($key) {return !strstr($key, '/');}, ARRAY_FILTER_USE_KEY);
            $sub_menu = array_filter($this->apps, function($key) {return  strstr($key, '/');}, ARRAY_FILTER_USE_KEY);

            // Add sub application to parent  App
            foreach ($sub_menu as $key => $sub_app)
            {
                if(isset($app_menu[explode('/', $sub_app->app_path)[0]]))
                {
                    $app_menu[explode('/', $sub_app->app_path)[0]]->sub[] = $sub_app;
                }
            }
            $this->apps_menu = $app_menu;
        }
        return $this->apps_menu;
    }

    /**
     * getByPath function
     *
     * @return  object
     */
    public function getByPath($app_path)
    {
        $this->loadApps();
        $app_path = trim($app_path, '/');
        if(isset($this->apps[$app_path]))
        {
            return $this->apps[$app_path];
        }
        return FALSE;
    }
}
