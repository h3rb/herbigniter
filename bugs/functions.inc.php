<?php

######################################
###      BEGIN FUNCTION DEFS       ###
######################################


FUNCTION admin_check() {

	GLOBAL $current_session_g;

	if ( $current_session_g["role"] != 'Admin' )
		die_now( msg("You are not an administrator.") );

	# zztop some good logging here?

}



FUNCTION append_xml_file_node( $filename, $node, $closing_root_tag ) {

	brute_force_lock( $filename );

	# Open file in append R+W mode and request an exclusive blocking lock on the file

	$fh = fopen($filename, 'rb+') or
		die_now( sprintf(msg("FATAL: Could not open or append to file 'X' ! Check that it is writable by the Apache owner."), $filename) );

	fseek($fh, 0, SEEK_END);
	$fsize = ftell($fh);         # We don't have to worry about nasty stat-caused caching issues now

	fseek($fh, -CT_LAST_LINE_SIZE, SEEK_END);  # Existing root tag should be somewhere in last 1K of file

	$last_line = fread($fh, CT_LAST_LINE_SIZE);

	$offset  = CT_LAST_LINE_SIZE - strrpos($last_line, $closing_root_tag );

	$append_location = $fsize - $offset;

	fseek($fh, $append_location, SEEK_SET);

	$node .= "\n$closing_root_tag";

	#die( "<pre>" . htmlspecialchars("offset: $offset trun: $trunc_loc last: *$last_line* node: $node") );

	# Translate form & XML tag CRs ( ? + \n  --->  ? + \r + \n ) for friendly *nix/Windows transfers

	$node = ereg_replace( "([^\r])\n", "\\1\r\n", $node);

	if ( fwrite($fh, $node) === FALSE )
		die_now( sprintf(msg("FATAL: Could not append to file 'X' ! Check that it is writable by the Apache owner."), $filename) );

	fclose($fh);

	brute_force_lock_release( $filename );
}



FUNCTION authorized_user( $project_id, $user_id, $permission_table ) {

	# print "<pre>"; var_dump($permission_table);
	# print "PI: $project_id UI: $user_id " ;  print "</pre>";

	foreach ($permission_table as $permission) {

		if ( ($permission["Project_ID"] == $project_id) and
		 ($permission["User_ID"] == $user_id) ) {
			return TRUE;
		}
	}
	return FALSE;
}



FUNCTION bar_graph( $values_array ) {

	#
	# A modest little function to draw a horizontal bar chart.  Requires relative access to "images/blue.gif"
	#
	# Expects a 1-dimensional associative array where the index names are meaningful:
	#   foo[x], foo[max], foo[avg], etc.
	# Note, scale type is NOT case sensitive.
	#

	if (!isset($values_array))
		die_now( msg("FATAL: No values passed to graph array!") );

	print "<div class='barGraph'>\n\t<table cellpadding='0' cellspacing='0' border='0' width='100%' summary='" .
		msg("Bar Graph") . "'>\n";

	$total = array_sum($values_array);

	foreach ($values_array as $this_name => $this_value) {

		$this_name = msg($this_name);

		if ($total == 0)
			$bar_value = " 0.0";  # Avoid division by zero problem
		else {
			$bar_value = sprintf("%2.1f", $this_value / $total * 100);
			if ($bar_value == '100.0')
				$bar_value='100';
		}

		# Print the bar, but only show image slice if non-zero

		print "<tr>\n\t<th>$this_name </th>\n\t<td>";

		if ( $bar_value != 0 ) {

			$html_width = (int)ceil($bar_value);  # Never allow WIDTH=0

			print "<img src='images/blue.gif' width='$html_width' height='5' " .
				"title='$this_value ($bar_value%) $this_name items' alt='Bar Graph' />&nbsp;" ;
		}

		print "</td>\n\t<td>$this_value</td>\n\t<td>($bar_value%)</td>\n</tr>\n";
	}

	# Display final total row

	print "<tr class='graphTotal'>\n\t<th><strong>Total </strong></th>\n\t" .
		"<td>&nbsp;</td>\n\t" .
		"<td><strong>$total</strong></td>\n\t" .
		"<td>(100%)</td>\n</tr>\n" .
		"</table>\n</div>\n\n";
}



FUNCTION brute_force_lock( $filename ) {

	brute_force_lock_check( $filename );

	$lock = "$filename.lck";

	touch( $lock ) or
		die_now(
			sprintf(
				msg("FATAL: Unable to create lockfile 'X' !  <br /> Make sure Apache has write permissions in this directory."), $lock
			)
		);

}



FUNCTION brute_force_lock_check( $filename ) {

	$timeout = 0;
	$lock = "$filename.lck";
	while ( file_exists( $lock ) ) {
		sleep(1);
		clearstatcache();  # Force OS level dump on possible dirty cache; expensive, but should be rarely needed
		if ( $timeout++ > CT_LOCKFILE_TIMEOUT ) {
			brute_force_lock_release( $filename );
			die_now( sprintf(msg("FATAL: Timeout releasing lockfile 'X' !<br />Please report this message to support."), $lock) );
		}
	}
}



FUNCTION brute_force_lock_release( $filename ) {

	$lock = "$filename.lck";
	unlink( $lock ) or
		die_now( sprintf(msg("FATAL: Could not delete lockfile 'X' !<br />Please report this message to support."), $lock) );

}



# A helper function to generate a bullet list of static links (called from admin & tools pages)
# Caller is responsible for ensuring msg translations exist for all link labels
# External links open in new browser window, internal links are built off codetrack.php?page=x

/* tess */

FUNCTION build_static_links( $link_list, $title, $external_link=FALSE ) {

	$content = msg( $title );
	$content.= "\t<ul>\n";

	foreach ($link_list as $page => $label)
		$content .= "\t\t<li>" .
		( ($external_link) ? "<a href='$page' onclick='this.target=\"_blank\";'>" : "<a href='codetrack.php?page=$page'>" ) .
		msg( $label ) . "</a></li>\n";

	$content .= "\t</ul>\n\n";

	return $content;

}



FUNCTION build_new_session_cookie( $user_data, $selected_project ) {

	if ( (! $user_data ) or (! $selected_project ) )
		die_now( msg("FATAL: Internal - No User Data or Selected Project! (This should never happen)") );

	$session_authentication = calc_session_id( $user_data["Full_Name"] );

	# These variables can be accessed via current_session_g

	$session = array ( "user_full_name" => $user_data["Full_Name"],
		"id" => $session_authentication,
		"project" => $selected_project,
		"username" => $user_data["Username"],
		"role" => $user_data["Role"] );

	$serialized_session = urlencode(base64_encode(serialize($session)));
	return $serialized_session;
}



FUNCTION build_updated_session_cookie( $project_name, $current_session ) {

	#var_dump($current_session);

	$session = array ( "user_full_name" => $current_session["user_full_name"],
		"id" => $current_session["id"],
		"project" => $project_name,
		"username" => $current_session["username"],
		"role" => $current_session["role"] );

	$serialized_session = urlencode(base64_encode(serialize($session)));

	return $serialized_session;
}



FUNCTION calc_next_node_id( $node_list ) {

	$max_id = 0;
	$node_list_cnt = sizeof($node_list);

	for ($i=0; $i < $node_list_cnt; $i++)
		if ($node_list[$i]["ID"] > $max_id)
			$max_id = $node_list[$i]["ID"];

	return $max_id + 1;
}



FUNCTION calc_session_id( $user_full_name ) {

	# Create a one-day session ID by calculating the MD5 hash of date + user full name + private key

	if (! defined( 'CT_PRIVATE_KEY' ))
		die_now( msg( "FATAL: Internal - No authentication key!" ) );

	if (! $user_full_name )
		return FALSE;

	$todays_SID = date("dMY") . $user_full_name . CT_PRIVATE_KEY ;

	return md5( $todays_SID );
}



FUNCTION create_summary( $project_table ) {

	GLOBAL $xml_array_g, $current_session_g;

	# Present executive QA summary of all issues.

	print "\n<br /><br /><div id='pageTitle'> " . msg("Quality Assurance Executive Summary") . ": <strong>" .
		$current_session_g['project'] . "</strong> " . msg("Activity for past 30 days") . " </div><div class='container'><br />" .
		"<table border=0 width=300 cellpadding=3 cellspacing=2>\n";

	parse_xml_file( CT_XML_BUGS_FILE, CT_DROP_HISTORY, "PROJECT", CT_ASCENDING );
	$bug_table = $xml_array_g;

	$bug_severity = explode(",", CT_BUG_SEVERITIES);
	$bug_status   = explode(",", CT_BUG_STATUSES);

	$stats["change_requests_created"]=0; $stats["change_requests_closed"]=0; $stats["change_requests_deferred"]=0;
	$stats["defect_reports_created"]=0; $stats["defect_reports_closed"]=0; $stats["defects_deferred"]=0;

	$stats["average_lifespan_of_change_requests"]=0;
	$mean_cnt["average_lifespan_of_defects"]=0;

	$stats["average_lifespan_of_defects"]=0;
	$mean_cnt["average_lifespan_of_change_requests"]=0;

	foreach($bug_table as $bug) {

		# Only count those for the current project
		if ($bug["Project"] != $current_session_g["project"])
			continue;

		# Don't count deleted bugs
		if ( isset($bug["Delete_Bug"]) )
			continue;

		# Collect stats on all *new* issues created in last 30 days
		if  ( strtotime($bug["Submit_Time"]) >= strtotime("-30 days") ) {

			if (strstr($bug["Severity"], "Change Req"))
				$stats["change_requests_created"]++ ;
			else
				$stats["defect_reports_created"]++ ;
		}

		# Collect stats on all *updated* issues modified in past 30 days
		if ( strtotime($bug["Last_Updated"]) >= strtotime("-30 days") ) {

			# Focus on Closed issues
			if (strstr($bug["Status"], "Closed")) {
	
				if (strstr($bug["Severity"], "Change Req")) {
			
					$stats["change_requests_closed"]++ ;

					$stats["average_lifespan_of_change_requests"] +=
					  (strtotime($bug["Last_Updated"]) - strtotime($bug["Submit_Time"]) );

					$mean_cnt["average_lifespan_of_change_requests"]++;
				}
				else {

					$stats["defect_reports_closed"]++ ;

					$stats["average_lifespan_of_defects"] +=
					  (strtotime($bug["Last_Updated"]) - strtotime($bug["Submit_Time"]) );

					$mean_cnt["average_lifespan_of_defects"]++;
				}
			}
			# Or focus on Deferred issues
			elseif (strstr($bug["Status"], "Deferred")) {

				if (strstr($bug["Severity"], "Change Req"))
					$stats["change_requests_deferred"]++ ;
				else
					$stats["defects_deferred"]++ ;
			}
		}
	}
	print "<tr><td colspan=2 align=left class='rowEven2'><strong>" . $current_session_g['project'] . "</strong></td></tr>\n";
	$rows=0;

	foreach($stats as $stat=>$value) {

		# Translate each category label
		$stat = msg($stat);

		#print "<pre>$stat:$value</pre>\n";

		#  if ($mean_cnt[$stat] != 0)
		print "<tr><td align=right class='" . ( ($rows % 2) ? "rowOdd" : "rowEven" ) . "'>" .
		  ucwords(strtr($stat, "_", " ")) .
		  "</td><td class='" . ( ($rows++ % 2) ? "rowOdd" : "rowEven" ) .  "'>" .
		  ( (strstr($stat, "lifespan")) ? get_stats($value/$mean_cnt[$stat]) : $value ) .
		  "</td></tr>\n";
	}
 
print "</table>\n</div><br /><br />";
}



FUNCTION create_keyfile() {

 	$fh = fopen("keyfile.inc.php", 'wb')  or
		die_now(msg("FATAL: Could not create private key file! Check that the CodeTrack directory is writable by the Apache owner!"));

	#	Create an alpha xxx-numeric-xxx key (e.g., 'ageadiejskejwizxcvnekwerjweradfwkkj'

#	for($len=14,$r=''; strlen($r)<$len; $r.=chr(!mt_rand(0,2)?mt_rand(48,57):(!mt_rand(0,1)?mt_rand(65,90):mt_rand(97,122))))


	for ($key="", $cnt=0; $cnt < 64; $cnt++)
		$key .= chr(mt_rand(97,122));

	$content = '<?php DEFINE( "CT_PRIVATE_KEY", "' . $key . '" ); ?>' ;

	fwrite($fh, $content) or
		die_now(
			msg("FATAL: Could create, but not write to private key file! Check that the CodeTrack drive is not full.")
		);

}



FUNCTION create_xml_backups() {

	if ( strstr( getcwd(), "\\" ) )  # In Windows, even the root is guaranteed to be: "X:\"
		$slash_style = "\\";     # PHP escape, not a UNC thing...
	else
		$slash_style = "/";

	$filenames = array("bugs.xml", "users.xml", "projects.xml", "permissions.xml");

	foreach ($filenames as $filename) {

	# E.g.:  xml/bugs.xml
	$source   = CT_XML_DIRECTORY . $slash_style . $filename;

	$destination = CT_XML_BACKUPS_DIRECTORY . $slash_style . date("Y-d-m__G-i") . "__$filename" ;
	# E.g:  backups/2008-05-13_14-51_bugs.xml

	if (! copy ( $source, $destination ) )
		die_now( sprintf(msg("FATAL: Unable to create backup file copy 'X' from source file 'Y' ! <br /> Check that the directory is readable and writable by Apache."), $source, $destination) );
	}
}



