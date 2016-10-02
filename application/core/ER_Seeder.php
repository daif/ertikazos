<?php
/**
 * ertikaz seeder class
 *
 * This class object for database seeding 
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class ER_Seeder {
    /**
     * The seeds array 
     *
     * @var array
     */
    public $seeds = array();

    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct(){
    }

    /**
     *
     * get row from table by where condition 
     *
     */
    function getRowBy($table, $where, $return=FALSE)
    {
        $row = get_instance()->db->get_where($table , $where)->row_object();
        if(is_object($row))
        {
            if($return && isset($row->$return))
            {
                return($row->$return);
            }
            return($row);
        }
    }

    /**
     *
     * get count of row in table 
     *
     */
    function getRowCount($table, $where)
    {
        $count = get_instance()->db->get_where($table , $where)->count();
        return($count);
    }

    /**
     * seeding the database
     *
     * @return  boolean 
     */
    function seeding() {
        foreach ($this->seeds as $table => $rows)
        {
            foreach ($rows as $key => $row)
            {
                if(preg_match('/(.+):(.+)/', $table, $table_name))
                {
                    $table      = $table_name[1];
                    $pid_name   = $table_name[2];
                }

                if(isset($row['seeds']) && isset($pid_name))
                {
                    $seeds = $row['seeds'];
                    unset($row['seeds']);
                    get_instance()->db->insert($table, $row);
                    $pid = get_instance()->db->insert_id();
                    foreach ($seeds as $key => $row)
                    {
                        $row[$pid_name] = $pid;
                        get_instance()->db->insert($table, $row);
                    }
                }
                else
                {
                    get_instance()->db->insert($table, $row);
                }
                print $table." Table has been seeded\n";
            }
        }
    }
}

/* End of file ER_Seeder.php */
/* Location: ./application/core/ER_Seeder.php */
