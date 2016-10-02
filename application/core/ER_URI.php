<?php
/**
 * URI Class
 *
 * Parses URIs and determines routing
 *
 * @package     Ertikaz
 * @subpackage  Libraries
 * @category    Libraries
 */

class ER_URI extends CI_URI{
	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct(CI_Config $config)
	{
		parent::__construct($config);
	}

	/**
	 * Filter URI
	 *
	 * Filters segments for malicious characters.
	 *
	 * @param	string	$str
	 * @return	void
	 */
	public function filter_uri(&$str)
	{
		// disabled on command line mode 
		if(!is_cli())
		{
			if ( ! empty($str) && ! empty($this->_permitted_uri_chars) && ! preg_match('/^['.$this->_permitted_uri_chars.']+$/i'.(UTF8_ENABLED ? 'u' : ''), $str))
			{
				show_error('The URI you submitted has disallowed characters.', 400);
			}
		}
	}
}
