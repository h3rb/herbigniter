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
 * HerbIgniter Flash Chart Helpers
 *
 * @package         HerbIgniter
 * @subpackage	    Helpers
 * @category	    Helpers
 * @author	    Herb
 * @link	    http://gudagi.net/herbigniter/HI_user_guide/helpers/file_helpers.html
 */

if ( !isset($chart_count) ) $chart_count=0;

/* function pie() { }
 * Returns code that embeds amCharts pie-chart
 *
 * notes: all angles are in degrees, all hex colors are in the form #xxxxxx, numbers may be percentages
 *
 *<settings>
   $settings['font']                font name
   $settings['size']                font size
   $settings['color']               font color
   $settings['decimal']             decimal seperator
   $settings['thousands']           thousands seperator
   $settings['digits']              digits to be shown after decimal to right
   $settings['max']                 maximum value for chart data item
   $settings['min']                 minimum value for chart data item
   $settings['polldelays']          number of seconds between data polls to xml source
   $settings['preload']             true/false - display preloaded data
   $settings['redraw']              true/false - redraw when zoomed / resized
   $settings['timestamp']           true/false - store a timestamp
   $settings['precision']           how many numbers shown after comma for calculated values (percents)
   $settings['exclude']             true/false - to exclude invisible slices when alpha=0 when calculating percents
   $settings['js']                  true/false - polls javascript for signals
   $settings['x']                   size of pie
   $settings['y']                   size of pie
   $settings['radius']              radius of pie chart
   $settings['inner_radius']        inner radius for hole in donut chart (or set to 0)
   $settings['height']              pie height used by 3d effect
   $settings['angle']               pie leaning angle used by 3d effect
   $settings['start_angle']         angle of the first slice, works properly only when height=0; when height>0, valid entries are 90, 270
   $settings['outline']             outline color
   $settings['outline_alpha']       outline opacity (percentage)
   $settings['base_color']          color of first slice
   $setting['brightness']           (-100% to 100%) brightness where 0=no change, negative means darker, positive means lighter 
   $settings['colors']              a list of colors for each slice, example: #00000F,#0D8ECF,... 
   $settings['target']              _blank, _top, _parent, etc
   $settings['alpha']               opacity of chart
   $settings['hover']               mouse hover brightness, -255 to 255
   $settings['gradient']            'linear' or 'radial'
   $settings['ratio']               number list (-255 to 255 each) separated by commas for gradiant
  <pie>
   $settings['fly']                 number in seconds of flying animation
   $settings['flyeffect']           'bounce', 'regular', 'strong'
   $settings['flyradius']           percentage radius used during animation
   $settings['flyalpha']            alpha
   $settings['sequenced']           true/false - whether the slices appear together or in sequence
   $settings['pull']                true/false - are slices 'pulled' from the chart?
   $settings['pulltime']            number in seconds of pulling animation
   $settings['pullfx']              'bounce', 'regular', 'strong'
   $settings['pullradius']          number (percentage) how far the slices should be pulled out
   $settings['pull1']               true/false - slices are pulled exclusively, rather than toggled in/out on click
   
   $settings['labelradius']         percentage/pixels - distance of the labels from the pie
   $settings['textcolor']           hex color code of label text (defaults to master text color)
   $settings['textsize']            size of label text (defaults to master text size, above)
   $settings['width']               label width
   $settings['lines']               true/false - show lines from slices to data labels?
   $settings['line_color']          color of the lines
   $settings['line_alpha']          opacity of the lines
   $settings['hide']                data labels of slices less than this percentage will be hidden
   $settings['avoid']               true/false - when true, labels are arranged so they do not overlap
   
   $settings['group']               a percentage threshold where values < this are 'grouped' into a single slice
   $settings['groupcolor']          color of the 'group' slice
   $settings['grouptitle']          title of the 'group' slice
   $settings['groupurl']            url of the 'group' slice
   $settings ['groupdesc']          text description of the group
   $settings['grouppull']           true/false - can this slice be removed from the pie?
   
   $settings['bgcolor']             background color
   $settings['bgalpha']             background opacity
   $settings['border_color']        border color
   $settings['border_alpha']        border opacity
   $settings['bgimage']             background image: swf, jpg (non progressive-scan), gif
   
   $settings['balloon']             true/false - enables the rollover-balloon label
   $settings['balloon_color']       balloon background color
   $settings['balloon_alpha']       balloon opacity
   $settings['balloon_text']        balloon text color
   $settings['balloon_size']        balloon size
   $settings['balloon_width']       balloon width
   $settings['balloon_corner']      balloon corner radius
   $settings['balloon_border_width']        balloon border width
   $settings['balloon_border_alpha']        balloon border alpha
   $settings['balloon_border_color']        balloon border color
   $settings['balloon_format']      {value} {title} {percents} {description}) You can format any data label: {value} - will be replaced with value and so on. You can add your own text or html code too.
   
   $settings['legend']              true/false - activate chart legend
   $settings['legend_x']            top left x of legend
   $settings['legend_y']            top left y of legend
   $settings['legend_width']        width of legend
   $settings['legend_color']        color of legend
   $settings['max_columns']         maximum number of columns in the legend
   $settings['legend_alpha']        opacity of the legend
   $settings['legend_border_color'] border color of the legend
   $settings['legend_border_alpha'] border opacity of the legend
   $settings['legend_text']         text color of the legend 
   $settings['legend_size']         text size of the legend
   $settings['legend_spacing']      legend spacing (vertical and horizontal gap between entries)
   $settings['legend_margins']      legend margin
   $settings['legend_reverse']      true/false - when true, sort in reverse order
   $settings['legend_align']        'left', 'center', 'right'
   $settings['key_size']            size of the key
   $settings['key_border_color']    border color (leave as '' for no border)
   
   $settings['key_values']          true/false - show values near legend entries?
   $settings['key_width']
   $settings['key_format']            content, using {value} and {percents} to designate locations
   
   $settings['export']              true/false - allow exporting of chart image
   $settings['export_target']       where to place it / file name
   $settings['export_x']            x upper left of exporting message
   $settings['export_y']            y upper left of exporting message
   $settings['export_bgcolor']      background color of exporting message
   $settings['export_alpha']        opacity of exporting message
   $settings['export_color']        exporting message font color
   $settings['export_size']         exporting message font size
   
   $settings['errors']              true/false - show error messages?
   $settings['error_x']             x location upper left of error message
   $settings['error_y']             y location upper left of error message
   $settings['error_color']         background color of error message
   $settings['error_alpha']         opacity of error messages
   $settings['error_text']          font used by error messages
   $settings['error_size']          size of font used by error messages
   $settings['strings_error']       what to report when there is no data available
   $settings['strings_export']      what to report when there is an exporting error
   $settings['strings_collecting']  what to report when there is an error collecting polled data
   $settings['menu_definitions']    a set of xml declarations that look like: <menu function_name="printChart" title="Print chart"></menu> 
   $settings['menu_zoom']           true/false - show flash player zoom menu
   $settings['menu_print']          true/false - show print option
   
  <labels>
   $label['lid']                    label id
   $label['x']                      label's x location (upper left)
   $label['y']                      label's y location (upper left)
   $label['rotate']                 label's rotation 0-360
   $label['width']                  label's maximum width
   $label['align']                  label's alignment
   $label['color']                  label's font color
   $label['size']                   label's font size
   $label['text']                   label text: supports <b>, <i>, <u>, <font>, <a href="">, <br/>
   
   $settings['messages'];           additional messages sent via xml of the form: <message>content</message>
   
  <slices>
   $slice['title']
   $slice['pullout']
   $slice['value']
   $slice['url']
 */