FUNCTION draw_add_edit_bug_form( $project_table, $user_table, $bug_id="" ) {

	GLOBAL $current_session_g, $debug_g, $xml_array_g;

	$project_cnt = sizeof($project_table);
	$user_cnt    = sizeof($user_table);

	for ($i=0; $i < $user_cnt; $i++) {  # zztop -- this block is redundant to c_s_g[role]?
		if ($user_table[$i]["Full_Name"] == $current_session_g["user_full_name"] ) {
			$user_role = $user_table[$i]["Role"];
			break;
		}
	}

	if (!$user_role)
		die_now( msg("FATAL: No user role found!") );

	if ( $user_role == 'Guest' and ($bug_id) and (!CT_GUESTS_CAN_EDIT) )
		die_now( msg("You are not authorized to EDIT issues.") );

	if ( $user_role == 'Guest' and (!$bug_id) and (!CT_GUESTS_CAN_CREATE) )
		die_now( msg("You are not authorized to create NEW issues.") );

	$browser_name = explode(",", CT_MAJOR_BROWSERS);
	$bug_severity = explode(",", CT_BUG_SEVERITIES);
	$bug_status   = explode(",", CT_BUG_STATUSES);
	$OS_name      = explode(",", CT_MAJOR_OS);
	$developer_response = explode("," , CT_DEVELOPER_RESPONSES);


	# Present bug form

	if ( $bug_id ) {		# We're editing an existing one

		parse_xml_file( CT_XML_BUGS_FILE, CT_DROP_HISTORY, "ID", CT_ASCENDING );
		$bug_array = $xml_array_g;

		$index = get_array_index_for_id( $bug_array, "$bug_id" );
		$bug_to_edit = $bug_array[$index] ;

		print "<div id='pageTitle'> " .
			sprintf( msg("Edit 'X' Issue"), $current_session_g['project'] ) .
			" <a href='codetrack.php?page=viewissue&amp;id=$bug_id'>$bug_id</a></div>\n";

		$prev_id = get_prev_bug_id( $bug_array, $index );
		$next_id = get_next_bug_id( $bug_array, $index );
		draw_prev_next_buttons( "$prev_id", "$next_id", $bug_to_edit, "editissue" );

	}
	else		# Or we're creating a new one
		print "\n\n<div id='pageTitle'> " . msg("Report a New Issue for") . " {$current_session_g['project']} </div>\n";

	print "
	<form enctype='multipart/form-data' id='bF' action='codetrack.php' method='post' onsubmit='return checkMissing(this)' >
	<div id='bugForm'>

	<input name='MAX_FILE_SIZE' type='hidden' value='" . CT_MAX_UPLOAD_FILE_SIZE . "' />
	<input name='page' type='hidden' value='saveissue' />
";
	
	if ($debug_g)
		print "<input name='debug' type='hidden' value='1' />";

	print "
	<div class='innerBorderFix'>
	<table cellpadding='5' cellspacing='4' border='1' width='100%' summary='Issue Report'>
";


	if ( $bug_to_edit ) {

		if ( isset( $bug_to_edit["Updated_By"] ) )
			$txt = "Last updated on";
		else {
			$bug_to_edit["Updated_By"] = $bug_to_edit["Submitted_By"];  # Fix for old bug of non-updated first post
			$txt = "Submitted on";
		}
		$txt .= " 'DATE' by 'PERSON' ";

		# Final header will read something like: "Last updated|Submitted on 1-JAN-2008 by John Adams"

		$header = sprintf( msg($txt), $bug_to_edit['Last_Updated'], $bug_to_edit['Updated_By'] ) ;

		print "\n\t<tr><td colspan='3'> $header \n" .
			"\t<a href='codetrack.php?page=audit&amp;id={$bug_to_edit['ID']}'>[" . msg("History") . "]</a></td></tr>\n";
	}

	print "\t<tr>\n\n\t<td id='addEditBugColumn'> " . msg("Project") . "\n\n\t<select name='bug_data[Project]'>";

	if ($bug_to_edit)
		$p = $bug_to_edit["Project"];
	else
		$p = $current_session_g["project"];

	for ($i=0; $i < $project_cnt; $i++) {

		print "\n\t\t<option value=\"" . $project_table[$i]["Title"] . '"';

		if ( $project_table[$i]["Title"] == $p ) {
			print " selected='selected' ";
			$this_project = $project_table[$i];
		}

		print ">" . $project_table[$i]["Title"] . "</option>" ;
	}

	print "\n\t</select></td>

	<td> " . msg("Module or Screen Name") . "
	<input name='bug_data[Module]' type='text' size='25' maxlength='25' ";

	# HTML-escape single quotes

	if ($bug_to_edit)
		print " value='" . ereg_replace( "'", "&#039", $bug_to_edit["Module"] ) . "' ";

	print " /></td>\n\n\t<td>" .
		msg("Version") . "\t<input name='bug_data[Version]' type='text' size='10' maxlength='12' " .
		(($bug_to_edit) ? (" value='" . ereg_replace( "'", "&#039", $bug_to_edit["Version"] ) . "' ") : "" ) .
	" /></td>
	</tr>\n
	<tr>
	<td>" . msg("Severity") . "*
	<select name='bug_data[Severity]'>
	";

	print "	<option value=''></option>\n";

	foreach ($bug_severity as $severity) {
		if ( (!$bug_to_edit and $severity == CT_DEFAULT_SEVERITY) or
		 ($bug_to_edit["Severity"] == $severity) )
			print "\n\t\t<option value='$severity' selected='selected'>" . msg($severity) . "</option>";
		else
			print "\n\t\t<option value='$severity'>" . msg($severity) . "</option>";
	}

	print "

	</select>
	</td>

	<td colspan='2'>" . msg("Title* <em>(e.g., &quot;BSOD on save&quot;)</em>") . "
	<input name='bug_data[Summary]' type='text' size='40' maxlength='55' ";

	if ($bug_to_edit)
		print " value='" . ereg_replace( "'", "&#039", $bug_to_edit["Summary"] ) . "' ";

	print " /></td>
	</tr>
	<tr>
		<td colspan='3'>" . msg("Full Description* <em> (the more details the better!)</em>") . "
		<textarea name='bug_data[Description]' rows='7' cols='50' ".
		" " . CT_OPTIONAL_UGLY_NN4_HACK . " > \n";

	if ($bug_to_edit)
		print $bug_to_edit["Description"];

	print "</textarea></td>
	</tr>
	<tr>
	<td colspan='";

	if ($bug_to_edit)
		print "2'>" . msg("Attachment: ") ;
	else
		print "3'>" . msg("Attachment <em>(screen print, data file, etc.)</em>") ;

	# If an attachment already exists, show link, otherwise present file upload button

	if ( isset($bug_to_edit["Attachment"]) ) {
		print "&nbsp;<span class='txtSpecial'>" .
			"<a href='attachments/" . $bug_to_edit["Attachment"] . "' " .
			"onclick=\"this.target='_blank';\" >" .
			$bug_to_edit["Attachment"] . "</a></span>".
			"<input name='bug_data[Attachment]' type='hidden' value='".
			$bug_to_edit["Attachment"] . "' />";
	}
	else {
		print "<input name='Attachment' type='file' size='40' />\n";
	}

	print "\n\t</td>\n";
	
		if ($bug_to_edit) {

		# A little complicated: If [some user role] is explictly blocked, and this user is not in that category...

		if ( (CT_QA_ENFORCE_PRIVS) and ( !in_array($current_session_g["role"], explode(',' , CT_QA_WHO_CAN_CLOSE))) ) {
			print "\n<td><span class='txtSpecial'> [ ] </span> " . msg("Delete Report") . " </td>";
		}
		else {

			#	Single ticks are required to embed js literal newlines

			print "\n<td><span class='bugFormGroup'>" .
				"\n<input type='checkbox' name='bug_data[Delete_Bug]' value='Y' " .
				"onclick='if (this.checked) " .
				"return confirm(\"" .
				msg("Checking the Delete box will permanently erase this report.") . 
				'\n' .
				msg("If you really want to delete this report, click OK then press Save.") .
				'\n' .
				msg("To simply close the issue, cancel now and change the Status category.") .
				"\");' />&nbsp; " .
				msg("Delete Report") .
				" </span></td>";
		}
	}

	print "\n\t</tr>\n\n";


	if ( $this_project["Project_Type"] == "Web-Based" ) {

		print"
	<tr>
	<td colspan='2'> " .
	msg("Tested Browser") .
	"  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  " .
	msg("Browser Specific?") .
	"<br />
	<span class='bugFormGroup'>

	<select name='bug_data[Tested_Browser]'>
	";

   foreach ($browser_name as $browser) {

    if ( ($bug_to_edit["Tested_Browser"] == $browser) or
     (!$bug_to_edit and $browser == CT_DEFAULT_BROWSER) )
      print "\t\t\t\t<option value='$browser' selected='selected'>$browser</option>\n";
    else
     print "\t\t\t\t<option value='$browser'>$browser</option>\n";
   }
?>
   </select>

   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

   <input type='radio' name='bug_data[Browser_Specific]' value='Y'
<?php if ($bug_to_edit["Browser_Specific"] == 'Y') print " checked='checked' "; ?> />Y &nbsp;
   <input type='radio' name='bug_data[Browser_Specific]' value='N'
<?php if ($bug_to_edit["Browser_Specific"] == 'N') print " checked='checked' "; ?> />N &nbsp;
   <input type='radio' name='bug_data[Browser_Specific]' value=''
<?php if ($bug_to_edit["Browser_Specific"] == '')
 print " checked='checked' ";
  else if (!$bug_to_edit)
 print " checked='checked' ";
?> />D/K
   </span>
  </td>
  <td>
<?php

	print "\n\t" . msg("Tested OS") . "\n".
		"<select name='bug_data[Tested_OS]'> \n";

   foreach ($OS_name as $OS) {

    if ( (!$bug_to_edit and $OS == CT_DEFAULT_OS) or
     ($bug_to_edit["Tested_OS"] == $OS) )
     print "<option value='$OS' selected='selected'>$OS</option> \n";
    else
     print "<option value='$OS'>$OS</option> \n";
   }
?>

  </select>
  </td>
 </tr>

<?php

 }  # End of Web-Based Project Check

?>

 <tr>
  <td>
<?php
	if ($bug_to_edit)
		$submitter = $bug_to_edit["Submitted_By"];
	else
		$submitter = $current_session_g["user_full_name"];

	print msg("Submitted By") .
		" <div class='txtSpecial'> &nbsp;&nbsp; $submitter" .
		" &nbsp;</div>" .
		"<input name='bug_data[Submitted_By]' type='hidden' value='" .
		$submitter . "' />";

	print "
	</td>
	<td><div id='addEditActionButtons' class='bugFormGroup'>
	<input type='submit' value=' ". msg("Save")  .  " ' />
	<input type='button' value='" . msg("Cancel") . "' onclick=\"location.replace('codetrack.php?page=home');\" />
	<input type='reset' value=' " . msg("Undo")  .  "' />
	</div></td> \n
	<td> \n";

	if ($bug_to_edit) {

		# A little complicated: If [some user role] is explictly blocked, and this user is not in that category...

		if ( (CT_QA_ENFORCE_PRIVS) and ( !in_array($current_session_g["role"], explode(',' , CT_QA_WHO_CAN_CLOSE))) ) {

			print msg("Status") . ": <div class='txtSpecial'>&nbsp; " . $bug_to_edit["Status"]  . "</div>" .
				"<input name='bug_data[Status]' type='hidden' value='" . $bug_to_edit["Status"] . "' />\n";
		}
		else {
	
			print msg("Status") . "\n<select name='bug_data[Status]'>\n";

			foreach ($bug_status as $status) {

				print "\t<option value='$status' ";

				if ( $bug_to_edit["Status"] == $status )
					print " selected='selected' ";

				print ">" . msg($status) . "</option>\n";
			}
			print "\n</select>\n";
		}
	}
	else
		print msg("Status") . ": <div class='txtSpecial'>&nbsp; " . msg("Open") . "</div>" .
			"<input name='bug_data[Status]' type='hidden' value='Open' />\n";
?>
  </td>
 </tr>
<?php
 if ($bug_to_edit) {
?>

 <tr>
  <td colspan="2">


  <?php  print $this_project["Preferred_Title"] . " Comment \n"; ?>
   <textarea name="bug_data[Developer_Comment]" rows="4" cols="50"
  <?php

   print CT_OPTIONAL_UGLY_NN4_HACK . " >";

   if ($bug_to_edit) {
    $comment = ereg_replace("__", "\r\n", $bug_to_edit["Developer_Comment"]);
    print "$comment";
   }

  ?></textarea>
  </td>
  <td>

  <?php  print $this_project["Preferred_Title"] . " Response \n"; ?>

   <select name='bug_data[Developer_Response]'>
    <?php
    foreach ($developer_response as $response) {
      if ( (!$bug_to_edit and $response == CT_DEFAULT_DEVELOPER_RESPONSE) or
         ($bug_to_edit["Developer_Response"] == $response) )
      print "<option value=\"$response\" selected='selected'>$response</option> \n";
     else
      print "<option value=\"$response\">$response</option> \n";
    }
   ?></select>
  </td>
 </tr>

<?php

 }  # End of Edited Bug-specific widgets

 print "<tr>\n\t\t<td colspan='3'>\n\t\tAssign To: \n<div class='bugFormGroup'>";
 print "<select name='bug_data[Assign_To]'><option value=''>&nbsp;</option>";

 if ($bug_to_edit)
  $assignee = $bug_to_edit["Assign_To"];

 for ($i=0; $i < $user_cnt; $i++) {
  print "<option value='"             .
    $user_table[$i]["Full_Name"] .
    ( ( $user_table[$i]["Full_Name"] == $assignee ) ? "' selected='selected' " : "'" ) .
    ">" . $user_table[$i]["Full_Name"] . "</option>\n" ;
 }
 print "</select>";


 if ( CT_ENABLE_EMAIL ) {

  print "<label title='Send an email to the Assignee'><input type='checkbox' name='send_mail' value='1' /><img ".
    "src='images/email.gif' \n\t\t onclick='send_mail.checked=!send_mail.checked' ".
    "alt='email' /></label> \n";

  print "<label title='Send an email to these people too'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; cc: ";
  print "<select name='cc_list[]' multiple='multiple' size='3'>";

  for ($i=0; $i < $user_cnt; $i++) {
   print "<option value=\"{$user_table[$i]["Email"]}\">" .
     $user_table[$i]["Full_Name"] . "</option>\n" ;
  }
  print "</select></label>\n";
 }

?>

  </div></td>
 </tr>
 </table>
 </div>
<?php
  if ($bug_to_edit) {
   print "<input name='id' type='hidden' value='" . $bug_to_edit["ID"] . "' />\n" ;
   print "<input name='original_submit_time' type='hidden' value ='" . $bug_to_edit["Submit_Time"] . "' />\n" ;
  }
?>
 </div>
 </form>
 <script type="text/javascript" src="javascript/bugform_prevalidate.js"> </script>
 <script type="text/javascript" src="javascript/form_validate.js"> </script>

<?php
}



FUNCTION die_now( $msg ) {

	die( "\n<br /><div class='sysMsg'> $msg </div> \n");

}

/* tess */

FUNCTION draw_admin_page() {

	DEFINE( "CT_EXTERNAL_LINK", TRUE );

	print "
 <div id='pageTitle'> " . msg("Administrator Tools") . " </div><br />
 <div style='margin-left: 20%; margin-top: 0px; margin-bottom: 0px;'>
 <hr class='hrBlack' /> \n\n";

	$link_list = array(
		'addproject' => 'Add a Project',
		'adduser' => 'Add a User',
		'changepassword' => 'Change a Password',
		'export' => 'Export Data',
		'backup'  =>  'Backup XML Databases',
		'users' => 'List Active Users',
		'projectaccess' => 'Set Permissions',
		'deletedissues' => 'List Deleted Issues'
	);
	print build_static_links( $link_list, "Maintenance" );


	$link_list = array(
		'docs/INSTALL.txt' => 'Installation Guide' ,
		'docs/CUSTOMIZING.txt' => 'Customizing CodeTrack' ,
		'docs/TROUBLESHOOTING.txt' => 'Troubleshooting Guide' ,
		'docs/CHANGELOG.txt' => 'CodeTrack ' . CT_CODE_VERSION . ' Changelog' ,
		'docs/FUNCTIONS.txt' => 'Functions Reference' ,
		'docs/FILES.txt' => 'Inventory of all CodeTrack Files'
	);
	print build_static_links( $link_list, "Technical References", CT_EXTERNAL_LINK );


	$link_list = array(
		'docs/LICENSE.txt' => 'CodeTrack is Free software',
		'docs/GPL_FAQ.html' => 'FAQ about the GNU Public License (GPL)'
	);
	print build_static_links( $link_list, "License", CT_EXTERNAL_LINK );


	print "<br />
  <strong>Support questions? <a href='mailto:codetrack@openbugs.org?subject=CodeTrack Support'>Write us</a>
 we&apos;re happy to help!</strong>
	<br /><br /><i>Copyright &copy; 2001-2008 Kenneth White</i>
 </div>
";

}



