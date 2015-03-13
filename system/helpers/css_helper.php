<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * HerbIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		HerbIgniter
 * @author		Herb
 * @copyright		Copyright (c) 2009 Gudagi
 * @license		http://gudagi.net/herbigniter/HI_user_guide/license.html
 * @link		http://gudagi.net/herbigniter/
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * HerbIgniter Persistent Hashing Helpers
 *
 * @package		HerbIgniter
 * @subpackage		Helpers
 * @category		Javascript
 * @author		Herb
 * @link		http://gudagi.net/herbigniter/HI_user_guide/helpers/css_helper.html
 */

// ------------------------------------------------------------------------

if(!function_exists('cssdiv'))
{
    // Creates a styled interface element
    function cssdiv( $id, $style ) {
             $div = '<style type="text/css">\n';
             $div = $div . 'div#' . $id . '{ ' . $style . ' }\n';
             $div = $div . '</style>';
             echo $div;
    }
}

/* End of file js_helper.php */
/* Location: ./system/helpers/css_helper.php */