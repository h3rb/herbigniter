<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * HerbIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		HerbIgniter
 * @author		Herb
 * @copyright	Copyright (c) 2009 Gudagi
 * @license		http://gudagi.net/herbigniter/user_guide/license.html
 * @link		http://gudagi.net/herbigniter/
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * HerbIgniter Cookie Helpers
 *
 * @package		HerbIgniter
 * @subpackage	Helpers
 * @category	Cookies
 * @author		Herb
 * @link		http://gudagi.net/herbigniter/user_guide/helpers/cookie_helper.html
 */

// ------------------------------------------------------------------------

/*************************************************
 * While you may find these redundant, mine are  *
 * heavily researched. Try them first.           *
 *                                               *
 * FYI:                                          *
 * Cookies don't really work anymore. There are  *
 * Issues with clearing them, thank Microsoft    * 
 * for your troubles, or the fact that we use    *
 * them at all.  All I really use them for is a  *
 * single one to track a session hash.           *
 *                                               *
 * HerbIgniter's cookie functions are:           *
 * cook, uncook, show_cookie and clear_cookie    *
 * You should just need uncook, not clear_cookie *
 * show_cookie is just used when debugging       *
 *************************************************/


if(!function_exists('cook'))
{ 
    function cook( $v, $s, $timeout=9000 ) {
        return setcookie( $v, $s, time()+$timeout, '/', domain, false, true );
    }
}

if(!function_exists('uncook'))
{
    function uncook( $v ) {
        unset($_COOKIE[$v]);
        return setcookie( $v, "", mktime(12,0,0,1, 1, 1970), '/', domain, false, true );      
    }
}


/**
 * Set cookie
 *
 * Accepts six parameter, or you can submit an associative
 * array in the first parameter containing all the values.
 *
 * @access	public
 * @param	mixed
 * @param	string	the value of the cookie
 * @param	string	the number of seconds until expiration
 * @param	string	the cookie domain.  Usually:  .yourdomain.com
 * @param	string	the cookie path
 * @param	string	the cookie prefix
 * @return	void
 */
if ( ! function_exists('set_cookie'))
{
	function set_cookie($name = '', $value = '', $expire = '', $domain = '', $path = '/', $prefix = '')
	{
		if (is_array($name))
		{		
			foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'name') as $item)
			{
				if (isset($name[$item]))
				{
					$$item = $name[$item];
				}
			}
		}
	
		// Set the config file options
		$Herb =& get_instance();
	
		if ($prefix == '' AND $Herb->config->item('cookie_prefix') != '')
		{
			$prefix = $Herb->config->item('cookie_prefix');
		}
		if ($domain == '' AND $Herb->config->item('cookie_domain') != '')
		{
			$domain = $Herb->config->item('cookie_domain');
		}
		if ($path == '/' AND $Herb->config->item('cookie_path') != '/')
		{
			$path = $Herb->config->item('cookie_path');
		}
		
		if ( ! is_numeric($expire))
		{
			$expire = time() - 86500;
		}
		else
		{
			if ($expire > 0)
			{
				$expire = time() + $expire;
			}
			else
			{
				$expire = 0;
			}
		}
	
		setcookie($prefix.$name, $value, $expire, $path, $domain, 0);
	}
}
	
// --------------------------------------------------------------------

/**
 * Fetch an item from the COOKIE array
 *
 * @access	public
 * @param	string
 * @param	bool
 * @return	mixed
 */
if ( ! function_exists('get_cookie'))
{
	function get_cookie($index = '', $xss_clean = FALSE)
	{
		$Herb =& get_instance();
		
		$prefix = '';
		
		if ( ! isset($_COOKIE[$index]) && config_item('cookie_prefix') != '')
		{
			$prefix = config_item('cookie_prefix');
		}
		
		return $Herb->input->cookie($prefix.$index, $xss_clean);
	}
}

// --------------------------------------------------------------------

/**
 * Delete a COOKIE
 *
 * @param	mixed
 * @param	string	the cookie domain.  Usually:  .yourdomain.com
 * @param	string	the cookie path
 * @param	string	the cookie prefix
 * @return	void
 */
if ( ! function_exists('delete_cookie'))
{
	function delete_cookie($name = '', $domain = '', $path = '/', $prefix = '')
	{
		set_cookie($name, '', '', $domain, $path, $prefix);
	}
}


/* End of file cookie_helper.php */
/* Location: ./system/helpers/cookie_helper.php */