FUNCTION draw_export_options( $current_project ) {

 ?>
 <div id='pageTitle'> Data Export Wizard </div><br />
 <form action='codetrack.php' method='post'>
 <div style='font-family: verdana, sans-serif; font-size: 10pt;'>
 <div style='margin-left: 15%; margin-top: 0px; margin-bottom: 0px;'>
 <input type='hidden' name='page' value='export' />
 File Format: <br />
 <em>
  &nbsp;&nbsp;<input type='radio' name='export_options[file_format]' value='csv' checked='checked' /> Excel file (CSV)<br />
  &nbsp;&nbsp;<input type='radio' name='export_options[file_format]' value='prn' /> Tab-separated text file <br />
  &nbsp;&nbsp;<input type='radio' name='export_options[file_format]' value='sql' /> SQL Import file <br />
  &nbsp;&nbsp;<input type='radio' name='export_options[file_format]' value='xml_download' /> XML file <br />
  &nbsp;&nbsp;<input type='radio' name='export_options[file_format]' value='xml' /> XML tree browser <br />
 </em><br />
 History: <br />
 <em>
  &nbsp;&nbsp;<input type='radio' name='export_options[show_history]' value='1' /> Show all changes for each issue <br />
  &nbsp;&nbsp;<input type='radio' name='export_options[show_history]' value='0' checked='checked' /> Only show the current status of each issue <br />
 </em><br />
 Sort issues by: <br />
 <em>&nbsp;&nbsp;<input type='radio' name='export_options[sort_type]' value='descending' checked='checked' />
   Oldest first (Ascending by ID)<br />
  &nbsp;&nbsp;<input type='radio' name='export_options[sort_type]' value='ascending' /> Newest first <br />
 </em><br />
 <input type='checkbox' name='export_options[project_filter]' value='<?php echo $current_project; ?>' checked='checked' />
 Restrict export to <strong><?php echo $current_project; ?></strong> issues <br />
 <input type='checkbox' name='export_options[show_deleted]' value='y' /> Include deleted issues in export <br /><br />
 <input type='submit' value="Download" />
 <br /><br /><em>Note: History and Sort options do not apply to XML exports</em>
 </div>
 </div>
 </form>
 <?php
}



FUNCTION draw_login_page( $project_table, $failed="", $session_expired="", $no_cookies="" ) {

 no_cache();
 draw_page_header();

 $project_cnt = sizeof($project_table);

?>
<form id="loginForm" action="codetrack.php" method="post" onsubmit="return validateLogin(this);" >
<div id="login">
 <h1>HerbIgniter Bug Tracker</h1>

 <div class="loginLabel"> Project </div>
 <div class="loginDD">
  <select name="userLogin[project_name]">
<?php
  for ($i=0; $i < $project_cnt; $i++)
   print "\t\t<option value='". $project_table[$i]["Title"] ."'" .
   ((CT_DEFAULT_PROJECT == $project_table[$i]["Title"]) ? " selected='selected' " : '' ) .
   ">". $project_table[$i]["Title"] ."</option>\n";
?>
  </select>
 </div>

 <div class="loginLabel"> Username </div>
 <input type="text" name="userLogin[username]" value="guest" />

 <div class="loginLabel"> Password </div>
 <input type="password" name="userLogin[password]" value="guest" />

 <div class="loginButton"><input type="submit" value="Login as guest" /></div>

 <input name="page" type="hidden" value="login" />

</div>
</form>

<a href="http://herbigniter.com/">Back to HerbIgniter</a>

<?php
 if ( $failed )
  print "<div class='loginMsg'><h1>Your login failed.</h1> Please enter a correct username and password or use the username <i>guest</i> password 'guest'.</div> \n";

 if ( $session_expired )
  print "<div class='loginMsg'><h2>Your session has expired.</h2> Please log in again.</div> \n";

 if ( $no_cookies )
  print "<div class='loginMsg'><h1>Login unsuccessful.</h1> " .
    "CodeTrack requires session cookies. Please ensure they are not being blocked.</div> \n";
?>

<script type="text/javascript" src="javascript/login_validate.js"> </script>

<noscript>
 <div class="cfgProb"> CodeTrack is pretty broken without Javascript. Please turn it on. </div>
</noscript>

<div class="cfgChkAccessCSS"> If you can see this, there are permission problems on the <em>style</em> directory. </div>
<div class="cfgChkAccessImg"> If you can see this, there are permission problems on the <em>images</em> directory. </div>
<div class="cfgChkAccessJs"> If you can see this, there are permission problems on the <em>javascript</em> directory. </div>

</body></html>

<?php
}



FUNCTION draw_page_bottom() {

 print "\n</div><!-- End bodyFrame -->\n</body>\n</html>";

}



FUNCTION draw_page_header() {

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
 <meta http-equiv="Refresh" content="<?php print CT_DEFAULT_PAGE_TIMEOUT ; ?>;URL=codetrack.php?page=timeout" />
 <title>CodeTrack: Bug and Defect Reporting <?php print CT_CODE_VERSION; ?> </title>
 <link rel="stylesheet" href="style/codetrack.css" type="text/css" />
 <link rel="stylesheet" href="images/cfgChkAccess.css" type="text/css" />
 <link rel="stylesheet" href="javascript/cfgChkAccess.css" type="text/css" />
 <link rel="stylesheet" href="style/codetrack_w3c.css" type="text/css" media="all" />
 </head>
 <body>

<?php
}



FUNCTION draw_page_top( $this_page ) {

 #
 #  Top navigation bar for CodeTrack.  Do a few W3C-legal tricks with span colors on current page, and
 #  (optionally) show balloon tooltips over links.
 # Unless we're debugging, don't cache any rendered (vs. form processing/redirect) page, and autologout after
 # CT_DEFAULT_PAGE_TIMOUT seconds (def. is 8 hours).  Logout is proactively forced via refresh head meta directive.
 #

 GLOBAL $debug_g, $current_session_g, $query_string_g;  # Latter is Apache server variable

 if ( !$debug_g )
  no_cache();

 draw_page_header();

?>
<div id="bodyFram">

<div id="navBar">
 <a href='codetrack.php?page=home' title='Summary of your current project'
 <?php if ($this_page=='home') print ' id="navCurrent"'; ?>>Home</a>

 <a href='codetrack.php?page=newIssue' title='Create a new defect report or Change Request'
 <?php if ($this_page=='newIssue') print ' id="navCurrent"'; ?> >New Issue</a>

 <a href='codetrack.php?page=reports' title='Create simple and advanced reports'
 <?php if ($this_page=='reports') print ' id="navCurrent"'; ?>>Reports</a>

 <a href='codetrack.php?page=projects' title='List of active projects'
 <?php if ($this_page=='projects') print ' id="navCurrent"'; ?>>Projects</a>

<?php
 if ( $current_session_g["role"] == "Admin" )
  print "\t<a href='codetrack.php?page=adminLinks' " . (($this_page=='adminLinks') ? 'id="navCurrent" ' : '' ).
    "title='CodeTrack Administration and Setup'>Admin</a> \n\n";
 else
  print "\t<a href='codetrack.php?page=tools' " . (($this_page=='tools') ? 'id="navCurrent" ' : '' ).
    "title='CodeTrack system tools'>Tools</a> \n\n";
?>
 <a href="javascript:this.print();" title="Printer friendly version of this page">Print</a>

 <a href="docs/help.html#home" title="Need help with this (or any) screen?" onclick='this.target="_blank";'>Help</a>

 <a href="codetrack.php?page=logout&amp;origin=user" title="Log off the CodeTrack System">Logout</a>
</div>

<hr class="legacyDivider" />

<noscript>
 <div class="cfgProb"> CodeTrack is pretty broken without Javascript. Please turn it back on. </div>
</noscript>

<?php
}



FUNCTION draw_prev_next_buttons( $prev_id, $next_id, $bug_to_edit, $destination_page, $extra_buttons=FALSE ) {

 #  Draw next/previous issue navigation buttons (seen on viewissue and editissue)

 $id = "{$bug_to_edit['ID']}";  # Force string promotion

 if ($prev_id == $id )  {
  $prev_color  = 'buttonTiny';
  $prev_widget = 'button';  # Grey non-working button
 }
 else {
  $prev_color  = 'buttonTiny';
  $prev_widget = 'submit';
 }

 if ($next_id == $id )  {
  $next_color  = 'buttonTiny';
  $next_widget = 'button';  # Grey non-working button
 }
 else {
  $next_color  = 'buttonTiny';  # Should be buttonWhite
  $next_widget = 'submit';
 }

 if (!$extra_buttons)    # zztop not optimal
  print "<div style='text-align: center;'>\n";

?><div class='subNav'><form action='codetrack.php' method='get'><div class='subNavBar'>
   <input type='hidden' name='page' value='<?php print $destination_page; ?>' />
   <input type='hidden' name='id'   value='<?php print "$prev_id"; ?>' />
   <input type='<?php print $prev_widget; ?>' value='&lt; Prev' class='<?php print $prev_color; ?>' />
 </div></form>
<?php
 if ($extra_buttons) {
  ?>
  <form action='codetrack.php' method='get'><div class='subNavBar'>
   <input type='hidden' name='page' value='editissue' />
   <input type='hidden' name='id'   value='<?php print $id; ?>' />
   <input type='submit' value='  Edit  ' class='buttonTiny' /></div></form>

  <form action='codetrack.php' method='get'><div class='subNavBar'>
   <input type='hidden' name='page' value='audit' />
   <input type='hidden' name='id'   value='<?php print $id; ?>' />
   <input type='submit' value='History' class='buttonTiny' /></div></form>
  <?php
 }

?>
 <form action='codetrack.php' method='get'><div class='subNavBar'>
  <input type='hidden' name='page' value='<?php print $destination_page; ?>' />
  <input type='hidden' name='id'   value='<?php print $next_id; ?>' />
  <input type='<?php print $next_widget; ?>' value='Next &gt;' class='<?php print $next_color; ?>' />
 </div></form></div>
<?php

 if (!$extra_buttons)  # zztop not optimal
  print "</div>\n";
}



FUNCTION draw_project_hotlist_widget( $project_table, $current_project, $destination ) {

 GLOBAL $debug_g;

 if ($debug_g) { print "<pre>" ; var_dump($project_table); var_dump($current_project); var_dump($destination); print "</pre>"; }

?>

<form id="f" class="dropDown" action="codetrack.php" method="get">
<div>

<input type="hidden" name="page" value="changeproject" />
<input type="hidden" name="redir" value="<?php  print $destination;  ?>" />

<select name="project"
 onchange='if (this.options[selectedIndex].value != "") document.forms[0].submit();' class="dropDown"
 <?php

 if ( CT_ENABLE_TOOLTIPS )
  print ' title="Change your current project"';

 print ">\n";

 $project_cnt = sizeof($project_table);

 for ($i=0; $i < $project_cnt; $i++) {
  print "\t<option value=\"" . $project_table[$i]["Title"] . '"' ; #zztop -- had urldecode here (new proj seems to scrub...)

  if ($current_project == $project_table[$i]["Title"])
   print " selected='selected'";

  print ">{$project_table[$i]["Title"]}</option>\n";
 }
 print "</select>\n";

 print "<input id='goButton' type='submit' value='Go' />\n</div>\n</form>\n\n";
}



FUNCTION draw_project_access_form( $project_table, $user_table, $permission_table ) {

 #print "<pre>"; var_dump($permission_table); print "</pre>";

 GLOBAL $current_session_g;
 $current_project = $current_session_g["project"];

 print '<script type="text/javascript" src="javascript/form_validate.js"> </script>';

 print "<div id='pageTitle'> Select Authorized Users for: &nbsp;$current_project </div><br />\n";
 draw_project_hotlist_widget($project_table, $current_project, "projectaccess");

 for ($i=0; $i < sizeof($project_table); $i++) {
  if ($project_table[$i]["Title"] == $current_project) {
   $description = $project_table[$i]["Description"];
   $this_project_id = $project_table[$i]["ID"];
   break;
  }
 }
 #print "<br /><span class='txtSmall'> Project $this_project_id Description: $description </span><br />\n";

?>
<form id="maintenance" action="codetrack.php" method="post" style="margin-top: 0px; margin-left:0; margin-bottom: 0px;">
 <input type="hidden" name="page" value="savepermissions" />
 <input type="hidden" name="permission_data[Project_ID]" value="<?php print $this_project_id; ?>" />
 <table cellspacing='0' cellpadding='0' border='1' style="margin-top: 0px; margin-left:0; margin-bottom: 5px;" summary="Permissions">
 <tr class='rowHeader'>
  <td style='text-align: center;' width='10'><input type='checkbox' name='toggleBoxes' onclick='toggle_checkboxes(document.forms["maintenance"]);' /></td>
  <td style='text-align: left;'><strong> Authorized User &nbsp;&nbsp;</strong></td>
  <td style='text-align: left;'><strong> Role &nbsp;&nbsp;</strong></td>
 </tr>
<?php

 $row = 0;

 foreach ( $user_table as $user ) {       # Traverse all users

  $checked = '';

  if ($user["Role"] == "Admin") {
   $widget_type = 'hidden';       # Admins have access to all projects
   $extra = '[<strong>x</strong>]' ;
  }
  else {

   $widget_type = 'checkbox';
   $extra = '';

   if ( authorized_user( $this_project_id, $user["ID"], $permission_table ) )
    $checked = ' checked="checked" ';        # If user is listed, they've already got access
  }

  if ($row++ % 2)
   $row_color = 'rowEven2';
  else
   $row_color = 'rowOdd';

  print "\n<tr>\n\t<td class='$row_color' align='center' height='22'>" .
    "<input type='$widget_type' name='permission_data[User_ID][$row]' " .
    "value='" . $user["ID"] . "' $checked >$extra" . "</td>\n" .
    "\t<td class='$row_color' > &nbsp; " .
    $user["Last_Name"] . ", " . $user["First_Name"] . "&nbsp;&nbsp;</td>\n" .
    "\t<td class='$row_color' >&nbsp; {$user['Role']} &nbsp;</td>\n</tr>" ;
 }
 print "\n</table>\n<input type='submit' value='Save' />\n</form>\n\n";
}



FUNCTION draw_read_only_table( $data_table ) {

	#print "<pre>"; var_dump($data_table); exit;

	print "\n<table border='1' cellspacing='1' cellpadding='3' width='600' summary='Data table'>\n";

	$rowcnt = 0;

	foreach ($data_table as $column_name => $value) {

		print "<tr class='" . ( ($rowcnt++ % 2 ) ? 'rowEven' : 'rowOdd' ) . "'>" .
			"<td class='readOnlyLabel'>" . strtr($column_name, "_", " ") . "</td>" ;

		if ($column_name == 'Attachment')
			$value = "<a href='attachments/$value' onclick=\"this.target='_blank';\" >$value</a>";

		print "<td class='readOnlyData'>" . nl2br($value) . " &nbsp;</td></tr>\n";
	}
	print "</table>\n";
}



