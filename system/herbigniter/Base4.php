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
 * HI_BASE - For PHP 4
 *
 * This file is used only when herbigniter is being run under PHP 4.
 *
 * In order to allow CI to work under PHP 4 we had to make the Loader class
 * the parent of the Website Base class.  It's the only way we can
 * enable functions like $this->load->library('email') to instantiate
 * classes that can then be used within controllers as $this->email->send()
 *
 * PHP 4 also has trouble referencing the CI super object within application
 * constructors since objects do not exist until the class is fully
 * instantiated.  Basically PHP 4 sucks...
 *
 * Since PHP 5 doesn't suffer from this problem so we load one of
 * two files based on the version of PHP being run.
 *
 * @package		herbigniter
 * @subpackage	herbigniter
 * @category	front-controller
 * @author		ExpressionEngine Dev Team
 * @link		http://herbigniter.com/HI_user_guide/
 */
 class HI_Base extends HI_Loader {

	function HI_Base()
	{
		// This allows syntax like $this->load->foo() to work
		parent::HI_Loader();
		$this->load =& $this;
		
		// This allows resources used within controller constructors to work
		global $OBJ;
		$OBJ = $this->load; // Do NOT use a reference.
	}
}

function &get_instance()
{
	global $HI, $OBJ;
	
	if (is_object($HI))
	{
		return $HI;
	}
	
	return $OBJ->load;
}


/* End of file Base4.php */
/* Location: ./system/herbigniter/Base4.php */