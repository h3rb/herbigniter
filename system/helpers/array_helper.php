<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * HerbIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		HerbIgniter
 * @author		ExpressionEngine Dev Team, Parts by Gudagi (c) 2009 
 * @copyright	Copyright (c) 2008 - 2009, EllisLab, Inc.
 * @license		http://herbigniter.com/HI_user_guide/license.html
 * @link		http://herbigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * HerbIgniter Array Helpers
 *
 * @package		HerbIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://herbigniter.com/HI_user_guide/helpers/array_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Element
 *
 * Lets you determine whether an array index is set and whether it has a value.
 * If the element is empty it returns FALSE (or whatever you specify as the default value.)
 *
 * @access	public
 * @param	string
 * @param	array
 * @param	mixed
 * @return	mixed	depends on what the array contains
 */	
if ( ! function_exists('element'))
{
	function element($item, $array, $default = FALSE)
	{
		if ( ! isset($array[$item]) OR $array[$item] == "")
		{
			return $default;
		}

		return $array[$item];
	}	
}

// ------------------------------------------------------------------------

/**
 * Random Element - Takes an array as input and returns a random element
 *
 * @access	public
 * @param	array
 * @return	mixed	depends on what the array contains
 */	
if ( ! function_exists('random_element'))
{
	function random_element($array)
	{
		if ( ! is_array($array))
		{
			return $array;
		}
		return $array[array_rand($array)];
	}	
}

if ( ! function_exists('multipleArrayIntersect')) {
 function multipleArrayIntersect($arrayOfArrays, $matchKey)
 {
    $compareArray = array_pop($arrayOfArrays);
   
    foreach($compareArray AS $key => $valueArray){
        foreach($arrayOfArrays AS $subArray => $contents){
            if (!in_array($compareArray[$key][$matchKey], $contents)){
                unset($compareArray[$key]);
            }
        }
    }

    return $compareArray;
 } 
}

# array_stack()
# Original idea from:
# http://www.ideashower.com/our_solutions/
#   create-a-parent-child-array-structure-in-one-pass/
if ( ! function_exists('array_stack')) {
function array_stack (&$a, $p = '@parent', $c = '@children')
{
  $l = $t = array();
  foreach ($a AS $key => $val):
    if (!$val[$p]) $t[$key] =& $l[$key];
    else $l[$val[$p]][$c][$key] =& $l[$key];
    $l[$key] = (array)$l[$key] + $val;
  endforeach;
  return $a = array('tree' => $t, 'leaf' => $l);
}
}

if ( ! function_exists('array_delete_by_index')) {
function array_delete_by_index($input_array, $del_indexes) {
    if (is_array($del_indexes)) {
        $indexes = $del_indexes;
    } elseif(is_string($del_indexes)) {
        $indexes = explode($del_indexes, " ");
    } elseif(is_numeric($del_indexes)) {
        $indexes[0] = (integer)$del_indexes;
    } else return;
    unset($del_indexes);
   
    for($i=0; $i<count($indexes); $i++) {
        unset($target_array[$indexes[$i]]);
    }
    return $target_array;
} 
}

if ( ! function_exists('array2js')) {
            function array2js($array,$show_keys=true)
            {
                $dimensoes = array();
                $valores = array();
              
                $total = count ($array)-1;
                $i=0;
                foreach($array as $key=>$value){
                    if (is_array($value)) {
                        $dimensoes[$i] = array2js($value,$show_keys);
                        if ($show_keys) $dimensoes[$i] = '"'.$key.'":'.$dimensoes[$i];
                    } else {
                        $dimensoes[$i] = '"'.addslashes($value).'"';
                        if ($show_keys) $dimensoes[$i] = '"'.$key.'":'.$dimensoes[$i];
                    }
                    if ($i==0) $dimensoes[$i] = '{'.$dimensoes[$i];
                    if ($i==$total) $dimensoes[$i].= '}';
                    $i++;
                }
                return implode(',',$dimensoes);
            }
}

if ( ! function_exists('array_avg')) {
function array_avg($array,$precision=2){
    if(!is_array($array))
        return 'ERROR in function array_avg(): this is a not array';

    foreach($array as $value)
        if(!is_numeric($value))
            return 'ERROR in function array_avg(): the array contains one or more non-numeric values';
   
    $cuantos=count($array);
  return round(array_sum($array)/$cuantos,$precision);
}
}


if ( ! function_exists('array_merge_recursive_distinct')) {
function array_merge_recursive_distinct ( array &$array1, array &$array2 )
{
  $merged = $array1;

  foreach ( $array2 as $key => &$value )
  {
    if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) )
    {
      $merged [$key] = array_merge_recursive_distinct ( $merged [$key], $value );
    }
    else
    {
      $merged [$key] = $value;
    }
  }

  return $merged;
}
}


if ( ! function_exists('array_mix')) {
function array_mix() {
    $array = array();
    $arrays = func_get_args();
   
    foreach($arrays as $array_i) if(is_array($array_i))
        $array = array_mixer($array, $array_i);

    return $array;
}

function array_mix_recursive() {
    $array = array();
    $arrays = func_get_args();
   
    foreach($arrays as $array_i) if(is_array($array_i))
        $array = array_mixer($array, $array_i, true);

    return $array;
}

function array_mixer($array_o, $array_i, $recursive=false) {
    foreach($array_i as $k => $v) {
        if(! isset($array_o[$k])) {
            $array_o[$k] = $v;
        } else {
            if(is_array($array_o[$k])) {
                if(is_array($v)) {
                    if($recursive) $array_o[$k] = array_mixer($array_o[$k], $v);
                    else $array_o[$k] = $v;
                } else $array_o[$k][] = $v;
            } else {
                if(! isset($array_o[$k])) {
                    $array_o[$k] = $v;
                } else {
                    $array_o[$k] = array($array_o[$k]);
                    $array_o[$k][] = $v;
                }
            }
        }
    }
   
    return $array_o;
}
}

/* End of file array_helper.php */
/* Location: ./system/helpers/array_helper.php */