FUNCTION draw_reports_page( $project_table, $user_table ) {

 GLOBAL $current_session_g;

 $current_project = $current_session_g["project"];
 $user_full_name  = $current_session_g["user_full_name"];
 ?>
 <div id='pageTitle'> Search and Reports Wizard </div>
 <div style='margin-left: 10%;'>
 <br /><hr class='hrBlack' /><br />

  Quick Reports
  <ul>
   <?php

   $link = "codetrack.php?page=filter" .
       "&amp;filter[Status]=Open" .
       "&amp;filter[Assign_To]=" . urlencode($user_full_name);
   print "<li><a href='$link' >All open issues assigned to me</a></li>\n";


   $link = "codetrack.php?page=filter" .
       "&amp;filter[Project]=" . urlencode($current_project) .
       "&amp;filter[Status]=Open" .
       "&amp;filter[Assign_To]=" . urlencode($user_full_name);
   print "<li><a href='$link' >Open <strong>$current_project</strong> issues assigned to me</a></li>\n";


   $link = "codetrack.php?page=filter" .
       "&amp;filter[Project]=" . urlencode($current_project) .
       "&amp;filter[Submitted_By]=" . urlencode($user_full_name);
   print "<li><a href='$link' ><strong>$current_project</strong> issues that I created</a></li>\n";

   print "<li><a href='codetrack.php?page=summary'>Quality Assurance Statistics for <strong>$current_project</strong></a></li>\n";

   ?>
  </ul>

 <br /><hr class='hrBlack' /><br />

 Custom Reports <br /><br />

 <div style='margin-left: 3%; margin-top: 0px; margin-bottom: 0px;'>
 <form action="codetrack.php" method="get"> <!-- Need to GET not POST for rational back behavior -->
 <table border='0' summary="">
  <tr class='txtSmall'>
   <td>Project</td>
   <td>Severity</td>
   <td>Status</td>
   <td>Submitted By</td>
   <td>Assigned To</td>
   <td> &nbsp; </td>
  </tr>
  <tr>
   <td>
    <select name='filter[Project]'>
     <option value=''>(All)</option>
  <?php
   for ($i=0; $i < sizeof($project_table); $i++) {
    print "\t\t\t<option value=\"" . $project_table[$i]["Title"] . "\">" .
     $project_table[$i]["Title"] . "</option>\n";
   }
   print "\t\t</select>\n\t\t</td>\n\t\t<td>\n";

   $bug_severity = explode(",", CT_BUG_SEVERITIES);

   print "<select name='filter[Severity]'>\n\t\t<option value=''>(All)</option>\n";
   foreach( $bug_severity as $severity )
				print "\t\t\t<option value=\"$severity\">$severity</option>\n";
			print "\t</select>\n\n\t\t</td>\n\t\t<td>\n";


			$bug_status = explode(",", CT_BUG_STATUSES);
			print "<select name='filter[Status]'>\n\t\t<option value=''>(All)</option>\n";

			foreach( $bug_status as $status )
				print "\t\t\t<option value=\"$status\">$status</option>\n";

			print "\t</select></td>\n\n";


			print "\t\t<td>\n\t\t<select name='filter[Submitted_By]'>\n\t\t".
     "<option value=''>(Anyone)</option>\n\t\t";

   for ($i=0; $i < sizeof($user_table); $i++) {
    print "\t\t\t<option value=\"{$user_table[$i]["Full_Name"]}\">" .
    $user_table[$i]["Full_Name"] . "</option>\n";
   }
 ?>
    </select>
   </td>
   <td>
    <input type='hidden' name='page' value='filter' />
    <select name='filter[Assign_To]'>
     <option value=''>(Anyone)</option>
     <?php
     for ($i=0; $i < sizeof($user_table); $i++) {
      print "\t\t\t<option value=\"{$user_table[$i]["Full_Name"]}\">" .
      $user_table[$i]["Full_Name"] . "</option>\n";
     }
     ?>
    </select>
   </td>
   <td>&nbsp;<input type='submit' value='Go!' /> <br /></td>
  </tr>
 </table>
 </form>
 </div>

 <br /><br /><hr class='hrBlack' /><br />

 Full Text Search 

 <div style='margin-left: 3%; margin-top: 0px; margin-bottom: 0px;'>
 <form action="codetrack.php" method="get"> <!-- Need to GET not POST for rational back behavior -->
<p>
  <input type='hidden' name='page' value='searchissue' />
  <input type='text' size='50' name='pattern' />&nbsp;
  <input type='submit' value='Go!' /> <br /><br />
 Look for:
		<input type='radio' name='search_options[phrase]'         value='0' checked='checked' />Any word &nbsp;&nbsp;&nbsp;
		<input type='radio' name='search_options[phrase]'         value='1' />Entire Phrase <br />
	Within: &nbsp;&nbsp;
		<input type='checkbox' name='search_options[summary]'     value='1' checked='checked' />Summaries &nbsp;
		<input type='checkbox' name='search_options[description]' value='1' checked='checked' />Descriptions &nbsp;
		<input type='checkbox' name='search_options[comment]'     value='1' checked='checked' />Comments <br />
</p>
	</form>
	</div>

	</div>
	<?php
}



FUNCTION draw_change_password_form( $user_table ) {

	GLOBAL $current_session_g, $debug_g;

	print '<script type="text/javascript" src="javascript/form_validate.js"> </script>' . "\n" .
			'<div class="container"><div class="txtSmall"><br /><strong>  Password Update  </strong><br /><br /><br /></div>' . "\n"  .
			'<form id="passwordForm" action="codetrack.php" method="post" '.
			"onsubmit='return checkPasswords(this, " . CT_MIN_PASSWORD_LENGTH . ");' >" ;

	if ($debug_g)
		print "\t<input type='hidden' name='debug' value='1' />\n";

	if ( $current_session_g["role"] == 'Admin' ) {		#	Admins can change anyone's password...

		$admin = TRUE;

		$widget_label = '&nbsp; User to update <br />&nbsp;';

		$widget = "<select name='user_data[ID]'> \n";

		foreach( $user_table as $user )
			$widget .= "\t\t <option value='" . $user["ID"] . "'>" . $user["Full_Name"] . "</option> \n";

		$widget .= "\t\t </select><br />&nbsp;\n";
	}
	else {												#	But users can only change their own

		$admin = FALSE;

		$widget_label = 'Old Password<br />&nbsp; &nbsp;';

		foreach ($user_table as $user) {
			if ( $user["Username"] == $current_session_g["username"] ) {
				$id = $user["ID"];
				break;
			}
		}
		$widget = "<input type='hidden' name='user_data[ID]' value='$id' />\n" .
					 "<input type='password' name='old_pw' maxlength='25' /><br />&nbsp; &nbsp;";
	}
?>

	<table border='0' cellpadding='5' cellspacing='0' class='quickForm' summary="Password Update">
		<tr>
			<td class='txtSmall' align='right'><?php print $widget_label; ?></td>
			<td><?php print $widget; ?></td>
		</tr>
		<tr>
			<td class='txtSmall' align='right'>New Password</td>
			<td><input type='password' name="user_data[Password]" maxlength='25' /></td>
		</tr>
		<tr>
			<td class='txtSmall' align='right'>Confirmation</td>
			<td><input type='password' name="retyped_pw" maxlength='25' /></td>
		</tr>
		<tr>
			<td align='left'><input type='submit' value='Save' /></td>
			<td align='right'>
				<input type='button' value='Cancel' onclick='location.href="codetrack.php?page=home";' />
				<input type='hidden' name='page' value='savepassword' />
			</td>
		</tr>
	</table>
	</form>
	</div>
<?php
	if ($admin)
		print	"\t<script type='text/javascript'> document.forms['passwordForm'].elements['user_data[Password]'].focus(); </script>\n";
	else
		print	"\t<script type='text/javascript'> document.forms['passwordForm'].elements['old_pw'].focus(); </script>\n";
}


FUNCTION draw_user_maintenance_form( $user = NULL ) {

	GLOBAL $debug_g;

	if ($user) {
		$title = 'Edit User Information';
		$action = 'edit';
	}
	else {
		$title = 'Add a New User';
		$action = 'add';
	}

	if ($debug_g) {
		print "<pre>"; var_dump($user); print "</pre> \n";
	}

	$roles = explode(",", CT_ACCESS_LEVELS);

	print "

	<div class='container'>
	<div class='txtSmall'><br /><strong> $title </strong><br /><br /><br /></div>

	<form id='userForm' action='codetrack.php' method='post'
		onsubmit=\"return checkMissing(this) ";

	if (CT_PEDANTIC_VALIDATION)
		print " &amp;&amp; validEmail(this['user_data[Email]'].value) ";

	print ';"';

	# CREATE A DUMMY ARRAY TO POPULATE FOR NEW USERS (otherwise, will fill in from
	# passed in $user, populated from the XML database)

	if ( !$user ) {
		$user["ID"] = -1;
		$user["First_Name"] = '';
		$user["Last_Name"] = '';
		$user["Email"] = '';
		$user["Phone"] = '';
		$user["Role"] = '';
		$user["Username"] = '';
		$user["Password"] ='';
	}

	print "

	<table border='2' cellpadding='7' cellspacing='0' class='quickForm' summary='User Maintenance'>
		<tr>
			<td class='txtSmall'>
				<input name='page' type='hidden' value='saveuser' />
				<input name='user_data[ID]' type='hidden' value='-1' />
				First Name* <br />
				<input value='{$user['First_Name']}' name='user_data[First_Name]' type='text' size='20' maxlength='25' />
			</td>
			<td class='txtSmall'>
				Last Name*  <br />
				<input value='{$user['Last_Name']}' name='user_data[Last_Name]' type='text' size='20' maxlength='25' />

				<input name='user_data[Full_Name]' type='hidden' value='x' />
				<input name='user_data[Initials]'  type='hidden' value='x' />
			</td>
		</tr>
		<tr>
			<td class='txtSmall' colspan='2'>
				email address*<br />
				<input value='{$user['Email']}' name='user_data[Email]' type='text' size='30' maxlength='60' />
			</td>
		</tr>
		<tr>
			<td class='txtSmall' colspan='2'>
				Phone Number<br />
				<input value='{$user['Phone']}' name='user_data[Phone]' type='text' size='20' maxlength='32' />
			</td>
		</tr>
		<tr>
			<td class='txtSmall' colspan='2'>Role<br />
				<select name='user_data[Role]'>
";

	foreach ($roles as $role) {

		if ($action == 'edit') {
			if ( $role == $user['Role'] )
				print "<option value='$role' selected='selected' > $role </option>\n";
			else
				print "<option value='$role'>$role</option>\n";
		}
		else {
			if ( $role == CT_DEFAULT_ACCESS_LEVEL )
				print "<option value='$role' selected='selected' > $role </option>\n";
			else
				print "<option value='$role'>$role</option>\n";
		}
	}

	print "\t\t\t</select>
			</td>
		</tr>
		<tr>
			<td class='txtSmall' colspan='2'>
				Username <em>(leave blank to autogenerate)</em><br />
				<input value='{$user['Username']}' name='user_data[Username]' type='text' size='20' maxlength='32' />
			</td>
		</tr> \n";
		
	if ($action == 'add') {
		print "
		<tr>
			<td class='txtSmall' colspan='2'>
				Initial Password <em>(leave blank to autogenerate)</em><br />
				<input value='{$user['Password']}' name='user_data[Password]' type='text' size='20' maxlength='32' />
			</td>
		</tr> \n";
	}

		
	print "
		<tr>
			<td class='txtSmall' colspan='2'><input type='submit' value='Save' />

			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type='checkbox' value='1' name='notify_user' /> email information to user
			</td>
		</tr>

		<tr><td> \n";

	if ($debug_g)
		print "<input type='hidden' name='debug' value='y' />";

	print "
			</td>
		</tr>
	</table>
	</form>
	</div>
	<script type='text/javascript' src='javascript/userform_prevalidate.js'> </script>
	<script type='text/javascript' src='javascript/form_validate.js'> </script> \n";

}



FUNCTION draw_project_maintenance_form( $user_table ) {

	GLOBAL $debug_g;

?>
	<div class='container'>
	<div class='txtSmall'><br /><strong><?php print msg("Add a New Project"); ?></strong><br /><br /><br /></div>

	<!-- Note NN4 chokes if onSubmit is on multiple lines (probably a bug in the js AND)  -->

	<form id="projectForm" action="codetrack.php" method="post" onsubmit="return checkMissing(this);" >

	<table border='2' cellpadding='7' cellspacing='0' class='quickForm' summary="Project Maintenance">
		<tr>
			<td class='txtSmall'><?php print msg("Project Title <em>(One or Two Words, or an acronym) </em>"); ?><br />
				<input name="page" type="hidden" value="saveproject" />
				<input name="project_data[ID]" type="hidden" value="-1" />
				<input name="project_data[Title]" type="text" size="20" maxlength="25" value="" />
			</td>
		</tr>
		<tr>
			<td class='txtSmall'> <?php print msg("Lead Developer or Analyst"); ?> <br />
				<select name='project_data[Test_Lead]'>
<?php

	$user_cnt = sizeof( $user_table );

	for ($i=0; $i < $user_cnt; $i++) {
		print "\t\t\t<option value=\"" . $user_table[$i]["First_Name"] . "\"" .
		( ($user_table[$i]["Full_Name"] == CT_DEFAULT_PROJECT_LEAD) ? " selected='selected'>" : ">" ) .
		$user_table[$i]["Full_Name"] . " </option>\n";
	}
	print "\t\t</select>\n\n";

	print "
			</td>
		</tr>
		<tr>
			<td class='txtSmall'> " . msg("Project Description") . " <br />
				<input name='project_data[Description]' type='text' size='55' maxlength='65' value='' />
			</td>
		</tr>
		<tr>
			<td class='txtSmall'>
			" . msg("Type of Project") . " <br /> \n";

	$project_types = explode( ',' , CT_PROJECT_TYPES ) ;

	for ( $j=0; $j < count($project_types); $j++) {
		print "\n\t\t\t\t<input name='project_data[Project_Type]' type='radio' value='{$project_types[$j]}' " .
			(( $j > 0) ? "" : "checked='checked'" ) . 
			" />" . msg( $project_types[$j] ) . " &nbsp;&nbsp;&nbsp;&nbsp;";
	}

	print "
			</td>
		</tr>
		<tr>
			<td class='txtSmall'> " . msg("Preferred Title of Responding Team Members") . "
				<br /> \n";

	$preferred_titles = explode( ',' , CT_PREFERRED_TITLES ) ;

	for ( $j=0; $j < count($preferred_titles); $j++) {
		print "\n\t\t\t\t<input name='project_data[Preferred_Title]' type='radio' value='{$preferred_titles[$j]}' " .
			(( $j > 0) ? "" : "checked='checked'" ) . 
			" />" . msg( $preferred_titles[$j] ) . " &nbsp;&nbsp;&nbsp;&nbsp;";
	}

	print "
			</td>
		</tr>
		<tr>
			<td class='txtSmall'><input type='submit' value='" . msg("Save") . "' /> \n";

	if ($debug_g)
		print "<input type='hidden' name='debug' value='y' />";

	print "
			</td>
		</tr>
	</table>
	</form>
	</div>
	<script type='text/javascript' src='javascript/form_validate.js'> </script>
	<script type='text/javascript' src='javascript/projectform_prevalidate.js'> </script>
";

}


FUNCTION draw_view_bug_page( $bug_id ) {

	GLOBAL $xml_array_g;

	parse_xml_file( CT_XML_BUGS_FILE, CT_DROP_HISTORY, "ID", CT_ASCENDING );
	$bug_array = $xml_array_g;

	$index = get_array_index_for_id( $bug_array, "$bug_id" );
	$bug_to_edit = $bug_array[$index] ;

	print "\n\t<div id='pageTitle'> Issue # $bug_id </div><br />\n";

	$prev_id = get_prev_bug_id( $bug_array, $index );
	$next_id = get_next_bug_id( $bug_array, $index );
	draw_prev_next_buttons( "$prev_id", "$next_id", $bug_to_edit, "viewissue", "all_buttons" );
	print "\n";
	draw_read_only_table( $bug_to_edit );

}



