<?php
/**
 *
 * Push command class
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class Push_Command extends Command {

    /**
     * The push URL 
     *
     * @var string
     */
    public $upload_url = 'https://ertikazos.com/store/upload/';

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
            'name' => 'push', 
            'desc' => 'Upload the application package to ErtikazOS store.', 
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
     * Push application.
     *
     */
    public function push($app_name='')
    {
        $this->_print('','',"\n");

        // check user-token
        if(!file_exists(APPPATH.'packages/.account-token'))
        {
            $this->_print('Account token is not found, create one using "creator token $name $mail".', 'error', "\n\n");
            return FALSE;
        }

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

        // check application files array 
        if(!isset($app_json->files) || !is_array($app_json->files) || count($app_json->files) == 0)
        {
            $this->_print('App json file application/packages/'.$app_name.'.json is not valid.', 'error', "\n\n");
            return FALSE;
        }

        // check application version  
        if(!isset($app_json->version) || !preg_match('/\d+\.\d+\.\d+/i', $app_json->version))
        {
            $this->_print('Invalid version number', 'error');
            return FALSE;
        }

        if(!file_exists(APPPATH.'packages/'.$app_name.'-'.$app_json->version.'.zip'))
        {
            $this->_print('Package '.APPPATH.'packages/'.$app_name.'-'.$app_json->version.'.zip is not found', 'error', "\n\n");
            return FALSE;
        }

        // upload the package
        $this->_print('Uploading '.$app_name.'-'.$app_json->version.' package...', 'success', "\n\n");
        $file = realpath(APPPATH.'packages/'.$app_name.'-'.$app_json->version.'.zip');
        $data = $this->_upload($file, $app_name, $app_json->version);
        if(is_object($data) && isset($data->code) && isset($data->message))
        {
            $this->_print($data->message, $data->code);
        }
        else
        {
            $this->_print('Invalid response from the server, try again later', 'error');
        }
        $this->_print('','',"\n");
    }

    /**
     *
     * upload the file application.
     *
     */
    public function _upload($file, $app_name, $app_version)
    {
        $token = file_get_contents(APPPATH.'packages/.account-token');
        $file = curl_file_create($file, 'application/zip', basename($file));
        $post = array('package' => $file, 'token'=>$token);
        $ch = curl_init($this->upload_url.$app_name.'/'.$app_version);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOPROGRESS, false);
        curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function($ch, $download_size = 0, $downloaded = 0, $upload_size = 0, $uploaded = 0){
            if($uploaded > 0)
            {
                $percent    = floor(($uploaded / $upload_size) * 100);
            } else {
                $percent    = 0;
            }
            $left       = 100 - $percent;
            $write      = sprintf("\033[0G\033[2K[%'={$percent}s>%-{$left}s] - $percent%% - $uploaded/$upload_size", "", "");
            fwrite(STDERR, $write);
        });
        $data = curl_exec($ch);

        curl_close($ch);
        $this->_print('','',"\n\n");

        $data = json_decode($data);
        if(json_last_error() == JSON_ERROR_NONE)
        {
            return $data;
        }
    }

}

?>