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
 * HerbIgniter Model Class
 *
 * @package		HerbIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://herbigniter.com/HI_user_guide/libraries/config.html
 */
class Model {

	var $_parent_name = '';

	/**
	 * Constructor
	 *
	 * @access public
	 */
	function Model()
	{
		// If the magic __get() or __set() methods are used in a Model references can't be used.
		$this->_assign_libraries( (method_exists($this, '__get') OR method_exists($this, '__set')) ? FALSE : TRUE );
		
		// We don't want to assign the model object to itself when using the
		// assign_libraries function below so we'll grab the name of the model parent
		$this->_parent_name = ucfirst(get_class($this));
		
		log_message('debug', "Model Class Initialized");
	}

	/**
	 * Assign Libraries
	 *
	 * Creates local references to all currently instantiated objects
	 * so that any syntax that can be legally used in a controller
	 * can be used within models.  
	 *
	 * @access private
	 */	
	function _assign_libraries($use_reference = TRUE)
	{
		$HI =& get_instance();				
		foreach (array_keys(get_object_vars($HI)) as $key)
		{
			if ( ! isset($this->$key) AND $key != $this->_parent_name)
			{			
				// In some cases using references can cause
				// problems so we'll conditionally use them
				if ($use_reference == TRUE)
				{
					$this->$key = NULL; // Needed to prevent reference errors with some configurations
					$this->$key =& $HI->$key;
				}
				else
				{
					$this->$key = $HI->$key;
				}
			}
		}		
	}

}
// END Model Class

/* End of file Model.php */
/* Location: ./system/libraries/Model.php */