if ( ! function_exists('pie'))
{
function pie( $settings, $labels, $slices,
              $filename="output.xml", $w="534", $h="170", $align="middle", $bg="transparent",
              $path=NULL, $chart_title=NULL  ) {
    global $chart_count;
    $HI =& get_instance();
    
  if ( !isset($settings['nocheck']) ) {
   if ( !isset($settings['font']) )         $settings['font'] = 'Arial';
   if ( !isset($settings['size']) )         $settings['size'] =  '11';
   if ( !isset($settings['color']) )        $settings['color'] = '#000001';
   if ( !isset($settings['decimal']) )      $settings['decimal'] = '.';
   if ( !isset($settings['thousands']))     $settings['thousands'] = 'none';
   if ( !isset($settings['digits']) )       $settings['digits'] = '';
   if ( !isset($settings['max']) )          $settings['max'] = '1000000000000000';
   if ( !isset($settings['min']) )          $settings['min'] = '0.000001';
   if ( !isset($settings['polldelays']))    $settings['polldelays'] = '0';
   if ( !isset($settings['preload']) )      $settings['preload'] = 'false';
   if ( !isset($settings['redraw']) )       $settings['redraw'] = 'false';
   if ( !isset($settings['timestamp']))     $settings['timestamp'] = 'false';
   if ( !isset($settings['precision']))     $settings['precision'] = '2';
   if ( !isset($settings['exclude']))       $settings['exclude'] = 'false';
   if ( !isset($settings['js']))            $settings['js'] = 'true';
   if ( !isset($settings['x']) )            $settings['x'] = '50%';
   if ( !isset($settings['y']) )            $settings['y'] = '50%';
   if ( !isset($settings['radius']))        $settings['radius'] = '25%';
   if ( !isset($settings['inner_radius']))  $settings['inner_radius'] = '0';
   if ( !isset($settings['height']))        $settings['height'] = '0';
   if ( !isset($settings['angle']))         $settings['angle'] = '0';
   if ( !isset($settings['start_angle']))   $settings['start_angle'] = '0';
   if ( !isset($settings['outline']))       $settings['outline'] = '#FFFFFF';
   if ( !isset($settings['outline_alpha'])) $settings['outline_alpha'] = '0';
   if ( !isset($settings['base_color']) )   $settings['base_color'] = '';
   if ( !isset($settings['brightness']) )   $settings['brightness'] = '20';
   if ( !isset($settings['colors']) )       $settings['colors'] = '#FF0F00,#FF6600,#FF9E01,#FCD202,#F8FF01,#B0DE09,#04D215,#0D8ECF,#0D52D1,#2A0CD0,#8A0CCF,#CD0D74,#754DEB,#DDDDDD,#999999,#333333,#990000';
   if ( !isset($settings['target']) )       $settings['target'] = '';
   if ( !isset($settings['alpha']) )        $settings['alpha'] = '100';
   if ( !isset($settings['hover']) )        $settings['hover'] = '0';
   if ( !isset($settings['gradient']))      $settings['gradient'] = '';
   if ( !isset($settings['ratio']))         $settings['ratio'] = '0,-40';
//  <pie>
   if ( !isset($settings['fly']) )          $settings['fly'] = '0';
   if ( !isset($settings['flyeffect']))     $settings['flyeffect'] = 'bounce';
   if ( !isset($settings['flyradius']))     $settings['flyradius'] = '500%';
   if ( !isset($settings['flyalpha']))      $settings['flyalpha'] = '0';
   if ( !isset($settings['sequenced']))     $settings['sequenced'] = 'false';
   if ( !isset($settings['pull']))          $settings['pull'] = 'true';
   if ( !isset($settings['pulltime']))      $settings['pulltime'] = '0';
   if ( !isset($settings['pullfx']))        $settings['pullfx'] = 'bounce';
   if ( !isset($settings['pullradius']))    $settings['pullradius'] = '20%';
   if ( !isset($settings['pull1']))         $settings['pull1'] = 'false';
   
   if ( !isset($settings['labelradius']))   $settings['labelradius'] = '20%';
   if ( !isset($settings['textcolor']) )    $settings['textcolor'] = '';
   if ( !isset($settings['textsize']) )     $settings['textsize'] = '';
   if ( !isset($settings['width']) )        $settings['width'] = '120';
   if ( !isset($settings['label_format']))  $settings['label_format'] = '{title}: {percents}%';
   if ( !isset($settings['lines']) )        $settings['lines'] = 'true';
   if ( !isset($settings['line_color']) )   $settings['line_color'] = '#000000';
   if ( !isset($settings['line_alpha']) )   $settings['line_alpha'] = '15';
   if ( !isset($settings['hide']) )         $settings['hide'] = '0';
   if ( !isset($settings['avoid']) )        $settings['avoid'] = 'true';
   
   if ( !isset($settings['group']) )        $settings['group%'] = '0';
   if ( !isset($settings['groupcolor']) )   $settings['groupcolor'] = '';
   if ( !isset($settings['grouptitle']) )   $settings['grouptitle'] = 'Others';
   if ( !isset($settings['groupurl']) )     $settings['groupurl'] = '';
   if ( !isset($settings ['groupdesc']) )   $settings['groupdesc'] = '';
   if ( !isset($settings['grouppull']) )    $settings['grouppull'] = 'false';
   
   if ( !isset($settings['bgcolor']) )      $settings['bgcolor'] = '#FFFFFF';
   if ( !isset($settings['bgalpha']) )      $settings['bgalpha'] = '0';
   if ( !isset($settings['border_color']) ) $settings['border_color'] = '#000000';
   if ( !isset($settings['border_alpha']) ) $settings['border_alpha'] = '0';
   if ( !isset($settings['bgimage']) )      $settings['bgimage'] = '';
   
   if ( !isset($settings['balloon']) )      $settings['balloon'] = 'true';
   if ( !isset($settings['balloon_color'])) $settings['balloon_color'] = '';
   if ( !isset($settings['balloon_alpha'])) $settings['balloon_alpha'] = '80';
   if ( !isset($settings['balloon_text']))  $settings['balloon_text'] = '#FFFFFF';
   if ( !isset($settings['balloon_size']))  $settings['balloon_size'] = '';
   if ( !isset($settings['balloon_max']) )  $settings['balloon_max'] = '220';
   if ( !isset($settings['balloon_format']))$settings['balloon_format'] = '';
   if ( !isset($settings['balloon_corner']))$settings['balloon_corner'] = '0';
   if ( !isset($settings['balloon_width'])) $settings['balloon_width'] = '0';
   if ( !isset($settings['balloon_border_alpha'])) $settings['balloon_border_alpha'] = '';
   if ( !isset($settings['balloon_border_color'])) $settings['balloon_border_color'] = '';
   
   if ( !isset($settings['legend']) )       $settings['legend'] = true;
   if ( !isset($settings['legend_x']) )     $settings['legend_x'] = '5%';
   if ( !isset($settings['legend_y']) )     $settings['legend_y'] = '';
   if ( !isset($settings['legend_width']) ) $settings['legend_width'] = '90%'; 
   if ( !isset($settings['legend_color']) ) $settings['legend_color'] = '';
   if ( !isset($settings['max_columns']) )  $settings['max_columns'] = '';
   if ( !isset($settings['legend_alpha']) ) $settings['legend_alpha'] = '0';
   if ( !isset($settings['legend_border_color']) ) $settings['legend_border_color'] = '#000000';
   if ( !isset($settings['legend_border_alpha']) ) $settings['legend_border_alpha'] = '20';
   if ( !isset($settings['legend_text']) )  $settings['legend_text'] = '';
   if ( !isset($settings['legend_size']) )  $settings['legend_size'] = '';
   if ( !isset($settings['legend_spacing']))$settings['legend_spacing'] = '9';
   if ( !isset($settings['legend_margins']))$settings['legend_margins'] = '5';
   if ( !isset($settings['legend_reverse']))$settings['legends_reverse'] = '';
   if ( !isset($settings['legend_align']) ) $settings['legend_align'] = 'center';
   
   if ( !isset($settings['key_size']) )         $settings['size'] = '16';
   if ( !isset($settings['key_border_color']))  $settings['border_color'] = '';
   if ( !isset($settings['key_values'] ))       $settings['key_values'] = true;
   if ( !isset($settings['key_format'] ))       $settings['key_format'] = '{percents}%';
   if ( !isset($settings['key_width'] ))        $settings['key_width'] = '';
   
   if ( !isset($settings['export'] ))       $settings['export'] = '';
   if ( !isset($settings['export_target'])) $settings['export_target'] = '';
   if ( !isset($settings['export_x']))      $settings['export_x'] = '0';
   if ( !isset($settings['export_y']))      $settings['export_y'] = '';
   if ( !isset($settings['export_bgcolor']))$settings['export_bgcolor'] = '#BBBB00';
   if ( !isset($settings['export_alpha']) ) $settings['export_alpha'] = '0';
   if ( !isset($settings['export_color']) ) $settings['export_color'] = '';
   if ( !isset($settings['export_size']) )  $settings['export_size'] = '';
   if ( !isset($settings['errors']) )       $settings['errors'] = false;
   if ( !isset($settings['error_x']) )      $settings['error_x'] = '';
   if ( !isset($settings['error_y']) )      $settings['error_y'] = '';
   if ( !isset($settings['error_color']) )  $settings['error_color'] = '#BBBB00';
   if ( !isset($settings['error_alpha']) )  $settings['error_alpha'] = '100';
   if ( !isset($settings['error_text']) )   $settings['error_text'] = '#FFFFFF';
   if ( !isset($settings['error_size']) )   $settings['error_size'] = '';
   if ( !isset($settings['strings_error'])) $settings['strings_error'] = '';
   if ( !isset($settings['strings_export']))$settings['strings_export'] = '';
   if ( !isset($settings['strings_collecting'])) $settings['strings_collection'] = '';
   if ( !isset($settings['menu_definitions'] ) ) $settings['menu_definitions'] = '';
   if ( !isset($settings['menu_zoom']))     $settings['menu_zoom'] = '';
   if ( !isset($settings['menu_print']))    $settings['menu_print'] = '';
  }
   
    if ( !isset($settings['messages'])) $settings['messages'] = '';
   
//  <slices> 
   foreach ( $slices as $slice ) {
    if ( !isset($slice['url'] ))    $slice['url'] = '';
    if ( !isset($slice['title']))   $slice['title'] = '';
    if ( !isset($slice['pullout'])) $slice['pullout'] = '';
    if ( !isset($slice['value']) )  $slice['value'] = 0;
   }
        
       // Produce configuration file for output
	$output = '
<settings> <data_type>xml</data_type> <csv_separator></csv_separator> <skip_rows></skip_rows> 
  <font>' . $settings['font'] . '</font><text_size>' . $settings['size'] . '</text_size> 
  <text_color>' . $settings['color'] . '</text_color>
  <decimals_separator>' . $settings['decimal'] . '</decimals_separator>
  <thousands_separator>' . $settings['thousands'] . '</thousands_separator>
  <digits_after_decimal>' . $settings['digits'] . '</digits_after_decimal>
  <scientific_min>' . $settings['max'] . '</scientific_min>
  <scientific_max>' . $settings['min'] . '</scientific_max>
  <reload_data_interval>' . $settings['polldelays'] . '</reload_data_interval>
  <preloader_on_reload>' . ($settings['preload'] === true ? 'true' : ($settings['preload'] === false ? 'false' : $settings['preload'] )) . '</preloader_on_reload>
  <redraw>' . ($settings['redraw'] === true ? 'true' : ($settings['redraw'] === false ? 'false' : $settings['redraw'] )) . '</redraw>  
  <add_time_stamp>' . ($settings['timestamp'] === true ? 'true' : ($settings['timestamp'] === false ? 'false' : $settings['timestamp'] )) . '</add_time_stamp>
  <precision>' . $settings['precision'] . '</precision>
  <exclude_invisible>' . ($settings['exclude'] === true ? 'true' : ($settings['exclude'] === false ? 'false' : $settings['exclude'] )) . '</exclude_invisible>
  <js_enabled>' . ($settings['js'] === true ? 'true' : ($settings['js'] === false ? 'false' : $settings['js'] )) . '</js_enabled>                                                                  
  <pie>
    <x>' . $settings['x'] . '</x>
    <y>' . $settings['y'] . '</y>
    <radius>' . $settings['radius'] . '</radius>
    <inner_radius>' . $settings['inner_radius'] . '</inner_radius>
    <height>' . $settings['height'] . '</height>
    <angle>' . $settings['angle'] . '</angle>
    <start_angle>' . $settings['start_angle'] . '</start_angle>
    <outline_color>' . $settings['outline'] . '</outline_color>
    <outline_alpha>' . $settings['outline_alpha'] . '</outline_alpha>
    <base_color>' . $settings['base_color'] . '</base_color>
    <brightness_step>' . $settings['brightness'] . '</brightness_step>
    <colors>' . $settings['colors'] . '</colors>
    <link_target>' . $settings['target'] . '</link_target>
    <alpha>' . $settings['alpha'] . '</alpha>
    <hover_brightness>' . $settings['hover'] . '</hover_brightness>
    <gradient>' . $settings['gradient'] . '</gradient>
    <gradient_ratio>' . $settings['ratio'] . '</gradient_ratio>
  </pie>  
  <animation>
    <start_time>' . $settings['fly'] . '</start_time>
    <start_effect>' . $settings['flyeffect'] . '</start_effect>
    <start_radius>' . $settings['flyradius'] . '</start_radius>
    <start_alpha>' . $settings['flyalpha'] . '</start_alpha>
    <sequenced>' . ($settings['sequenced'] === true ? 'true' : ($settings['sequenced'] === false ? 'false' : $settings['sequenced'] )) . '</sequenced>
    <pull_out_on_click>' . ($settings['pull'] === true ? 'true' : ($settings['pull'] === false ? 'false' : $settings['pull'] )) . '</pull_out_on_click>
    <pull_out_time>' . $settings['pulltime'] . '</pull_out_time>
    <pull_out_effect>' . $settings['pullfx'] . '</pull_out_effect>
    <pull_out_radius>' . $settings['pullradius'] . '</pull_out_radius>
    <pull_out_only_one>' . ($settings['pull1'] === true ? 'true' : ($settings['pull1'] === false ? 'false' : $settings['pull1'] )) . '</pull_out_only_one>
  </animation>  
  <data_labels>
    <radius>' . $settings['labelradius'] . '</radius>
    <text_color>' . $settings['textcolor'] . '</text_color>
    <text_size>' . $settings['textsize'] . '</text_size>
    <max_width>' . $settings['width'] . '</max_width>
    <show>
       <![CDATA[' . $settings['label_format'] . ']]>
    </show>
    <show_lines>' . ($settings['lines'] === true ? 'true' : ($settings['lines'] === false ? 'false' : $settings['lines'] )) . '</show_lines>
    <line_color>' . $settings['line_color'] . '</line_color>
    <line_alpha>' . $settings['line_alpha'] . '</line_alpha>
    <hide_labels_percent>' . $settings['hide'] . '</hide_labels_percent>
    <avoid_overlapping>' . ($settings['avoid'] === true ? 'true' : ($settings['avoid'] === false ? 'false' : $settings['avoid'] )) . '</avoid_overlapping>
  </data_labels>
  <group>
    <percent>' . $settings['group'] . '</percent>
    <color>' . $settings['groupcolor'] . '</color>
    <title>' . $settings['grouptitle'] . '</title>
    <url>' . $settings['groupurl'] . '</url>
    <description>' . $settings ['groupdesc'] . '</description>
    <pull_out>' . $settings['grouppull'] . '</pull_out>
  </group>
  <background>
    <color>' . $settings['bgcolor'] .'</color>
    <alpha>' . $settings['bgalpha'] . '</alpha>
    <border_color>' . $settings['border_color'] . '</border_color>
    <border_alpha>' . $settings['border_alpha'] . '</border_alpha>
    <file>' . $settings['bgimage'] . '</file>
  </background>
  <balloon>
    <enabled>' . ($settings['balloon'] === true ? 'true' : ($settings['balloon'] === false ? 'false' : $settings['balloon'] )) . '</enabled>
    <color>' . $settings['balloon_color'] . '</color>
    <alpha>' . $settings['balloon_alpha'] . '</alpha>
    <text_color>' . $settings['balloon_text'] . '</text_color>
    <text_size>' . $settings['balloon_size'] . '</text_size>
    <show>
       <![CDATA[' . $settings['balloon_format'] . ']]>
    </show>
    <max_width>' . $settings['balloon_width'] . '</max_width>
    <corner_radius>' . $settings['balloon_corner'] . '</corner_radius>
    <border_width>'  . $settings['balloon_border_width']  . '</border_width>
    <border_alpha>'  . $settings['balloon_border_alpha']  . '</border_alpha>
    <border_color>'  . $settings['balloon_border_color']  . '</border_color>
  </balloon>
  <legend>
    <enabled>' . $settings['legend'] . '</enabled>
    <x>' . $settings['legend_x'] . '</x>
    <y>' . $settings['legend_y'] . '</y>
    <width>' . $settings['legend_width'] . '</width>
    <color>' . $settings['legend_color'] . '</color>
    <max_columns>' . $settings['max_columns'] . '</max_columns>
    <alpha>' . $settings['legend_alpha'] . '</alpha>
    <border_color>' . $settings['legend_border_color'] . '</border_color>
    <border_alpha>' . $settings['legend_border_alpha'] . '</border_alpha>
    <text_color>' . $settings['legend_text'] . '</text_color>
    <text_size>' . $settings['legend_size'] . '</text_size>
    <spacing>' . $settings['legend_spacing'] . '</spacing>
    <margins>' . $settings['legend_margins'] . '</margins>
    <reverse_order>' . ($settings['legend_reverse'] === true ? 'true' : ($settings['legend_reverse'] === false ? 'false' : $settings['legend_reverse'] )) . '</reverse_order>
    <align>' . $settings['legend_align'] . '</align>
    <key>
      <size>' . $settings['key_size'] . '</size>
      <border_color>' . $settings['key_border_color'] . '</border_color>
    </key>
    <values>                                                  <!-- VALUES -->          
      <enabled>' . $settings['key_values'] . '</enabled>
      <width>' . $settings['key_width'] . '</width>
      <text><![CDATA[' . $settings['key_format'] . ']]></text>
     </values>    
  </legend>  
  <export_as_image>
    <file>' . $settings['export'] . '</file>
    <target>' . $settings['export_target'] . '</target>
    <x>' . $settings['export_x'] . '</x>
    <y>' . $settings['export_y'] . '</y>
    <color>' . $settings['export_bgcolor'] . '</color>
    <alpha>' . $settings['export_alpha'] . '</alpha>
    <text_color>' . $settings['export_color'] . '</text_color>
    <text_size>' . $settings['export_size'] . '</text_size>
  </export_as_image>
  <error_messages>
    <enabled>' . $settings['errors'] . '</enabled>
    <x>' . $settings['error_x'] . '</x>
    <y>' . $settings['error_y'] . '</y>
    <color>' . $settings['error_color'] . '</color>
    <alpha>' . $settings['error_alpha'] . '</alpha>
    <text_color>' . $settings['error_text'] . '</text_color>
    <text_size>' . $settings['error_size'] . '</text_size>
  </error_messages>      
  <strings>
    <no_data>' . $settings['strings_error'] . '</no_data>
    <export_as_image>' . $settings['strings_export'] . '</export_as_image>
    <collecting_data>' . $settings['strings_collecting'] . '</collecting_data>
  </strings>    
  <context_menu>
     ' . $settings['menu_definitions'] . '     
     <default_items>
       <zoom>' . ($settings['menu_zoom'] === true ? 'true' : ($settings['menu_zoom'] === false ? 'false' : $settings['menu_zoom'] )) . '</zoom>
       <print>' . ($settings['menu_print'] === true ? 'true' : ($settings['menu_print'] === false ? 'false' : $settings['menu_print'] )) . '</print>
     </default_items>
  </context_menu>  
  <labels>
  ';

  $n=0;  
  foreach ( $labels as $label ) {
    $n++;
    if ( !isset($label['lid']))     $label['lid'] = $n; 
    if ( !isset($label['x']))       $label['x'] = 0;
    if ( !isset($label['y']))       $label['y'] = 10;
    if ( !isset($label['rotate']))  $label['rotate'] = false;
    if ( !isset($label['width']))   $label['width'] = '';
    if ( !isset($label['align']))   $label['align'] = 'center';
    if ( !isset($label['color']))   $label['color'] = '';
    if ( !isset($label['size']))    $label['size'] = 12;
    if ( !isset($label['text']))    $label['text'] = '';

    $output .= 
   '<label lid="' . $label['lid'] . '">
      <x>' . $label['x'] . '</x>
      <y>' . $label['y'] . '</y>
      <rotate>' . ($label['rotate'] === true ? 'true' : ($label['rotate'] === false ? 'false' : $label['rotate'] )) . '</rotate>
      <width>' . $label['width'] . '</width>
      <align>' . $label['align'] . '</align>
      <text_color>' . $label['color'] . '</text_color>
      <text_size>' . $label['size'] . '</text_size>
      <text>
        <![CDATA[' . $label['text'] . ']]>
      </text>        
    </label>
    ';
  }
      
    $output .= '</labels><data><pie>' . $settings['messages'];
    
    foreach( $slices as $slice ) {
        $output .= '<slice url="' . $slice['url'] . '" title="' . $slice['title'] . '" pull_out="' . $slice['pullout'] . '">' . $slice['value'] . '</slice>';
    }
        
    $output .= '</pie></data></settings>';
			
	@file_put_contents(realpath( APPPATH . "../../" ) . "/" . $filename, $output);

    if ( is_null($path) ) {
        $path = base_url() . '/charts/pie.swf';
    }

    // Create HTML/JS Code

    $code = '<div class="amChart" id="chart_' . $chart_count . '_div">' . "\n";
    
    if( not_null($chart_title) )
    	$code .= '<div class="amChartTitle" id="chart_' . $chart_count . '_div_title">' . $chart_title . '</div>' . "\n";
    
    $code .= ''
    	. '<div class="amChartInner" id="chart_' . $chart_count . '_div_inner"><div id="chart_' . $chart_count . '_flash">Chart loading ...</div></div>' . "\n"
    	. '</div>' . "\n"
    	. '<script type="text/javascript">' . "\n"
    	. '// <![CDATA[' . "\n"
    	. 'var flashvars = {};' . "\n"
    	. 'flashvars.chart_id = "' . $chart_count . '";' . "\n"
    	. 'flashvars.chart_settings = escape("' . str_replace("\"", "'", $output) . '");' . "\n"
    	. 'flashvars.chart_data = escape("' . '");' . "\n"
    	. 'var params = {};' . "\n"
    	. ($bg== "transparent" ? 'params.wmode="transparent";' . "\n" : "")
    	. 'swfobject.embedSWF("' . $path . '", "chart_' . $chart_count. '_flash", "' . $w . '", "' . $h . '", "8", "", flashvars, params, {});' . "\n"
    	. '// ]]>' . "\n"
    	. '</script>' . "\n";

    $chart_count++;
    return $code;
}
}

