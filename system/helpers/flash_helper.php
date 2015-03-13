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

if(!function_exists('swf'))
{

function swf( $object, $w, $h, $wmode="transparent", $flashvars="&auto_start=true&auto_play=true&initial_sound_volume=0.5&resizeMode=fillScreen" ) {
   return '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
	codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="' . $w . '" height="' . $h . '">
  <param name="movie" value="' . $object . '">
  <param name="flashvars" value="' . $flashvars . '">
  <param name="quality" value="high">
  <param name="scale" value="showall">
  <param name="wmode" value="' . $wmode . '">
  <param name="allowFullScreen" value="true">
  <embed src="' . $object . '" width="' . $w . '" height="' . $h . '" allowFullScreen="true"
         scale="showall" quality="high" xml="appData.xml"
	 flashvars="' . $flashvars . '"
	 pluginspage="http://www.macromedia.com/go/getflashplayer"
	 type="application/x-shockwave-flash" wmode="transparent"></embed>
</object>';  
}

}
/* End of file js_helper.php */
/* Location: ./system/helpers/css_helper.php */