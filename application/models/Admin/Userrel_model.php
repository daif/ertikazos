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
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
    }
}
