<?php

#
#	CodeTrack v. 0.99.4  - A tool to track software defects, bug reports, and change requests
#
#	CodeTrack is a web-based system for reporting and managing bugs and other software
#	defects.  The interface is W3C-compliant, and was specifically designed for cross-browser
#	and cross-platform friendliness.  The engine is written in PHP, and data are stored in
#	simple XML text files.  No database or mail server is required, although there is an option
#	for e-mail notifications using a major SMTP agent (such as sendmail or Qmail).  The
#	goals of the project are to offer a simpler alternative to applications like Bugzilla and
#	Jitterbug, using a professional-quality front-end that can be set up in under 10 minutes.
#
#	See http://www.openbugs.org/ for the latest version of CodeTrack and all documentation.
#
#	This software is released under the GPL and is copyrighted by Kenneth White, 2001-2008
#	Complete license is LICENSE.txt in the docs directory
#
#	It is released under the terms of the GNU General Public License as published by
#	the Free Software Foundation; either version 2 of the License, or
#	(at your option) any later version.
#
#	This program is distributed in the hope that it will be useful,
#	but WITHOUT ANY WARRANTY; without even the implied warranty of
#	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#	GNU General Public License for more details.
#
#	You should have received a copy of the GNU General Public License
#	along with this program; if not, write to the Free Software
#	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
#
#	Note: This document uses tab stops set to 3 (saves 25K of space!)
#


# IF CLIENT PRESSES STOP ON THE BROWSER, DON'T ALLOW FILE UPDATES TO CHOKE. DO *NOT* CHANGE!

ignore_user_abort(TRUE);


# SET ERROR LEVEL TO "E_ALL" ONLY DURING DEBUGGING; PRODUCES OVERLY-PEDANTIC PHP WARNINGS. BELOW IS REQ. FOR PHP 4.2+

error_reporting(E_ERROR);
#error_reporting(E_ALL);


# All customizations in the file below
require("config.inc.php");

# All user-defined functions in file below
require("functions.inc.php");

# Retrieve local server private session key seed, if available (will need to create on first use)
if ( file_exists("keyfile.inc.php") )
	require("keyfile.inc.php");
else {
	create_keyfile();
	header("Location: codetrack.php?page=login");
	exit;
}

