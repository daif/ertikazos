<?php
/**
 * Ertikaz {class_name} class
 *
 * This class object is the {class_name} class
 *
 * @package     Ertikaz
 * @subpackage  model
 * @category    model
 */

class {class_name} extends ER_Model {
/**
     * The table name for the model.
     *
     * @var string
     */
    public $table = '';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    public $primaryKey = '';

    /**
     * The primary Label for the model.
     *
     * @var string
     */
    public $primaryLabel = '';

    /**
     * The default ordering parameters.
     *
     * @var string
     */
    public $orderBy = '';

    /**
     * The create by field name.
     *
     * @var string
     */
    public $createBy = '';

    /**
     * The create at field name.
     *
     * @var string
     */
    public $createAt = '';

    /**
     * The update by field name.
     *
     * @var string
     */
    public $updateBy = '';

    /**
     * The update at field name.
     *
     * @var string
     */
    public $updateAt = '';

    /**
     * The row permission like Linux file system permissions.
     * to understand this go to http://permissions-calculator.org/
     *
     * @var integer
     */
    public $permission = 700;

    /**
     * The array of Form Validation rules.
     * please refer to this link
     * http://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference 
     *
     * @var array
     */
    public $rules = [
        '*' => ['required']
    ];

    /**
     * The array of the forms input fields.
     *
     * @var array
     */
    public $forms = [
        '*' => [],
        'list' => [],
        'edit' => [], 
        'show' => [],
        'search' => [],
        'create' => [],
        'delete' => []
    ];

    /**
     * The array of the row action buttons.
     *
     * @var array
     */
    public $action_buttons = [
        '*' => [
            'url' => '',
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
     * Class constructor
     *
     * @return  void
     */
    function __construct(){
        parent::__construct();
    }
}
