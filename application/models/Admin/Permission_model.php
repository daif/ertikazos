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
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
    }
}
