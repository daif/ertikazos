<?php
/**
 * Ertikaz Permission model class
 *
 * This class object is the Permission model class
 *
 * @package     Ertikaz
 * @subpackage  model
 * @category    model
 */

class Permission_model extends ER_Model {
    /**
     * The table name for the model.
     *
     * @var string
     */
    public $table = 'er_permissions';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    public $primaryKey = 'perm_id';

    /**
     * The primary Label for the model.
     *
     * @var string
     */
    public $primaryLabel = 'perm_id';

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
        'perm_id'       =>'integer',
        'perm_app_id'   =>'required|integer',
        'perm_group_id' =>'integer',
        'perm_user_id'  =>'integer',
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
