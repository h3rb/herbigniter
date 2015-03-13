<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * HerbIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		HerbIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2009, EllisLab, Inc.
 * @license		http://herbigniter.com/HI_user_guide/license.html
 * @link		http://herbigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * HerbIgniter Email Helpers
 *
 * @package		HerbIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://herbigniter.com/HI_user_guide/helpers/email_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Validate email address
 *
 * @access	public
 * @return	bool
 */	
if ( ! function_exists('valid_email'))
{
	function valid_email($address)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
	}
}

// ------------------------------------------------------------------------

/**
 * Send an email
 *
 * @access	public
 * @return	bool
 */	
if ( ! function_exists('send_email'))
{
	function send_email($recipient, $subject = 'Test email', $message = 'Hello World')
	{
		return mail($recipient, $subject, $message);
	}
}


/* End of file email_helper.php */
/* Location: ./system/helpers/email_helper.php */