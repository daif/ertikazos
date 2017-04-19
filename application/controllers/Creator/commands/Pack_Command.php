<?php
/**
 *
 * Pack command class
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class Pack_Command extends Command {

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
            'name' => 'pack', 
            'desc' => 'Creating application package for ErtikazOS store.', 
            'vars' => [
                [
                    'name' => '$app_name', 
                    'desc' => 'The application name.', 
                ],
            ], 
        ];
    }

    /**
     *
     * pack application.
     *
     */
    public function pack($app_name='')
    {
        $this->_print('','',"\n");

        // check app_name.json file
        if(!file_exists(APPPATH.'packages/'.$app_name.'.json'))
        {
            $this->_print('App json file application/packages/'.$app_name.'.json is not found.', 'error', "\n\n");
            return FALSE;
        }

        // read the application info
        $app_json = file_get_contents(APPPATH.'packages/'.$app_name.'.json');
        $app_json = json_decode($app_json);
        if(!is_object($app_json))
        {
            $this->_print('App json file application/packages/'.$app_name.'.json is not valid.', 'error', "\n\n");
            return FALSE;
        }
        if(!isset($app_json->files) || !is_array($app_json->files) || count($app_json->files) == 0)
        {
            $this->_print('App json file application/packages/'.$app_name.'.json is not valid.', 'error', "\n\n");
            return FALSE;
        }

        // create the package archive
        $root_path  = realpath(APPPATH.'..').'/';
        $zip_file   = APPPATH.'packages/'.$app_name.'-'.$app_json->version.'.zip';
        $zip        = new ZipArchive;
        if ($zip->open($zip_file, ZipArchive::CREATE) !== TRUE)
        {
            $this->_print('Cannot create '.$zip_file, 'error');
            return FALSE;
        }      
        foreach ($app_json->files as $key => $app_file)
        {
            if(!$zip->addFile($root_path.$app_file, $app_file))
            {
                $this->_print('Cannot add '.$app_file, 'error', "\n\n");
                unlink($zip_file);
                return FALSE;
            }
        }

        // if app name icon is existed include it
        if(file_exists(APPPATH.'packages/'.$app_name.'.png'))
        {
            $zip->addFile(APPPATH.'packages/'.$app_name.'.png', 'application/packages/'.$app_name.'-'.$app_json->version.'.png');
        }

        // update package time
        $app_json->time = gmdate('YmdHis');
        // add app_name.json file to the package
        $zip->addFromString('application/packages/'.$app_name.'-'.$app_json->version.'.json', json_encode($app_json, JSON_PRETTY_PRINT));
        // close zip file
        $zip->close();

        $this->_print('Package created in application/packages/'.$app_name.'-'.$app_json->version.'.zip', 'success');
        $this->_print('Upload to the store using creator command: php creator '.$app_name.' '.$app_json->version, 'success');
        $this->_print('','',"\n");
    }

}

?>