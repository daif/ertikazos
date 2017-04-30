<?php
/**
 * Ertikaz Setting model class
 *
 * This class object is the Setting model class
 *
 * @package     Ertikaz
 * @subpackage  model
 * @category    model
 */

class Setting_model extends ER_Model {
    // constants
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * The table name for the model.
     *
     * @var string
     */
    public $table = 'er_settings';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    public $primaryKey = 'id';

    /**
     * The primary Label for the model.
     *
     * @var string
     */
    public $primaryLabel = 'name';

    /**
     * The default ordering parameters.
     *
     * @var string
     */
    public $orderBy = 'sort ASC, id ASC';

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
            'url' => 'Admin/Setting',
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
            'id'    => array(
                'field' => 'id',
                'rules' => 'integer'
            ),
            'name'  => array(
                'field' => 'name',
                'rules' => 'required'
            ),
            'value' => array(
                'field' => 'value',
                'rules' => 'required'
            ),
            'sort'  => array(
                'field' => 'sort',
                'rules' => 'integer',
                'type'  => 'range:[1,10]'
            ),
        ),
        'list' => array(
            'id'    => array(),
            'name'  => array(),
            'value' => array(),
            'sort'  => array(),
        ),
        'search' => array(

        ),
        'create' => array(
            'name'  => array(),
            'value' => array(),
            'sort'  => array(),
        ),
        'edit' => array(
            'id'    => array(
                'type'  =>'hidden'
            ),
            'name'  => array(),
            'value' => array(),
            'sort'  => array(),
        ), 
        'show' => array(
            'id'    => array(),
            'name'  => array(),
            'value' => array(),
            'sort'  => array(),
        ), 
        'delete' => array(
            'id'   => array(
                'type' => 'hidden'
            ),
        ),
    );

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * mainSettings function
     *
     * @return  object
     */
    public function mainSettings()
    {
        return $this->db->select('*')
            ->from($this->table)
            ->not_like('name', '/')
            ->order_by('sort', 'ASC')
            ->get()
            ->result(get_called_class());
    }

    /**
     * subSettings function
     *
     * @return  object
     */
    public function subSettings()
    {
        return $this->db->select('*')
            ->from($this->table)
            ->like('name', $this->name.'/', 'after')
            ->order_by('sort', 'ASC')
            ->get()
            ->result(get_called_class());
    }
}
