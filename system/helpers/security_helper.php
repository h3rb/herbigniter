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
 * HerbIgniter Security Helpers
 *
 * @package		HerbIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Herb
 * @link		http://gudagi.net/herbigniter/user_guide/helpers/security_helper.html
 */

// ------------------------------------------------------------------------

/**
 * XSS Filtering
 *
 * @access	public
 * @param	string
 * @param	bool	whether or not the content is an image file
 * @return	string
 */	
if ( ! function_exists('xss_clean'))
{
	function xss_clean($str, $is_image = FALSE)
	{
		$Herb =& get_instance();
		return $Herb->input->xss_clean($str, $is_image);
	}
}

// --------------------------------------------------------------------

/**
 * Hash encode a string
 *
 * @access	public
 * @param	string
 * @return	string
 */	
if ( ! function_exists('dohash'))
{	
	function dohash($str, $type = 'sha1')
	{
		if ($type == 'sha1')
		{
			if ( ! function_exists('sha1'))
			{
				if ( ! function_exists('mhash'))
				{	
					require_once(BASEPATH.'libraries/Sha1'.EXT);
					$SH = new HI_SHA;
					return $SH->generate($str);
				}
				else
				{
					return bin2hex(mhash(MHASH_SHA1, $str));
				}
			}
			else
			{
				return sha1($str);
			}	
		}
		else
		{
			return md5($str);
		}
	}
}
	
// ------------------------------------------------------------------------

/**
 * Strip Image Tags
 *
 * @access	public
 * @param	string
 * @return	string
 */	
if ( ! function_exists('strip_image_tags'))
{
	function strip_image_tags($str)
	{
		$str = preg_replace("#<img\s+.*?src\s*=\s*[\"'](.+?)[\"'].*?\>#", "\\1", $str);
		$str = preg_replace("#<img\s+.*?src\s*=\s*(.+?).*?\>#", "\\1", $str);
			
		return $str;
	}
}
	
// ------------------------------------------------------------------------

/**
 * Convert PHP tags to entities
 *
 * @access	public
 * @param	string
 * @return	string
 */	
if ( ! function_exists('encode_php_tags'))
{
	function encode_php_tags($str)
	{
		return str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
	}
}

if ( ! function_exists('inject_protect_sql'))
{
	// For SQL injection protection, limits to alpha numeric
	function inject_protect_sql( $s ) {         
		return preg_replace( "/[^a-zA-Z0-9s]/", "", strip_tags(trim($s)) );
	}
}

if ( ! function_exists('inject_protect_email'))
{
	// For SQL injection protection email specific
	function inject_protect_email( $s ) {
//		return preg_replace( "^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.(([0-9]{1,3})|([a-zA-Z]{2,3})|(aero|coop|info|museum|name))$",
//		 "", strip_tags(trim($s)) );
		return strip_tags(trim($s));
	}
}

if ( ! function_exists('inject_protect_numbers'))
{		
	function inject_protect_numbers( $s ) {
		return preg_replace( "/[^0-9s]/", "", strip_tags(trim($s)) );
	}
}

if ( ! function_exists('inject_protect_ssn'))
{
	function inject_protect_ssn( $s ) {
		$t = preg_replace( "^(?!000)(?!666)(?<SSN3>[0-6]\d{2}|7(?:[0-6]\d|7[012]))([- ]?)(?!00)(?<SSN2>\d\d)\1(?!0000)(?<SSN4>\d{4})$", "", strip_tags(trim($s)) );
		if ( strlen($t) == 0 ) return 0;
		return $t;
	}
}

if ( ! function_exists('inject_protect_CC'))
{
	function inject_protect_CC ( $s ) {
		return preg_replace( "^(\d{4}-){3}\d{4}$|^(\d{4} ){3}\d{4}$|^\d{16}$", "", strip_tags(trim($s)) );
	}
}

