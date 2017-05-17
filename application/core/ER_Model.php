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
    public $fields = [];

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
     * Strip HTML and PHP tags from a string.
     *
     * @var integer
     */
    public $stripTags = true;

    /**
     * The array of fields that are excluded from stripTags protection
     *
     * @var array
     */
    public $stripTagsExclude = [];

    /**
     * The array of related models
     *
     * @var array
     */
    public $relations = [];

    /**
     * The array of the forms input fields with rules.
     *
     * please refer to this link for rules
     * https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference 
     * 
     * and this link for forms
     * https://www.codeigniter.com/user_guide/helpers/form_helper.html
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
        // build the relations array from the forms array.
        foreach ($this->forms['*'] as $key => $column)
        {
            if(!isset($column['type'])) continue;
            if(preg_match('/select:hasOne\[(.+)\]\[(.+)\](\[.+\]|)/Ui', $column['type'], $match))
            {
                if(preg_match('/(.+)::(.+)/i', $match[1], $model_match))
                {
                    $match[1]          = $model_match[1];
                }
                $this->relations[$key] = ['model'=>$match[1], 'key'=>$match[2], 'where'=>$match[3]];
            }
        }
    }

    /**
     * __set magic
     *
     * Set models variable and apply strip_tags if its enabled.
     *
     * @param   string  $name
     * @param   mixed   $value
     */
    public function __set($name, $value)
    {
        if($this->stripTags && !in_array($name, $this->stripTagsExclude) && is_string($value))
        {
            $value = strip_tags($value);
        }
        $this->$name = $value;
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
     * @param   string  $form       the form name.
     * @param   mixed   $index      Index for item to be fetched from $array
     * @return  array
     */
    public function forms($form, $index = NULL) {
        if(!isset($this->forms['*']) || !is_array($this->forms['*']))
        {
            show_error('$this->forms[\'*\'] array is not defined in '.get_called_class(), '500');
        }

        if(!isset($this->forms[$form]) || !is_array($this->forms[$form]))
        {
            show_error('$this->forms[\''.$form.'\'] array is not defined in '.get_called_class(), '500');
        }
        // start merging * from with this form.
        foreach ($this->forms[$form] as $key => $value)
        {
            foreach (['field'=>'', 'rules'=>'', 'label'=>'', 'class'=>'', 'type'=>'text', 'alias'=>''] as $merge_key => $merge_value)
            {
                if(!isset($this->forms[$form][$key][$merge_key]))
                {
                    if(isset($this->forms['*'][$key][$merge_key]))
                    {
                        $this->forms[$form][$key][$merge_key] = $this->forms['*'][$key][$merge_key];
                    }
                    else
                    {
                        $this->forms[$form][$key][$merge_key] = $merge_value;
                    }
                }
            }
        }
        // if $index is available 
        if(is_array($index))
        {
            $output = [];
            foreach ($index as $key => $value)
            {
                if(!isset($this->forms[$form][$key])) continue;
                $output[$key] = $this->forms[$form][$key];
            }
            return $output;
        }
        return $this->forms[$form];
    }

    /**
     * get rules for the form
     *
     * @param   string   $form       the form name.
     * @param   mixed    $index      Index for item to be fetched from $array
     * @return  array
     */
    public function rules($form, $index = NULL) {
        $rules = [];
        $form  = $this->forms($form, $index);
        foreach ($form as $key => $value)
        {
            if(isset($form[$key]['rules']) && trim($form[$key]['rules']) != '')
            {
                $rules[$key]['field'] = $form[$key]['field'];
                $rules[$key]['label'] = $form[$key]['label'];
                $rules[$key]['rules'] = $form[$key]['rules'];
            }
        }
        return $rules;
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
        if(!in_array($permission, ['show','list','edit','delete']))
        {
            return FALSE;
        }

        // check other permission
        if(($permission == 'show' || $permission == 'list') && in_array($otherOctal, array(4,5,6,7)))
        {
            return TRUE;
        }
        if($permission == 'edit' && in_array($otherOctal, [2,3,6,7]))
        {
            return TRUE;
        }
        if($permission == 'delete' && in_array($otherOctal, [1,3,5,7]))
        {
            return TRUE;
        }

        // if user is available check user permission
        if($this->getCreateBy())
        {
            if(($permission == 'show' || $permission == 'list') && in_array($userOctal, [4,5,6,7]) && $this->getCreateBy() == get_user_id())
            {
                return TRUE;
            }
            if($permission == 'edit' && in_array($userOctal, [2,3,6,7]) && $this->getCreateBy() == get_user_id())
            {
                return TRUE;
            }
            if($permission == 'delete' && in_array($userOctal, [1,3,5,7]) && $this->getCreateBy() == get_user_id())
            {
                return TRUE;
            }
        }

        // if user is available check user groups permission
        if($this->getCreateBy())
        {
            $createByGroups = [];
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
                    if(($permission == 'show' || $permission == 'list') && in_array($groupOctal, [4,5,6,7]))
                    {
                        return TRUE;
                    }
                    if($permission == 'edit' && in_array($groupOctal, [2,3,6,7]))
                    {
                        return TRUE;
                    }
                    if($permission == 'delete' && in_array($groupOctal, [1,3,5,7]))
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
     * Find rows by where condition with limit and offset or return null.
     *
     * @return  object or null
     */
    public function query($columns = ['*'], $where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = TRUE)
    {
        if(is_array($columns))
        {
            $columns = implode(',', $columns);
            if(trim($columns) == '*')
            {
                $columns = $this->table.'.*';
            }
        }

        if($withRelations)
        {
            foreach ($this->relations as $key => $relation)
            {
                if(!is_array($relation) || !isset($relation['model'])) continue;
                if(!isset($relation['alias'])) $relation['alias'] = $key;

                $relation_table = load_model($relation['model'])->table;
                $relation_alias = $relation_table.'_'.$relation['alias'];
                $relation_forms = load_model($relation['model'])->forms['*'];
                $relation_colmn = array_keys($relation_forms);
                foreach ($relation_colmn as $key => $colmn)
                {
                    $relation_colmn[$key] = $relation_alias.'.'.$colmn.' AS '.$relation['alias'].'_'.$colmn;
                }
                $columns = $columns.','.implode(',', $relation_colmn);
            }
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

        if($withRelations)
        {
            foreach ($this->relations as $key => $relation)
            {
                if(!is_array($relation) || !isset($relation['model'])) continue;
                if(!isset($relation['alias'])) $relation['alias'] = $key;
                $relation_table = load_model($relation['model'])->table;
                $relation_alias = $relation_table.'_'.$relation['alias'];
                // prepare ON condition
                $on_cond    = [];
                $on_cond[]  = $relation_alias.'.'.$relation['key'] .' = '.$key;
                if(isset($relation['where']) && preg_match('/\[([a-z0-9_]+)(\^=|\$=|\*=|=)([a-z0-9_]+)\]/Ui', $relation['where'], $match))
                {
                    if($match[2] == '^=') $on_cond[] = $relation_alias.'.'.$match[1].' LIKE \''.$match[3].'%\'';
                    if($match[2] == '$=') $on_cond[] = $relation_alias.'.'.$match[1].' LIKE \'%'.$match[3].'\'';
                    if($match[2] == '*=') $on_cond[] = $relation_alias.'.'.$match[1].' LIKE \'%'.$match[3].'%\'';
                    if($match[2] == '=')  $on_cond[] = $relation_alias.'.'.$match[1].' = \''.$match[3].'\'';
                }
                $this->db->join($relation_table.' AS '.$relation_alias, implode(' AND ', $on_cond), 'LEFT');
            }
        }

        return $this->db;
    }


    /**
     * Get all records
     *
     * @param  array  $columns
     * @return object
     */
    public function all($columns = ['*'], $where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = TRUE) {
        return $this->query($columns, $where, $limit, $offset, $order_by, $withRelations)->get()->result(get_called_class());
    }

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return  object or null
     */
    public function find($id, $columns = ['*']) {
        $where = [$this->getKeyName()=>$id];
        return $this->row($where, $columns);
    }

    /**
     * Find a row by where condition  or return null.
     *
     * @return  object or null
     */
    public function row($where = NULL, $columns = ['*'], $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = TRUE)
    {
        $query = $this->query($columns, $where, $limit, $offset, $order_by, $withRelations);
        return $query->get()->row(0, get_called_class());
    }

    /**
     * Find rows by where condition with limit and offset or return null.
     *
     * @return  array or null
     */
    public function rows($where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = TRUE)
    {
        $query = $this->query(['*'], $where, $limit, $offset, $order_by, $withRelations);
        return $query->get()->result(get_called_class());
    }

    /**
     * Find rows by where condition with limit and offset or return null.
     *
     * @return  array or null
     */
    public function search($where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = TRUE)
    {
        foreach ($where as $key => $value) {
            if(trim($value) != '') {
                $where['like'][$key] = $value;
            }
            unset($where[$key]);
        }
        $query = $this->query(['*'], $where, $limit, $offset, $order_by, $withRelations);
        return $query->get()->result(get_called_class());
    }

    /**
     * total count of search records
     *
     * @return  integer
     */
    public function count_search($where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = FALSE)
    {
        foreach ($where as $key => $value) {
            if(trim($value) != '') {
                $where['like'][$key] = $value;
            }
            unset($where[$key]);
        }
        $query = $this->query(['count(*) as count'], $where, $limit, $offset, $order_by, $withRelations);
        $row   = $query->get()->row_object();
        return $row->count;
    }

    /**
     * total count of records
     *
     * @return  integer
     */
    public function count($where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = FALSE)
    {
        $query = $this->query(['count(*) as count'], $where, $limit, $offset, $order_by, $withRelations);
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
    public function user_find($id, $columns = ['*']) {
        $where = [$this->getKeyName()=>$id];
        return $this->user_row($where, $columns);
    }

    /**
     * Find a user row by where condition or return null.
     *
     * @return  object or null
     */
    public function user_row($where = [], $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = TRUE)
    {
        if($this->getCreateByName())
        {
            $where[$this->getCreateByName()] = get_user_id();
            return $this->row($where, $limit, $offset, $order_by, $withRelations);
        }
        show_error('Unable to find createBy field in '.get_called_class(), '500');
    }

    /**
     * Find rows by where condition with limit and offset or return null.
     *
     * @return  array or null
     */
    public function user_rows($where = [], $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = TRUE)
    {
        if($this->getCreateByName())
        {
            $where[$this->getCreateByName()] = get_user_id();
            return $this->rows($where, $limit, $offset, $order_by, $withRelations);
        }
        show_error('Unable to find createBy field in '.get_called_class(), '500');
    }

    /**
     * Find user rows by where condition with limit and offset or return null.
     *
     * @return  array or null
     */
    public function user_search($where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = TRUE)
    {
        if($this->getCreateByName())
        {
            $where = (array) $where;
            $where[$this->getCreateByName()] = get_user_id();
            foreach ($where as $key => $value) {
                if(trim($value) != '') {
                    $where['like'][$key] = $value;
                }
                unset($where[$key]);
            }
            $query = $this->query(['*'], $where, $limit, $offset, $order_by, $withRelations);
            return $query->get()->result(get_called_class());
        }
        show_error('Unable to find createBy field in '.get_called_class(), '500');
    }

    /**
     * total count of user search records
     *
     * @return  integer
     */
    public function user_count_search($where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = FALSE)
    {
        if($this->getCreateByName())
        {
            $where = (array) $where;
            $where[$this->getCreateByName()] = get_user_id();
            foreach ($where as $key => $value) {
                if(trim($value) != '') {
                    $where['like'][$key] = $value;
                }
                unset($where[$key]);
            }
            $query = $this->query(['count(*) as count'], $where, $limit, $offset, $order_by, $withRelations);
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
    public function user_count($where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = FALSE)
    {
        if($this->getCreateByName())
        {
            $where = (array) $where;
            $where[$this->getCreateByName()] = get_user_id();
            $query = $this->query(['count(*) as count'], $where, $limit, $offset, $order_by, $withRelations);
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
    public function group_find($id, $columns = ['*']) {
        $where = [$this->getKeyName()=>$id];
        return $this->group_row($where, $columns);
    }

    /**
     * Find a group row by where condition or return null.
     *
     * @return  object or null
     */
    public function group_row($where = [], $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = TRUE)
    {
        if($this->getCreateByName())
        {
            $sql = '(SELECT rel_user_id FROM `er_users_rels` WHERE rel_group_id IN (
                        SELECT rel_group_id FROM `er_users_rels` WHERE rel_user_id ='.get_user_id().'
                    ) UNION SELECT '.get_user_id().')';
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
    public function group_rows($where = [], $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = TRUE)
    {
        if($this->getCreateByName())
        {
            $sql = '(SELECT rel_user_id FROM `er_users_rels` WHERE rel_group_id IN (
                        SELECT rel_group_id FROM `er_users_rels` WHERE rel_user_id ='.get_user_id().'
                    ) UNION SELECT '.get_user_id().')';
            $where['sql'][$this->getCreateByName().' IN '] = $sql;
            return $this->rows($where, $limit, $offset, $order_by, $withRelations);
        }
        show_error('Unable to find createBy field in '.get_called_class(), '500');
    }

    /**
     * Find group rows by where condition with limit and offset or return null.
     *
     * @return  array or null
     */
    public function group_search($where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = TRUE)
    {
        if($this->getCreateByName())
        {
            $where = (array) $where;
            $sql = '(SELECT rel_user_id FROM `er_users_rels` WHERE rel_group_id IN (
                        SELECT rel_group_id FROM `er_users_rels` WHERE rel_user_id ='.get_user_id().'
                    ) UNION SELECT '.get_user_id().')';
            $where['sql'][$this->getCreateByName().' IN '] = $sql;
            foreach ($where as $key => $value) {
                if(trim($value) != '') {
                    $where['like'][$key] = $value;
                }
                unset($where[$key]);
            }
            $query = $this->query(['*'], $where, $limit, $offset, $order_by, $withRelations);
            return $query->get()->result(get_called_class());
        }
        show_error('Unable to find createBy field in '.get_called_class(), '500');
    }

    /**
     * total count of group search records
     *
     * @return  integer
     */
    public function group_count_search($where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = FALSE)
    {
        if($this->getCreateByName())
        {
            $where = (array) $where;
            $sql = '(SELECT rel_user_id FROM `er_users_rels` WHERE rel_group_id IN (
                        SELECT rel_group_id FROM `er_users_rels` WHERE rel_user_id ='.get_user_id().'
                    ) UNION SELECT '.get_user_id().')';
            $where['sql'][$this->getCreateByName().' IN '] = $sql;
            foreach ($where as $key => $value) {
                if(trim($value) != '') {
                    $where['like'][$key] = $value;
                }
                unset($where[$key]);
            }
            $query = $this->query(['count(*) as count'], $where, $limit, $offset, $order_by, $withRelations);
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
    public function group_count($where = NULL, $limit = NULL, $offset = NULL, $order_by = NULL, $withRelations = FALSE)
    {
        if($this->getCreateByName())
        {
            $where = (array) $where;
            $sql = '(SELECT rel_user_id FROM `er_users_rels` WHERE rel_group_id IN (
                        SELECT rel_group_id FROM `er_users_rels` WHERE rel_user_id ='.get_user_id().'
                    ) UNION SELECT '.get_user_id().')';
            $where['sql'][$this->getCreateByName().' IN '] = $sql;
            $query = $this->query(['count(*) as count'], $where, $limit, $offset, $order_by, $withRelations);
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
        $data   = [];
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
                    $data[$field] = get_user_id();
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
                    $data[$field] = get_user_id();
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
        $data   = [];
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
                        $data[$field] = get_user_id();
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
            if($this->db->update($this->table, $data, [$this->getKeyName()=>$this->getKey()]))
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
        $this->db->delete($this->table, [$this->getKeyName() => $this->getKey()]);
        if($this->db->affected_rows() == 1)
        {
            return TRUE;
        }
        return FALSE; 
    }
}

/* End of file ER_Model.php */
/* Location: ./application/core/ER_Model.php */
