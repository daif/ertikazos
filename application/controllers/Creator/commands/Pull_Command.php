<?php
/**
 *
 * Pull command class
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class Pull_Command extends Command {

    /**
     * The pull URL 
     *
     * @var string
     */
    public $pull_url = 'https://ertikazos.com/store/pull/';

    /**
     * The download URL 
     *
     * @var string
     */
    public $download_url = 'https://ertikazos.com/store/';


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
            'name' => 'pull', 
            'desc' => 'Download application package from ErtikazOS store.', 
            'vars' => [
                [
                    'name' => '$app_name', 
                    'desc' => 'The application name.', 
                ],
                [
                    'name' => '$app_version', 
                    'desc' => 'The application version.', 
                ],
            ], 
        ];
    }

    /**
     *
     * pull application.
     *
     */
    public function pull($app_name='', $app_version='')
    {
        $this->_print('','',"\n");

        // fetching package info
        $this->_print('Fetching '.$app_name.' information ...', 'success');

        // if app meta file is existed read it from remote
        $json_file = APPPATH .'packages/'.$app_name.'-'.$app_version.'.json';
        if(file_exists($json_file))
        {
            $app_json = file_get_contents($json_file);
            $app_json = json_decode($app_json);
            if(json_last_error() !== JSON_ERROR_NONE)
            {
                $this->_print('Invalid JSON file format "'.$json_file.'".', 'error', "\n\n");
                return FALSE;
            }
        }
        else
        {
            $json_file = rtrim($this->pull_url.$app_name.'/'.$app_version, '/');
            $app_json = file_get_contents($json_file);
            $app_json = json_decode($app_json);
            if(json_last_error() !== JSON_ERROR_NONE)
            {
                $this->_print('Invalid response from the server, try again later.', 'error', "\n\n");
                return FALSE;
            }
            if(!isset($app_json->package_json))
            {
                $this->_print($app_json->message, $app_json->code, "\n\n");
                if($app_json->code == 'error')
                {
                    return FALSE;
                }
            }
            // set $app_json
            $app_json = json_decode($app_json->package_json);
            if(json_last_error() !== JSON_ERROR_NONE)
            {
                $this->_print('Invalid response from the server, try again later.', 'error', "\n\n");
                return FALSE;
            }
        }


        // set base path variables
        $package_name = $app_name;
        $package_file = APPPATH .'packages/'.$app_name.'-'.$app_json->version.'.zip';
        $package_dir  = APPPATH .'packages/'.$app_name.'-'.$app_json->version.'/';

        // download the package is it not existed 
        if(file_exists($package_file))
        {
            $this->_print('Package '.$app_name.'-'.$app_json->version.' already downloaded.', 'success', "\n\n");
        }
        else
        {
            $this->_print('Download '.$app_name.'-'.$app_json->version.' package...', 'success', "\n\n");
            if($this->_download($app_name, $app_json->version))
            {
                $this->_print('Package '.$app_name.'-'.$app_json->version.' has been downloaded.', 'success', "\n\n");
            }
            else
            {
                $this->_print('Invalid response from the server, try again later', 'error', "\n\n");
                return FALSE;
            }
        }

        // open package file
        $zip = new ZipArchive;
        if ($zip->open($package_file) !== TRUE) {
            $this->_print('Invalid Zip archive file.', 'error');
            return FALSE;
        }

        // extract package temporary folder inside application/package folder 
        if(!$zip->extractTo($package_dir)) {
            $this->_print('Cannot extract file.', 'error');
            return FALSE;
        }

        // looking for package meta data file
        if(!file_exists($package_dir.'application/packages/'.$package_name.'-'.$app_json->version.'.json'))
        {
            $this->_print('App json file '.$package_dir.'application/packages/'.$package_name.'-'.$app_json->version.'.json is not found.', 'error', "\n\n");
            return FALSE;
        }

        // files array must be available in meta data
        if(!isset($app_json->files) || !is_array($app_json->files))
        {
            $this->_print('App json file packages/'.$package_name.'/'.$package_name.'.json is not valid.', 'error', "\n\n");
            return FALSE;
        }

        // auto add package json file to the files array
        $app_json->files[] = 'application/packages/'.$package_name.'-'.$app_json->version.'.json';

        // all files must be available in package, if something missing stop
        foreach ($app_json->files as $key => $app_file) {
            if(!file_exists($package_dir.$app_file)) {
                $this->_print('package file "'.$app_file.'" is missing.', 'error', "\n\n");
                return FALSE;
            }
        }

        // if file already existed make backup , we can rollback later.
        foreach ($app_json->files as $key => $app_file) {
            if(file_exists(APPPATH .'../'.$app_file)) {
                //rename(APPPATH .'../'.$app_file, APPPATH .'../'.$app_file.'-'.time());
                $this->_print('Backup "'.$app_file.'".', 'success', "\n");
            }
        }

        $this->_print('','',"\n");
        // extract to application folder 
        if($zip->extractTo(APPPATH .'../', $app_json->files)) {
            $this->_print('Package '.$app_name.'-'.$app_json->version.' has been installed.', 'success');
        }

        $this->_print('','',"\n");
    }

    /**
     *
     * download the file application.
     *
     */
    public function _download($app_name, $app_version)
    {
        $package_file = APPPATH .'packages/'.$app_name.'-'.$app_version.'.zip';
        $fp = fopen($package_file, 'w+');
        $ch = curl_init($this->download_url.'packages/'.$app_name.'-'.$app_version.'.zip');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_NOPROGRESS, false);
        curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function($ch, $download_size = 0, $downloaded = 0, $upload_size = 0, $uploaded = 0){
            if($downloaded > 0)
            {
                $percent    = floor(($downloaded / $download_size) * 100);
            } else {
                $percent    = 0;
            }
            $left       = 100 - $percent;
            $write      = sprintf("\033[0G\033[2K[%'={$percent}s>%-{$left}s] - $percent%% - $downloaded/$download_size", "", "");
            fwrite(STDERR, $write);
        });
        curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->_print('','',"\n\n");
        return ($http_code == 200);
    }

}

?>