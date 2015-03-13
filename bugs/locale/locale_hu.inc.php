<?php

#Global language string mapper file for all text output in CodeTrack

# To localize, first, make a backup of this original file.
# Then translate the 2nd line of each statement, leaving the first in tact
# Example:       "Foo bar baz" => "Foo bar baz"
#  changes to:
#   "Foo bar baz" => "FĂź ĂĂĽĂ bĂĂŚĂ"
#


# Note: Date localizations options can be found in: config.inc.php

$locale_messages_g = array(

# codetrack.php message text

"You have not been authorized to access any project! Please contact your administrator." =>
"Nincsen hozzáférési jogosultsága a projekekhez! Forduljon a rendszergazdához!",

"FATAL: No issue id specified for audit record!" =>
"HIBA: Hiányzik a hiba azonosító a rekord auditálásához!",

"FATAL: No issue ID specified to view!" =>
"HIBA: Hiányzik a hiba azonosító a megtekintéshez!",

"FATAL: No issue ID specified to edit!" =>
"HIBA: Hiányzik a hiba azonosító a szerkesztéshez." ,

"Address Book" =>
"Címjegyzék",

"Active Projects" =>
"Aktív projektek",

"Show me only the open issues" =>
"Csak a nyitott hibákat mutatsd!",

"Custom Report:<br /><em> All issues matching criteria: &nbsp;" =>
"Egyedi jelentés:<br /><em> Hibák, melyek megfelelnek a következő feltételeknek: &nbsp;",

"Custom Report: <em> Issues from All Projects" =>
"Egyedi jelentés: <em> Az összes projekt hibái.",

"Status" =>
"Állapot",

"Assign_To" =>
"Felelős",

"Project" =>
"Projekt",

"Submitted_By" =>
"Beküldő",

"Severity" =>
"Súlyosság",

"Invalid page requested" =>
"Érvénytelen oldalra próbált lépni",

"Redirecting to login page in six seconds." =>
"Átirányítás a bejelentkező oldalra 6mp múlva",


# functions.inc.php message text

"You are not an administrator." =>
"Nem vagy adminisztrátor.",

"FATAL: Could not open or append to file 'X' ! Check that it is writable by the Apache owner." =>
"HIBA: A(z) '%s' fájl nem nyitható meg vagy nem szerkeszthető! Ellenőrizze, hogy az Apache-nak van-e írási joga.",

"FATAL: Could not append to file 'X' ! Check that it Is writable by the Apache owner." =>
"HIBA: A(z) '%s' fájl nem szerkeszthető! Ellenőrizze, hogy az Apache-nak van-e írási joga.",

"FATAL: Unable to add XML node to " =>
"HIBA: Nem lehet XML node-ot adni a következőhöz:",

"FATAL: No values passed to graph array!" =>
"HIBA: A grafikon nem kapott bemenő adatokat!",

"Bar Graph" =>
"Oszlop diagram",

"Fatal" =>
"Végzetes",

"Serious" =>
"Súlyos",

"Minor" =>
"Kisebb",

"Cosmetic" =>
"Szépséghiba",

"Change Req." =>
"Vált. kér.",

"Total" =>
"Összesen",

"Count by Status" =>
"Állapot szerint",

"Issue" =>
"Hiba",

"All" =>
"Mind",

"Issues" =>
"Hibák",

"Open" =>
"Nyitott",

"Closed" =>
"Zárt",

"Deferred" =>
"Elnapolt",

"FATAL: Unable to create lockfile 'X' !  <br /> Make sure Apache has write permissions in this directory." =>
"HIBA: Nem tudtam zárolási fájlt létrehozni! '%s' <br /> Ellenőrizze, hogy az Apache-nak van-e írási jogosultságe erre a könyvtárra.",

"FATAL: Timeout releasing lockfile 'X' !<br />Please report this message to support." =>
"HIBA: Időtúllépés a zárolási fájl elengedésekor'%s' !<br />Jelentsd a fejlesztőknek!",

"FATAL: Could not delete lockfile 'X' !<br />Please report this message to support." =>
"HIBA: A zárolási fájl nem törölhető '%s' !<br />Jelentsd a fejlesztőknek!",

"FATAL: Internal - No User Data or Selected Project! (This should never happen)" =>
"HIBA: Belső hiba - Nincsenek felhasználói adatok vagy kiválasztott projekt! (Ez nem fordulhat elő)",

"FATAL: Internal - No authentication key!" =>
"HIBA: Belső hiba - Nincs hitelesítő kulcs",

"Quality Assurance Executive Summary" =>
"Minőségbiztosítási összefoglaló",

"Activity for past 30 days" =>
"Tevékenység az elmúlt 30 napban",


# Next several require underscores in the translated version

"change_requests_created" =>
"létrehozott_változtatási_kérelem",

"change_requests_closed" =>
"lezárt_változtatási_kérelem",

"change_requests_deferred" =>
"elnapolt_változtatási_kérelem",

"defect_reports_created" =>
"létrehozott_hiba_jelentés",

"defect_reports_closed" =>
"lezárt_hiba_jelentés",

"defects_deferred" =>
"elnapolt_hiba_jelentés",

"average_lifespan_of_change_requests" =>
"változtatási_kérelmek_átlagos_élettartama",

"average_lifespan_of_defects" =>
"hibák_átlagos_élettartama",


"FATAL: Could not create private key file! Check that the CodeTrack directory is writable by the Apache owner!" =>
"HIBA: Nem tudtam létrehozni személyes kulcs fájlt.! Ellenőrizd, hogy az Apache tulajdonos tudja írni a CodeTrack könyvtárat.",

"FATAL: Could create, but not write to private key file! Check that the CodeTrack drive is not full." =>
"HIBA: Létre tudtam hozni, de nem tudtam írni a személyes kulcs fájlt! Ellenőrizd, hogy a CodeTrack meghajtója nincs-e tele!",

"FATAL: Unable to create backup file copy 'X' from source file 'Y' ! <br /> Check that the directory is readable and writable by Apache." =>
"HIBA: Nem tudtam létrehozni a mentési fájl másolatát '%s' az eredetiből '%s' ! <br /> Ellenőrizd, hogy az Apache írhatja-, és olvashatja-e a könyvtárat!",

"FATAL: No user role found!" =>
"HIBA: Nem találtam felhasználói szerepet!",

"You are not authorized to EDIT issues." =>
"Nincs jogosultsága a bejegyzések módosítására.",

"You are not authorized to create NEW issues." =>
"Nincs jogosultsága új bejegyzés létrehozására.",

"Edit 'X' Issue" =>
"A következő módosítása: %s ",

"Report a New Issue for" =>
"A bejegyzésről jelentést kap",


# Example of the next two reads like:
#	Last updated 1-JAN-2009 by John Adams
#	Submitted 1-JAN-2009 by John Adams

"Last updated on 'DATE' by 'PERSON' " =>
"Utoljára módosította %s napon %s!!!!",

"Submitted on 'DATE' by 'PERSON' " =>
"Beküldte %s napon %s!!!!",


"History" =>
"Előzmények",

"Module or Screen Name" =>
"Modul vagy ablak név",

"Title* <em>(e.g., &quot;BSOD on save&quot;)</em>" =>
"Cím ",

"Full Description* <em> (the more details the better!)</em>" =>
"Teljes leírás* <em> (minél részletesebb, annál jobb!)</em>!!!!",

"Attachment: " =>
"Csatolmány",

"Attachment <em>(screen print, data file, etc.)</em>" =>
"Csatolmány <em>(képernyőkép, adat fájl, stb.)</em>!!!!",

"Version" =>
"Verzió",

"Delete Report" =>
"Jelentés törlése",

"Checking the Delete box will permanently erase this report." =>
"A Töröl négyzet bejejölése véglegesen törölni fogja ezt a jelentést.",

"If you really want to delete this report, click OK then press Save." =>
"Ha valóban törölni szeretné ezt a jelentést, kattitnson az OK-ra, majd nyomja meg a MENTÉS-t.",

"To simply close the issue, cancel now and change the Status category." =>
"Ha csak le szeretné zárni a bejegyzést, nyomjon Mégsem-et, és változtassa meg az Állapot-ot.",

"Tested Browser" =>
"Tesztelt böngésző",

"Browser Specific?" =>
"Böngésző specifikus",


# OS = Operating System
"Tested OS" =>
"Tesztelt op. rendszer.",

"Submitted By" =>
"Beküldő",

"Save" =>
"Mentés",

"Cancel" =>
"Mégsem",

"Undo" =>
"Visszavon",

# Next Three: Issue Status

"Open" =>
"Nyitott",

"Closed" =>
"Lezárt",

"Deferred" =>
"Elnapolt",

"Fatal" =>
"Végzetes",

"Serious" =>
"Súlyos",

"Minor" =>
"Kisebb",

"Cosmetic" =>
"Szépséghiba",

"Change Req." =>
"Vált. kér.",

"Project Title <em>(One or Two Words, or an acronym) </em>" =>
"A projekt címe <em>(Egy vagy két szó, vagy rövidítés) </em>!!!!",

"Lead Developer or Analyst" =>
"Vezető Fejlesztő vagy Elemző",

"Project Description" =>
"A projekt leírása",

"Preferred Title of Responding Team Members" =>
"A válaszadó személy lehetőleg az alábbi beosztású:",

"Web-Based" =>
"Web-alapú",

"Desktop Application" =>
"Normál alkalmazás",

"Data Analysis" =>
"Adatelemzés",

"Type of Project" =>
"A projekt típusa",

"Add a New Project" =>
"Új projekt hozzáadása",

"Developer" =>
"Fejlesztő",

"Analyst" =>
"Elemző",

"Engineer" =>
"Mérnök",

"XML Database Backup Utility" =>
"XML adatbázis biztonsági mentése",

"Existing Entries in" =>
"Bejegyzések a backups mappában",

"Fatal:  Could not read the 'X' directory! Make sure it is read &amp; writable by Apache." =>
"Hiba:  Nem tudtam olvasni a(z) '%s' könyvtárat! Ellenőrizd, hogy az Apache  olvashatja &amp; írjatja.",

"Backup Now" =>
"Biztonsági mentés létrehozása",

"Backups successfully created." =>
"Biztonsági mentés sikeresen létrehozva.",

"Fatal: Draw table passed no data array or title!" =>
"Hiba: A táblázat rajzoló nem küldött adatokat vagy címet!",

"Click on column name to sort" =>
"Kattints az oszlop nevére a rendezéshez",

"ID" =>
"Azon.",

"Summary" =>
"Összefoglaló",

"Last_Updated" =>
"Utoljára módosítva",

"Assigned_To" =>
"Felelős",

"Developer_Response" =>
"Fejlesztő válasza",

"Analyst_Response" =>
"Elemző válasza",

"Engineer_Response" =>
"Mérnök válasza",

"Submit_Time" =>
"Beküldés ideje",

"Updated_By" =>
"Frissítette",

"Title" =>
"Cím",

"Test_Lead" =>
"Test_Lead!!!!",

"Description" =>
"Leírás",

"Project_Type" =>
"Projekt típusa",

"Preferred_Title" =>
"Előnyben részesített beosztás",

"First_Name" =>
"Keresztnév",

"Last_Name" =>
"Vezetéknév",

"Full_Name" =>
"Teljes név",

"Initials" =>
"Monogram",

"Username" =>
"Felhasználói név",

"Email" =>
"Email",

"Phone" =>
"Telefon",

"Role" =>
"Szerep",

"Issue Count:" =>
"Bejegyzések száma:",

"(Oldest to newest, by severity. Red status indicates response or comment needed.)" =>
"(A legrégibbtól a legújabbig, súlyosság szerint. A piros állapotú bejegyzések válaszra vagy megjegyzésre várnak.)",

"Count by Severity" =>
"Bejegyzések szúlyosság szerint",

"Count by Status" =>
"Bejegyzések állapot szerint",

"Fatal:  Could not construct valid encrypted password!" =>
"Hiba:  Nem tudtam létrehozni érvényes, titkosított jelszót.",

"Internal Fatal: No download type specified!" =>
"Belső Hiba: A letöltés típusa nincs meghatározva!",

"Fatal: No issue matching ID# 'X' found!" =>
"Hiba: Nem találtam behegyzést ezzel az azonosítóval '%s'",

"days" =>
"napok",

);
?>