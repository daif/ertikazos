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
     * The array of Form Validation rules.
     *
     * @var array
     */
    public $rules = [
        'log_id'        => '',
        'log_date'      => '',
        'log_type'      => 'min_length[1]',
        'log_ip'        => 'valid_ip',
        'log_app_id'    => 'integer',
        'log_user_id'   => 'integer',
        'log_variables' => '',
        'log_time'      => '',
    ];

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
            'log_id'    => array(
                'field' => 'log_id'
            ),
            'log_date'  => array(
                'field' => 'log_date'
            ),
            'log_type'  => array(
                'field' => 'log_type'
            ),
            'log_ip'  => array(
                'field' => 'log_ip'
            ),
            'log_app_id'  => array(
                'field' => 'log_app_id',
                'type'  => 'select:hasOne[Admin/App][app_id]'
            ),
            'log_user_id'  => array(
                'field' => 'log_user_id',
                'type'  => 'select:hasOne[Admin/User][user_id]'
            ),
            'log_variables'  => array(
                'field' => 'log_variables'
            ),
            'log_time'  => array(
                'field' => 'log_time'
            ),
        ),
        'list' => array(
            'log_id'        => array(),
            'log_date'      => array(),
            'log_type'      => array(),
            'log_ip'        => array(),
            'log_app_id'    => array(),
            'log_user_id'   => array(),
            'log_time'      => array(),
        ),
        'search' => array(
            'log_type'      => array(),
            'log_ip'        => array(),
            'log_app_id'    => array(),
            'log_user_id'   => array(),
        ),
        'show' => array(
            'log_id'        => array(),
            'log_date'      => array(),
            'log_type'      => array(),
            'log_ip'        => array(),
            'log_app_id'    => array(),
            'log_user_id'   => array(),
            'log_variables' => array(),
            'log_time'      => array(),
        ),
    );

    public function __construct()
    {
        parent::__construct();
    }
}
