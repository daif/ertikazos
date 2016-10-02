<?php
/**
 * Ertikaz UserRel model class
 *
 * This class object is the UserRel model class
 *
 * @package     Ertikaz
 * @subpackage  model
 * @category    model
 */

class Userrel_model extends ER_Model {
    /**
     * The table name for the model.
     *
     * @var string
     */
    public $table = 'er_users_rels';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    public $primaryKey = 'rel_id';

    /**
     * The primary Label for the model.
     *
     * @var string
     */
    public $primaryLabel = 'rel_id';

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
        'rel_id'        =>'integer',
        'rel_user_id'   =>'integer|required',
        'rel_group_id'  =>'integer|required',
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
