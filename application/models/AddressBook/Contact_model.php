<?php
/**
 * Ertikaz Contact model class
 *
 * This class object is the Contact model class
 *
 * @package     Ertikaz
 * @subpackage  model
 * @category    model
 */

class Contact_model extends ER_Model {
    /**
     * The table name for the model.
     *
     * @var string
     */
    public $table = 'address_contacts';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    public $primaryKey = 'contact_id';

    /**
     * The primary Label for the model.
     *
     * @var string
     */
    public $primaryLabel = 'contact_name';

    /**
     * The create by field name.
     *
     * @var string
     */
    public $createBy = 'contact_create_by';

    /**
     * The create at field name.
     *
     * @var string
     */
    public $createAt = 'contact_create_at';

    /**
     * The update by field name.
     *
     * @var string
     */
    public $updateBy = 'contact_update_by';

    /**
     * The update at field name.
     *
     * @var string
     */
    public $updateAt = 'contact_update_at';

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
        'contact_id'        => 'integer',
        'contact_group_id'  => 'required|integer',
        'contact_name'      => 'required',
        'contact_email'     => 'required|valid_email',
        'contact_mobile'    => 'required',
    );

    /**
     * The array of the row action buttons.
     *
     * @var array
     */
    public $action_buttons = [
        '*' => [
            'url' => 'AddressBook/Contact',
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
            'contact_id'    => array(
                'field' => 'contact_id'
            ),
            'contact_group_id'  => array(
                'field' => 'contact_group_id',
                'type'  => 'select:hasOne[Addressbook/Group::user_rows][group_id]'
            ),
            'contact_name'  => array(
                'field' => 'contact_name'
            ),
            'contact_email' => array(
                'field' => 'contact_email'
            ),
            'contact_mobile'  => array(
                'field' => 'contact_mobile'
            ),
        ),
        'list' => array(
            'contact_id'        => array(),
            'contact_group_id'  => array(
                'alias'  =>'group_name'
            ),
            'contact_name'      => array(),
            'contact_email'     => array(),
            'contact_mobile'    => array(),
        ),
        'search' => array(
            'contact_name'      => array(
                'rules'=>''
            ),
            'contact_group_id'  => array(
                'rules'         => 'integer',
            ),
        ),
        'create' => array(
            'contact_group_id'  => array(),
            'contact_name'      => array(),
            'contact_email'     => array(),
            'contact_mobile'    => array(),
        ),
        'edit' => array(
            'contact_id'    => array(
                'type'  =>'hidden'
            ),
            'contact_group_id'  => array(),
            'contact_name'      => array(),
            'contact_email'     => array(),
        ),
        'show' => array(
            'contact_id'        => array(),
            'contact_group_id'  => array(
                'alias'  =>'group_name'
            ),
            'contact_name'      => array(),
            'contact_email'     => array(),
            'contact_mobile'    => array(),
        ),
        'delete' => array(
            'contact_id'   => array(
                'type'  => 'hidden'
            ),
        ),
    );

    public function __construct($id=NULL)
    {
        parent::__construct($id);
    }

    public function group()
    {
        return $this->hasMany('Addressbook/Group', 'group_id', 'contact_group_id');
    }
}