FUNCTION draw_xml_backups_page( $success='' ) {

	print "<div class='txtSmall'><div class='container'><br /><strong>".
			msg("XML Database Backup Utility").
			"</strong></div><br />" .
			"<div style='margin-left: 20%; margin-top: 0px; margin-bottom: 0px;'>" .
			"<hr class='hrBlack' /><br />" .
			msg("Existing Entries in") .
			" <em>" . CT_XML_BACKUPS_DIRECTORY . "</em> :\n<br />";

	$dir_h = opendir( CT_XML_BACKUPS_DIRECTORY );

	if (! $dir_h )
		die_now(
			sprintf( msg("Fatal:  Could not read the 'X' directory! Make sure it is read &amp; writable by Apache."),
				CT_XML_BACKUPS_DIRECTORY
			)
		);

	#	Yes, that's the correct evaluation operator -- see PHP manual on readdir for more info.

	while ( FALSE !== ($filename = readdir( $dir_h )) ) {
		if ( $filename == '.' or $filename == '..' or $filename == 'index.html' )
			continue;
		$file_list[] = $filename;
	}
	closedir( $dir_h );

	if ( $file_list ) {
		sort( $file_list );
		print "\n<form action='#'>\n<textarea rows='8' cols='35'>\n";
		foreach ($file_list as $entry)
			print "$entry\n";
		print "</textarea>\n</form>\n\n";
	}
	else
		print "<br /><em>(none)</em><br />\n";

	print "<br /><br /><form action='codetrack.php' method='post'>\n" .
			"<input type='submit' value='" . msg("Backup Now") . "' />\n".
			"<input type='hidden' name='page' value='dobackup' /></form>\n";

	if ($success)
		print "<br /><em>" . msg("Backups successfully created.") . "</em>\n";

	print "\n</div></div>\n";
}



FUNCTION draw_table( $data_array, $title, $filter, $project_table='', $hide_deleted_bugs = TRUE ) {

	#print "<pre>"; print_r($data_array); exit;

	GLOBAL $debug_g, $current_session_g;

	$bug_statuses	 = explode("," , CT_BUG_STATUSES);
	$bug_severities = explode("," , CT_BUG_SEVERITIES);

	foreach ($bug_statuses as $status)
			$status_counts[$status] = 0;							# ex. $status_counts["Closed"]

	foreach ($bug_severities as $severity)
			$severity_counts[$severity] = 0;						# ex. $severity_counts["Fatal"]

	if ($debug_g) {
		print "<pre>"; var_dump($data_array); var_dump($filter); print "</pre>";
	}

	if ( (!$data_array) or (!$title) )
		die( msg("Fatal: Draw table passed no data array or title!") );

	print '<script type="text/javascript" src="javascript/table_sort.js"> </script>';
	print "\n\n<div id='pageTitle'>$title</div><br />\n";

	$row_cnt = sizeof($data_array);  # This is correct; We need original size.

	#	Project Hot List Drop Down

	if ($project_table)
		draw_project_hotlist_widget($project_table, $current_session_g["project"], "home");

	#	Sort by $data_array by Project, Severity (desc), ID (bug lists only)

	$in  = 'return strcasecmp($a["Project"].$b["Severity"].$a["ID"]';
	$out = '$b["Project"].$a["Severity"].$b["ID"]);';
	$f   = $in . "," . $out;
	usort($data_array, create_function('$a,$b', $f));

	#	EXTRACT COLUMN HEADINGS

	if ($title == msg('Active Projects') or $title == msg('Address Book') )
		$element_entry = array_keys( $data_array[0] );
	else
		$element_entry = explode(",", CT_HOME_PAGE_DATA_COLUMNS );

	if (!$element_entry)
		return;

	#	Build Generic Summary Table

	print "<div class='dataBlock'>\n";
	print "<table id='results' cellspacing='1' border='0' width='100%' summary='Data Table'>\n";
	print "<thead>\n\t<tr title='" . msg("Click on column name to sort") . "'>\n";

	$hdr_cnt = 0;

	foreach ($element_entry as $column_name) {

		if ( $column_name == 'Project' and !stristr($title, "All Projects") )	# zztop this is a kludge
			continue;

		if ( $column_name == 'Password')
			continue;

		if ($column_name == 'Assign_To')
			$column_name = 'Assigned_To';		# Gramatical fix for deprecated CT XML structures

		if ($column_name == 'Developer_Response') {			# zztop customization has it's costs...

			# Use Preferred Title set in PROJECTS.XML (i.e., to convert "Developer_Response" to "Analyst_Response"
			if (sizeof($project_table))
				foreach ($project_table as $tmp_proj)
					if ($tmp_proj["Title"] == $current_session_g["project"]) {
						if (isset($tmp_proj["Preferred_Title"]))
							$column_name = $tmp_proj["Preferred_Title"] . "_Response";
						break;
					}
		}

		print "\t\t<th onclick='SortTable(" . $hdr_cnt++ . ");'> " .
			strtr(msg($column_name), "_", " ") . " </th>\n";
	}
	print "\t</tr>\n</thead>\n<tbody>";


	#	WALK EACH NODE (BUG, USER, WHATEVER) THROUGH EACH OF ITS ELEMENTS

	$row_cnt = sizeof($data_array);
	$shown_row_cnt = 0;
	$delcnt = 0;

	for ( $row = 0; $row < $row_cnt; $row++ ) {

		if (!$data_array[$row]["ID"])
			continue;

		for ($j=0; $j < $delcnt; $j++) {
			if ($data_array[$row]["ID"] == $already_shown[$delcnt])
				break;
			else
				$already_shown[$delcnt++] = $data_array[$row]["ID"];
		}

		if ( isset($data_array["$row"]["Delete_Bug"]) && ($hide_deleted_bugs) )
				continue;

		$display = TRUE;

		if ($filter) {
			foreach ($filter as $filter_column => $filter_value) {
				if ($filter_column == 'Project' and $filter_value == 'All' )
					continue;  				# Don't fail looking for a project "all"
				if ( $data_array[$row][$filter_column] != $filter_value  ) {
					$display = FALSE;
					break;
				}
			}
		}

		if ( $display )	{

			#	Got one -- let's show it

			$highlight_class = "";

			print "\t<tr" . (($shown_row_cnt % 2) ? " class='rowOdd'" : " class='rowEven'" ) . ">\n";

			if ( stristr($title, "issue") ) {

				foreach ($bug_statuses as $status) {
					if ($data_array[$row]["Status"] == $status)
						$status_counts[$status]++;									# ex. $status_counts["Closed"]
				}

				foreach ($bug_severities as $severity) {
					if ($data_array[$row]["Severity"] == $severity)
						$severity_counts[$severity]++;							# ex. $severity_counts["Fatal"]
				}
			}


			foreach ($element_entry as $column) {

				if ( ($column == 'Project' and !stristr($title, "All Projects" )) or ( $column == 'Password') )  # zztop kludge
					continue;

				$data_array[$row][$column] = trim( $data_array[$row][$column] );

				if ($project_table)		# Limit column size for bug-related tables (not user/proj)
					if (strlen($data_array[$row][$column]) > 40)
						$data_array[$row][$column] = substr($data_array[$row][$column],0,40) . "...";

				#	If this is a designated (and populated) link entry, make it a link

				if ($title != msg("Address Book")) {

# Next section breaks table sort
/*						if ( ($column == "Attachment") and ($data_array[$row][$column]) ) {
									$data_array[$row][$column] = "<a href='attachments/"  .
																		  $data_array[$row][$column] .
																		  "' onclick=\"this.target='_blank';\" >{$data_array[$row][$column]}</a>" ;
						}
*/
						if ( ($column == "ID") and ($data_array[$row][$column]) ) {

							$accession = (int) $data_array[$row][$column];

							if ( $title == msg("Active Projects") )
									$data_array[$row][$column] = "<a href='codetrack.php"  .
																		  "?page=changeproject&amp;redir=home&amp;project=" .
																		  urlencode($data_array[$row]["Title"]) .
																		  "'>{$data_array[$row][$column]}</a>" ;
							else
								$data_array[$row][$column] = "<a href='codetrack.php" .
																	  "?page=viewissue&amp;id=" .
																	  $data_array[$row][$column] .
																	  "'>{$data_array[$row][$column]}</a>" ;

							# Embed a numeric, sortable numeric-only comment so the column sort works
							# in human-order (lastNode value in table_sort.js)

							$data_array[$row][$column] .= '<!-- ' . $accession . ' -->';
					}

				}

				#	If developer has posted a response, highlight the status column

				if ( ($column == 'Status') and
					  ( ! isset($data_array[$row]["Developer_Comment"]) and
						 ! isset($data_array[$row]["Developer_Response"]) ) ) {
					$highlight_class = " class='devResponse'";
				}

				# Embed a numeric, sortable Unix-style timestamp as an HTML comment, so the column sort js will use

				if ($column == 'Last_Updated' or $column == "Submit_Time") {
						$human_date = parse_human_date( $data_array[$row][$column] );
						$unix_timestamp = strtotime( $data_array[$row][$column] );
						$data_array[$row][$column] = $human_date . '<!-- ' . $unix_timestamp . ' -->';
				}

				print "\t\t<td$highlight_class>";
				print (($data_array[$row][$column]) ? $data_array[$row][$column] : '&nbsp;' );
				print "</td>\n";

				$highlight_class = "";
			}

			print "\t</tr>\n";

			$shown_row_cnt++;
		}
	}
	print "</tbody></table>\n</div>\n";

	if ( stristr($title, msg("Issue")) ) {	#zztop ?

		print "<span id='resultsTotal'>" . msg( "Issue Count:" ) . " $shown_row_cnt </span>";

		if ( $shown_row_cnt > 0 ) {

			print "\n<span id='resultsFooter'> " .
				msg( "(Oldest to newest, by severity. Red status indicates response or comment needed.)" ) .
				"</span><br /><br /><br /> \n";

			print "<div class='graphTitle'> " . msg("Count by Severity") . " </div>\n";
			bar_graph( $severity_counts );

			print "<div class='graphTitle'> " . msg("Count by Status") . " </div>\n";
			bar_graph( $status_counts );

		}
	}
}



FUNCTION encrypt_password( $plaintext_password ) {

	#	Produce an MD5 hash that is alphanumeric only, for HTML & XML friendliness.
	#	Keyspace is SLIGHTLY reduced, but if raw MD5 is revealed, and they're determined, we're hosed anyway...

	mt_srand(time());
	$sanity_check = 0;

	do {

		$salt  = chr(mt_rand(48,122));		#	Look for a "mostly" alphanumeric salt
		$salt .= chr(mt_rand(48,122));

		$encrypted_password = crypt($plaintext_password, $salt);

		if ( $sanity_check++ > 250 )
			die_now( msg("Fatal:  Could not construct valid encrypted password!") );

	} while( eregi("[^0-9a-z]+", $encrypted_password) );

	return $encrypted_password;
}



FUNCTION export_database( $export_options, $user_agent ) {

	GLOBAL $xml_array_g, $debug_g;

	#  PUSH XML DATABASE TO CLIENT IN VARIOUS FORMATS (XML, CSV, SQL, TXT, ETC.)

	$show_history = $export_options["show_history"];
	$sort_type = $export_options["sort_type"];
	$show_deleted = $export_options["show_deleted"];
	$file_format = $export_options["file_format"];
	$project_filter = $export_options["project_filter"];

	if ($file_format == 'xml_download') {
		$force_download = TRUE;
  $file_format = 'xml';
 }
 else
  $force_download = FALSE;

 $table_name = 'bugs';
 $download_filename = "$table_name.$file_format";  # i.e., "bugs.csv"


	# START FILE PUSH

	if ( !$debug_g )
		push_mime_info( $user_agent, $download_filename, $force_download );


	# PUSH FILE DIRECTLY IF XML FORMAT REQUESTED

	if ( $file_format == 'xml' ) {

	readfile ( CT_XML_BUGS_FILE )  or
		die_now(
			sprintf(
				msg("FATAL: Could not open or append to file 'X' ! Check that it is writable by the Apache owner."),
				CT_XML_BUGS_FILE
			)
		);
	flush();
	exit;
}



 # EXTRACT XML ELEMENT LIST (DATA COLUMN NAMES) FROM DOCUMENT TYPE DEFINITION FILE

 $column_names  = parse_dtd_file( "$table_name.dtd" ); # i.e., "bugs.dtd"

 $xml_filename = CT_XML_DIRECTORY . "/$table_name.xml";


 $xml_array_g = array();  # Lost scope, so reset

	if ($show_history)
		parse_xml_file( $xml_filename, CT_KEEP_HISTORY, "ID", CT_ASCENDING);		# i.e., "bugs.xml"
	else
		parse_xml_file( $xml_filename, CT_DROP_HISTORY, "ID", CT_ASCENDING);		# i.e., "bugs.xml"

	$data_array = $xml_array_g;

	$row_cnt = sizeof($data_array);  #	Yes, this is correct; need original # of rows

	usort($data_array, create_function('$a,$b',
			'return strcasecmp($a["Project"].$a["ID"],$b["Project"].$b["ID"]);'));

	$table_prefix=''; $table_suffix=''; $line_prefix=''; $line_suffix='';

	if ( $file_format == "csv") {
		$delimiter   = '"' ;
		$escaped_delimiter = '""';				# Excel '97/2K escapes this way
		$separator   = ',' ;
		$line_suffix = "\r\n" ;
	}
	elseif ( $file_format == "prn") {
		$delimiter    = "\t" ;
		$escaped_delimiter = "[tab]";			# We're open to any clever alternatives here...
		$separator    = "" ;
		$line_suffix  = "\r\n" ;
		$table_prefix = "\n" ;
		$table_suffix = "\n\n" ;
	}
	elseif ( $file_format == "sql") {
		$delimiter = "'" ;
		$escaped_delimiter = "'''";			# Oracle escapes this way, not sure if it's "official" ANSI SQL, though...
		$separator = "," ;
		$table_prefix = "-- ALTER SESSION SET NLS_DATE_FORMAT='DD-MON-YYYY HH:MI:SS AM' ;\n\n";
		$table_suffix = "\n.\n/\n\n" ;
		$line_prefix  = "INSERT INTO bugs VALUES (" ;
		$line_suffix  = ") ; \r\n" ;
	}
	else
		die_now ( msg("Internal Fatal: No download type specified!") );


	#	SEND COLUMN NAMES

	if ($file_format == "csv" or $file_format == "prn" ) {

		for ($j=0; $j < sizeof($column_names); $j++)
			print ( ($j) ? "$separator" : "" ) . $delimiter . $column_names[$j] . $delimiter;
		print "\r\n";

	}


	#	PUSH DATA, EITHER ASCENDING OR DESCENDING

	print $table_prefix;

	for ( $row = 0; $row <= $row_cnt; $row++ ) {

		if (!isset($data_array[$row]))
			continue;

		if ( (!$show_deleted) and ($data_array[$row]["Delete_Bug"] == 'Y') )
			continue;

		if ( $project_filter && $data_array[$row]["Project"] != $project_filter )
			continue;

		foreach ($column_names as $column) {

			$cell = reverse_htmlspecialchars ( $data_array[$row][$column] );
			$cell = strtr($cell, "\n\r", CT_NEWLINE_SYMBOL);								# NO CROSS-VENDOR WAY TO EMBED NEWLINES
			$cell = ereg_replace( $delimiter, $escaped_delimiter, $cell );


			# DO NOT PRINT LEADING SEPARATORS IN FRONT OF FIRST COLUMN, I.E.:   ,foo,bar

			if ($column == $column_names[0]) {

				print $line_prefix ;

				if ($file_format == "prn" )
					print $cell . $delimiter ;
				else
					print $delimiter . $cell . $delimiter;
			}
			else {

				print $separator . $delimiter . $cell ;

				if ($file_format != "prn" )	# TAB-SEPARATORS SHOULD NOT DOUBLE. BAD:  \t foo \t \t bar \t  GOOD: "foo","bar"
					print $delimiter;
			}
		}
		print $line_suffix;
	}
	print $table_suffix;

	#	EMPTY STDOUT CACHE AND END DOWNLOAD

	flush();
	exit;
}



