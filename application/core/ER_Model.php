<?php
/**
 * Ertikaz base model class
 *
 * This class object is the base model class
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class ER_Model extends CI_Model {
    /**
     * The table name for the model.
     *
     * @var string
     */
    public $table = '';

    /**
     * The table fields name array.
     *
     * @var array
     */
    public $fields = array();

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
     * The array of related models
     *
     * @var array
     */
    public $relations = array();

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

    /**
     * get table fields array 
     *
     * @return  array
     */
    public function fields() {
        if(!is_array($this->fields) || count($this->fields)<=0)
        {
            $this->fields = $this->db->list_fields($this->table);
        }
        return $this->fields;
    }

    /**
     * get forms function
     *
     * @return  array
     */
    public function forms($form) {
        return $this->rules($form, TRUE);
    }

    /**
     * get rules for the form
     *
     * @param  string   $form the form name.
     * @param  boolean  $merge_all true will merge type ti used in forms . 
     * @return  array
     */
    public function rules($form, $merge_all = FALSE) {
        if(isset($this->rules))
        {
            foreach ($this->rules as $key => $value)
            {
                $value = trim($value);
                // merge asterisk array with $form array
                if(isset($this->forms[$form][$key]))
                {
                    // merge field
                    if(!isset($this->forms[$form][$key]['field']) && isset($this->forms['*'][$key]['field']))
                    {
                        $this->forms[$form][$key]['field'] = $this->forms['*'][$key]['field'];
                    }
                    // merge label
                    if(!isset($this->forms[$form][$key]['label']) && isset($this->forms['*'][$key]['label']))
                    {
                        $this->forms[$form][$key]['label'] = $this->forms['*'][$key]['label'];
                    }

                    if($merge_all)
                    {
                        // merge class
                        if(!isset($this->forms[$form][$key]['class']) && isset($this->forms['*'][$key]['class']))
                        {
                            $this->forms[$form][$key]['class'] = $this->forms['*'][$key]['class'];
                        }
                        // merge type
                        if(!isset($this->forms[$form][$key]['type']))
                        {
                            if(isset($this->forms['*'][$key]['type']))
                            {
                                $this->forms[$form][$key]['type'] = $this->forms['*'][$key]['type'];
                            }
                            else
                            {
                                $this->forms[$form][$key]['type'] = 'text';
                            }
                        }
                    }
                    // set rules if it doesn't
                    if(!isset($this->forms[$form][$key]['rules']) && $value !== '')
                    {
                        $this->forms[$form][$key]['rules'] = $value;
                    }
                    // remove the rules if it's empty
                    if(isset($this->forms[$form][$key]['rules']) && $this->forms[$form][$key]['rules'] === '')
                    {
                        unset($this->forms[$form][$key]['rules']);
                    }
                }
            }
        }
        return $this->forms[$form];
    }

    /**
     * get action buttons list
     *
     * @param   string   $name the button form name.
     * @return  array
     */
    public function action_buttons($name = NULL) {
        if(is_array($this->action_buttons))
        {
            foreach ($this->action_buttons as $key => $button)
            {
                if(!isset($this->action_buttons[$key]['url']))
                {
                    $this->action_buttons[$key]['url'] = $this->action_buttons['*']['url'].'/'.$key;
                }
                if(!isset($this->action_buttons[$key]['class']))
                {
                    $this->action_buttons[$key]['class'] = $this->action_buttons['*']['class'];
                }
                if(!isset($this->action_buttons[$key]['method']))
                {
                    $this->action_buttons[$key]['method'] = $this->action_buttons['*']['method'];
                }
            }
        }
        if($name)
        {
            if(isset($this->action_buttons[$name]))
            {
                return $this->action_buttons[$name];
            }
            else
            {
                return FALSE;
            }
        }
        return $this->action_buttons;
    }

    /**
     * Get the model's primary key name.
     *
     * @return  string
     */
    public function getKeyName()
    {
        if($this->primaryKey)
        {
            return $this->primaryKey;
        }
        elseif ($this->db->primary($this->table))
        {
            return $this->db->primary($this->table);
        }
        show_error('Unable to find the primary key field in '.get_called_class(), '500');
    }

    /**
     * Get the model's primary key value.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->{$this->getKeyName()};
    }

    /**
     * Get the model's primary Label name.
     *
     * @return string
     */
    public function getLabelName()
    {
        if($this->primaryLabel && array_search($this->primaryLabel, $this->fields()))
        {
            return $this->primaryLabel;
        }
        // if the above is not existed.
        // try to find any fields name has name or title keyword
        foreach ($this->fields() as $key => $field) {
            if(preg_match('/name|title/i', $field))
            {
                return $field;
            }
        }
        // else return the primary key
        return $this->getKeyName();
    }

    /**
     * Get the model's CreateBy field name.
     *
     * @return string
     */
    public function getCreateByName()
    {
        if($this->createBy)
        {
            if(array_search($this->createBy, $this->fields()))
            {
                return $this->createBy;
            }
            else
            {
                show_error('Unable to find createBy field in '.get_called_class(), '500');
            }
        }
        else
        {
            show_error('createBy field is not set in '.get_called_class(), '500');
        
        }
    }

    /**
     * Get the model's CreateBy filed value.
     *
     * @return integer
     */
    public function getCreateBy()
    {
        return $this->{$this->getCreateByName()};
    }

    /**
     * Get the model's CreateAt field name.
     *
     * @return string
     */
    public function getCreateAtName()
    {
        if($this->createAt)
        {
            if(array_search($this->createAt, $this->fields()))
            {
                return $this->createAt;
            }
            else
            {
                show_error('Unable to find createAt field in '.get_called_class(), '500');
            }
        }
        else
        {
            show_error('createAt field is not set in '.get_called_class(), '500');
        }
    }

    /**
     * Get the model's CreateAt field value.
     *
     * @return integer
     */
    public function getCreateAt()
    {
        return $this->{$this->getCreateAtName()};
    }

    /**
     * Get the model's UpdateBy field name.
     *
     * @return string
     */
    public function getUpdateByName()
    {
        if($this->updateBy)
        {
            if(array_search($this->updateBy, $this->fields()))
            {
                return $this->updateBy;
            }
            else
            {
                show_error('Unable to find updateBy field in '.get_called_class(), '500');
            }
        }
        else
        {
            show_error('updateBy field is not set in '.get_called_class(), '500');
        }
    }

    /**
     * Get the model's UpdateBy filed value.
     *
     * @return integer
     */
    public function getUpdateBy()
    {
        return $this->{$this->getUpdateByName()};
    }

    /**
     * Get the model's UpdateAt field name.
     *
     * @return string
     */
    public function getUpdateAtName()
    {
        if($this->updateAt)
        {
            if(array_search($this->updateAt, $this->fields()))
            {
                return $this->updateAt;
            }
            else
            {
                show_error('Unable to find updateAt field in '.get_called_class(), '500');
            }
        }
        else
        {
            show_error('updateAt field is not set in '.get_called_class(), '500');
        }
    }

    /**
     * Get the model's UpdateAt field value.
     *
     * @return integer
     */
    public function getUpdateAt()
    {
        return $this->{$this->getUpdateAtName()};
    }

    /**
     * Get the model's permission.
     *
     * @return boolean
     */
    public function getPermission()
    {
        if(strlen((int)$this->permission) >= 3)
        {
            return (int)$this->permission;
        }
        show_error('permission value is not set correctly in '.get_called_class(), '500');
    }

    /**
     * Check if current user has permission to 
     * the row according to permission value.
     *
     * @param  string  $permission the permission can be 
     *         show as read, edit as write and delete as execute.
     * @return boolean
     */

    public function hasPermission($permission)
    {
        $ci         = &get_instance();
        $userOctal  = substr($this->getPermission(), 0, 1);
        $groupOctal = substr($this->getPermission(), 1, 1);
        $otherOctal = substr($this->getPermission(), 2, 1);

        // ignore the permission if it is not supported
        if(!in_array($permission, array('show','list','edit','delete')))
        {
            return FALSE;
        }

        // check other permission
        if(($permission == 'show' || $permission == 'list') && in_array($otherOctal, array(4,5,6,7)))
        {
            return TRUE;
        }
        if($permission == 'edit' && in_array($otherOctal, array(2,3,6,7)))
        {
            return TRUE;
        }
        if($permission == 'delete' && in_array($otherOctal, array(1,3,5,7)))
        {
            return TRUE;
        }

        // if user is available check user permission
        if($this->getCreateBy())
        {
            if(($permission == 'show' || $permission == 'list') && in_array($userOctal, array(4,5,6,7)) && $this->getCreateBy() == $ci->userdata->user_id)
            {
                return TRUE;
            }
            if($permission == 'edit' && in_array($userOctal, array(2,3,6,7)) && $this->getCreateBy() == $ci->userdata->user_id)
            {
                return TRUE;
            }
            if($permission == 'delete' && in_array($userOctal, array(1,3,5,7)) && $this->getCreateBy() == $ci->userdata->user_id)
            {
                return TRUE;
            }
        }

        // if user is available check user groups permission
        if($this->getCreateBy())
        {
            $createByGroups = array();
            // first find the created by user groups
            $createByUser = $ci->user->find($this->getCreateBy());
            foreach ($createByUser->groups() as $key => $group)
            {
                $createByGroups[] = $group->group_id;
            }
            // second foreach the current user groups
            foreach ($ci->userdata->groups() as $key => $group)
            {
                if(in_array($group->group_id, $createByGroups))
                {
                    if(($permission == 'show' || $permission == 'list') && in_array($groupOctal, array(4,5,6,7)))
                    {
                        return TRUE;
                    }
                    if($permission == 'edit' && in_array($groupOctal, array(2,3,6,7)))
                    {
                        return TRUE;
                    }
                    if($permission == 'delete' && in_array($groupOctal, array(1,3,5,7)))
                    {
                        return TRUE;
                    }
                    break;
                }
            }
        }

        return FALSE;
    }

    /**
     * Get the model's primary Label value.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->{$this->getLabelName()};
    }

    /**
     * Get the default foreign key name for the model.
     *
     * @param  string  $primaryKey of related model
     * @return string
     */
    public function getForeignKey($primaryKey)
    {
        foreach ($this->fields() as $key => $field) {
            if(preg_match('/'.$primaryKey.'/', $field))
            {
                return $field;
            }
        }
    }

    /**
     * load related model.
     *
     * @param  string  $relation class name
     * @return object of $related class
     */
    public function loadRelated($relation)
    {
        return load_model($relation);
    }

    /**
     * one-to-one relationship.
     *
     * @param  string  $relation class name
     * @param  string  $foreignKey name
     * @param  string  $otherKey  name
     * @return object of $related class
     */
    public function hasOne($relation, $foreignKey = NULL, $otherKey = NULL)
    {
        if($this->loadRelated($relation))
        {
            $otherKey   = ($otherKey    === NULL)?$this->loadRelated($relation)->getKeyName:$otherKey;
            $foreignKey = ($foreignKey  === NULL)?$this->getForeignKey($this->$relation->getKeyName()):$foreignKey;
            return $this->loadRelated($relation)->row(array($otherKey=>$this->$foreignKey));
        }
    }

    /**
     * one-to-many relationship.
     *
     * @param  string  $relation class name
     * @param  string  $foreignKey name
     * @param  string  $otherKey  name
     * @return object of $related class
     */
    public function hasMany($relation, $foreignKey = NULL, $otherKey = NULL)
    {
        if($this->loadRelated($relation))
        {
            $otherKey   = ($otherKey    === NULL)?$this->loadRelated($relation)->getKeyName:$otherKey;
            $foreignKey = ($foreignKey  === NULL)?$this->getForeignKey($this->$relation->getKeyName()):$foreignKey;
            return $this->loadRelated($relation)->rows(array($otherKey=>$this->$foreignKey));
        }
    }

    /**
     * loading related models
     *
     * @param  string|array  $relations
     * @return  object or null
     */
    public function with($relations) {
        if(!is_string($relations) || !is_array($relations))
        {
            $relations = func_get_args();
        }
        foreach ($relations as $key => $relation) {
            if($this->loadRelated($relation))
            {
                $this->relations[$relation] = strtolower(preg_replace('#(.+)/#', '', $relation));
            }
        }
        return $this;
    }

    /**
     * Find rows by where condition with limit and offset or return null.
     *
     * @return  object or null
     */
    public function query($columns = array('*'), $where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL)
    {
        if(is_array($columns)){
            $columns = implode(',', $columns);
        }
        $this->db->reset_query();
        $this->db->select($columns);
        $this->db->from($this->table);
        if($where !== NULL)
        {
            if(is_array($where) && isset($where['like']))
            {
                $where_like = $where['like'];
                unset($where['like']);
                $this->db->like($where_like);
            }
            if(is_array($where) && isset($where['like_after']))
            {
                $where_like = $where['like_after'];
                unset($where['like_after']);
                $this->db->like($where_like, '', 'after');
            }
            if(is_array($where) && isset($where['like_before']))
            {
                $where_like = $where['like_before'];
                unset($where['like_before']);
                $this->db->like($where_like, '', 'before');
            }
            if(is_array($where) && isset($where['sql']))
            {
                $where_sql = $where['sql'];
                unset($where['sql']);
                $this->db->where($where_sql, NULL, FALSE);
            }
            $this->db->where($where);
        }
        if($limit !== NULL)
        {
            $this->db->limit($limit);
        }
        if($offset !== NULL)
        {
            $this->db->offset($offset);
        }
        if($order_by !== NULL)
        {
            $this->db->order_by($order_by);
        }
        elseif($this->orderBy != '')
        {
            $this->db->order_by($this->orderBy);
        }

        foreach ($this->relations as $key => $relation) {
            $this->db->join($this->$relation->table, $this->$relation->getKeyName() .' = '.$this->getForeignKey($this->$relation->getKeyName()), 'LEFT');
        }
        return $this->db;
    }

    /**
     * Get all records
     *
     * @param  array  $columns
     * @return object
     */
    public function all($columns = array('*')) {
        return $this->query($columns)->get()->result(get_called_class());
    }

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return  object or null
     */
    public function find($id, $columns = array('*')) {
        $where = array($this->getKeyName()=>$id);
        return $this->row($where, $columns);
    }

    /**
     * Find a row by where condition  or return null.
     *
     * @return  object or null
     */
    public function row($where = NULL, $columns = array('*'))
    {
        $query = $this->query($columns, $where);
        return $query->get()->row(0, get_called_class());
    }

    /**
     * Find rows by where condition with limit and offset or return null.
     *
     * @return  array or null
     */
    public function rows($where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL)
    {
        $query = $this->query(array('*'), $where, $limit, $offset, $order_by);
        return $query->get()->result(get_called_class());
    }

    /**
     * Find rows by where condition with limit and offset or return null.
     *
     * @return  array or null
     */
    public function search($where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL)
    {
        foreach ($where as $key => $value) {
            if(trim($value) != '') {
                $where['like'][$key] = $value;
            }
            unset($where[$key]);
        }
        $query = $this->query(array('*'), $where, $limit, $offset, $order_by);
        return $query->get()->result(get_called_class());
    }

    /**
     * total count of search records
     *
     * @return  integer
     */
    public function count_search($where = NULL)
    {
        foreach ($where as $key => $value) {
            if(trim($value) != '') {
                $where['like'][$key] = $value;
            }
            unset($where[$key]);
        }
        $query = $this->query(array('count(*) as count'), $where);
        $row   = $query->get()->row_object();
        return $row->count;
    }

    /**
     * total count of records
     *
     * @return  integer
     */
    public function count($where = NULL)
    {
        $query = $this->query(array('count(*) as count'), $where);
        $row   = $query->get()->row_object();
        return $row->count;
    }

    /**
     * Find a model by its primary key using user_row.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return  object or null
     */
    public function user_find($id, $columns = array('*')) {
        $where = array($this->getKeyName()=>$id);
        return $this->user_row($where, $columns);
    }

    /**
     * Find a user row by where condition or return null.
     *
     * @return  object or null
     */
    public function user_row($where = array(), $limit = NULL, $offset = NULL)
    {
        $ci = &get_instance();
        if($this->getCreateByName())
        {
            $where[$this->getCreateByName()] = $ci->userdata->user_id;
            return $this->row($where, $limit, $offset);
        }
        show_error('Unable to find createBy field in '.get_called_class(), '500');
    }

    /**
     * Find rows by where condition with limit and offset or return null.
     *
     * @return  array or null
     */
    public function user_rows($where = array(), $limit = NULL, $offset = NULL, $order_by = NULL)
    {
        $ci = &get_instance();
        if($this->getCreateByName())
        {
            $where[$this->getCreateByName()] = $ci->userdata->user_id;
            return $this->rows($where, $limit, $offset, $order_by);
        }
        show_error('Unable to find createBy field in '.get_called_class(), '500');
    }

    /**
     * Find user rows by where condition with limit and offset or return null.
     *
     * @return  array or null
     */
    public function user_search($where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL)
    {
        $ci = &get_instance();
        if($this->getCreateByName())
        {
            $where = (array) $where;
            $where[$this->getCreateByName()] = $ci->userdata->user_id;
            foreach ($where as $key => $value) {
                if(trim($value) != '') {
                    $where['like'][$key] = $value;
                }
                unset($where[$key]);
            }
            $query = $this->query(array('*'), $where, $limit, $offset, $order_by);
            return $query->get()->result(get_called_class());
        }
        show_error('Unable to find createBy field in '.get_called_class(), '500');
    }

    /**
     * total count of user search records
     *
     * @return  integer
     */
    public function user_count_search($where = NULL)
    {
        $ci = &get_instance();
        if($this->getCreateByName())
        {
            $where = (array) $where;
            $where[$this->getCreateByName()] = $ci->userdata->user_id;
            foreach ($where as $key => $value) {
                if(trim($value) != '') {
                    $where['like'][$key] = $value;
                }
                unset($where[$key]);
            }
            $query = $this->query(array('count(*) as count'), $where);
            $row   = $query->get()->row_object();
            return $row->count;
        }
        return 0;
    }

    /**
     * total count of user records
     *
     * @return  integer
     */
    public function user_count($where = NULL)
    {
        $ci = &get_instance();
        if($this->getCreateByName())
        {
            $where = (array) $where;
            $where[$this->getCreateByName()] = $ci->userdata->user_id;
            $query = $this->query(array('count(*) as count'), $where);
            $row   = $query->get()->row_object();
            return $row->count;
        }
        return 0;
    }

    /**
     * Find a model by its primary key using group_row.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return  object or null
     */
    public function group_find($id, $columns = array('*')) {
        $where = array($this->getKeyName()=>$id);
        return $this->group_row($where, $columns);
    }

    /**
     * Find a group row by where condition or return null.
     *
     * @return  object or null
     */
    public function group_row($where = array(), $limit = NULL, $offset = NULL)
    {
        $ci = &get_instance();
        if($this->getCreateByName())
        {
            $sql = '(SELECT rel_user_id FROM `er_users_rels` WHERE rel_group_id IN (
                        SELECT rel_group_id FROM `er_users_rels` WHERE rel_user_id ='.(int)$ci->userdata->user_id.'
                    ) UNION SELECT '.(int)$ci->userdata->user_id.')';
            $where['sql'][$this->getCreateByName().' IN '] = $sql;
            return $this->row($where, $limit, $offset);
        }
        show_error('Unable to find createBy field in '.get_called_class(), '500');
    }

    /**
     * Find rows by where condition with limit and offset or return null.
     *
     * @return  array or null
     */
    public function group_rows($where = array(), $limit = NULL, $offset = NULL, $order_by = NULL)
    {
        $ci = &get_instance();
        if($this->getCreateByName())
        {
            $sql = '(SELECT rel_user_id FROM `er_users_rels` WHERE rel_group_id IN (
                        SELECT rel_group_id FROM `er_users_rels` WHERE rel_user_id ='.(int)$ci->userdata->user_id.'
                    ) UNION SELECT '.(int)$ci->userdata->user_id.')';
            $where['sql'][$this->getCreateByName().' IN '] = $sql;
            return $this->rows($where, $limit, $offset, $order_by);
        }
        show_error('Unable to find createBy field in '.get_called_class(), '500');
    }

    /**
     * Find group rows by where condition with limit and offset or return null.
     *
     * @return  array or null
     */
    public function group_search($where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL)
    {
        $ci = &get_instance();
        if($this->getCreateByName())
        {
            $where = (array) $where;
            $sql = '(SELECT rel_user_id FROM `er_users_rels` WHERE rel_group_id IN (
                        SELECT rel_group_id FROM `er_users_rels` WHERE rel_user_id ='.(int)$ci->userdata->user_id.'
                    ) UNION SELECT '.(int)$ci->userdata->user_id.')';
            $where['sql'][$this->getCreateByName().' IN '] = $sql;
            foreach ($where as $key => $value) {
                if(trim($value) != '') {
                    $where['like'][$key] = $value;
                }
                unset($where[$key]);
            }
            $query = $this->query(array('*'), $where, $limit, $offset, $order_by);
            return $query->get()->result(get_called_class());
        }
        show_error('Unable to find createBy field in '.get_called_class(), '500');
    }

    /**
     * total count of group search records
     *
     * @return  integer
     */
    public function group_count_search($where = NULL)
    {
        $ci = &get_instance();
        if($this->getCreateByName())
        {
            $where = (array) $where;
            $sql = '(SELECT rel_user_id FROM `er_users_rels` WHERE rel_group_id IN (
                        SELECT rel_group_id FROM `er_users_rels` WHERE rel_user_id ='.(int)$ci->userdata->user_id.'
                    ) UNION SELECT '.(int)$ci->userdata->user_id.')';
            $where['sql'][$this->getCreateByName().' IN '] = $sql;
            foreach ($where as $key => $value) {
                if(trim($value) != '') {
                    $where['like'][$key] = $value;
                }
                unset($where[$key]);
            }
            $query = $this->query(array('count(*) as count'), $where);
            $row   = $query->get()->row_object();
            return $row->count;
        }
        return 0;
    }

    /**
     * total count of group records
     *
     * @return  integer
     */
    public function group_count($where = NULL)
    {
        $ci = &get_instance();
        if($this->getCreateByName())
        {
            $where = (array) $where;
            $sql = '(SELECT rel_user_id FROM `er_users_rels` WHERE rel_group_id IN (
                        SELECT rel_group_id FROM `er_users_rels` WHERE rel_user_id ='.(int)$ci->userdata->user_id.'
                    ) UNION SELECT '.(int)$ci->userdata->user_id.')';
            $where['sql'][$this->getCreateByName().' IN '] = $sql;
            $query = $this->query(array('count(*) as count'), $where);
            $row   = $query->get()->row_object();
            return $row->count;
        }
        return 0;
    }

    /**
     * insert function
     *
     * @return  object or FALSE
     */
    public function insert() {
        $data   = array();
        foreach ($this->fields() as $key => $field)
        {
            // if createAt is available and name equal to current field, set to current time
            // else
            // if createBy is available and name equal to current field, set current user or 0
            // if updateAt is available and name equal to current field, set to current time
            // else
            // if updateBy is available and name equal to current field, set current user or 0
            if($this->createAt && $this->getCreateAtName() == $field)
            {
                $data[$field] = date('Y-m-d H:i:s');
            }
            elseif($this->createBy && $this->getCreateByName() == $field)
            {
                if(is_object($this->session->userdata('userdata')))
                {
                    $data[$field] = $this->session->userdata('userdata')->user_id;
                }
                else
                {
                    $data[$field] = 0;
                }
            }
            elseif($this->updateAt && $this->getUpdateAtName() == $field)
            {
                $data[$field] = date('Y-m-d H:i:s');
            }
            elseif($this->updateBy && $this->getUpdateByName() == $field)
            {
                if(is_object($this->session->userdata('userdata')))
                {
                    $data[$field] = $this->session->userdata('userdata')->user_id;
                }
                else
                {
                    $data[$field] = 0;
                }
            }
            else
            {
                if(isset($this->$field))
                {
                    $data[$field] = $this->$field;
                }
            }
        }
        if($this->db->insert($this->table, $data))
        {
            return $this->find($this->db->insert_id());
        }
        return FALSE;
    }

    /**
     * update function
     *
     * @return  object or FALSE
     */
    public function update() {
        $data   = array();
        if(isset($this->{$this->getKeyName()}))
        {
            foreach ($this->fields() as $key => $field)
            {
                // if updateAt is available and name equal to current field, set to current time
                // else
                // if updateBy is available and name equal to current field, set current user or 0
                if($this->updateAt && $this->getUpdateAtName() == $field)
                {
                    $data[$field] = date('Y-m-d H:i:s');
                }
                elseif($this->updateBy && $this->getUpdateByName() == $field)
                {
                    if(is_object($this->session->userdata('userdata')))
                    {
                        $data[$field] = $this->session->userdata('userdata')->user_id;
                    }
                    else
                    {
                        $data[$field] = 0;
                    }
                }
                else
                {
                    if(isset($this->$field) && $field != $this->getKeyName())
                    {
                        $data[$field] = $this->$field;
                    }
                }
            }
            if($this->db->update($this->table, $data, array($this->getKeyName()=>$this->getKey())))
            {
                return $this->find($this->getKey());
            }
        }
        return FALSE;
    }

    /**
     * save function
     *
     * @return  object
     */
    public function save() {
        if($this->getKey())
        {
            return $this->update();
        }
        else
        {
            return $this->insert();
        }
    }

    /**
     * delete function
     *
     * @return  TRUE or FALSE
     */
    public function delete()
    {
        $this->db->delete($this->table, array($this->getKeyName() => $this->getKey()));
        if($this->db->affected_rows() == 1)
        {
            return TRUE;
        }
        return FALSE; 
    }
}

/* End of file ER_Model.php */
/* Location: ./application/core/ER_Model.php */