# Retrieve localization language file (set in config.inc.php)
if (! include( CT_LOCALIZATION_FILE ) )
		die( "<br /><br /><center>FATAL: No localization language file could be found.
				Failed to load file '" . CT_LOCALIZATION_FILE . "'" );

#print_r($locale_messages_g); exit;


##################################
#  BEGIN MAIN CODE BLOCK  #
##################################


$just_posted = ($_SERVER["REQUEST_METHOD"] == 'POST');


# INITIALIZE GLOBAL VARIABLES


$query_string_g = $_SERVER["QUERY_STRING"];

$debug_g = FALSE;
if ( ( CT_ENABLE_AD_HOC_DEBUG ) and ( isset($_GET["debug"]) or isset($_POST["debug"])) )
	$debug_g = TRUE;

$xml_array_g       = array();
$current_session_g = array();


# SANITY CHECK. WAS ANYTHING REQUESTED?
# DEPENDING ON PAGE, DATA COULD COME EITHER WAY

$page = $_GET["page"];

if (!$page)
	$page = $_POST["page"];

if (!$page) {
	header("Location: codetrack.php?page=login");
	exit;
}

# JUST BECAUSE YOU'RE PARANOID DOESN'T MEAN THEY AREN'T AFTER YOU...
$page = eregi_replace("[^a-z]+", "", $page);


#
# FOR EVERY PAGE BESIDES LOGIN/LOGOUT, VALIDATE THAT A SESSION
# COOKIE EXISTS AND IF SO, EXTRACT SESSION AND PREFERENCE DATA
#

if ( ($page != 'login') and ($page != 'logout') ) {

	$current_session_g = NULL;

	if (isset( $_COOKIE["codetrack_session"] )) {
		$session_cookie = eregi_replace("[^0-9a-z]+", "", $_COOKIE["codetrack_session"]);

		$session_cookie = $_COOKIE["codetrack_session"] ;
		$current_session_g = unserialize(base64_decode(urldecode($session_cookie)));
	}

	if ($current_session_g) {

		$full_name = $current_session_g["user_full_name"];
		$username  = $current_session_g["username"];

		$valid_sid = calc_session_id( $full_name );

		if ( !$valid_sid or ($valid_sid != $current_session_g["id"]) ) {
			header( "Location: codetrack.php?page=login&session=expired&user=$username" );
			exit;
		}
	}
	else {

		# Page other than login requested, but no session cookie present. No bueno!
		#  Either cookies are being blocked (check the referring page), or a direct page bookmark was followed

		if ( stristr($_SERVER['HTTP_REFERER'], "codetrack.php") )
			header( "Location: codetrack.php?page=login&nocookies=true" );
		else
			header("Location: codetrack.php?page=login&session_authentication=missing");
		exit;
	}

}


# IF WE'VE MADE IT HERE, THE SESSION ID WAS FOUND AND IS AUTHENTIC.

if ($debug_g) {
	print "<tt><pre>Raw Cookie:\n$session_cookie \n\nSession Info: \n";  var_dump($current_session_g);  print "</pre></tt><br />\n";
}


# RETRIEVE USERS
# Make complete copy of users (by value) & sort on user last name

parse_xml_file( CT_XML_USERS_FILE , CT_DROP_HISTORY, "Last_Name", CT_ASCENDING );
$user_table = $xml_array_g;


# RETRIEVE USER ACCESS LIST
# Make complete copy of user access list (by value)

parse_xml_file( CT_XML_PERMISSIONS_FILE , CT_DROP_HISTORY, "Project_ID", CT_ASCENDING);
$permission_table = $xml_array_g;

/*
foreach ($user_table as $user)
 if ($user["Full_Name"] == $full_name )  {
  $user_id = $user["ID"];
  break;
 }
*/


# RETRIEVE PROJECTS
# Make complete copy of projects (by value) & sort on project title

parse_xml_file( CT_XML_PROJECTS_FILE, CT_DROP_HISTORY, "Title", CT_ASCENDING );
$project_table = $xml_array_g;
$project_cnt = sizeof($project_table);

# Unless this user is an administrator, pare back the project list to what's authorized
/*
 $all_project_cnt = sizeof( $xml_array_g );

if ( $current_session_g["role"] != 'Admin' )
  for ($p=0; $p < $all_project_cnt; $p++)
   if (! authorized_user( $xml_array_g[$p]["ID"], $user_id, $permission_table ) )
    unset($xml_array_g[$p]);

 usort($xml_array_g, create_function('$a,$b', 'return strcasecmp($a["ID"],$b["ID"]);'));

if ( ! $project_cnt )
 die( msg("You have not been authorized to access any project! Please contact your administrator.") );

#*/


###########################################################
#   MAIN PROGRAM LOGIC BLOCK: WHAT PAGE TO DISPLAY?     #
###########################################################


switch ($page) {


 # LOGIN PAGE & AUTHENTICATION.  IN: $userLogin[] array , $failed flag

 case "login":

  if ($just_posted) {

   $user_login = $_POST["userLogin"];

   $index = "";

    # If password hash matches saved hash, user index is passed back by reference

   if ( valid_password($user_login, $user_table, $index) ) {

    $user_entry = $user_table[$index];
    $selected_project = $user_login["project_name"];

    $cookie = build_new_session_cookie ($user_entry, $selected_project);

    if ( $user_login["password"] == 'codetrack' )    # First time login for Administrators
     set_session_cookie_load_page( $cookie, "changepassword" );
    else
     set_session_cookie_load_page( $cookie, "home" );

    exit;  # We have to stop execution following set_session...()
   }
   else {
    header("Location: codetrack.php?page=login&failed=y");
    exit;
   }
  }
  else {

   $prev_login_failed = FALSE;
   if ( isset($_GET["failed"])  )
    $prev_login_failed = TRUE;

   $session_expired = FALSE;
   if ( isset($_GET["session"])  )
    $session_expired = TRUE;

   $no_cookies = FALSE;
   if ( isset($_GET["nocookies"])  )
    $no_cookies = TRUE;

   draw_login_page( $project_table, $prev_login_failed, $session_expired, $no_cookies );
   exit;
  }



 # LOGOUT OF CODETRACK. IN: $origin

 #  Note, page=timeout is called when client META refresh expires; code below in turn calls login,
 #   passing expiration flag.  Could call page=login&session=expired directly from META,
 #   but ampersands in META clause (escaped or not) break many 4.x browsers.

 case "logout":
 case "timeout":

  print "<html><body><script type='text/javascript'> " .
    "document.cookie=\"codetrack_session=;\";  " .
    "location.replace('codetrack.php?page=login";

  if ( $page == "timeout" )
   print "&session=expired";

  print "');</script></body></html>";
  exit;
 break;



 # PRESENT NEW BUG/ISSUE FORM. IN: (NONE)

 case "newIssue":
  draw_page_top( $page );
  draw_add_edit_bug_form($project_table, $user_table);
  draw_page_bottom();
 break;



 # PRESENT CHANGE PASSWORD FORM. IN: (NONE)

 case "changepassword":
  draw_page_top( $page );
  draw_change_password_form( $user_table );
  draw_page_bottom();
 break;



 # QUICK-AND-DIRTY FULL TEXT SEARCH. IN: $pattern, $search_options[] array

 case "searchissue":
  draw_page_top( $page );
  search_bugs( $_GET["pattern"], $_GET["search_options"] );
  draw_page_bottom();
 break;



 # SAVE POSTED UPDATED PASSWORD.  IN: $user_data[] array, $old_pw

 case "savepassword":
  if ( $just_posted ) {
   draw_page_top( $page );
   update_user( $_POST["user_data"], $user_table, $_POST["old_pw"] );
   draw_page_bottom();
  }
 break;



 # NEW OR EDITED BUG JUST SUBMITTED.
 # IN:  $Attachment[] data array + upload file, $id, $bug_data[] array, $original_submit_time, $cc_list[] array


 case "saveissue":

  if ( $just_posted ) {     # We only allow POSTed bug forms

   if ( $_FILES["Attachment"]["size"] > 0 )
    $attachment_data = $_FILES["Attachment"];
   else
    $attachment_data = array();

   $id        = $_POST["id"];
   $bug_data     = $_POST["bug_data"];
   $original_submit_time = $_POST["original_submit_time"];
   $send_mail      = $_POST["send_mail"];
   $cc_list      = $_POST["cc_list"];

   $xml_array_g = array(); # Lost global scope inside function, so must reinit

   parse_xml_file( CT_XML_BUGS_FILE, CT_DROP_HISTORY, "ID", CT_ASCENDING );
   $bug_table = $xml_array_g;

   draw_page_top( $page );
   save_bug_data( $id, $bug_data, $attachment_data, $original_submit_time,
        $send_mail, $cc_list, $bug_table, $user_table );
   draw_page_bottom();
  }

 break;



 # DOWNLOAD/EXPORT DATA FILES.  IN: $export_options, browser data

 case "export":

  if ($just_posted) {

   $export_options = $_POST["export_options"];
   $user_agent    = $_SERVER["HTTP_USER_AGENT"];
   export_database( $export_options, $user_agent );
   exit;
  }
  else {
   draw_page_top( $page );
   draw_export_options( $current_session_g["project"] );
   draw_page_bottom();
  }

 break;



 # PRESENT TOOLS MENU.  IN: (none)

 case "tools":

  draw_page_top( $page );
  draw_tools_page();
  draw_page_bottom();

 break;



 # PRESENT EXECUTIVE SUMMARY REPORT.  IN: (none)

 case "summary":

  draw_page_top( $page );
  create_summary( $project_table );
  draw_page_bottom();

 break;



 # PRINT AUDIT LIST OF DELETED BUG LINKS.  IN: (none)
 # Note, this is currently only accessible via admin, but it should not be particularly sensitive

 case "deletedissues":

  draw_page_top( $page );
  parse_xml_file( CT_XML_BUGS_FILE, CT_DROP_HISTORY, "ID", CT_ASCENDING );
  $bug_table = $xml_array_g;
  print_deleted_bugs( $bug_table );
  draw_page_bottom();

 break;



 # DISPLAY AUDIT TRAIL.  IN: $id

 case "audit":

  $id = $_GET["id"];

  if (!$id)
   die( msg("FATAL: No issue id specified for audit record!") );

  parse_xml_file( CT_XML_BUGS_FILE, CT_KEEP_HISTORY );

  $data_array = $xml_array_g;

  draw_page_top( $page );
  print_audit_trail($data_array, "$id");  # Force string promotion on id
  draw_page_bottom();

 break;



 # VIEW AN ISSUE.  IN: $id

 case "viewissue":

  $id = $_GET["id"];

  if ( $id ){
   draw_page_top( $page );
   draw_view_bug_page( "$id" );
   draw_page_bottom();
   break;
  }
  else
   die( msg("FATAL: No issue ID specified to view!") );

 break;



 # EDIT AN ISSUE.  IN: $id

 case "editissue":

  $id = $_GET["id"];

  if ( $id ){
   draw_page_top( $page );
   draw_add_edit_bug_form( $project_table, $user_table, "$id" );
   draw_page_bottom();
   break;
  }
  else
   die( msg("FATAL: No issue ID specified to edit!") );

 break;



 # SHOW ALL ACTIVE USERS.  IN: (none)

 case "users":

  draw_page_top( $page );
  draw_table( $user_table, msg("Address Book") , '' );
  draw_page_bottom();

 break;



 # SHOW ALL ACTIVE PROJECTS.  IN: (none)

 case "projects":

  draw_page_top( $page );
  draw_table($project_table, msg("Active Projects"), '');  # Tied to logic in Prj Links
  draw_page_bottom();

 break;



 # DEFAULT PROJECT CHANGE FROM HOTLIST.  IN: $redir, $project;

 case "changeproject":

  $redir = $_GET["redir"];
  $project = $_GET["project"];

  $cookie = build_updated_session_cookie($project, $current_session_g);
  set_session_cookie_load_page($cookie, $redir);
  exit;
 break;



 # HOME PAGE (ISSUE LIST).  IN: $project, $all

 case "home":

  $project = $_GET["project"];
  $all   = $_GET["all"];

  parse_xml_file( CT_XML_BUGS_FILE, CT_DROP_HISTORY, "ID", CT_ASCENDING);

  $bug_array = $xml_array_g;

  if ( isset($project) )
   $filter["Project"] = $project;
  else
   $filter["Project"] = $current_session_g["project"];

  $t = "title='" . msg("Show me only the open issues") . "'";

  if ( isset ($all) )
   $title = msg("All") . " <a href='codetrack.php?page=home' $t>" . $filter["Project"] . "</a> " . msg("Issues");
  else {
   $filter["Status"] = "Open";
   $title = msg("Open"). " <a href='codetrack.php?page=home&amp;all=1' $t>" . $filter["Project"] . "</a> ". msg("Issues");
  }

  draw_page_top( $page );
  draw_table( $bug_array, $title, $filter, $project_table );
  draw_page_bottom();

 break;



 # FILTERED ISSUE REPORT

 case "filter":

  $filter  = $_GET["filter"];

  parse_xml_file( CT_XML_BUGS_FILE, CT_DROP_HISTORY, "ID", CT_ASCENDING);

  $bug_array = $xml_array_g;

  $report_title='';

	foreach ($filter as $filter_name => $filter_value) {
		if (!$filter_value)
			unset($filter[$filter_name]);
		else {
			if (!$report_title)
				$report_title = msg("Custom Report:<br /><em> All issues matching criteria: &nbsp;") ;
			else
				$report_title .= ', &nbsp;';

			$filter_name = msg($filter_name);

			$report_title .= strtr($filter_name, "_", " ") .
			( (strstr($filter_name, "Submitted_By")) ? "" : " =" ) . " $filter_value";
		}
	}
	if (!$report_title)
		$report_title = msg("Custom Report: <em> Issues from All Projects") ;

	draw_page_top( $page );
	draw_table( $bug_array, "$report_title</em>", $filter, $project_table );
	draw_page_bottom();

 break;



 # DRAW REPORTS AND SEARCH PAGE.  IN: (NONE)

 case "reports":
  draw_page_top( $page );
  draw_reports_page($project_table, $user_table);
  draw_page_bottom();
 break;



 #######################################
 #    BEGINNING OF ADMIN-ONLY PAGES    #
 #######################################



 # CREATE BACKUPS OF KEY XML FILES.  IN: (NONE)

  case "dobackup":
   admin_check();
   create_xml_backups();
   header("Location: codetrack.php?page=backup&success=1");
  exit;



  # PRESENT ADMIN TOOLS STATIC LINKS PAGE. IN: (NONE)

  case "adminLinks":
   admin_check();
   draw_page_top( $page );
   draw_admin_page();
   draw_page_bottom();
  break;



  # PRESENT PROJECT ACCESS ADMIN FORM.  IN: (NONE)

  case "projectaccess":
   admin_check();
   draw_page_top( $page );
   draw_project_access_form( $project_table, $user_table, $permission_table );
   draw_page_bottom();
  break;



  # PRESENT XML DATA FILE BACKUP PAGE.  IN: $success

  case "backup":
   admin_check();
   $success = $_GET["success"];
   draw_page_top( $page );
   draw_xml_backups_page( $success );
   draw_page_bottom();
  break;



  # SAVE USER-BY-PROJECT PERMISSION SETTINGS.  IN: $permission_data[] array

  case "savepermissions":
   admin_check();
   $permission_data = $_POST["permission_data"];
   draw_page_top( $page );
   save_permission_data( $permission_data, $permission_table, $project_table );
   draw_page_bottom();
  break;



  # PRESENT ADD NEW USER ADMIN FORM.  IN: (NONE)

  case "adduser":
   admin_check();
   draw_page_top( $page );
   draw_user_maintenance_form();
   draw_page_bottom();
  break;


  # PRESENT EDIT USER ADMIN FORM.  IN: User ID

  case "edituser":
	admin_check();
	draw_page_top( $page );

	$user_id = '0001';
	
	$current_user_data = search_users( $user_table, $user_id );
	
	if ( !$current_user_data )
		die_now( msg('Fatal:  No user data found for this ID!') );

	
	draw_user_maintenance_form( $current_user_data );
	draw_page_bottom();
  break;



  # PRESENT ADD NEW PROJECT ADMIN FORM.  IN: (NONE)

  case "addproject":
   admin_check();
   draw_page_top( $page );
   draw_project_maintenance_form( $user_table );
   draw_page_bottom();
  break;



  # SAVE POSTED NEW USER DATA.  IN: $user_data[] array, $notify_user flag

  case "saveuser":
   admin_check();
   if ($just_posted) {
    $user_data = $_POST["user_data"];
    $notify_user = $_POST["notify_user"];
    $apache_vars = $_SERVER;
    draw_page_top( $page );
    save_user_data( $user_data, $user_table, $notify_user, $apache_vars );
    draw_page_bottom();
   }
  break;



  # SAVE POSTED NEW PROJECT DATA.  IN: $project_data[] array

  case "saveproject":
   admin_check();
   if ($just_posted) {
    $project_data = $_POST["project_data"];
    draw_page_top( $page );
    save_project_data( $project_data, $project_table );
    draw_page_bottom();
   }
  break;


 ###################################
 #   END OF ADMIN-SPECIFIC PAGES   #
 ###################################


 # GENERIC BIT BUCKET FOR BOGUS PAGE REQUEST

 default:
  no_cache();

  $ref = htmlentities($_SERVER['HTTP_REFERER']);

	print '<html><body><meta http-equiv="refresh" content="6; url=' .
		'codetrack.php?page=login&invalidpage=' . $page .'"><center><h4><br /><br /><br />' .
		msg("Invalid page requested")  . " '$page'<br /><br />Referer: $ref <em><br /><br />" .
		msg("Redirecting to login page in six seconds.") . "</em></center></h4></body></html>";
		break;
}

exit;  # EXECUTION STOPS AFTER CASE SWITCH IS PROCESSED

?>