//==============================================================================

if ( ! function_exists('chart_line'))
{
function chart_line( $settings, $labels, $bars,
               $filename="playlist.xml", $w="534", $h="170", $align="middle", $bg="transparent",
               $path=NULL, $chart_title=NULL  ) {
    global $chart_title;
    $HI =& get_instance();
    
    
}
}
//==============================================================================

if ( ! function_exists('bar'))
{
function bar( $settings, $labels, $guides, $graphs, $data, $series,
              $filename="playlist.xml", $w="534", $h="170", $align="middle", $bg="transparent",
              $path=NULL, $chart_title=NULL ) {
    $settings['type'] = 'bar';
    return column_chart( $settings, $labels, $guides, $graphs, $data, $series, $filename, $w, $h, $align, $bg, $path, $chart_title );
}
}

//==============================================================================

if ( ! function_exists('column_chart'))
{
function column_chart( $settings, $labels, $guides, $graphs, $data, $series='1',
                 $filename="output.xml", $w="534", $h="170", $align="middle", $bg="transparent",
                 $path=NULL, $chart_title=NULL ) {
    global $chart_title;
    $HI =& get_instance();

  if ( !isset($settings['nocheck']) ) {
   if ( !isset($settings['type']) )         $settings['type'] = 'column';
   if ( !isset($settings['font']) )         $settings['font'] = 'Arial';
   if ( !isset($settings['size']) )         $settings['size'] =  '11';
   if ( !isset($settings['color']) )        $settings['color'] = '#000001';
   if ( !isset($settings['decimal']) )      $settings['decimal'] = '.';
   if ( !isset($settings['thousands']))     $settings['thousands'] = 'none';
   if ( !isset($settings['digits']) )       $settings['digits'] = '';
   if ( !isset($settings['max']) )          $settings['max'] = '1000000000000000';
   if ( !isset($settings['min']) )          $settings['min'] = '0.000001';
   if ( !isset($settings['polldelays']))    $settings['polldelays'] = '0';
   if ( !isset($settings['preload']) )      $settings['preload'] = 'false';
   if ( !isset($settings['redraw']) )       $settings['redraw'] = 'false';
   if ( !isset($settings['timestamp']))     $settings['timestamp'] = 'false';
   if ( !isset($settings['precision']))     $settings['precision'] = '2';
   if ( !isset($settings['exclude']))       $settings['exclude'] = 'false';
   if ( !isset($settings['js']))            $settings['js'] = 'true';
//  <column>
   if ( !isset($settings['column_type']) )      $settings['column_type'] = 'clustered';
   if ( !isset($settings['column_width']) )     $settings['column_width'] = '80';
   if ( !isset($settings['column_spacing']) )   $settings['column_spacing'] = '0';
   if ( !isset($settings['grow_time']) )        $settings['grow_time'] = '3';
   if ( !isset($settings['grow_effect']) )      $settings['grow_effect'] = 'elastic';
   if ( !isset($settings['sequenced_grow']) )   $settings['sequenced_grow'] = 'elastic';
   if ( !isset($settings['column_alpha']) )     $settings['column_alpha'] = '';
   if ( !isset($settings['column_border_color']) )     $settings['column_border_color'] = '#FFFFFF';
   if ( !isset($settings['column_border_alpha']) )     $settings['column_border_alpha'] = '0';
// <data_labels>
   if ( !isset($settings['data_labels']))              $settings['data_labels'];
   if ( !isset($settings['data_labels_text_color']) )  $settings['data_labels_text_color'] = '';
   if ( !isset($settings['data_labels_text_size']))    $settings['data_labels_text_size'] = '';
   if ( !isset($settings['data_labels_position']))     $settings['data_labels_position'] = 'outside';
   if ( !isset($settings['data_labels_always_on']))    $settings['data_labels_always_on'] = 'false';
// <balloon_text>                                                    
   if ( !isset($settings['balloon_format']) )   $settings['balloon_format'] = '';
   if ( !isset($settings['link_target']) )      $settings['link_target'] = '';
   if ( !isset($settings['gradient']))          $settings['gradient'] = 'vertical';
   if ( !isset($settings['bullet_offset']))     $settings['bullet_offset'] = '0';
   if ( !isset($settings['hover_brightness']))  $settings['hover_brightness'] = '0';
// <line>
   if ( !isset($settings['connect']))           $settings['connect'] = 'false';
   if ( !isset($settings['line_width']) )       $settings['line_width'] = '2';
   if ( !isset($settings['line_alpha']) )       $settings['line_alpha'] = '100';
   if ( !isset($settings['fill_alpha']) )       $settings['fill_alpha'] = '0';
   if ( !isset($settings['bullet']) )           $settings['bullet'] = '';
   if ( !isset($settings['bullet_size'] ) )     $settings['bullet_size'] = 8;
   if ( !isset($settings['line_labels'] ) )     $settings['line_labels'] = '';
   if ( !isset($settings['line_labels_text_color'])) $settings['line_labels_text_color'] = '';
   if ( !isset($settings['line_labels_text_size']))  $settings['line_labels_text_size'] = '';
   if ( !isset($settings['line_balloon_text']) )     $settings['line_balloon_text'] = '';
   if ( !isset($settings['line_link_target']) )      $settings['line_link_target'] = '';
// <background>
   if ( !isset($settings['bgcolor']) )          $settings['bgcolor'] = '#FFFFFF';
   if ( !isset($settings['bgalpha']) )          $settings['bgalpha'] = '15';
   if ( !isset($settings['bg_border_color']) )  $settings['bg_border_color'] = '';
   if ( !isset($settings['bg_border_alpha']) )  $settings['bg_border_alpha'] = '';
   if ( !isset($settings['bgimage']) )          $settings['bgimage'] = '';
// <plot_area>   
   if ( !isset($settings['plot_color'] ) )      $settings['plot_color'] = '#FFFFFF';
   if ( !isset($settings['plot_alpha'] ) )      $settings['plot_alpha'] = '0';
   if ( !isset($settings['plot_border_color'])) $settings['plot_border_color'] = '#000000';
   if ( !isset($settings['plot_border_alpha'])) $settings['plot_border_alpha'] = '0';
   if ( !isset($settings['margin_left']) )      $settings['margin_left'] = 70;
   if ( !isset($settings['margin_top']) )       $settings['margin_left'] = 60;
   if ( !isset($settings['margin_right']) )     $settings['margin_left'] = 50;
   if ( !isset($settings['margin_bottom']) )    $settings['margin_left'] = 80;
// <grid>   
   if ( !isset($settings['grid_color']) )       $settings['grid_color'] = '#000000';
   if ( !isset($settings['grid_alpha']) )       $settings['grid_alpha'] = 5;
   if ( !isset($settings['grid_dashed']) )      $settings['grid_dashed'] = false;
   if ( !isset($settings['grid_dash_length']) ) $settings['grid_dash_length'] = 5;   
   if ( !isset($settings['grid_value_color']) )      $settings['grid_value_color'] = '#000000';
   if ( !isset($settings['grid_value_alpha']) )      $settings['grid_value_alpha'] = '15';
   if ( !isset($settings['grid_value_dashed']))      $settings['grid_value_dashed'] = false;
   if ( !isset($settings['grid_value_dash_length'])) $settings['grid_value_dash_length'] = 5;
   if ( !isset($settings['grid_line_count']))   $settings['grid_line_count'] = 10;
   if ( !isset($settings['grid_fill_color']))   $settings['grid_fill_count'] = '#FFFFFF';
   if ( !isset($settings['grid_fill_alpha']))   $settings['grid_fill_alpha'] = 5;

// <values>
   if ( !isset($settings['values']) )          $settings['values'] = true;
   if ( !isset($settings['frequency']) )       $settings['frequency'] = 1;
   if ( !isset($settings['start_from']))       $settings['start_from'] = 1;
   if ( !isset($settings['values_rotate']))    $settings['values_rotate'] = 45;
   if ( !isset($settings['values_color']))     $settings['values_color'] = '';
   if ( !isset($settings['values_size']))      $settings['values_size'] = '';
   if ( !isset($settings['values_inside']))    $settings['values_inside'] = false;
   if ( !isset($settings['values_axis']))      $settings['values_axis'] = true;
   if ( !isset($settings['values_reverse']))   $settings['values_reverse'] = false;
   if ( !isset($settings['values_min']))       $settings['values_min'] = '';
   if ( !isset($settings['values_max']))       $settings['values_max'] = '';
   if ( !isset($settings['values_strict_min_max'])) $settings['values_strict_min_max'] = false;
   if ( !isset($settings['values_frequency']) ) $settings['values_frequency'] = 1;
   if ( !isset($settings['values_value_rotate']))    $settings['values_value_rotate'] = 0;
   if ( !isset($settings['skip_first']))       $settings['skip_first'] = true;
   if ( !isset($settings['skip_last']))        $settings['skip_last'] = true;
   if ( !isset($settings['values_value_color'])) $settings['values_value_color'] = '';
   if ( !isset($settings['values_value_size'])) $settings['values_value_size'] = '';
   if ( !isset($settings['values_unit']))      $settings['values_unit'] = '';
   if ( !isset($settings['values_unit_position'])) $settings['values_unit_position'] ='right';
   if ( !isset($settings['integers']))         $settings['integers'] = false;
   if ( !isset($settings['values_value_inside'])) $settings['values_value_inside'] = false;
   if ( !isset($settings['values_duration'])) $settings['values_duration'] = '';
// <axes>
   if ( !isset($settings['axes_color']))       $settings['axes_color'] = '#000000';
   if ( !isset($settings['axes_alpha']))       $settings['axes_alpha'] = 100;
   if ( !isset($settings['axes_width']))       $settings['axes_width'] = 2;
   if ( !isset($settings['axes_tick']))        $settings['axes_tick'] = 7;
   if ( !isset($settings['axes_value_color'])) $settings['axes_value_color'] = '#000000';
   if ( !isset($settings['axes_value_alpha'])) $settings['axes_value_alpha'] = 100;
   if ( !isset($settings['axes_value_width'])) $settings['axes_value_width'] = 2;
   if ( !isset($settings['axes_value_tick']))  $settings['axes_value_tick'] = 7;
   if ( !isset($settings['logarithmic']))      $settings['logarithmic'] = false;
// <balloon>
   if ( !isset($settings['balloon']))       $settings['balloon'] = true;
   if ( !isset($settings['balloon_color'])) $settings['balloon_color'] = '';
   if ( !isset($settings['balloon_alpha'])) $settings['balloon_alpha'] = 100;
   if ( !isset($settings['balloon_text_color'])) $settings['balloon_text_color'] = '#FFFFFF';
   if ( !isset($settings['balloon_text_size']))  $settings['balloon_text_size'] = '';
   if ( !isset($settings['balloon_width']))      $settings['balloon_width'] = 220;
   if ( !isset($settings['balloon_corner']))     $settings['balloon_corner'] = 0;
   if ( !isset($settings['balloon_border_width'])) $settings['balloon_border_width'] = 0;
   if ( !isset($settings['balloon_border_alpha'])) $settings['balloon_border_width'] = '';
   if ( !isset($settings['balloon_border_color'])) $settings['balloon_border_color'] = '';
// <legend>
   if ( !isset($settings['legend']))        $settings['legend'] = true;
   if ( !isset($settings['legend_x']))      $settings['legend_x'] = '';
   if ( !isset($settings['legend_y']))      $settings['legend_y'] = '';
   if ( !isset($settings['legend_width']))  $settings['legend_width'] = '';
   if ( !isset($settings['legend_columns']))$settings['legend_columns'] = '';
   if ( !isset($settings['legend_color']))  $settings['legend_color'] = '#FFFFFF';
   if ( !isset($settings['legend_alpha']))  $settings['legend_alpha'] = 0;
   if ( !isset($settings['legend_border_color']))  $settings['legend_border_color'] = '#000000';
   if ( !isset($settings['legend_border_alpha']))  $settings['legend_border_alpha'] = '0';
   if ( !isset($settings['legend_text_color']))  $settings['legend_text_color'] = '';
   if ( !isset($settings['legend_size']))        $settings['legend_size'] = '';
   if ( !isset($settings['legend_spacing']))     $settings['legend_spacing'] = 10;
   if ( !isset($settings['legend_margins']))     $settings['legend_margins'] = 0;
   if ( !isset($settings['legend_reverse']))     $settings['legend_reverse'] = false;
   if ( !isset($settings['legend_align']))       $settings['legend_align'] = 'left';

   if ( !isset($settings['key']) )        $settings['key'] = 16;
   if ( !isset($settings['key_border']))  $settings['key_border'] = '';

   if ( !isset($settings['export'] ))       $settings['export'] = '';
   if ( !isset($settings['export_target'])) $settings['export_target'] = '';
   if ( !isset($settings['export_x']))      $settings['export_x'] = '0';
   if ( !isset($settings['export_y']))      $settings['export_y'] = '';
   if ( !isset($settings['export_bgcolor']))$settings['export_bgcolor'] = '#BBBB00';
   if ( !isset($settings['export_alpha']) ) $settings['export_alpha'] = '0';
   if ( !isset($settings['export_color']) ) $settings['export_color'] = '';
   if ( !isset($settings['export_size']) )  $settings['export_size'] = '';
   if ( !isset($settings['errors']) )       $settings['errors'] = false;
   if ( !isset($settings['error_x']) )      $settings['error_x'] = '';
   if ( !isset($settings['error_y']) )      $settings['error_y'] = '';
   if ( !isset($settings['error_color']) )  $settings['error_color'] = '#BBBB00';
   if ( !isset($settings['error_alpha']) )  $settings['error_alpha'] = '100';
   if ( !isset($settings['error_text']) )   $settings['error_text'] = '#FFFFFF';
   if ( !isset($settings['error_size']) )   $settings['error_size'] = '';
   if ( !isset($settings['strings_error'])) $settings['strings_error'] = '';
   if ( !isset($settings['strings_export']))$settings['strings_export'] = '';
   if ( !isset($settings['strings_collecting'])) $settings['strings_collection'] = '';

//  <!-- the strings below are only important if you format your axis values as durations -->
   if ( !isset($settings['ss'])) $settings['ss'] = '';
   if ( !isset($settings['mm'])) $settings['mm'] = ':';
   if ( !isset($settings['hh'])) $settings['hh'] = ':';
   if ( !isset($settings['DD'])) $settings['DD'] = 'd. ';
   
   if ( !isset($settings['menu_definitions'] ) ) $settings['menu_definitions'] = '';
   if ( !isset($settings['menu_zoom']))     $settings['menu_zoom'] = '';
   if ( !isset($settings['menu_print']))    $settings['menu_print'] = '';
   if ( !isset($settings['guides_max_min'] )) $settings['guides_max_min'] = false;

  }
  
       // Produce configuration file for output
	$output = '
        <settings> <data_type>xml</data_type> <csv_separator></csv_separator> <skip_rows>1</skip_rows> 
  <font>' . $settings['font'] . '</font><text_size>' . $settings['size'] . '</text_size> 
  <text_color>' . $settings['color'] . '</text_color>
  <decimals_separator>' . $settings['decimal'] . '</decimals_separator>
  <thousands_separator>' . $settings['thousands'] . '</thousands_separator>
  <digits_after_decimal>' . $settings['digits'] . '</digits_after_decimal>
  <scientific_min>' . $settings['max'] . '</scientific_min>
  <scientific_max>' . $settings['min'] . '</scientific_max>
  <reload_data_interval>' . $settings['polldelays'] . '</reload_data_interval>
  <preloader_on_reload>' . ($settings['preload'] === true ? 'true' : ($settings['preload'] === false ? 'false' : $settings['preload'] )) . '</preloader_on_reload>
  <redraw>' . ($settings['redraw'] === true ? 'true' : ($settings['redraw'] === false ? 'false' : $settings['redraw'] )) . '</redraw>  
  <add_time_stamp>' . ($settings['timestamp'] === true ? 'true' : ($settings['timestamp'] === false ? 'false' : $settings['timestamp'] )) . '</add_time_stamp>
  <precision>' . $settings['precision'] . '</precision>
  <exclude_invisible>' . ($settings['exclude'] === true ? 'true' : ($settings['exclude'] === false ? 'false' : $settings['exclude'] )) . '</exclude_invisible>
  <js_enabled>' . ($settings['js'] === true ? 'true' : ($settings['js'] === false ? 'false' : $settings['js'] )) . '</js_enabled>
  <column>
    <type>' . $settings['column_type'] . '</type>                                             
    <width>' . $settings['column_width'] . '</width>                                         
    <spacing>' . $settings['column_spacing'] . '</spacing>                                      
    <grow_time>' . $settings['grow_time'] . '</grow_time>                                  
    <grow_effect>' . $settings['grow_effect'] . '</grow_effect>                               
    <sequenced_grow>' . $settings['sequenced_grow'] . '</sequenced_grow>                     
    <alpha>' . $settings['column_alpha'] . '</alpha>                                           
    <border_color>' . $settings['column_border_color'] . '</border_color>                             
    <border_alpha>' . $settings['column_border_alpha'] . '</border_alpha>                             
    <data_labels>
      <![CDATA[' . $settings['data_labels'] . ']]>                                            
    </data_labels>
    <data_labels_text_color>' . $settings['data_labels_text_color'] . '</data_labels_text_color>         
    <data_labels_text_size>' . $settings['data_labels_text_size'] . '</data_labels_text_size>           
    <data_labels_position>' . $settings['data_labels_position'] . '</data_labels_position>             
    <data_labels_always_on>' . $settings['data_labels_always_on'] . '</data_labels_always_on>           
    <balloon_text>                                            
     <![CDATA[' . $settings['balloon_format'] . ']]>
    </balloon_text>    
    <link_target>' . $settings['link_target'] . '</link_target>                               
    <gradient>' . $settings['gradient'] . '</gradient>                                     
    <bullet_offset>' . $settings['bullet_offset'] . '</bullet_offset>                           
    <hover_brightness>' . $settings['hover_brigthness'] . '</hover_brightness>                   
  </column>
  <line>                                                      
    <connect>' . $settings['connect'] . '</connect>                                       
    <width>' . $settings['line_width'] . '</width>                                           
    <alpha>' . $settings['line_alpha'] . '</alpha>                                           
    <fill_alpha>' . $settings['line_fill_alpha'] . '</fill_alpha>                                 
    <bullet>' . $settings['bullet'] . '</bullet>                                         
    <bullet_size>' . $settings['bullet_size'] . '</bullet_size>                               
    <data_labels>
       <![CDATA[' . $settings['line_labels'] . ']]>                                           
    </data_labels>
    <data_labels_text_color>' . $settings['line_labels_text_color'] . '</data_labels_text_color>         
    <data_labels_text_size>' . $settings['line_labels_text_size'] . '</data_labels_text_size>           
    <balloon_text>                                            
      <![CDATA[' . $settings['line_balloon_text'] . ']]>                                            
    </balloon_text>      
    <link_target>' . $settings['line_link_target'] . '</link_target>                               
  </line>
  <background>                                                
    <color>' . $settings['bgcolor'] . '</color>                                           
    <alpha>' . $settings['bgalpha'] . '</alpha>                                           
    <border_color>' . $settings['bg_border_color'] . '</border_color>                             
    <border_alpha>' . $settings['bg_border_alpha'] . '</border_alpha>                           
    <file>' . $settings['bgimage'] . '</file>
  </background>
  <plot_area>                                                 
    <color>' . $settings['plot_color'] . '</color>                                           
    <alpha>' . $settings['plot_alpha'] . '</alpha>                                           
    <border_color>' . $settings['plot_border_color'] . '</border_color>                             
    <border_alpha>' . $settings['plot_border_alpha'] . '</border_alpha>                             
    <margins>                                                 
      <left>' . $settings['margin_left'] . '</left>                                         
      <top>' . $settings['margin_top'] . '</top>                                           
      <right>' . $settings['margin_right'] . '</right>                                       
      <bottom>' . $settings['margin_bottom'] . '</bottom>                                     
    </margins>
  </plot_area>
  
  <grid>                                                      
    <category>                                                
      <color>' . $settings['grid_color'] . '</color>                                         
      <alpha>' . $settings['grid_alpha'] . '</alpha>                                        
      <dashed>' . $settings['grid_dashed'] . '</dashed>                                       
      <dash_length>' . $settings['grid_dash_length'] . '</dash_length>                             
    </category>
    <value>                                                   
      <color>' . $settings['grid_value_color'] . '</color>                                         
      <alpha>' . $settings['grid_value_alpha'] . '</alpha>                                        
      <dashed>' . $settings['grid_value_dashed'] . '</dashed>                                       
      <dash_length>' . $settings['grid_value_length'] . '</dash_length>                             
      <approx_count>' . $settings['grid_line_count'] . '</approx_count>                           
      <fill_color>' . $settings['grid_fill_color'] . '</fill_color>                         
      <fill_alpha>' . $settings['grid_fill_alpha'] . '</fill_alpha>                              
    </value>
  </grid>
  <values>                                                    
    <category>                                                
      <enabled>' . $settings['values'] . '</enabled>                                     
      <frequency>' . $settings['frequency'] . '</frequency>                                
      <start_from>' . $settings['start_from'] . '</start_from>                               
      <rotate>' . $settings['values_rotate'] . '</rotate>                                     
      <color>' . $settings['values_color'] . '</color>                                         
      <text_size>' . $settings['values_size'] . '</text_size>                                 
      <inside>' . $settings['values_inside'] . '</inside>                                        
    </category>
    <value>                                                   
      <enabled>' . $settings['values_value'] . '</enabled>                                 
      <reverse>' . $settings['values_reverse'] . '</reverse>                                     
      <min>' . $settings['values_min'] . '</min>                                            
      <max>' . $settings['values_max'] . '</max>                                             
      <strict_min_max>' . $settings['values_strict_min_max'] . '</strict_min_max>                     
      <frequency>' . $settings['values_frequency'] . '</frequency>                                 
      <rotate>' . $settings['values_value_rotate'] . '</rotate>                                       
      <skip_first>' . $settings['skip_first'] . '</skip_first>                               
      <skip_last>' . $settings['skip_last'] . '</skip_last>                                 
      <color>' . $settings['values_value_color'] . '</color>                                         
      <text_size>' . $settings['values_value_size'] . '</text_size>                                 
      <unit>' . $settings['values_unit'] . '</unit>                                           
      <unit_position>' . $settings['values_unit_position'] . '</unit_position>                         
      <integers_only>' . $settings['integers'] . '</integers_only>                         
      <inside>' . $settings['values_value_inside'] . '</inside>                                       
      <duration>' . $settings['values_duration'] . '</duration>                                        
    </value>
  </values>
  
  <axes>                                                      
    <category>                                                
      <color>' . $settings['axes_color'] . '</color>                                         
      <alpha>' . $settings['axes_alpha'] . '</alpha>                                         
      <width>' . $settings['axes_width'] . '</width>                                        
      <tick_length>' . $settings['axes_tick'] . '</tick_length>                             
    </category>
    <value>                                                   
      <color>' . $settings['axes_value_color'] . '</color>                                         
      <alpha>' . $settings['axes_value_alpha'] . '</alpha>                                         
      <width>' . $settings['axes_value_width'] . '</width>                                        
      <tick_length>' . $settings['axes_value_tick'] . '</tick_length>                             
      <logarithmic>' . $settings['logarithmic'] . '</logarithmic>                             
    </value>
  </axes>  
  
  <balloon>                                                   
    <enabled>' . $settings['balloon'] . '</enabled>                                       
    <color>' . $settings['balloon_color'] . '</color>                                           
    <alpha>' . $settings['balloon_alpha'] . '</alpha>                                         
    <text_color>' . $settings['balloon_text_color'] . '</text_color>                                 
    <text_size>' . $settings['balloon_text_size'] . '</text_size>                                   
    <max_width>' . $settings['balloon_width'] . '</max_width>                                   
    <corner_radius>' . $settings['balloon_corner'] . '</corner_radius>                           
    <border_width>' . $settings['balloon_border_width'] . '</border_width>                             
    <border_alpha>' . $settings['balloon_border_alpha'] . '</border_alpha>                             
    <border_color>' . $settings['balloon_border_color'] . '</border_color>                             
  </balloon>
  <legend>                                                    
    <enabled>' . $settings['legend'] . '</enabled>                                  
    <x>' . $settings['legend_x'] . '</x>                                                   
    <y>' . $settings['legend_y'] . '</y>                                                   
    <width>' . $settings['legend_width'] . '</width>                                           
    <max_columns>' . $settings['legend_columns'] . '</max_columns>                               
    <color>' . $settings['legend_color'] . '</color>                                           
    <alpha>' . $settings['legend_alpha'] . '</alpha>                                           
    <border_color>' . $settings['legend_border_color'] . '</border_color>                             
    <border_alpha>' . $settings['legend_border_alpha'] . '</border_alpha>                             
    <text_color>' . $settings['legend_text_color'] . '</text_color>                                 
    <text_size>' . $settings['legend_text_size'] . '</text_size>                                   
    <spacing>' . $settings['legend_spacing'] . '</spacing>                                       
    <margins>' . $settings['legend_margins'] . '</margins>                                   
    <reverse_order>' . $settings['legend_reverse'] . '</reverse_order>                           
    <align>' . $settings['legend_align'] . '</align>                                           
    <key>                                                     
      <size>' . $settings['key_size'] . '</size>                                           
      <border_color>' . $settings['key_border_color'] . '</border_color>                           
    </key>
  </legend>  
  <export_as_image>                                           
    <file>' . $settings['export'] . '</file>                                           
    <target>' . $settings['export_target'] . '</target>                                         
    <x>' . $settings['export_x'] . '</x>                                                   
    <y>' . $settings['export_y'] . '</y>                                                   
    <color>' . $settings['export_color'] . '</color>                                           
    <alpha>' . $settings['export_alpha'] . '</alpha>                                           
    <text_color>' . $settings['export_text_color'] . '</text_color>                                 
    <text_size>' . $settings['export_text_size'] . '</text_size>                                   
  </export_as_image>
  <error_messages>
    <enabled>' . $settings['errors'] . '</enabled>
    <x>' . $settings['error_x'] . '</x>
    <y>' . $settings['error_y'] . '</y>
    <color>' . $settings['error_color'] . '</color>
    <alpha>' . $settings['error_alpha'] . '</alpha>
    <text_color>' . $settings['error_text'] . '</text_color>
    <text_size>' . $settings['error_size'] . '</text_size>
  </error_messages>      
  <strings>
    <no_data>' . $settings['strings_error'] . '</no_data>
    <export_as_image>' . $settings['strings_export'] . '</export_as_image>
    <collecting_data>' . $settings['strings_collecting'] . '</collecting_data>
    <ss>' . $settings['ss'] . '</ss>
    <mm>' . $settings['mm'] . '</mm>
    <hh>' . $settings['hh'] . '</hh>
    <DD>' . $settings['DD'] . '</DD>
 </strings>
  <context_menu>
     ' . $settings['menu_definitions'] . '
     <default_items>
       <zoom>' . ($settings['menu_zoom'] === true ? 'true' : ($settings['menu_zoom'] === false ? 'false' : $settings['menu_zoom'] )) . '</zoom>
       <print>' . ($settings['menu_print'] === true ? 'true' : ($settings['menu_print'] === false ? 'false' : $settings['menu_print'] )) . '</print>
     </default_items>
  </context_menu>
  <guides>
';


  foreach ( $guides as $guide ) { 
   if ( !isset($guide['behind']))       $guide['behind'] = false;
   if ( !isset($guide['start']))        $guide['start'] = '';
   if ( !isset($guide['end']))          $guide['end'] = '';
   if ( !isset($guide['title']))        $guide['title'] = '#BBBB00';
   if ( !isset($guide['width']) )       $guide['width'] = '0';
   if ( !isset($guide['color']) )       $guide['color'] = '';
   if ( !isset($guide['alpha']) )       $guide['alpha'] = '';
   if ( !isset($guide['fill_color']) )  $guide['fill_color'] = false;
   if ( !isset($guide['fill_alpha']) )  $guide['fill_alpha'] = '';
   if ( !isset($guide['inside']) )      $guide['inside'] = '';
   if ( !isset($guide['centered']) )    $guide['centered'] = '#BBBB00';
   if ( !isset($guide['rotate']) )      $guide['rotate'] = '100';
   if ( !isset($guide['text_size']) )   $guide['text_size'] = '#FFFFFF';
   if ( !isset($guide['text_color']))   $guide['text_color'] = '';
   if ( !isset($guide['dashed']))       $guide['dashed'] = '';
   if ( !isset($guide['dash_length']))  $guide['dash_length'] = '';

   $output .= '<guide>
  	 <behind>' . $guide['behind'] . '</behind>
	   <start_value>' . $guide['start'] . '</start_value>
	   <end_value>' . $guide['end'] . '</end_value>
	   <title>' . $guide['title'] . '</title>
	   <width>' . $guide['width'] . '</width>
	   <color>' . $guide['color'] . '</color>
	   <alpha>' . $guide['alpha'] . '</alpha>
	   <fill_color>' . $guide['fill_color'] . '</fill_color>
	   <fill_alpha>' . $guide['fill_alpha'] . '</fill_alpha>
	   <inside>' . $guide['inside'] . '</inside>
	   <centered>' . $guide['centered'] . '</centered>
	   <rotate>' . $guide['rotate'] . '</rotate>
	   <text_size>' . $guide['text_size'] . '</text_size>
	   <text_color>' . $guide['text_color'] . '</text_color>
     <dashed>' . $guide['dashed'] . '</dashed>
     <dash_length>' . $guide['dash_length'] . '</dash_length>
	 </guide>
';
  }
  
  $output .= '</guides><labels>
';
  
  $n=0;  
  foreach ( $labels as $label ) { $n++;
    if ( !isset($label['lid']))     $label['lid'] = $n; 
    if ( !isset($label['x']))       $label['x'] = 0;
    if ( !isset($label['y']))       $label['y'] = 10;
    if ( !isset($label['rotate']))  $label['rotate'] = false;
    if ( !isset($label['width']))   $label['width'] = '';
    if ( !isset($label['align']))   $label['align'] = 'center';
    if ( !isset($label['color']))   $label['color'] = '';
    if ( !isset($label['size']))    $label['size'] = 12;
    if ( !isset($label['text']))    $label['text'] = '';

    $output .= 
   '<label lid="' . $label['lid'] . '">
      <x>' . $label['x'] . '</x>
      <y>' . $label['y'] . '</y>
      <rotate>' . ($label['rotate'] === true ? 'true' : ($label['rotate'] === false ? 'false' : $label['rotate'] )) . '</rotate>
      <width>' . $label['width'] . '</width>
      <align>' . $label['align'] . '</align>
      <text_color>' . $label['color'] . '</text_color>
      <text_size>' . $label['size'] . '</text_size>
      <text>
        <![CDATA[' . $label['text'] . ']]>
      </text>        
    </label>
';
  }

  $output .= '</labels><graphs>
  ';

  $m=0;
  foreach ( $graphs as $graph ) { $m++;
    if ( !isset($graph['gid']))                     $graph['gid'] = $m;
    if ( !isset($graph['type']))                    $graph['type'] = '';
    if ( !isset($graph['title']))                   $graph['title'] ='';
    if ( !isset($graph['color']))                   $graph['color'] = '';
    if ( !isset($graph['alpha']))                   $graph['alpha'] = '';
    if ( !isset($graph['labels']))                  $graph['labels'] = '';
    if ( !isset($graph['gradient_fill_colors']))    $graph['gradient_fill_colors'] = '';
    if ( !isset($graph['balloon_color']))           $graph['balloon_color'] = '';
    if ( !isset($graph['balloon_alpha']))           $graph['balloon_alpha'] = '';
    if ( !isset($graph['balloon_text_color']))      $graph['balloon_text_color'] = '';
    if ( !isset($graph['balloon_text']))            $graph['balloon_text'] = '';
    if ( !isset($graph['fill_alpha']))              $graph['fill_alpha'] = '';
    if ( !isset($graph['width']))                   $graph['width'] = '';
    if ( !isset($graph['bullet']))                  $graph['bullet'] = '';
    if ( !isset($graph['bullet_size']))             $graph['bullet_size'] = '';
    if ( !isset($graph['bullet_color']))            $graph['bullet_color'] = '';
    if ( !isset($graph['visible_in_legend']))       $graph['visible_in_legend'] = '';
    if ( !isset($graph['pattern']))                 $graph['pattern'] = '';
    if ( !isset($graph['pattern_color']))           $graph['pattern_color'] = '';
    
    $output .= '<graph gid="' . $graph['gid'] . '">
      <type>' . $graph['type'] . '</type>
      <title>' . $graph['title'] . '</title>
      <color>' . $graph['color'] . '</color>
      <alpha>' . $graph['alpha'] . '</alpha>
      <data_labels>
        <![CDATA[' . $graph['labels'] . ']]>
      </data_labels>
      <gradient_fill_colors>' . $graph['gradient_fill_colors'] . '</gradient_fill_colors>
      <balloon_color>' . $graph['balloon_color'] . '</balloon_color>
      <balloon_alpha>' . $graph['balloon_alpha'] . '</balloon_alpha>
      <balloon_text_color>' . $graph['balloon_text_color'] . '</balloon_text_color>
      <balloon_text>
        <![CDATA[' . $graph['balloon_text'] . ']]>
      </balloon_text>
      <fill_alpha>' . $graph['fill_alpha'] . '</fill_alpha>
      <width>' . $graph['width'] . '</width>
      <bullet>' . $graph['bullet'] . '</bullet>
      <bullet_size>' . $graph['bullet_size'] . '</bullet_size>
      <bullet_color>' . $graph['bullet_color'] . '</bullet_color>
      <visible_in_legend>' . $graph['visible_in_legend'] . '</visible_in_legend>
      <pattern>' . $graph['pattern'] . '</pattern>
      <pattern_color>' . $graph['pattern_color'] . '</pattern_color>
    </graph>
';
  }
    
  $output .= '<data><chart><series>';
 
  $o=0;
  foreach ( $series as $serie ) { $o++;
    if ( !isset($serie['xid']) ) $serie['xid'] = $o;
    if ( !isset($serie['value'] )) $serie['value'] = '';
    $output .= '<value xid="' . $serie['xid'] . '">' . $serie['value'] . '</value>';
  }

  $output .= '</series><graphs>';

  $p=0;
  foreach ( $data as $d ) {
    if (!isset($d['gid']))    $d['xid']    = $p++;
    if (!isset($d['title']))  $d['title']  = '';
    if (!isset($d['values'])) $d['values'] = array();
    $output .= '<graph gid="' . $d['gid'] . '" title="' . $d['title'] . '">';
    foreach ( $d['values'] as $k=>$v ) $output .= '<value xid="' . $k . '">' . $v . '</value>';
  }

  $output .= '</graphs></chart></data></settings>';


    @file_put_contents(realpath( APPPATH . "../../" ) . "/" . $filename, $output);

    if ( is_null($path) ) {
        $path = base_url() . '/charts/pie.swf';
    }

    // Create HTML/JS Code

    $code = '<div class="amChart" id="chart_' . $chart_count . '_div">' . "\n";
    
    if( not_null($chart_title) )
    	$code .= '<div class="amChartTitle" id="chart_' . $chart_count . '_div_title">' . $chart_title . '</div>' . "\n";
    
    $code .= 
    	  '<div class="amChartInner" id="chart_' . $chart_count . '_div_inner"><div id="chart_' . $chart_count . '_flash">Chart loading ...</div></div>' . "\n"
    	. '</div>' . "\n"
    	. '<script type="text/javascript">' . "\n"
    	. '// <![CDATA[' . "\n"
    	. 'var flashvars = {};' . "\n"
    	. 'flashvars.chart_id = "' . $chart_count . '";' . "\n"
    	. 'flashvars.chart_settings = escape("' . str_replace("\"", "'", $output) . '");' . "\n"
    	. 'flashvars.chart_data = escape("' . '");' . "\n"
    	. 'var params = {};' . "\n"
    	. ($bg== "transparent" ? 'params.wmode="transparent";' . "\n" : "")
    	. 'swfobject.embedSWF("' . $path . '", "chart_' . $chart_count. '_flash", "' . $w . '", "' . $h . '", "8", "", flashvars, params, {});' . "\n"
    	. '// ]]>' . "\n"
    	. '</script>' . "\n";

    $chart_count++;
    return $code;
}
}

//==============================================================================

if ( ! function_exists('chartxy'))
{
function chartxy( $settings, $labels, $bars, $filename="playlist.xml",
                  $w="534", $h="170", $align="middle", $bg="transparent",
                  $path=NULL, $chart_title=NULL  ) {
    global $chart_title;
    $HI =& get_instance();
    
    
}
}

?>