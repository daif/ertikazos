<?php
/**
 * Ertikaz Watchdog model class
 *
 * This class object is the Watchdog model class
 *
 * @package     Ertikaz
 * @subpackage  model
 * @category    model
 */

class Watchdog_model extends ER_Model {

    /**
     * The table name for the model.
     *
     * @var string
     */
    public $table = 'er_logs';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    public $primaryKey = 'log_id';

    /**
     * The primary Label for the model.
     *
     * @var string
     */
    public $primaryLabel = 'log_type';

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
            'url' => 'Admin/Watchdog',
            'class' => '',
            'method' => 'get',
        ],
        'show' => [
            'class' => 'glyphicon glyphicon-eye-open',
        ]
    ];

    /**
     * The array of the forms input fields.
     *
     * @var array
     */
    public $forms = [
        '*' => [
            'log_id'    => [
                'field' => 'log_id',
                'rules' => ''
            ],
            'log_date'  => [
                'field' => 'log_date',
                'rules' => ''
            ],
            'log_type'  => [
                'field' => 'log_type',
                'rules' => 'min_length[1]'
            ],
            'log_ip'  => [
                'field' => 'log_ip',
                'rules' => 'valid_ip'
            ],
            'log_app_id'  => [
                'field' => 'log_app_id',
                'rules' => 'integer',
                'type'  => 'select:hasOne[Admin/App][app_id]'
            ],
            'log_user_id'  => [
                'field' => 'log_user_id',
                'rules' => 'integer',
                'type'  => 'select:hasOne[Admin/User][user_id]'
            ],
            'log_variables'  => [
                'field' => 'log_variables',
                'rules' => ''
            ],
            'log_time'  => [
                'field' => 'log_time',
                'rules' => ''
            ]
        ],
        'list' => [
            'log_id'        => [],
            'log_date'      => [],
            'log_type'      => [],
            'log_ip'        => [],
            'log_app_id'    => [],
            'log_user_id'   => [],
            'log_time'      => [],
        ],
        'search' => [
            'log_type'      => [],
            'log_ip'        => [],
            'log_app_id'    => [],
            'log_user_id'   => [],
        ],
        'show' => [
            'log_id'        => [],
            'log_date'      => [],
            'log_type'      => [],
            'log_ip'        => [],
            'log_app_id'    => [],
            'log_user_id'   => [],
            'log_variables' => [],
            'log_time'      => [],
        ]
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
}
