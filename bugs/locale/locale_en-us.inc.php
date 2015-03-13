<?php

#Global language string mapper file for all text output in CodeTrack

# To localize, first, make a backup of this original file.
# Then translate the 2nd line of each statement, leaving the first in tact
# Example:
#
#
#     "Foo bar baz" => "Foo bar baz"
#
#  changes to:
#
#     "Foo bar baz" => "Fü ßåÆ bÆæÇ"
#
#
#   NOTE: This file is set in  *** config.inc.php ****
#
#   Note: Date localizations options can be found in: config.inc.php
#

$locale_messages_g = array(

# codetrack.php message text

'You have not been authorized to access any project! Please contact your administrator.' =>
'You have not been authorized to access any project! Please contact your administrator.',

'FATAL: No issue id specified for audit record!' =>
'FATAL: No issue id specified for audit record!',

'FATAL: No issue ID specified to view!' =>
'FATAL: No issue ID specified to view!',

'FATAL: No issue ID specified to edit!' =>
'FATAL: No issue ID specified to edit!' ,

'Address Book' =>
'Address Book',

'Active Projects' =>
'Active Projects',

'Show me only the open issues' =>
'Show me only the open issues',

'Custom Report:<br /><em> All issues matching criteria: &nbsp;' =>
'Custom Report:<br /><em> All issues matching criteria: &nbsp;',

'Custom Report: <em> Issues from All Projects' =>
'Custom Report: <em> Issues from All Projects',

'Status' =>
'Status',

'Assign_To' =>
'Assign_To',

'Project' =>
'Project',

'Submitted_By' =>
'Submitted_By',

'Severity' =>
'Severity',

'Invalid page requested' =>
'Invalid page requested',

'Redirecting to login page in six seconds.' =>
'Redirecting to login page in six seconds.',


# functions.inc.php message text

'You are not an administrator.' =>
'You are not an administrator.',

"FATAL: Could not open or append to file 'X' ! Check that it is writable by the Apache owner." =>
"FATAL: Could not open or append to '%s' ! Check that it is writable by the Apache owner.",


"FATAL: Could not append to file 'X' ! Check that it is writable by the Apache owner." =>
"FATAL: Could not append to file '%s' ! Check that it Is writable by the Apache owner.",

'FATAL: Unable to add XML node to ' =>
'FATAL: Unable to add XML node to ',

'FATAL: No values passed to graph array!' =>
'FATAL: No values passed to graph array!',

'Bar Graph' =>
'Bar Graph',

'Fatal' =>
'Fatal',

'Serious' =>
'Serious',

'Minor' =>
'Minor',

'Cosmetic' =>
'Cosmetic',

'Change Req.' =>
'Change Req.',

'Total' =>
'Total',

'Count by Status' =>
'Count by Status',

'Issue' =>
'Issue',

'All' =>
'All',

'Issues' =>
'Issues',

'Open' =>
'Open',

'Closed' =>
'Closed',

'Deferred' =>
'Deferred',

"FATAL: Unable to create lockfile 'X' !  <br /> Make sure Apache has write permissions in this directory." =>
"FATAL: Unable to create lockfile! '%s' <br /> Make sure Apache has write permissions in this directory.",

"FATAL: Timeout releasing lockfile 'X' !<br />Please report this message to support." =>
"FATAL: Timeout releasing lockfile '%s' !<br />Please report this message to support.",

"FATAL: Could not delete lockfile 'X' !<br />Please report this message to support." =>
"FATAL: Could not delete lockfile '%s' !<br />Please report this message to support.",

'FATAL: Internal - No User Data or Selected Project! (This should never happen)' =>
'FATAL: Internal - No User Data or Selected Project! (This should never happen)',

'FATAL: Internal - No authentication key!' =>
'FATAL: Internal - No authentication key!',

'Quality Assurance Executive Summary' =>
'Quality Assurance Executive Summary',

'Activity for past 30 days' =>
'Activity for past 30 days',


# Next several require underscores in the translated version

'change_requests_created' =>
'change_requests_created',

'change_requests_closed' =>
'change_requests_closed',

'change_requests_deferred' =>
'change_requests_deferred',

'defect_reports_created' =>
'defect_reports_created',

'defect_reports_closed' =>
'defect_reports_closed',

'defects_deferred' =>
'defects_deferred',

'average_lifespan_of_change_requests' =>
'average_lifespan_of_change_requests',

'average_lifespan_of_defects' =>
'average_lifespan_of_defects',


'FATAL: Could not create private key file! Check that the CodeTrack directory is writable by the Apache owner!' =>
'FATAL: Could not create private key file! Check that the CodeTrack directory is writable by the Apache owner!',

'FATAL: Could create, but not write to private key file! Check that the CodeTrack drive is not full.' =>
'FATAL: Could create, but not write to private key file! Check that the CodeTrack drive is not full.',

"FATAL: Unable to create backup file copy 'X' from source file 'Y' ! <br /> Check that the directory is readable and writable by Apache." =>
"FATAL: Unable to create backup file copy '%s' from source file '%s' ! <br /> Check that the directory is readable and writable by Apache.",

'FATAL: No user role found!' =>
'FATAL: No user role found!',

'You are not authorized to EDIT issues.' =>
'You are not authorized to EDIT issues.',

'You are not authorized to create NEW issues.' =>
'You are not authorized to create NEW issues.',

"Edit 'X' Issue" =>
"Edit %s Issue",

'Report a New Issue for' =>
'Report a New Issue for',


# Example of the next two reads like:
#	Last updated 1-JAN-2009 by John Adams
#	Submitted 1-JAN-2009 by John Adams

"Last updated on 'DATE' by 'PERSON' " =>
"Last updated on %s by %s",

"Submitted on 'DATE' by 'PERSON' " =>
"Submitted on %s by %s",


'History' =>
'History',

'Module or Screen Name' =>
'Module or Screen Name',

'Title* <em>(e.g., &quot;BSOD on save&quot;)</em>' =>
'Title* <em>(e.g., &quot;BSOD on save&quot;)</em>',

'Full Description* <em> (the more details the better!)</em>' =>
'Full Description* <em> (the more details the better!)</em>',

'Attachment: ' =>
'Attachment: ',

'Attachment <em>(screen print, data file, etc.)</em>' =>
'Attachment <em>(screen print, data file, etc.)</em>',

'Version' =>
'Version',

'Delete Report' =>
'Delete Report',

'Checking the Delete box will permanently erase this report.' =>
'Checking the Delete box will permanently erase this report.',

'If you really want to delete this report, click OK then press Save.' =>
'If you really want to delete this report, click OK then press Save.',

'To simply close the issue, cancel now and change the Status category.' =>
'To simply close the issue, cancel now and change the Status category.',

'Tested Browser' =>
'Tested Browser',

'Browser Specific?' =>
'Browser Specific?',


# OS = Operating System
'Tested OS' =>
'Tested OS',

'Submitted By' =>
'Submitted By',

'Save' =>
'Save',

'Cancel' =>
'Cancel',

'Undo' =>
'Undo',

# Next Three: Issue Status

'Open' =>
'Open',

'Closed' =>
'Closed',

'Deferred' =>
'Deferred',

'Fatal' =>
'Fatal',

'Serious' =>
'Serious',

'Minor' =>
'Minor',

'Cosmetic' =>
'Cosmetic',

'Change Req.' =>
'Change Req.',

'Project Title <em>(One or Two Words, or an acronym) </em>' =>
'Project Title <em>(One or Two Words, or an acronym) </em>',

'Lead Developer or Analyst' =>
'Lead Developer or Analyst',

'Project Description' =>
'Project Description',

'Preferred Title of Responding Team Members' =>
'Preferred Title of Responding Team Members',

'Web-Based' =>
'Web-Based',

'Desktop Application' =>
'Desktop Application',

'Data Analysis' =>
'Data Analysis',

'Type of Project' =>
'Type of Project',

'Add a New Project' =>
'Add a New Project',

'Developer' =>
'Developer',

'Analyst' =>
'Analyst',

'Engineer' =>
'Engineer',

'XML Database Backup Utility' =>
'XML Database Backup Utility',

'Existing Entries in' =>
'Existing Entries in',

"Fatal:  Could not read the 'X' directory! Make sure it is read &amp; writable by Apache." =>
"Fatal:  Could not read the '%s' directory! Make sure it is read &amp; writable by Apache.",

'Backup Now' =>
'Backup Now',

'Backups successfully created.' =>
'Backups successfully created.',

'Fatal: Draw table passed no data array or title!' =>
'Fatal: Draw table passed no data array or title!',

'Click on column name to sort' =>
'Click on column name to sort',

'ID' =>
'ID',

'Summary' =>
'Summary',

'Last_Updated' =>
'Last_Updated',

'Assigned_To' =>
'Assigned_To',

'Developer_Response' =>
'Developer_Response',

'Analyst_Response' =>
'Analyst_Response',

'Engineer_Response' =>
'Engineer_Response',

'Submit_Time' =>
'Submit_Time',

'Updated_By' =>
'Updated_By',

'Title' =>
'Title',

'Test_Lead' =>
'Test_Lead',

'Description' =>
'Description',

'Project_Type' =>
'Project_Type',

'Preferred_Title' =>
'Preferred_Title',

'First_Name' =>
'First_Name',

'Last_Name' =>
'Last_Name',

'Full_Name' =>
'Full_Name',

'Initials' =>
'Initials',

'Username' =>
'Username',

'Email' =>
'Email',

'Phone' =>
'Phone',

'Role' =>
'Role',

'Issue Count:' =>
'Issue Count:',

'(Oldest to newest, by severity. Red status indicates response or comment needed.)' =>
'(Oldest to newest, by severity. Red status indicates response or comment needed.)',

'Count by Severity' =>
'Count by Severity',

'Count by Status' =>
'Count by Status!',

'Fatal:  Could not construct valid encrypted password!' =>
'Fatal:  Could not construct valid encrypted password!',

'Internal Fatal: No download type specified!' =>
'Internal Fatal: No download type specified!',

"Fatal: No issue matching ID# 'X' found!" =>
"Fatal: No issue matching ID# %s found!",

'days' =>
'days',

"Fatal: Could not open 'X' ! Check that it is readable by the Apache owner." =>
"Fatal: Could not open '%s' ! Check that it is readable by the Apache owner.",

'Today' =>
'Today',

'Yesterday' =>
'Yesterday',

'History for Issue #' =>
'History for Issue #',

'Original Report' =>
'Original Report',

"On X_DATE, <strong> X_USER </strong> modified this entry" =>
"On %s, <strong> %s </strong> modified this entry",

'someone' =>
'someone',

"'X' was <strong>added</strong>." =>
"'%s' was <strong>added</strong>.",

'Developer_Comment' =>
'Developer_Comment',

'Tested_Browser' =>
'Tested_Browser',

'Browser_Specific' =>
'Browser_Specific',

# OS is short for Operating System
'Tested_OS' =>
'Tested_OS',

"The <strong>X</strong> was <strong>erased</strong>." =>
"The <strong>%s</strong> was <strong>erased</strong>.",

"<strong>SECTION_X</strong> 'VALUE_Y' <strong>changed to</strong> 'VALUE_Z'." =>
"<strong>%s</strong> '%s' <strong> changed to </strong>'%s'.",

'(No updates have been made to this report)' =>
'(No updates have been made to this report)',

'Deleted Issues by Project' =>
'Deleted Issues by Project',

'All Deleted Issues' =>
'All Deleted Issues',

'No deleted issues found' =>
'No deleted issues found',

'Delete_Bug' =>
'Delete_Bug',

"FATAL: Your attachment is not a permissible file. Allowed file types in your config.inc.php are: <br /> 'X'" =>
"FATAL: Your attachment is not a permissible file. Allowed file types in your config.inc.php are: <br /> %s ",

"FATAL: File attachment transfer failed for <br />TMP: 'X' <br />FILE: 'Y' <br />Please notify support!" =>
"FATAL: File attachment transfer failed for <br />TMP: '%s' <br />FILE: '%s' <br />Please notify support!",

"FATAL: Could not truncate 'X' ! Check that it is writable by the Apache owner." =>
"FATAL: Could not truncate '%s' ! Check that it is writable by the Apache owner.",

"FATAL: Unable to write XML data to 'X' " =>
"FATAL: Unable to write XML data to '%s' ",

"System Tools" =>
"System Tools",

'Change your Password' =>
'Change your Password',

'Export Data' =>
'Export Data',

"CodeTrack is 'XLINK' Free </a>software." =>
"CodeTrack is %s Free </a>software.",

'Administrator Tools' =>
'Administrator Tools',

'Maintenance' =>
'Maintenance',

'Technical References' =>
'Technical References',

'License' =>
'License',

'Add a Project' =>
'Add a Project',

'Add a User' =>
'Add a User',

'Change a Password' =>
'Change a Password',

'Export Data' =>
'Export Data',

'Backup XML Databases' =>
'Backup XML Databases',

'List Active Users' =>
'List Active Users',

'Set Permissions' =>
'Set Permissions',

'List Deleted Issues' =>
'List Deleted Issues',

'Installation Guide' =>
'Installation Guide',

'Customizing CodeTrack' =>
'Customizing CodeTrack',

'Troubleshooting Guide' =>
'Troubleshooting Guide',

'CodeTrack ' . CT_CODE_VERSION . ' Changelog' =>
'CodeTrack ' . CT_CODE_VERSION . ' Changelog',

'Functions Reference' =>
'Functions Reference',

'Inventory of all CodeTrack Files' =>
'Inventory of all CodeTrack Files',

'CodeTrack is Free software' =>
'CodeTrack is Free software',

'FAQ about the GNU Public License (GPL)' =>
'FAQ about the GNU Public License (GPL)',

'Fatal:  Vital form data for create issue not received (Project/Severity/Summary/Description/Submitted By)!' =>
'Fatal:  Vital form data for create issue not received (Project/Severity/Summary/Description/Submitted By)!',

"New issue (ID# 'X') has been filed for the 'Y' project by 'Z'" =>
"New issue (ID# 'X') has been filed for the 'Y' project by 'Z'",

# skipped big section...

'Fatal:  No user data found for this ID!' =>
'Fatal:  No user data found for this ID!',


"Search Results" =>
"Search Results",

"Search Results: <em>No matches found</em>" =>
"Search Results: <em>No matches found</em>",


########################################################################
#  Date functions -- see php.net http://xxx/ for customization options #
########################################################################

# hour:minute AM/PM
'g:i A' =>
'g:i A',

);
?>