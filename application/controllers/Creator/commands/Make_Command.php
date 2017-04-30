<?php
/**
 *
 * Make command class
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class Make_Command extends Command {

    /**
     * Class constructor
     *
     * @return  void
     */
    function __construct()
    {
        parent::__construct();
        $this->config->load('migration');
    }

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
            'name' => 'make', 
            'desc' => 'Make new migration or seeder or model or controller or view.', 
            'vars' => [
                [
                    'name' => '$type', 
                    'desc' => 'Can be one of the following values: [success]command[/success], [success]migration[/success], [success]seeder[/success], [success]model[/success], [success]controller[/success] and [success]view[/success].',
                ],
                [
                    'name' => '$name', 
                    'desc' => 'The name of the file.', 
                ],
                [
                    'name' => '$app', 
                    'desc' => 'The application name.', 
                ],
            ], 
        ];
    }

    /**
     *
     * make migration or seeder or model file.
     *
     */
    public function make($type='', $name='', $app='')
    {
        $this->_print('','',"\n");
        $templates_path = APPPATH .'controllers/Creator/templates/';

        // make sure the type is one of the options
        $type_list = ['command', 'migration', 'seeder', 'model', 'controller', 'view'];
        if(!in_array(strtolower($type), $type_list))
        {
            $this->_print('Type must be one of the following values: '.implode(', ', $type_list).'.', 'error', "\n\n");
            return FALSE;
        }

        // the minimum length is 2
        $name = trim($name);
        if(strlen($name)<=1)
        {
            $this->_print('The minimum length for $name is two chars.', 'error', "\n\n");
            return FALSE;
        }

        // command
        if($type == 'command')
        {
            $command    = strtolower($name);
            $class_name = ucfirst($command).'_Command';
            $class_file = $class_name.'.php';
            $class_data = file_get_contents($templates_path.'command.php');
            $class_data = str_replace('{class_name}', $class_name, $class_data);
            $class_data = str_replace('{function_name}', strtolower($class_name), $class_data);

            // make sure the file is not existed
            if(file_exists(APPPATH .'controllers/Creator/commands/'.$class_file))
            {
                $this->_print('Command file is already existed.', 'error', "\n\n");
                return FALSE;
            }
            if(file_put_contents(APPPATH .'controllers/Creator/commands/'.$class_file, $class_data))
            {
                $this->_print($class_file.' Created', 'success');
            }
            else
            {
                $this->_print($class_file.' Error', 'error');
            }
        }

        // migration
        if($type == 'migration')
        {
            $table_name = strtolower($name);
            $class_name = 'Migration_'.ucfirst($table_name);
            $class_file = date('YmdHis').'_'.$table_name.'.php';
            $class_data = file_get_contents($templates_path.'migration.php');
            $class_data = str_replace('{class_name}', $class_name, $class_data);

            // make sure the file is not existed
            if(file_exists(config_item('migration_path').$class_file))
            {
                $this->_print('Migration file is already existed.', 'error', "\n\n");
                return FALSE;
            }
            if(file_put_contents(config_item('migration_path').$class_file, $class_data))
            {
                $this->_print($class_file.' Created', 'success');
            }
            else
            {
                $this->_print($class_file.' Error', 'error');
            }
        }

        // seeder
        if($type == 'seeder')
        {
            $table_name = strtolower($name);
            $class_name = ucfirst($table_name).'_Seeder';
            $class_file = date('YmdHis').'_'.$table_name.'.php';
            $class_data = file_get_contents($templates_path.'seeder.php');
            $class_data = str_replace('{class_name}', $class_name, $class_data);

            // make sure the file is not existed
            if(file_exists(config_item('migration_path').'seeds/'.$class_file))
            {
                $this->_print('Seeder file is already existed.', 'error', "\n\n");
                return FALSE;
            }
            if(file_put_contents(config_item('migration_path').'seeds/'.$class_file, $class_data))
            {
                $this->_print('seeds/'.$class_file.' Created', 'success');
            }
            else
            {
                $this->_print('seeds/'.$class_file.' Error', 'error');
            }
        }

        // model
        if($type == 'model')
        {
            $app_name   = ($app)?ucfirst($app).'/':'';
            $table_name = strtolower($name);
            $class_name = ucfirst($table_name).'_model';
            $class_file = 'models/'.$app_name.ucfirst($table_name).'_model.php';
            $class_data = file_get_contents($templates_path.'model.php');
            $class_data = str_replace('{class_name}', $class_name, $class_data);

            if(!file_exists(APPPATH.'models/'.$app_name))
            {
                if(mkdir(APPPATH.'models/'.$app_name))
                {
                    $this->_print('models/'.$app_name.' Created', 'success');
                }
                else
                {
                    $this->_print('models/'.$app_name.' Error', 'error');
                    return;
                }
            }

            // make sure the file is not existed
            if(file_exists(APPPATH.$class_file))
            {
                $this->_print('Model file is already existed.', 'error', "\n\n");
                return FALSE;
            }
            if(file_put_contents(APPPATH.$class_file, $class_data))
            {
                $this->_print($class_file.' Created', 'success');
            }
            else
            {
                $this->_print($class_file.' Error', 'error');
            }
        }

        // controller
        if($type == 'controller')
        {
            $app_name   = ($app)?ucfirst($app).'/':'';
            $table_name = strtolower($name);
            $class_name = ucfirst($table_name);

            if(!file_exists(APPPATH.'controllers/'.$app_name))
            {
                if(mkdir(APPPATH.'controllers/'.$app_name))
                {
                    $this->_print('controllers/'.$app_name.' Created', 'success');
                }
                else
                {
                    $this->_print('controllers/'.$app_name.' Error', 'error', "\n\n");
                    return FALSE;
                }
            }

            if(!file_exists(APPPATH.'controllers/'.$app_name.'routes.php'))
            {
                $class_data = file_get_contents($templates_path.'routes.php');
                $class_data = str_replace('{class_name}', $class_name, $class_data);
                $class_data = str_replace('{app_name}', trim($app_name,'/'), $class_data);
                if(file_put_contents(APPPATH.'controllers/'.$app_name.'routes.php', $class_data))
                {
                    $this->_print('controllers/'.$app_name.'routes.php'.' Created', 'success');
                }
                else
                {
                    $this->_print('controllers/'.$app_name.'routes.php'.' Error', 'error', "\n\n");
                    return FALSE;
                }
            }

            $langs_dir = glob(APPPATH.'language/*', GLOB_ONLYDIR);
            foreach ($langs_dir as $key => $lang_dir)
            {
                // copy {appname}_lang.php file
                if(!file_exists($lang_dir.'/'.strtolower(trim($app_name,'/')).'_lang.php'))
                {
                    if(copy($templates_path.'lang.php', $lang_dir.'/'.strtolower(trim($app_name,'/')).'_lang.php'))
                    {
                        $this->_print(str_replace(APPPATH, '',$lang_dir).'/'.strtolower(trim($app_name,'/')).'_lang.php'.' Created', 'success');
                        // replace {app_name}, {class_name}
                        $lang_data = file_get_contents($lang_dir.'/'.strtolower(trim($app_name,'/')).'_lang.php');
                        $lang_data = str_replace('{class_name}', $class_name, $lang_data);
                        $lang_data = str_replace('{app_name}', trim($app_name,'/'), $lang_data);
                        file_put_contents($lang_dir.'/'.strtolower(trim($app_name,'/')).'_lang.php', $lang_data);
                    }
                    else
                    {
                        $this->_print(str_replace(APPPATH, '',$lang_dir).'/'.strtolower(trim($app_name,'/')).'_lang.php'.' Error', 'error', "\n\n");
                        return FALSE;
                    }
                }

                // copy global_{appname}_lang.php file
                if(!file_exists($lang_dir.'/global_'.strtolower(trim($app_name,'/')).'_lang.php'))
                {
                    if(copy($templates_path.'global_lang.php', $lang_dir.'/global_'.strtolower(trim($app_name,'/')).'_lang.php'))
                    {
                        $app_lang_line = "\$lang['".strtolower(trim($app_name,'/'))."'] = '".ucfirst(trim($app_name,'/'))."';\n";
                        file_put_contents($lang_dir.'/global_'.strtolower(trim($app_name,'/')).'_lang.php', $app_lang_line, FILE_APPEND);
                        $this->_print(str_replace(APPPATH, '',$lang_dir).'/global_'.strtolower(trim($app_name,'/')).'_lang.php'.' Created', 'success');
                        // replace {app_name}, {class_name}
                        $lang_data = file_get_contents($lang_dir.'/'.strtolower(trim($app_name,'/')).'_lang.php');
                        $lang_data = str_replace('{class_name}', $class_name, $lang_data);
                        $lang_data = str_replace('{app_name}', trim($app_name,'/'), $lang_data);
                        file_put_contents($lang_dir.'/'.strtolower(trim($app_name,'/')).'_lang.php', $lang_data);
                    }
                    else
                    {
                        $this->_print(str_replace(APPPATH, '',$lang_dir).'/global_'.strtolower(trim($app_name,'/')).'_lang.php'.' Error', 'error', "\n\n");
                        return FALSE;
                    }
                }

                // add language line for the controller
                $app_lang_line = "\$lang['".strtolower(trim($app_name,'/')."/".$class_name)."'] = '".ucfirst(trim($class_name))."';\n";
                if(file_put_contents($lang_dir.'/global_'.strtolower(trim($app_name,'/')).'_lang.php', $app_lang_line,  FILE_APPEND))
                {
                    $this->_print(str_replace(APPPATH, '',$lang_dir).'/global_'.strtolower(trim($app_name,'/')).'_lang.php'.' language line added', 'success');
                }
                else
                {
                    $this->_print(str_replace(APPPATH, '',$lang_dir).'/global_'.strtolower(trim($app_name,'/')).'_lang.php'.' Error adding language line', 'error', "\n\n");
                    return FALSE;
                }
            }

            $class_file = 'controllers/'.$app_name.ucfirst($table_name).'.php';
            $class_data = file_get_contents($templates_path.'controller.php');
            $class_data = str_replace('{class_name}', $class_name, $class_data);
            $class_data = str_replace('{model_name}', strtolower($class_name), $class_data);
            $class_data = str_replace('{app_name}', trim($app_name,'/'), $class_data);

            // make sure the file is not existed
            if(file_exists(APPPATH.$class_file))
            {
                $this->_print('Controller file is already existed.', 'error', "\n\n");
                return FALSE;
            }
            if(file_put_contents(APPPATH.$class_file, $class_data))
            {
                $this->_print($class_file.' Created', 'success');
            }
            else
            {
                $this->_print($class_file.' Error', 'error');
            }
        }

        // view
        if($type == 'view')
        {
            $app_name   = ($app)?strtolower($app).'/':'';
            $class_name = strtolower($name);
            $view_files = glob($templates_path.'view_*.php');

            if(!file_exists(APPPATH.'views/'.$app_name))
            {
                if(mkdir(APPPATH.'views/'.$app_name))
                {
                    $this->_print('views/'.$app_name.' Created', 'success');
                }
                else
                {
                    $this->_print('views/'.$app_name.' Error', 'error', "\n\n");
                    return FALSE;
                }
            }

            if(!file_exists(APPPATH.'views/'.$app_name.'layout.php'))
            {
                if(copy($templates_path.'layout.php', APPPATH.'views/'.$app_name.'layout.php'))
                {
                    $this->_print('views/'.$app_name.'layout.php Created', 'success');
                }
                else
                {
                    $this->_print('views/'.$app_name.'layout.php Error', 'error', "\n\n");
                    return FALSE;
                }
            }

            if(!file_exists(APPPATH.'views/'.$app_name.$class_name))
            {
                if(mkdir(APPPATH.'views/'.$app_name.$class_name))
                {
                    $this->_print('views/'.$app_name.$class_name.' Created', 'success');
                }
                else
                {
                    $this->_print('views/'.$app_name.$class_name.' Error', 'error', "\n\n");
                    return FALSE;
                }
            }

            foreach ($view_files as $key => $view_file) {
                $class_file = 'views/'.$app_name.$class_name.'/'.str_replace($templates_path.'view_', '', $view_file);

                // make sure the file is not existed
                if(file_exists(APPPATH.$class_file))
                {
                    $this->_print('View file '.$class_file.' is already existed.', 'warning');
                    continue;
                }
                $class_data = file_get_contents($view_file);
                $class_data = str_replace('{class_name}', $class_name, $class_data);
                $class_data = str_replace('{app_name}', trim($app_name,'/'), $class_data);

                $class_data = str_replace('{Class_name}', ucfirst($class_name), $class_data);
                $class_data = str_replace('{App_name}', ucfirst(trim($app_name,'/')), $class_data);

                if(file_put_contents(APPPATH.$class_file, $class_data))
                {
                    $this->_print($class_file . ' Created', 'success');
                }
                else
                {
                    $this->_print($class_file . ' Error', 'error');
                }
            }
        }

        $this->_print('','',"\n");
    }

}

?>