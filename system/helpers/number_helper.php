<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
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
 * HerbIgniter Number Helpers
 *
 * @package		HerbIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://herbigniter.com/HI_user_guide/helpers/number_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Formats a numbers as bytes, based on size, and adds the appropriate suffix
 *
 * @access	public
 * @param	mixed	// will be cast as int
 * @return	string
 */
if ( ! function_exists('byte_format'))
{
	function byte_format($num)
	{
		$HI =& get_instance();
		$HI->lang->load('number');
	
		if ($num >= 1000000000000) 
		{
			$num = round($num / 1099511627776, 1);
			$unit = $HI->lang->line('terabyte_abbr');
		}
		elseif ($num >= 1000000000) 
		{
			$num = round($num / 1073741824, 1);
			$unit = $HI->lang->line('gigabyte_abbr');
		}
		elseif ($num >= 1000000) 
		{
			$num = round($num / 1048576, 1);
			$unit = $HI->lang->line('megabyte_abbr');
		}
		elseif ($num >= 1000) 
		{
			$num = round($num / 1024, 1);
			$unit = $HI->lang->line('kilobyte_abbr');
		}
		else
		{
			$unit = $HI->lang->line('bytes');
			return number_format($num).' '.$unit;
		}

		return number_format($num, 1).' '.$unit;
	}	
}

/* End of file number_helper.php */
/* Location: ./system/helpers/number_helper.php */