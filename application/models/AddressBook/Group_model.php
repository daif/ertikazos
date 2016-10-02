<?php
/**
 * Ertikaz group model class
 *
 * This class object is the group model class
 *
 * @package     Ertikaz
 * @subpackage  model
 * @category    model
 */

class Group_model extends ER_Model {
    /**
     * The table name for the model.
     *
     * @var string
     */
    public $table = 'address_groups';

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
     * The create by field name.
     *
     * @var string
     */
    public $createBy = 'group_create_by';

    /**
     * The create at field name.
     *
     * @var string
     */
    public $createAt = 'group_create_at';

    /**
     * The update by field name.
     *
     * @var string
     */
    public $updateBy = 'group_update_by';

    /**
     * The update at field name.
     *
     * @var string
     */
    public $updateAt = 'group_update_at';

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
        'group_id'      => 'integer',
        'group_name'    => 'required',
    );

    /**
     * The array of the row action buttons.
     *
     * @var array
     */
    public $action_buttons = [
        '*' => [
            'url' => 'AddressBook/Group',
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
            'group_id'    => array(
                'field' => 'group_id'
            ),
            'group_name'  => array(
                'field' => 'group_name'
            ),
        ),
        'list' => array(
            'group_id'      => array(),
            'group_name'    => array(),
        ),
        'search' => array(
            'group_name'  => array(),
        ),
        'create' => array(
            'group_name'    => array(),
        ),
        'edit' => array(
            'group_id'    => array(
                'type'  =>'hidden'
            ),
            'group_name'    => array(),
        ),
        'show' => array(
            'group_id'      => array(),
            'group_name'    => array(),
        ),
        'delete' => array(
            'group_id'   => array(
                'type'  => 'hidden'
            ),
        ),
    );

    public function __construct($id=NULL)
    {
        parent::__construct($id);
    }

    public function contact()
    {
        return $this->hasMany('Addressbook/Contact', 'group_id', 'contact_group_id');
    }
}
