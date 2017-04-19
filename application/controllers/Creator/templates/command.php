<?php
/**
 *
 * {class_name} command class
 *
 */

class {class_name} extends Command {
    
    /**
     * commands function.
     *
     * return command list as array.
     *
     * @access public
     * @return array
     */
    public static function commands()
    {
        return [
            'name' => '{class_name}', 
            'desc' => '{class_name} description.',
            'vars' => [
                [
                    'name' => '$var', 
                    'desc' => '$var description.',
                ]
            ],
        ];
    }
    /**
     *
     * function_name command.
     *
     */
    public function {function_name}($var='')
    {
        /*
            {function_name} code ...
        */
        $this->_print('{function_name} success message.', 'success');
    }
}
