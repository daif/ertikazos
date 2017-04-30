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
    public $forms = [
        '*' => [
            'notify_id' => [
                'field' => 'notify_id',
                'rules' => 'required'
            ],
            'notify_user_id' => [
                'field' => 'notify_user_id',
                'rules' => 'required|integer'
            ],
            'notify_title' => [
                'field' => 'notify_title',
                'rules' => 'required'
            ],
            'notify_body' => [
                'field' => 'notify_body',
                'rules' => 'required'
            ],
            'notify_status' => [
                'field' => 'notify_status',
                'rules' => 'required|integer',
                'type'  => 'select:hasOne[Admin/Setting][value][name^=notify_status]'
            ],
        ],
        'list' => [
            'notify_id'     => [],
            'notify_title'  => [],
            'notify_status'  => [],
        ],
        'search' => [
        ],
        'create' => [
            'notify_user_id' => [],
            'notify_title' => [],
            'notify_body' => [],
            'notify_status' => [],
        ],
        'edit' => [
        ],
        'delete' => [
            'notify_id' => [
                'type' => 'hidden'
            ],
        ],
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
