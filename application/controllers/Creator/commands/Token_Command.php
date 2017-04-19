<?php
/**
 *
 * Token command class
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class Token_Command extends Command {

    /**
     * The token URL 
     *
     * @var object
     */
    public $token_url = 'https://ertikazos.com/store/token/';

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
            'name' => 'token', 
            'desc' => 'Create account on ErtikazOS store.', 
            'vars' => [
                [
                    'name' => '$action', 
                    'desc' => 'Perform one of the following [success]create[/success], [success]reset[/success] and [success]set[/success].',
                ],
                [
                    'name' => '$email', 
                    'desc' => 'Your email.', 
                ],
                [
                    'name' => '$name or $code', 
                    'desc' => 'Your name on create action or activation code on set action.', 
                ],
            ], 
        ];
    }

    /**
     *
     * get token.
     *
     */
    public function token($action='', $email='', $name_or_code='')
    {
        $this->_print('','',"\n");

        if($action != 'create' && $action != 'reset' && $action != 'set') {
            $this->_print('Invalid action.', 'error', "\n\n");
            return FALSE;
        }

        if($action == 'create')
        {
            if(!$email || !$name_or_code) {
                $this->_print('Your email or name is not valid.', 'error', "\n\n");
                return FALSE;
            }

            $post = ['email'=>$email, 'name'=>$name_or_code];
            $data = $this->_post($post, $action);
            if(is_object($data) && isset($data->code) && isset($data->message))
            {
                $this->_print($data->message, $data->code);
                if(isset($data->token))
                {
                    if(file_put_contents(APPPATH.'packages/.account-token', $data->token))
                    {
                        $this->_print('Token save to: packages/.account-token', 'success');
                    }
                    else
                    {
                        $this->_print('Cannot save token to: packages/.account-token', 'error');
                    }
                }
            }
            else
            {
                $this->_print('Wrong response from the server, try again later', 'error');
            }
        }

        if($action == 'reset')
        {
            if(!$email) {
                $this->_print('Your email is not valid.', 'error', "\n\n");
                return FALSE;
            }

            $post = ['email'=>$email];
            $data = $this->_post($post, $action);
            if(is_object($data) && isset($data->code) && isset($data->message))
            {
                $this->_print($data->message, $data->code);
            }
            else
            {
                $this->_print('Wrong response from the server, try again later', 'error');
            }
        }

        if($action == 'set')
        {
            if(!$email) {
                $this->_print('Your email is not valid.', 'error', "\n\n");
                return FALSE;
            }

            $post = ['email'=>$email, 'code'=>$name_or_code];
            $data = $this->_post($post, $action);
            if(is_object($data) && isset($data->code) && isset($data->message))
            {
                $this->_print($data->message, $data->code);
                if(isset($data->token))
                {
                    if(file_put_contents(APPPATH.'packages/.account-token', $data->token))
                    {
                        $this->_print('Token save to: packages/.account-token', 'success');
                    }
                    else
                    {
                        $this->_print('Cannot save token to: packages/.account-token', 'error');
                    }
                }
            }
            else
            {
                $this->_print('Wrong response from the server, try again later', 'error');
            }
        }

        $this->_print('','',"\n");
    }

    /**
     *
     * post data to url.
     *
     */
    public function _post($post, $action)
    {
        $ch = curl_init($this->token_url.$action);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($data);
        if(json_last_error() == JSON_ERROR_NONE)
        {
            return $data;
        }
    }

}

?>