FUNCTION get_array_index_for_id( $data_array, $id ) {

	$row_cnt = sizeof($data_array);

	if ($row_cnt == 1)		# Special case, single array element isn't really sorted, thus not compacted, thus index=1
		return 0;

	for ($index=0; $index < $row_cnt; $index++)
		if ($data_array[$index]["ID"] == $id)
			return $index;

	# If we've fallen through without match, no bueno!

	die_now( sprintf( msg("Fatal: No issue matching ID# 'X' found!"), $id ) );

}



FUNCTION get_next_bug_id( $bug_array, $current_row_id ) {

	GLOBAL $current_session_g;

	#	Count forward from current bug, until we find a non-deleted bug or hit max array

	$n = $current_row_id;
	while ( ++$n < sizeof($bug_array) ) {
		if ( (!isset($bug_array[$n]["Delete_Bug"])) &&
			  ($bug_array[$n]["Project"] == $current_session_g["project"]) &&
			(! stristr( $bug_array[$n]["Status"], "Closed" ) ))  {
			$next_id = $bug_array[$n]["ID"];
			break;
		}
	}

	if ( !isset($next_id) )   #	If we couldn't find it, set next to current
		$next_id = $bug_array[$current_row_id]["ID"];

	return $next_id;
}



FUNCTION get_prev_bug_id( $bug_array, $current_row_id ) {

	GLOBAL $current_session_g;

	#	Count backwards from current bug, until we find a non-deleted bug or index is negative

	$p = $current_row_id;
	while ( --$p >= 0 ) {
		if ( (!isset($bug_array[$p]["Delete_Bug"])) &&
			($bug_array[$p]["Project"] == $current_session_g["project"]) &&
			(! stristr( $bug_array[$p]["Status"], "Closed" ) ))  {
			$prev_id = $bug_array[$p]["ID"];
			break;
		}
	}
	if ( !isset($prev_id) )   #	If we couldn't find it, set prev to current
		$prev_id = $bug_array[$current_row_id]["ID"];

	return $prev_id;
}



FUNCTION get_stats( $time_in_seconds ) {

 $days = $time_in_seconds / 86400.0;

 if ($days)
  return ( sprintf("%3.1f ", $days) . msg("days") );
 else
  return "(n/a)";

}


# Translate to local language settings

FUNCTION msg($s) {

	#return $s;

	# locale_messages_g[] are loaded in config.inc.php via a language file, for example: us_en.locale.inc.php

	GLOBAL $locale_messages_g;

	#var_dump($locale_messages_g); exit;
   
	if (isset($locale_messages_g[$s]))
		return $locale_messages_g[$s] ;
	else
		die_now( "FATAL: No localization text found for <br /> '$s' !" );

}



FUNCTION no_cache() {
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    # Date in the past
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");  # HTTP/1.1
	header ("Pragma: no-cache");                          # HTTP/1.0
}



FUNCTION parse_dtd_file( $filename ) {

	$fh = fopen($filename, "r") or
		die_now(
			sprintf(
				msg("Fatal: Could not open 'X' ! Check that it is readable by the Apache owner."),
				$filename
		)	);

	$dtd = fread ($fh, filesize ($filename));
	fclose ($fh);

	#
	#	Extract all element words in the form of:
	#
	#	<!ELEMENT (whitespace)
	#		ELEMENT_WORD_OF_INTEREST
	#		(whitespace) ( (maybe whitespace) #PCDATA (maybe whitespace) )
	#

	preg_match_all ("|<\!ELEMENT\s+(\w+)\s+\(\s*#PCDATA\s*\)>|", $dtd, $match_array);
	$dtd_array = $match_array[1];  # Convert matches to flat single-dimensional array

 return ($dtd_array);
}



FUNCTION parse_human_date( $date_string ){

#  Check if $date_string occurred yesterday or today, and if so augment the string. Otherwise return as passed.

	if ( strtotime( $date_string ) > strtotime( "00:00 today" ) )
		return msg("Today") . ", " . date( msg("g:i A") , strtotime($date_string)) ;
	elseif ( strtotime( $date_string ) > strtotime( "00:00 yesterday" ) )
		return msg("Yesterday") . ", " . date( msg("g:i A") , strtotime($date_string)) ;
	else
		return $date_string;
}



FUNCTION parse_xml_file( $xml_filename, $drop_history=FALSE, $sort_element='', $sort_order='' ) {

	#
	# A non-validating, non-generic, single-pass XML parser.  Returns data by populating
	# the global $xml_array_g.  Although written specifically for CodeTrack, it could be
	# recycled, as long as elements are no more than one level deep, and minor elements
	# do not repeat hierarchically inside major elements.  For example, this would not work:
	#
	#  <bar><foo>x</foo><foo>y</foo></bar>
	#
	# The returned array would contain only one $foo, y.
	#

	GLOBAL $xml_array_g, $debug_g;

	$xml_array_g = array();   # Reset it, in case we've been called before

	$fp = fopen($xml_filename, "r") or
		die_now(
			sprintf(
				msg("Fatal: Could not open 'X' ! Check that it is readable by the Apache owner."),
				$xml_filename
		)	);

	$xml_document = fread($fp, filesize($xml_filename)) ;
	fclose($fp);

	#	Pull out <tag_name>(data)</tag_name> as: $matches[j][1]=$tag_name & $matches[j][2]=$data

	preg_match_all( "#<([\w]+)[^>]*>([^<]*)</\\1>#", $xml_document, $matches, PREG_SET_ORDER );

	$node_cnt=0;			# Nodes here are a complete <bug>, <user>, <project>, etc.
	$node_id=0;

	$match_cnt = count($matches);					# Element matches, not node matches

	for ($i=0; $i < $match_cnt; $i++) {			# Walk every element found

		if ( $matches[$i][1] == 'ID' ) {			# [1] is column, [2] is content

			#
			#	If we need a bug's complete history, the node list will continue to grow, otherwise
			#	each successive entry of, say, bug[37] gets replaced with the newest bug[37].
			#	This will could theoretically leave gaps: bug[39], bug[41], etc., but this will be
			#	corrected in the sort below (the index will be packed, and the array index will no
			#	longer be guaranteed to match the bug ID.)
			#

			$node_id = 0 + $matches[$i][2];			# Ex., bug[23][ID]=006 -> 0+006 -> $node_id = 6

			if ($drop_history) {
				$index = $node_id;
				unset( $xml_array_g[$node_id] );		# Clear all elements of any previous entry
			}
			else
				$index = $node_cnt;

			$node_cnt++;									# New bug entry, new user, etc.
		}
		$xml_array_g[$index][$matches[$i][1]] = $matches[$i][2];		# ex. $bug[8][Author]=Bob
	}

	#	Sort arrays, but lose key associations:  $foo[3], $foo[5], vs. $foo[0], $foo[1]

	if ($sort_element) {

		# Weirdo once-in-the-life-of-the-system boundary condition
		if ( ( sizeof($xml_array_g) == 1 ) and ( isset($xml_array_g[1]) ) ){

	   	if ($debug_g)
				print "<tt><br />Encountered 1-element array with non-zero index.  Shifting down **** <br /></tt>\n";

			$tmp = $xml_array_g[1];
			unset($xml_array_g[1]);
			$xml_array_g[0] = $tmp;
		}
		elseif ($sort_order == CT_ASCENDING) {
			usort($xml_array_g,
			create_function('$a,$b','return strcasecmp($a["'.$sort_element.'"],$b["'.$sort_element.'"]);'));
		}
		else {
			usort($xml_array_g,
			create_function('$a,$b','return strcasecmp($b["'.$sort_element.'"],$a["'.$sort_element.'"]);'));
		}
	}
	if ($debug_g)
		printf("<tt><br />Parsing: *%s*&nbsp; Sort element: *%s*&nbsp; Ascending: %0d  ".
			"Drop history: %0d  Node count: %0d <br /> Total # of parsed elements: %0d <br /></tt>\n",
			$xml_filename, $sort_element, $sort_order, $drop_history, $node_cnt, $i);
}



FUNCTION print_audit_trail( $data_array, $id ) {

	$row_cnt = sizeof($data_array);   # Yes, this is correct; need original # of rows

	$last_entry = '';

	$updates_posted = FALSE;

	for ($i=0; $i < $row_cnt; $i++) {

		if ( $data_array[$i]["ID"] == $id ) {

			if ( !$last_entry ) {

				print "<div class='txtSmall'>\n<div class='container'><br /><strong>" .
					msg( "History for Issue #" ) .
					"<a href='codetrack.php?page=viewissue&amp;id=$id'>$id</a>" .
					"</strong></div>\n";

				print "<br />&nbsp;" . msg("Original Report") ." \n";
				$initial_report = $data_array[$i];
				unset($initial_report["Last_Updated"]) ;  # It might be confusing to show this
				draw_read_only_table( $initial_report );
				$last_entry = $data_array[$i];
				continue;         # Search next node in loop
			}

			$updates_posted = TRUE;

			if (! isset( $data_array[$i]["Last_Updated"] ) ) {

				# For those just tuning in, legacy CT data fix follows (zztop WTF?)

				$data_array[$i]["Last_Updated"] = $data_array[$i]["Submit_Time"];
				$data_array[$i]["Updated_By"]   = msg("someone");
				$data_array[$i]["Submit_Time"] = $last_entry["Submit_Time"];
			}

			print "<br /><hr class='hrBlack' />\n<br />" .
				sprintf( msg("On X_DATE, <strong> X_USER </strong> modified this entry"),
					$data_array[$i]["Last_Updated"],
					$data_array[$i]["Updated_By"]
				) . ": <br /><br />";

			foreach ($data_array[$i] as $column => $newval)	{

				$newval = nl2br($newval);

				if ( ($column == 'Last_Updated') or ($column == 'Updated_By') )
						continue;

				$oldval = $last_entry[$column];
				if ( !$oldval )
					print "<strong>" . strtr( msg( $column ) , "_", " " ) . "</strong> " .
						sprintf( msg("'X' was <strong>added</strong>."),
							$newval
						) . " <br />";

			}

			foreach ($last_entry as $column => $oldval)	{

				if ( ($column == 'Last_Updated') or ($column == 'Updated_By') )
					continue;

				$newval = $data_array[$i][$column];
				#print "[Scanning *$column* of *$oldval* against *$newval*]<br />";
				if ( !$newval ) {
					print	
						sprintf(
							msg("The <strong>X</strong> was <strong>erased</strong>."),
							strtr( msg($column), "_", " ")
						) . "<br />";
				}
				elseif ( $oldval != $newval ) {
					print
						sprintf(
							msg("<strong>SECTION_X</strong> 'VALUE_Y' <strong>changed to</strong> 'VALUE_Z'."),
							strtr($column, "_", " "),
							$oldval,
							$newval
						) ." <br />";
				}
			}
			$last_entry = $data_array[$i];
		}
	}
	print "<br /><br />\n</div>\n";

	if (!$last_entry)
		die_now( sprintf(msg("Fatal: No issue matching ID# 'X' found!"), $id) );

	if (!$updates_posted)
		print "<em>" . msg("(No updates have been made to this report)") . "</em>";

		return;
}



FUNCTION print_deleted_bugs( $bug_table ) {

	usort($bug_table, create_function('$a,$b',
		'return strcasecmp($a["Project"].$a["ID"],$b["Project"].$b["ID"]);'));

	$deleted_bugs = array();

	$n_bugs = sizeof($bug_table);

	for ($j=0; $j < $n_bugs; $j++) {
		if ( isset( $bug_table[$j]["Delete_Bug"] ) && $bug_table[$j]["Delete_Bug"] == 'Y' )
			$deleted_bugs[] = $bug_table[$j];
	}

	if ($deleted_bugs)
		draw_table( $deleted_bugs, msg("All Deleted Issues"), NULL, NULL, CT_SHOW_DELETED_BUGS );
	else
		print "<br /><br /><em>(" . msg("No deleted issues found") . ")</em><br />\n";

}



FUNCTION process_file_attachment( $tmp_filename, $requested_filename ) {

	GLOBAL $debug_g;

	if ( $debug_g )
		print "\n<pre> Temp File: '$tmp_filename' \n Requested filename: '$requested_filename' </pre>\n";


	#	WEBIFY LONG WINDOWS NAMES, SANITIZE THE FILENAME, AND MOVE UPLOADED FILE TO ATTACHMENTS DIRECTORY.
	#	ONLY ALLOW CHARACTERS WE EXPLICITY SPECIFY, DROP ALL OTHERS (I.E., UNICODE CRAP, ESCAPE/CONTROL CHARACTERS, ETC.)

	# Only allow numbers, alpha (and any optional international characters) and  . - _


	$filename = ereg_replace("( +)", "_",  trim($requested_filename));		# Convert all spaces to underscores

	# Only allow numbers, alpha, and  . - _

	$filename = eregi_replace('([^.0-9a-z_-' . CT_INTERNATIONAL_CHARS . ']+)', "", $filename );

	$filename = ereg_replace("(\.+)", ".", $filename);								# No directory traversal hacks, i.e., ".."

	if ( $debug_g )
		print "<pre> Scrubbed filename: '$filename' </pre>\n";

	# Don't exceed a reasonable maximum character limit
	$filename = substr( $filename, 0, CT_MAX_ATTACHMENT_FILENAME_SIZE );

	$legal_attachment_types = explode( ","  , CT_LEGAL_ATTACHMENTS );

	$legal = FALSE;
	foreach( $legal_attachment_types as $legal_type ) {
		if ( eregi( "\.$legal_type\$" , $filename) ) {		# E.g., [a_long_filename][.pdf]  ( $ = right end of string match )
			$legal = TRUE;
			break;
		}
	}
	if ( !$legal )
		die_now(
			sprintf(
				msg("FATAL: Your attachment is not a permissible file. Allowed file types in your config.inc.php are: <br /> 'X'"),
				strtr( CT_LEGAL_ATTACHMENTS, ',' , ' ,')
			)
		);

	if (move_uploaded_file($tmp_filename, "attachments/$filename")) {   	# PHP built-in function. Windows & *nix all use /
		chmod("attachments/$filename", 0664);
		return $filename;
	}
	else
		die_now(
			sprintf(
				msg("FATAL: File attachment transfer failed for <br />TMP: 'X' <br />FILE: 'Y' <br />Please notify support!"),
				$tmp_filename, $filename
			)
		);
}



FUNCTION push_mime_info( $browser_string, $download_filename, $force_download ) {

	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

	if ( stristr($download_filename, ".xml") and !stristr($browser_string, "Opera") )	#  Painful, but Opera is stupid
		header("Content-Type: application/xml");
	else
		header("Content-Type: application/force-download");

	if ($force_download)
		$behavior = "attachment";
	else
		$behavior = "inline";

   header("Content-Disposition: $behavior; filename=\"$download_filename\"");

	return;
}



FUNCTION reverse_htmlspecialchars ( $html_encoded_string ) {

	# UNDO WHAT PHP'S htmlspecialchars() DOES, I.E.,  &quot; -->  "
	# DON'T KNOW WHY PHP DOESN'T ALREADY DO THIS...

	$trans = array_flip( get_html_translation_table( HTML_SPECIALCHARS ));  	# PHP BUILT-IN FUNCTION
	return ( strtr($html_encoded_string, $trans) );
}



