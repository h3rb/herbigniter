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
 * HerbIgniter Typography Helpers
 *
 * @package		HerbIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Herb
 * @link		http://gudagi.net/herbigniter/user_guide/helpers/typography_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Convert newlines to HTML line breaks except within PRE tags
 *
 * @access	public
 * @param	string
 * @return	string
 */	
if ( ! function_exists('nl2br_except_pre'))
{
	function nl2br_except_pre($str)
	{
		$Herb =& get_instance();
	
		$Herb->load->library('typography');
		
		return $Herb->typography->nl2br_except_pre($str);
	}
}
	
// ------------------------------------------------------------------------

/**
 * Auto Typography Wrapper Function
 *
 *
 * @access	public
 * @param	string
 * @param	bool	whether to reduce multiple instances of double newlines to two
 * @return	string
 */
if ( ! function_exists('auto_typography'))
{
	function auto_typography($str, $reduce_linebreaks = FALSE)
	{
		$Herb =& get_instance();	
		$Herb->load->library('typography');
		return $Herb->typography->auto_typography($str, $reduce_linebreaks);
	}
}

/* End of file typography_helper.php */
/* Location: ./system/helpers/typography_helper.php */