if ( ! function_exists('inject_protect_zip'))
{
	function inject_protect_zip ( $s ) {
		return preg_replace( "^\d{5}$|^\d{5}-\d{4}$", "", strip_tags(trim($s)) );
	}
}

if ( ! function_exists('inject_protect_punctuate'))
{	
	// For text entry
	function inject_protect_punctuate( $s ) {
             
             # URL that generated this code:
             # http://www.txt2re.com/index-php.php3?s=abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ()*%$@!,.:;/\&3&61&58&57&67&56&62&63&65&66&64&94&-2
           
             $txt='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ()*%$@!,.:;/\\';
           
             $re1='(abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ)';	# Word 1
             $re2='(\\(.*\\))';	# Round Braces 1
             $re3='(.)';	# Single Character 1
             $re4='(.)';	# Single Character 2
             $re5='(.)';	# Single Character 3
             $re6='(.)';	# Single Character 4
             $re7='(.)';	# Single Character 5
             $re8='(.)';	# Single Character 6
             $re9='(.)';	# Single Character 7
             $re10='(.)';	# Single Character 8
             $re11='(.)';	# Single Character 9
             $re12='(.)';	# Single Character 10
             $re13='(.)';	# Single Character 11
             $regex="/".$re1.$re2.$re3.$re4.$re5.$re6.$re7.$re8.$re9.$re10.$re11.$re12.$re13."/is";
           /*
             if ($c=preg_match_all ("/".$re1.$re2.$re3.$re4.$re5.$re6.$re7.$re8.$re9.$re10.$re11.$re12.$re13."/is", $txt, $matches))
             {
                 $word1=$matches[1][0];                 $rbraces1=$matches[2][0];
                 $c1=$matches[3][0];                    $c2=$matches[4][0];
                 $c3=$matches[5][0];                    $c4=$matches[6][0];
                 $c5=$matches[7][0];                    $c6=$matches[8][0];
                 $c7=$matches[9][0];                    $c8=$matches[10][0];
                 $c9=$matches[11][0];                   $c10=$matches[12][0];
                 $c11=$matches[13][0];
                 return "($word1) ($rbraces1) ($c1) ($c2) ($c3) ($c4) ($c5) ($c6) ($c7) ($c8) ($c9) ($c10) ($c11)";
             }             */
           
             return preg_replace( $regex, "", strip_tags(trim($s)) );
//		return preg_replace( "^[a-zA-Z0-9\s.\?\!\-_']+$", "", strip_tags(trim($s)) );
	}
}


/*
 * Functions by Maurice Rickard
 */

if ( ! function_exists('keyED'))
{
function keyED($txt,$encrypt_key)  { 
	$encrypt_key = md5($encrypt_key); 
	$ctr=0; 
	$tmp = ""; 
	for ($i=0;$i<strlen($txt);$i++)  { 
		if ($ctr==strlen($encrypt_key)) 
			$ctr=0; 
		$tmp.= substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1); 
		$ctr++; 
	} 
	return $tmp; 
} 
}

if ( ! function_exists('encrypt'))
{
function encrypt($txt,$key) { 
	srand((double)microtime()*1000000); 
	$encrypt_key = md5(rand(0,32000)); 
	$ctr=0; 
	$tmp = ""; 
	for ($i=0;$i<strlen($txt);$i++) { 
		if ($ctr==strlen($encrypt_key)) 
			$ctr=0; 
		$tmp.= substr($encrypt_key,$ctr,1) . (substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1)); 
		$ctr++; 
	} 
	return keyED($tmp,$key); 
} 
}

if ( ! function_exists('decrypt'))
{
function decrypt($txt,$key) { 
	$txt = keyED($txt,$key); 
	$tmp = ""; 
	for ($i=0;$i<strlen($txt);$i++) { 
		$md5 = substr($txt,$i,1); 
		$i++; 
		$tmp.= (substr($txt,$i,1) ^ $md5); 
	} 
	return $tmp; 
} 
}



/* End of file security_helper.php */
/* Location: ./system/helpers/security_helper.php */