FUNCTION rewrite_xml_file( $filename, $parsed_tree, $major_element_tag ) {


	#	Take a pre-parsed xml tree (table), and create or rebuild an existing file


	$root_element_tag = $major_element_tag . "s";			# <users><user>, <bugs><bug>, etc.

	brute_force_lock( $filename );

	#	Open file write-only and set length to zero

	$fh = fopen($filename, 'wb')  or
		die_now(
			sprintf(
				msg("FATAL: Could not truncate 'X' ! Check that it is writable by the Apache owner."),
				$filename
			)
		);

	$opening_content = "<?xml version=\"1.0\" ?>\r\n" .
							 "<!--  " . ucfirst($root_element_tag) . " database for CodeTrack.  Edit at your own risk.    -->\r\n" .
							 "<!--  Meaning: re-check for XML well-formedness and validity!  -->\r\n" .
							 "<!DOCTYPE $root_element_tag SYSTEM \"$root_element_tag.dtd\">\r\n" .
							 "<$root_element_tag>\r\n";

	fwrite($fh, $opening_content) or
		die_now( sprintf( msg("FATAL: Unable to write XML data to 'X' "), $filename) );

	foreach ($parsed_tree as $node) {

		fwrite( $fh, "\t<$major_element_tag>\r\n" ) or
			die_now( sprintf( msg("FATAL: Unable to write XML data to 'X' "), $filename) );

		foreach ($node as $element => $contents)
			fwrite($fh, "\t\t<$element>$contents</$element>\r\n") or
				die_now( sprintf( msg("FATAL: Unable to write XML data to 'X' "), $filename) );

		fwrite($fh, "\t</$major_element_tag>\r\n") or
			die_now( sprintf( msg("FATAL: Unable to write XML data to 'X' "), $filename) );
	}

	fwrite($fh, "</$root_element_tag>") or
		die_now( sprintf( msg("FATAL: Unable to write XML data to 'X' "), $filename) );

	brute_force_lock_release( $filename );

	fclose($fh);
}


FUNCTION draw_tools_page() {

print "
	<div id='pageTitle'> " . msg("System Tools") . " </div><br />
	<div style='margin-left: 10%;'>
	<hr class='hrBlack' />
";

	$link_list = array( 'export' => 'Export Data' );

	# Expose email addresses unless they've been blocked
	if ( CT_DISPLAY_ADDRESSES )
		$link_list['users'] = "Address Book" ;

	print build_static_links( $link_list, "System Tools" );

	readfile( CT_LOCALIZED_TIPS );

	print "\n <br /><br />" .
		sprintf( msg("CodeTrack is 'XLINK' Free </a>software."), 
			"<a href='http://www.gnu.org/licenses/gpl.html#SEC1' onclick=\"this.target='_blank';\">"
		) . " Copyright &copy; 2001-2008 Kenneth White \n";

	print "\n	</div> \n";

}



FUNCTION save_bug_data( $id='', $raw_bug_data, $attachment_data='', $original_submit_time='',
        $send_mail=FALSE, $cc_list='', $bug_table, $user_table ) {

 #
 # SAVE A NEW OR UPDATED BUG
 #
 # 1. Scrub and process attachment (if any)
 # 2. Prep notification email (if requested)
 # 3. Generate a submission (or modified) date/time stamp for this issue
 # 4. Construct a new (or preserve the old) ID
 # 5. Scrub the raw posted data and build up the complete <bug>...</bug> xml node
 # 6. Append the new node to the bugs.xml database
 # 7. Send out any email (if requested)
 # 8. Present success message where "OK" returns to home
 #

	GLOBAL $debug_g, $current_session_g;

	if ( $attachment_data ) {

		if ( $debug_g ) {
			print "<pre>\n*** Inside save_bug_data() *** \n\nattachment_data[]: \n"; var_dump($attachment_data); print "</pre>";
		}

		$sanitized_name = process_file_attachment( $attachment_data["tmp_name"], $attachment_data["name"] );
		$final_filename = '<Attachment>' . $sanitized_name . '</Attachment>';
	}
	else
		$final_filename = '';

	if ( CT_ENABLE_EMAIL ) {

		$b_submittor = eregi_replace("([^a-z0-9" . CT_INTERNATIONAL_CHARS . "' -]+)", '', $raw_bug_data["Submitted_By"] );

		$b_assignee  = eregi_replace("([^a-z0-9" . CT_INTERNATIONAL_CHARS . "' -]+)", '', $raw_bug_data["Assign_To"] );
		if (!$b_assignee)
			$b_assignee = "(unassigned)";

		$b_modifier  = eregi_replace("([^a-z0-9" . CT_INTERNATIONAL_CHARS . "' -]+)", '', $raw_bug_data["Modified_By"] );
		$b_project   = eregi_replace("([^a-z0-9" . CT_INTERNATIONAL_CHARS . "' -]+)", '', $raw_bug_data["Project"] );
		$b_summary   = eregi_replace("([^a-z0-9" . CT_INTERNATIONAL_CHARS . "'/@:\" -]+)", '', $raw_bug_data["Summary"] );
		$b_description = eregi_replace("([^a-z0-9" . CT_INTERNATIONAL_CHARS . "'/@:\"\r\n. -]+)", '', $raw_bug_data["Description"] );
		$b_severity = eregi_replace("([^a-z0-9" . CT_INTERNATIONAL_CHARS . "' -]+)", '', $raw_bug_data["Severity"] );

	}

	$bug_data = scrub_and_tag_form_data($raw_bug_data);	# zztop: why is this done twice?


	#	DEFAULT CT_DATE_FORMAT IS: 12-JUN-2001 12:34 PM

	if ( $original_submit_time ) {

		$Submit_Time	= "<Submit_Time>" . $original_submit_time . "</Submit_Time>";
		$Updated_By		= "<Updated_By>" . $current_session_g["user_full_name"] . "</Updated_By>";
	}
	else
		$Submit_Time = "<Submit_Time>" . date( CT_DATE_FORMAT ) . "</Submit_Time>";

	$Last_Updated	= "<Last_Updated>" . date( CT_DATE_FORMAT ) . "</Last_Updated>";


	if ( $id )
		$this_is_new_bug = FALSE;
	else {
		$id = calc_next_node_id ( $bug_table );
		$this_is_new_bug = TRUE;
	}
	$ID = sprintf("<ID>%04d</ID>", $id);


	if ( !$bug_data['Project'] or !$bug_data['Severity'] or !$bug_data['Summary'] or !$bug_data['Description'] or !$bug_data['Submitted_By'])
		die_now( msg('Fatal:  Vital form data for create issue not received (Project/Severity/Summary/Description/Submitted By)!') );


	#	BUILD THE COMPLETE XML TEXT NODE TO INSERT


	$node = "\t<bug>\n\t\t$ID\n\t\t$Submit_Time";

	foreach ($bug_data as $content)
		$node .= "\n\t\t$content";

	if ( $final_filename )
		$node .= "\n\t\t$final_filename";

	$node .= "\n\t\t$Last_Updated";

	if ( $original_submit_time )
		$node .= "\n\t\t$Updated_By";

	$node .= "\n\t</bug>";

	if ($debug_g) {
		print "<pre> About to append node to " . CT_XML_BUGS_FILE .
				" as: \n\n" . htmlspecialchars($node) . "</pre>\n";
	}

	append_xml_file_node( CT_XML_BUGS_FILE, $node, "</bugs>" );

	if ($debug_g)
		print "<pre> Back from append_xml_file_node().  About to check email request... </pre>";

	if ( ( CT_ENABLE_EMAIL ) and ( $send_mail == TRUE ) ) {

		if ($debug_g)
			print "<pre> Email has been requested, preparing now... </pre>\n";

		if ( isset($b_assignee) ) {
			for ($j=0; $j < sizeof($user_table); $j++)
				if ($user_table[$j]["Full_Name"] == $b_assignee) {
					$assignee_email = $user_table[$j]["Email"];
					break;
				}
			$cc_list[] = $assignee_email;			# Append to end of recipient mail address array
		}

		if ( $this_is_new_bug ) {

/* tess */

			$subject = sprintf( msg("New issue (ID# 'X') has been filed for the 'Y' project by 'Z'"),
				$id, $b_project, $b_submittor );

			$msg = "A $b_severity issue has been submitted by $b_submittor for the $b_project project\r\n" .
					 "____________________________________________________________\r\n\r\n" .
					 "Summary: $b_summary \r\n\r\n" .
					 "Description: $b_description \r\n\r\n" .
					 "____________________________________________________________\r\n\r\n" .
					 "This issue has been assigned to $b_assignee";
		}
		else {
			$subject = "An update to issue # $id on the $b_project project has been filed by $b_modifier";
			$msg = "Issue is currently rated as: $b_severity \r\n" .
					 "____________________________________________________________\r\n\r\n" .
					 "Summary: $b_summary \r\n\r\n" .
					 "Description: $b_description \r\n\r\n" .
					 "____________________________________________________________\r\n\r\n" .
					 "This issue has been assigned to $b_assignee.";
		}

		if ($debug_g) {
			print "<pre>Subject line in mail will be:\n$subject\n\n";
			print "Message will be:\n*****\n$msg\n***** \n\n\n";
		}


		foreach ($cc_list as $recipient) {

			if ($debug_g)
				print "About to send mail to: $recipient \n";

			if ( CT_USE_EXTRA_MAIL_HEADER )
				mail( $recipient, $subject, $msg,
						"From: " . CT_RETURN_ADDRESS . "\r\n"
						."Reply-To: " . CT_RETURN_ADDRESS . "\r\n"
						."X-Mailer: CodeTrack Mail" , "-f" . CT_RETURN_ADDRESS );
			else
				mail( $recipient, $subject, $msg,
						"From: " . CT_RETURN_ADDRESS . "\r\n"
						."Reply-To: " . CT_RETURN_ADDRESS . "\r\n"
						."X-Mailer: CodeTrack Mail" );
		}

		if ($debug_g)
			print "Mail has been sent. \n</pre>\n";

	}
	else {
		if ($debug_g)
			print "<pre> Email was not requested, skipping... </pre>\n";
	}

	if ($this_is_new_bug)
		$issue_action = 'created';
	else
		$issue_action = 'updated';


	print  "<span class'txtSmall'><div class='container'><br /><br /><br /><br /><em>Issue # $id has been $issue_action.</em>"
			."<br /><br /><br /><form action='codetrack.php' method='get'>"
			."<input type='hidden' name='page' value='home' /><input type='submit' value=' OK ' /></form>"
			."</div></span>\n";

	return;
}



FUNCTION update_user( $user_data, $user_table, $old_password=""  ) {

	#	Rebuild users.xml for a given user node.  If $old_password is passed in, only the password field will update

	GLOBAL $current_session_g, $debug_g;

	#	Re-sort users by ID
	usort( $user_table, create_function('$a,$b','return strcasecmp($a["ID"],$b["ID"]);') );

	$matching_id = '-1';
	for ($i=0; $i < sizeof($user_table); $i++)  {
		if ( $user_table[$i]["ID"] == $user_data["ID"] ) {
			$matching_id = $i;
			break;
		}
	}

	if ( $matching_id < 0)
		die("<br /><br /><center>Fatal:  No matching user ID found!");

	$username_to_update = $user_table[$matching_id]["Username"];

	if ( $current_session_g['role'] == 'Admin' )
		$next_page = "adminLinks";
	else
		$next_page = "home";


	# Are we changing a password?

	if ( isset($user_data["Password"]) ) {

		if ( $current_session_g['role'] != 'Admin' ) {

			if ( $username_to_update != $current_session_g['username'] )
				die("<br /><br /><center> Unless you're an admin, you can only change your own password. ");

			# Non-admin users have supplied old password, let's compare it to what we have

			$old_hash  = $user_table[$matching_id]["Password"];
			$old_salt  = substr($old_hash, 0, 2);
			$plaintext = $old_password;

			if ( crypt($plaintext, $old_salt) != $old_hash ) {
				draw_change_password_form( $user_table );
				print "<br /><div class='container'><div class='txtSmall'><strong>Password change failed.</strong> <br />" .
					"The old password you gave was incorrect. <br />Please try again. </div></div>\n";
				return FALSE;
			}
		}

		# Everyone has to have reasonable passwords, even Admin
		if ( strlen($user_data["Password"]) < CT_MIN_PASSWORD_LENGTH )
			die("<br /><br /><center> Password length was less than the minimum " . CT_MIN_PASSWORD_LENGTH .
				"characters. Changes were NOT saved .");

		# Create new password hash and save it
		$user_data["Password"] = encrypt_password ( $user_data["Password"] );

	}


	# Whether password-only, or full record, replace the old user entry with the new one

	if ($debug_g) {
		print "\n<pre>\n\$user_data:\n"; var_dump($user_data); print "</pre>\n";
	}

	update_xml_file_node( CT_XML_USERS_FILE, $user_table, "user", $user_data );

	print "<br /><br /><br /><br /><div class='container'><strong><em> Password for {$user_table[$matching_id]['Full_Name']} successfully changed. </em></strong>" .
			"<br /><br /><br /><form action='codetrack.php' method='get'>" .
			"<input type='hidden' name='page' value='$next_page' /><input type='submit' value=' OK ' /></form></div>";

}



FUNCTION save_permission_data( $permission_data, $permission_table, $project_table ) {

	if ( (! isset($permission_data)) or (! isset($permission_data["Project_ID"])) )
		die("<br /><center>Fatal:  Received permission data are corrupt! Nothing was saved.");

	#
	#	Traverse existing permission table, and create a packed array of permissions
	#		from all projects except the one at hand.  Updated form data will then be appended,
	#		and the new (full) permission matrix will overwrite (or create) permissions.xml
	#

	$packed_index = 0;

	foreach ($permission_table as $row) {
		if ( $row["Project_ID"] != $permission_data["Project_ID"] ) {
			$permission_tree[$packed_index]["ID"]			= sprintf("%04d", $packed_index);
			$permission_tree[$packed_index]["Project_ID"]= $row["Project_ID"];
			$permission_tree[$packed_index++]["User_ID"]	= $row["User_ID"];
		}
	}
	#print "<pre>* Dumping preserved data * \n"; var_dump($permission_tree);

	#	Now add new permission data from form (selected users) to end of packed data array

	foreach ($permission_data["User_ID"] as $checked_user_id) {
		$permission_tree[$packed_index]["ID"]			= sprintf("%04d", $packed_index);
		$permission_tree[$packed_index]["Project_ID"]= $permission_data["Project_ID"];
		$permission_tree[$packed_index++]["User_ID"]	= $checked_user_id;
	}
	#print "\n\n* Dumping preserved and appended form data * \n"; var_dump($permission_tree);

	rewrite_xml_file ( CT_XML_PERMISSIONS_FILE, $permission_tree, "permission" );

	print "<div class='container'><br /><br /><br /><div class='txtSmall'>Project permissions successfully saved.<br />\n".
			"<form method='get' action='codetrack.php'><input type='hidden' name='page' value='adminLinks' />\n".
			"<br /><input type='submit' value=' OK ' /></form></div></div>";
}



