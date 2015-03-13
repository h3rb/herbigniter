<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * HerbIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		HerbIgniter
 * @author		Herb
 * @copyright	Copyright (c) 2009 Gudagi
 * @license		http://gudagi.net/herbigniter/HI_user_guide/license.html
 * @link		http://gudagi.net/herbigniter/
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Output Class
 *
 * Responsible for sending final output to browser
 *
 * @package		HerbIgniter
 * @subpackage		Libraries
 * @category		Page
 * @author		Herb
 * @link		http://gudagi.net/herbigniter/HI_user_guide/libraries/output.html
 */
class HI_Page {

	var $head, $body;
	var $onload;
	var $head_tag_attributes;
	var $body_tag_attributes;
	var $doc_type		='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
	var $rendered=false;

	function HI_Page()
	{
		log_message('debug', "Page Class Initialized");
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get <BODY>
	 *
	 * Returns the current body content
	 *
	 * @access	public
	 * @return	string
	 */	
	function get_body()
	{
		return $this->body;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set <BODY>
	 *
	 * Sets the body string
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */	
	function set_body($output)
	{
		$this->body = $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Append Output; function _echo is mapped here from the output library
	 *
	 * Appends data onto the output string
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */	
	function append_body($output)
	{
		if ($this->body == '')
		{
			$this->body = $output;
		}
		else
		{
			$this->body .= $output;
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get <HEAD>
	 *
	 * Returns the current head string
	 *
	 * @access	public
	 * @return	string
	 */	
	function get_head()
	{
		return $this->head;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set <HEAD>
	 *
	 * Sets the head string
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */	
	function set_head($output)
	{
		$this->head = $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Append Output; function _echo is mapped here from the output library
	 *
	 * Appends data onto the output string
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */	
	function append_head($output)
	{
		if ($this->head == '')
		{
			$this->head = $output;
		}
		else
		{
			$this->head .= $output;
		}
	}

	// --------------------------------------------------------------------
	
	/**
	 * Get <HEAD>
	 *
	 * Returns the current head string
	 *
	 * @access	public
	 * @return	string
	 */	
	function get_onload()
	{
		return $this->onload;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set <HEAD>
	 *
	 * Sets the head string
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */	
	function set_onload($output)
	{
		$this->onload = $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Append Output; function _echo is mapped here from the output library
	 *
	 * Appends data onto the output string
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */	
	function append_onload($output)
	{
		if ($this->onload == '')
		{
			$this->onload = $output;
		}
		else
		{
			$this->onload .= ';' . $output;
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Display Output
	 *
	 * All "view" data is automatically put into this variable by the controller class:
	 *
	 * $this->final_output
	 *
	 * This function sends the finalized output data to the browser along
	 * with any server headers and profile data.  It also stops the
	 * benchmark timer so the page rendering speed and memory usage can be shown.
	 *
	 * @access	public
	 * @return	mixed
	 */		
	function render($output = '')
	{	
		if ( $this->rendered === true ) return;
		// --------------------------------------------------------------------

		// Grab the super object.  We'll need it in a moment...
		$Herb =& get_instance();		
		
		// Does the controller contain a function named _output()?
		// If so send the output there.  Otherwise, echo it.
		if (method_exists($Herb, '_head'))
		{
			$Herb->_head($output);
		}

		// Does the controller contain a function named _output()?
		// If so send the output there.  Otherwise, echo it.
		if (method_exists($Herb, '_body'))
		{
			$Herb->_body($output);
		}
		
		
		// --------------------------------------------------------------------
		
		// Set the output data
		if ($output == '')
		{
			$output =& $doc_type . '\n'
			           . '<head' . (!empty($this->head_tag_attributes) ? ' ' . $this->head_tag_attributes : '') . '>\n'
				   . $this->head
				   . '\n</head>\n'
				   . '<body onload="' . $this->onload . '"' . (!empty($this->body_tag_attributes) ? ' ' . $this->body_tag_attributes : '') . '>\n'
				   . $this->body
				   . '\n</body>\n';
		}
		
		$this->rendered=true;

		// --------------------------------------------------------------------
		
		// Does the get_instance() function exist?
		// If not we know we are dealing with a cache file so we'll
		// simply echo out the data and exit.
		if ( ! function_exists('get_instance'))
		{
			$Herb->append_output($output);
			log_message('debug', "Page Rendered");
			log_message('debug', "Interim execution time: ".$elapsed);
			return TRUE;
		}

	}

}
// END Page Class

/**
 * Append Output; function _echo is mapped here from above
 *
 * Appends data onto the output string
 *
 * @access	public
 * @param	string
 * @return	void
 
if ( ! function_exists('_body'))
{	function _body( $output ) { $Herb =& get_instance();	$Herb->append_body($output);	}	}
if ( ! function_exists('_head'))
{	function _head( $output ) { $Herb =& get_instance();	$Herb->append_head($output);	}	}
 */

/* End of file Output.php */
/* Location: ./system/libraries/Output.php */