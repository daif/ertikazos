<?php
/**
 * Ertikaz notify model class
 *
 * This class object is the nominee model class
 *
 * @package     Ertikaz
 * @subpackage  model
 * @category    model
 */

class Notify_model extends ER_Model {
    // constants
    const STATUS_READ = 1;
    const STATUS_UNREAD = 0;

    /**
     * The table name for the model.
     *
     * @var string
     */
    public $table = 'er_users_notify';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    public $primaryKey = 'notify_id';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    public $primaryLabel = 'notify_title';

    /**
     * The row permission.
     * @var integer
     */
    public $permission = 700;

    /**
     * The create at field name.
     *
     * @var string
     */

    public $createAt = 'notify_create_at';
    /**
     * The create by field name.
     *
     * @var string
     */

    public $createBy = 'notify_create_by';
    /**
     * The update at field name.
     *
     * @var string
     */

    public $updateAt = 'notify_update_at';

    /**
     * The update by field name.
     *
     * @var string
     */

    public $updateBy = 'notify_update_by';
  
    /**
     * The array of Form Validation rules.
     *
     * @var array
     */
    public $rules = [
        'notify_id'         =>'required',
        'notify_user_id'    =>'required|integer',
        'notify_title'      =>'required',
        'notify_body'       =>'required',
        'notify_status'     =>'required|integer',
    ];

    /**
     * The array of the row action buttons.
     *
     * @var array
     */
    public $action_buttons = [
        '*' => [
            'url' => 'User/Notify',
            'class' => '',
            'method' => 'get',
        ],
        'show' => [
            'class' => 'glyphicon glyphicon-eye-open',
        ],
    ];

    /**
     * The array of the forms input fields.
     *
     * @var array
     */
    public $forms = array(
        '*' => array(
            'notify_id'    => array(
                'field' => 'notify_id'
            ),
            'notify_title'  => array(
                'field' => 'notify_title'
            ),
            'notify_body'  => array(
                'field' => 'notify_body'
            ),
            'notify_status'  => array(
                'field' => 'notify_status',
                'type'  => 'select:hasOne[Admin/Setting][value][name^=notify_status]'
            ),
        ),
        'list' => array(
            'notify_id'     => array(),
            'notify_title'  => array(),
            'notify_status'  => array(),
        ),
        'search' => array(
        ),
        'create' => array(
            'notify_user_id' => array(),
            'notify_title'  => array(),
            'notify_body'  => array(),
            'notify_status'  => array(),
        ),
        'edit' => array(
        ),
        'delete' => array(
            'notify_id'   => array(
                'type'  => 'hidden'
            ),
        ),
    );

    public function __construct()
    {
        parent::__construct();
    }
}