FUNCTION save_project_data( $project_data, $project_table ) {

	GLOBAL $debug_g;

	$id = calc_next_node_id( $project_table );
	$project_data["ID"] = sprintf("%02d", $id );

	$project_data["Title"] = eregi_replace( "[^a-z0-9" . CT_INTERNATIONAL_CHARS . ", '\"-]+", '', $project_data["Title"] );
	$project_data["Description"] = eregi_replace( "[^a-z0-9" . CT_INTERNATIONAL_CHARS . " (),-]+", '', $project_data["Description"] );
	$project_data["Test_Lead"]  = eregi_replace( "[^a-z0-9" . CT_INTERNATIONAL_CHARS . "(),-]+", '', $project_data["Test_Lead"] );

	if (	!$project_data["Title"] or !$project_data["Description"] )
		die("<br /><h3><center>Fatal:  Vital form data for create project not received (Title or Description)!");

	print "\n\n<br /><br /><br />\n<div class='container'><table border=0 cellpadding=3 cellspacing=0 summary='Project Maintenance'>\n";
	print "<tr><td colspan=2 class='newUserSummaryTitle'>" .
			" &nbsp; New Project Summary<br />&nbsp;</td></tr>\n\n";

	foreach ($project_data as $field => $contents) {

		$field = strtr($field, '_', ' ');

		print "<tr><td class='newUserSummaryField' align=RIGHT> $field &nbsp;</td>" .
				"<td class='newUserSummaryInfo' align=LEFT>&nbsp; $contents &nbsp;</td></tr>\n\n";
	}
	print "</table>\n";

	$project_data = scrub_and_tag_form_data( $project_data );

	$node = "\t<project>";

	foreach ($project_data as $content)
		$node .= "\n\t\t$content";

	$node .= "\n\t</project>";

	if ($debug_g) {
		print "<pre>\n" . htmlspecialchars($node) . "</pre><br /><br />";
		#phpinfo();
	}
	append_xml_file_node( CT_XML_PROJECTS_FILE, $node, "</projects>" );

	print "<br /><br /><em>This project has been successfully added.</em>".
			"<br /><br /><br /><form action='codetrack.php' method='get'><input type='hidden' name='page' value='adminLinks' /><input type='submit' value=' OK ' /></form>".
			"</div>\n";

}



FUNCTION save_user_data( $user_data, $user_table, $notify_user='', $apache_vars ) {

	GLOBAL $debug_g;

	$host = $apache_vars["HTTP_HOST"];
	$uri  = $apache_vars["REQUEST_URI"];
	$ssl  = $apache_vars["HTTPS"];

	if ( $debug_g )
		print "<br /><strong>Inside save_user_data</strong><br />";

	if (!$user_data["First_Name"] or !$user_data["Last_Name"])
		die("<br /><h3><center>Fatal:  Vital form data for create user not received (First or Last Name)!");


	# Autogenerate password if none given

	if ($user_data["Password"])
		$initial_password = $user_data["Password"];
	else {
		for($len=14,$r=''; strlen($r)<$len; $r.=chr(!mt_rand(0,2)?mt_rand(48,57):(!mt_rand(0,1)?mt_rand(65,90):mt_rand(97,122))))
			;
		$initial_password = $r;
	}

	$id = calc_next_node_id($user_table);

	$user_data["ID"] = sprintf("%04d", $id );

	$user_data["First_Name"] = eregi_replace( "[^a-z0-9" . CT_INTERNATIONAL_CHARS . ",-]+", '', $user_data["First_Name"] );

	$user_data["Last_Name"]  = eregi_replace( "[^a-z0-9" . CT_INTERNATIONAL_CHARS . ",-]+", '', $user_data["Last_Name"] );

	#	$user_data["Phone"] = substr($phone,0,3) ."-". substr($phone,3,3) ."-". substr($phone,6,4);  # Too US-centric

	$phone = ereg_replace( '[^0-9\-\,\.\+ ]+', '', $user_data["Phone"] );		# Be nice to the rest of the world
	$user_data["Phone"] = substr($phone,0,32);


	$user_data["Email"] = eregi_replace( "[^a-z0-9" . CT_INTERNATIONAL_CHARS . "@._-]+", '', strtolower($user_data["Email"]) );

	$user_data["Full_Name"]= $user_data["First_Name"] ." ". $user_data["Last_Name"];

	$user_data["Initials"] = strtoupper(substr($user_data["First_Name"], 0, 1) .
									 substr($user_data["Last_Name"], 0, 1)) ;


	# Autogenerate username if none supplied

	if ($user_data["Username"])
		$user_data["Username"] = eregi_replace ( "[^a-z0-9" . CT_INTERNATIONAL_CHARS . "._-]+", '', $user_data["Username"] );
	else
		$user_data["Username"] = eregi_replace ( "[^a-z0-9" . CT_INTERNATIONAL_CHARS . "._-]+", '',
			 strtolower(substr($user_data["First_Name"], 0, 1) . $user_data["Last_Name"] ) );


	# Check for conflict with existing username, if so, suffix an accession number (jsmith, jsmith1, jsmith2, etc.)

	foreach ($user_table as $user_entry)
		$name_list[] = $user_entry["Username"];

	$username_accession = 0;
	$base_name = $user_data["Username"];
	while (in_array($user_data["Username"], $name_list))
		$user_data["Username"] =  $base_name . ++$username_accession;


	$user_data["Password"] = encrypt_password( $initial_password );

	if ( $debug_g )
		print "<br /><strong>Passed Reg-Ep cleaning in save_user_data</strong><br />";

	print "\n\n<br /><br /><br />\n<div class='container'><table border=0 cellpadding=3 cellspacing=0 summary='New User Confirmation'>\n";
	print "<tr><td colspan=2 class='newUserSummaryTitle'>" .
			" &nbsp; New User Summary<br />&nbsp;</td></tr>\n\n";

	foreach ($user_data as $field => $contents) {

		$field = strtr($field, '_', ' ');

		print "<tr><td class='newUserSummaryField' align=RIGHT> $field &nbsp;</td>" .
				"<td class='newUserSummaryInfo' align=LEFT> &nbsp;" .
				( ($field == 'Password') ? "<span id='displayPwd'>$initial_password</span>" : $contents ) . " &nbsp;</td></tr>\n\n";
	}
	print "</table>\n";


	if ( $notify_user ) {

		$protocol = ( (isset( $ssl )) ? "https://" : "http://" ) ;

		$url = $protocol . $host . $uri ;

		$msg = "\nA new account has been set up for you on CodeTrack. \r\n\r\n" .
				 "Your username is: " . $user_data["Username"] . "\r\n" .
				 "Your initial password is: $initial_password \r\n\r\n" .
				 "Please bookmark this URL: \r\n$url " .
				 "to access the system. \r\n\r\n" .
				 "Also, please verify the spelling and accuracy of the information below. \r\n\r\n" .
				 $user_data["Full_Name"] . ": " . $user_data["Role"];

		$recipient = $user_data["Email"];
	}


	$user_data = scrub_and_tag_form_data($user_data);

	if ( $debug_g )
		print "<br /></div><strong>Passed Data scrub and tag in save_user_data</strong><br />";

	$node = "\t<user>";

	foreach ($user_data as $content)
		$node .= "\n\t\t$content";

	$node .= "\n\t</user>";

	if ( $debug_g ) {
		print "<pre>\n" . htmlspecialchars($node) . "</pre><br /><br />";
		#phpinfo();
	}

	if ( $debug_g ) {
		print "<br /><strong>About to append xml node in save_user_data</strong><br />";
		flush();
	}

	append_xml_file_node( CT_XML_USERS_FILE, $node, "</users>" );


	if ( $notify_user ) {

		if ( $debug_g ) {
			print "<br /><strong>About to mail verification to user in save_user_data</strong><br />";
			flush();
		}

		$subject = "New account on the CodeTrack system";

		if ( CT_USE_EXTRA_MAIL_HEADER )
			mail( $recipient, $subject, $msg,
					"From: " . CT_RETURN_ADDRESS . "\r\n"
					."Reply-To: " . CT_RETURN_ADDRESS . "\r\n"
					."X-Mailer: CodeTrack Mail" , "-f" . CT_RETURN_ADDRESS );
		else
			mail( $recipient, $subject, $msg,
					"From: " . CT_RETURN_ADDRESS . "\r\n"
					."Reply-To: " . CT_RETURN_ADDRESS . "\r\n"
					."X-Mailer: CodeTrack Mail" );

	print "<br /><br />The new account information has been mailed to $recipient.";

		if ( $debug_g ) {
			print "<br /><strong>Mail has been sent in save_user_data</strong><br />";
			flush();
		}
	}

	print (($username_accession) ? '<br /><br /><strong>Note: This username already existed.  An accession number was added.</strong><br />' : '' ) .
			"<br /><br /><em>This user has been successfully added.</em>" .
			"<br /><br /><form action='codetrack.php' method='get'><input type='hidden' name='page' value='adminLinks' />" .
			"<input type='submit' value=' OK ' /></form></div>\n";

	if ( $debug_g )
		print "<br /><strong>About to successfully exit save_user_data!!!</strong><br />";
}



# Take an array in the form: $foo['Summary']="AB`rm *`CD", $foo['Module'] ... etc.
#  First sanitize and then XML-ify it:
#		$foo["Summary"] = "<Summary>ABrm *CD</Summary>" , $foo["Module"] = "<Module>xyz</Module>", etc.
# Any elements containing empty CDATA are removed (unset)

FUNCTION scrub_and_tag_form_data( $data_array ) {

	foreach ($data_array as $element => $untrusted_content) {

		if ( $untrusted_content == '' ) {
			unset($data_array["$element"]);
		}
		else {
			$untrusted_content = trim(substr($untrusted_content, 0, CT_MAX_DESCR_SIZE));		# Keep it to a sane length

			#	Only allow reasonable alphanumerics in text & dropdown fields

			$content = eregi_replace('[^ 0-9a-z' . CT_INTERNATIONAL_CHARS . '_@#$%();:?+*!=&,/"' . "'\n\t.-]+", '',
							$untrusted_content);

			$clean_data = "<$element>" . htmlspecialchars( trim($content) ) . "</$element>";

			$data_array["$element"] = $clean_data;
		}
	}
 return $data_array;
}



FUNCTION search_bugs( $pattern, $search_options ) {

	#	A poor-man's (*very* poor!) full text search.  Not terribly efficient, but at most, we're scanning 2-3MBs...

	GLOBAL $debug_g, $xml_array_g;

	#	Sanitize search string & block cross-site scripting

 $pattern = trim( eregi_replace('([^ .#&$=0-9a-z' . CT_INTERNATIONAL_CHARS . '_-]+)', ' ', $pattern) );
 $pattern = eregi_replace('[ ]+', ' ', $pattern);

 if (!$pattern)
  die('<br /><br /><center><h3>No search terms were supplied. Enter a keyword or phrase, e.g., "blue screen".');

 if (!$search_options)
  die("<br /><br /><center><h3>No search fields specified.  You must choose at least one field (i.e., Summaries).");


 parse_xml_file( CT_XML_BUGS_FILE, CT_DROP_HISTORY, "ID", CT_ASCENDING);

 $bug_array = $xml_array_g;

 if ( $search_options['phrase'] ) {
  $needles[] = $pattern;
  $title = "Issues Containing: &nbsp;<em>&quot;{$pattern}&quot;</em>";
 }
 else {
  $needles = explode(" ", $pattern);
  $title = "Issues Containing: ";
  for ($j=0; $j < sizeof($needles); $j++) {
   if ($j)
    $title .= " or <em>&quot;{$needles[$j]}&quot;</em>";
   else
    $title .= "<em>&quot;{$needles[$j]}&quot;</em>";
  }
 }
 $title .= " &nbsp;(Detailed View Below)";

 $hits = array();
 $max_ceiling_hit = FALSE;

 foreach ($bug_array as $bug) {

  if ( isset($bug["Delete_Bug"]) )
   continue;

  $haystack = '';

  if ( isset($search_options['summary']) )
   $haystack  = $bug["Summary"];
  if ( isset($search_options['description']) )
   $haystack .= " " . $bug["Description"];
  if ( isset($search_options['comment']) )
   $haystack .= " " . $bug["Developer_Comment"];

  $haystack = trim( $haystack );

  foreach($needles as $needle)
   if ( stristr($haystack, $needle) ) {
    $hits[] = $bug;
    break;
   }

		if ( sizeof($hits) == CT_MAX_SEARCH_RESULTS ) {
			$max_ceiling_hit = TRUE;
			$title .= "<br /><em>Note: Too many hits for this search term. Showing first " . CT_MAX_SEARCH_RESULTS . " results.</em>";
			break;
		}
	}

	if ( $hits ) {

		#	Sort hits by ID

		usort($hits, create_function('$a,$b', 'return strcasecmp($a["ID"],$b["ID"]);'));

		draw_table( $hits, $title, '', $project_table );

		print "<div class='container'><span class='txtSmall'>" . msg("Search Results") .
				"</span></div><hr class='hrBlack' /><br />\n";

		/* zztop Why does this work? */

/* tess */

		foreach($hits as $hit) {
			draw_read_only_table($hit);
			print "<br /> __________________________________________ <br /><br />\n";
		}
	}
	else
		print "<br /><br /><center><span class='txtSmall'>" .
			msg("Search Results: <em>No matches found</em>") . "</span></div>\n";

}


# Given the users table and a particular User ID, return current (single) user information

FUNCTION search_users( $user_table, $target_user_id ) {

	$user_data = NULL;
	
	# Do exact type match ( 0001 <> 1 and NULL <> 0 and NULL <> 0000 )
	
	foreach ($user_table as $user)
 		if ($user["ID"] === $target_user_id )  {
  			$user_data = $user;
  			break;
 	}

	if ( !$user_data )
		return FALSE;
	else
		return $user_data;

}



FUNCTION set_session_cookie_load_page( $serialized_session, $page_to_load ) {

	print "<html><script type='text/javascript'>" .
			" document.cookie='codetrack_session=$serialized_session' ; ".
			" location.replace('codetrack.php?page=$page_to_load'); </script></html>";

	exit;
}



FUNCTION update_xml_file_node( $filename, $parsed_tree, $major_element_tag, $update_node ) {

	#	Take a pre-parsed xml data tree, update (replace) specific elements by id, re-write file

	$node_cnt	  = sizeof($parsed_tree);
	$target_id	  = $update_node["ID"];
	$node_updated = FALSE;


	#	Update specific elements with new content, but leave all remaining elements for node in tact

	for ( $node_index = 0; $node_index < $node_cnt; $node_index++ )
		if ( $parsed_tree[$node_index]["ID"] == $target_id ) {
			foreach ( $update_node as $element => $contents ) {
				#print "Updating node: $major_element_tag [ $element ] = $contents <br />\n";
				$parsed_tree[$node_index][$element] = $contents;
			}
			$node_updated = TRUE;
		}

	if (! $node_updated )
		die_now( sprintf( msg("Fatal:  XML Node ID 'X' not found!"), $target_id ));

	#print "<pre>";  var_dump($parsed_tree);  print "</pre>";

	rewrite_xml_file( $filename, $parsed_tree, $major_element_tag );
}



FUNCTION valid_password( $login_data, $user_table, &$user_index ) {

	#	If username is matched, and password is correct, user index is passed back by reference

	$user_cnt = sizeof($user_table);

	$match = FALSE;

	for ($i=0; $i < $user_cnt; $i++) {
		if ( $login_data["username"] == $user_table[$i]["Username"] ) {
			$plaintext = $login_data["password"];
			$hashed    = $user_table[$i]["Password"];
			$salt      = substr($hashed, 0, 2);
			$match     = ( crypt($plaintext, $salt) == $hashed );
			break;
		}
	}
	if ($match) {
		$user_index = $i;
  		return TRUE;
 	}
	else
		return FALSE;
}


?>