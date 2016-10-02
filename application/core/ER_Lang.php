<?php
/**
 * Ertikaz base lang class
 *
 * This class object is the base lang class 
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class ER_Lang extends CI_Lang{

    function __construct(){
        parent::__construct();
    }

    /**
     * override Language line function 
     */
    public function line($line, $log_errors = TRUE)
    {
        $value = parent::line($line, $log_errors);
        if($value) {
            return $value;
        }
        return str_replace('_',' ',$line);
    }

    /**
     * check if the language has this line or not 
     */
    public function has_line($line, $log_errors = TRUE)
    {
        return isset($this->language[$line]) ? $this->language[$line] : FALSE;
    }
}

/* End of file ER_Lang.php */
/* Location: ./application/core/ER_Lang.php */
