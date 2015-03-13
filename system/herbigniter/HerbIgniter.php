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
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * System Front Website
 *
 * Loads the base classes and executes the request.
 *
 * @package		herbigniter
 * @subpackage	herbigniter
 * @category	Front-controller
 * @author		ExpressionEngine Dev Team
 * @link		http://herbigniter.com/HI_user_guide/
 */

// CI Version
define('HI_VERSION',	'1.7.2');

/*
 * ------------------------------------------------------
 *  Load the global functions
 * ------------------------------------------------------
 */
require(BASEPATH.'herbigniter/Common'.EXT);

/*
 * ------------------------------------------------------
 *  Load the compatibility override functions
 * ------------------------------------------------------
 */
require(BASEPATH.'herbigniter/Compat'.EXT);

/*
 * ------------------------------------------------------
 *  Load the framework constants
 * ------------------------------------------------------
 */
require(APPPATH.'config/constants'.EXT);

/*
 * ------------------------------------------------------
 *  Define a custom error handler so we can log PHP errors
 * ------------------------------------------------------
 */
set_error_handler('_exception_handler');

if ( ! is_php('5.3'))
{
	@set_magic_quotes_runtime(0); // Kill magic quotes
}

/*
 * ------------------------------------------------------
 *  Start the timer... tick tock tick tock...
 * ------------------------------------------------------
 */

$BM =& load_class('Benchmark');
$BM->mark('total_execution_time_start');
$BM->mark('loading_time_base_classes_start');

/*
 * ------------------------------------------------------
 *  Instantiate the hooks class
 * ------------------------------------------------------
 */

$EXT =& load_class('Hooks');

/*
 * ------------------------------------------------------
 *  Is there a "pre_system" hook?
 * ------------------------------------------------------
 */
$EXT->_call_hook('pre_system');

/*
 * ------------------------------------------------------
 *  Instantiate the base classes
 * ------------------------------------------------------
 */

$CFG =& load_class('Config');
$URI =& load_class('URI');
$RTR =& load_class('Router');
$OUT =& load_class('Output');
$PAG =& load_class('Page');

/*
 * ------------------------------------------------------
 *	Is there a valid cache file?  If so, we're done...
 * ------------------------------------------------------
 */

if ($EXT->_call_hook('cache_override') === FALSE)
{
	if ($OUT->_display_cache($CFG, $URI) == TRUE)
	{
		exit;
	}
}

/*
 * ------------------------------------------------------
 *  Load the remaining base classes
 * ------------------------------------------------------
 */

$IN		=& load_class('Input');
$LANG	=& load_class('Language');

/*
 * ------------------------------------------------------
 *  Load the app controller and local controller
 * ------------------------------------------------------
 *
 *  Note: Due to the poor object handling in PHP 4 we'll
 *  conditionally load different versions of the base
 *  class.  Retaining PHP 4 compatibility requires a bit of a hack.
 *
 *  Note: The Loader class needs to be included first
 *
 */
if ( ! is_php('5.0.0'))
{
	load_class('Loader', FALSE);
	require(BASEPATH.'herbigniter/Base4'.EXT);
}
else
{
	require(BASEPATH.'herbigniter/Base5'.EXT);
}

// Load the base controller class
load_class('Website', FALSE);

// Load the local application controller
// Note: The Router class automatically validates the controller path.  If this include fails it 
// means that the default controller in the Routes.php file is not resolving to something valid.
if ( ! file_exists(APPPATH.'controllers/'.$RTR->fetch_directory().$RTR->fetch_class().EXT))
{
	show_error('Unable to load your default controller.  Please make sure the controller specified in your Routes.php file is valid.');
}

include(APPPATH.'controllers/'.$RTR->fetch_directory().$RTR->fetch_class().EXT);

// Set a mark point for benchmarking
$BM->mark('loading_time_base_classes_end');


/*
 * ------------------------------------------------------
 *  Security check
 * ------------------------------------------------------
 *
 *  None of the functions in the app controller or the
 *  loader class can be called via the URI, nor can
 *  controller functions that begin with an underscore
 */
$class  = $RTR->fetch_class();
$method = $RTR->fetch_method();

if ( ! class_exists($class)
	OR $method == 'controller'
	OR strncmp($method, '_', 1) == 0
	OR in_array(strtolower($method), array_map('strtolower', get_class_methods('Website')))
	)
{
	show_404("{$class}/{$method}");
}

/*
 * ------------------------------------------------------
 *  Is there a "pre_controller" hook?
 * ------------------------------------------------------
 */
$EXT->_call_hook('pre_controller');

/*
 * ------------------------------------------------------
 *  Instantiate the controller and call requested method
 * ------------------------------------------------------
 */

// Mark a start point so we can benchmark the controller
$BM->mark('controller_execution_time_( '.$class.' / '.$method.' )_start');

$HI = new $class();

// Is this a scaffolding request?
if ($RTR->scaffolding_request === TRUE)
{
	if ($EXT->_call_hook('scaffolding_override') === FALSE)
	{
		$HI->_hi_scaffolding();
	}
}
else
{
	/*
	 * ------------------------------------------------------
	 *  Is there a "post_controller_constructor" hook?
	 * ------------------------------------------------------
	 */
	$EXT->_call_hook('post_controller_constructor');
	
	// Is there a "remap" function?
	if (method_exists($HI, '_remap'))
	{
		$HI->_remap($method);
	}
	else
	{
		// is_callable() returns TRUE on some versions of PHP 5 for private and protected
		// methods, so we'll use this workaround for consistent behavior
		if ( ! in_array(strtolower($method), array_map('strtolower', get_class_methods($HI))))
		{
			show_404("{$class}/{$method}");
		}

		// Call the requested method.
		// Any URI segments present (besides the class/function) will be passed to the method for convenience
		call_user_func_array(array(&$HI, $method), array_slice($URI->rsegments, 2));
	}
}

// Mark a benchmark end point
$BM->mark('controller_execution_time_( '.$class.' / '.$method.' )_end');

/*
 * ------------------------------------------------------
 *  Is there a "post_controller" hook?
 * ------------------------------------------------------
 */
$EXT->_call_hook('post_controller');

/*
 * ------------------------------------------------------
 *  Send the final rendered output to the browser
 * ------------------------------------------------------
 */

if ($EXT->_call_hook('display_override') === FALSE)
{
	$OUT->append_output($PAG->render());
	$OUT->_display();
}

/*
 * ------------------------------------------------------
 *  Is there a "post_system" hook?
 * ------------------------------------------------------
 */
$EXT->_call_hook('post_system');

/*
 * ------------------------------------------------------
 *  Close the DB connection if one exists
 * ------------------------------------------------------
 */
if (class_exists('HI_DB') AND isset($HI->db))
{
	$HI->db->close();
}


/* End of file herbigniter.php */
/* Location: ./system/herbigniter/herbigniter.php */