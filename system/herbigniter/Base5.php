<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * herbigniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		herbigniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2009, EllisLab, Inc.
 * @license		http://herbigniter.com/HI_user_guide/license.html
 * @link		http://herbigniter.com
 * @since		Version 1.3
 * @filesource
 */


// ------------------------------------------------------------------------

/**
 * HI_BASE - For PHP 5
 *
 * This file contains some code used only when herbigniter is being
 * run under PHP 5.  It allows us to manage the CI super object more
 * gracefully than what is possible with PHP 4.
 *
 * @package		herbigniter
 * @subpackage	herbigniter
 * @category	front-controller
 * @author		ExpressionEngine Dev Team
 * @link		http://herbigniter.com/HI_user_guide/
 */

class HI_Base {

	private static $instance;

	public function HI_Base()
	{
		self::$instance =& $this;
	}

	public static function &get_instance()
	{
		return self::$instance;
	}
}

function &get_instance()
{
	return HI_Base::get_instance();
}



/* End of file Base5.php */
/* Location: ./system/herbigniter/Base5.php */