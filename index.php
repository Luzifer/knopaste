<?

#
# K-Nopaste - Free Nopaste-System
# Copyright (C) 2005-2009  Knut Ahlers
#
# This program is free software; you can redistribute it and/or modify it under the terms of the GNU General 
# Public License as published by the Free Software Foundation; either version 2 of the License, or (at your 
# option) any later version.
# 
# This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the 
# implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for 
# more details.
# 
# You should have received a copy of the GNU General Public License along with this program; if not, write to the 
# Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
#

/*
 * Mainscript to decide which module is loaded
*/

$content = "";

# Load configuration
if (is_file("config.php") && is_readable("config.php")) {
	include ("config.php");
} else {
	die("ERROR: CONFIGFILE NOT FOUND!<br />");
}

# Find out what database-engine to use and load it
$edir = dir("./classes/db/");
$availengines = array ();
while ($file = $edir->read()) {
	if(is_file("./classes/db/$file")){
		$availengines[] = substr($file, 0, strlen($file) - 7);
	}
}
$dbarr = explode("|", $config->database);
if(in_array($dbarr[0], $availengines))
	include("./classes/db/$dbarr[0].db.php");
else
	die("ERROR: The selected database-engine '$dbarr[0]' does not exist!");

# Load pasteengine
if (is_file("classes/pastehandler.php") && is_readable("classes/pastehandler.php")) {
	include ("classes/pastehandler.php");
	$pengine = new pastehandler($config);
} else
	die("ERROR: Pasteengine not found. Renew your complete copy of K-Nopaste!");

# Generate main menuentries
$tl_links = "<span class=\"tl_links\"><a href=\"?\">Create new paste</a></span>";
if($pengine->pasteindex_available())
	$tl_links .= "<span class=\"tl_links\"><a href=\"?_showindex\">Show index of pastes</a></span>";

# Try to get pastename and rise the action to generate the output
if ($_SERVER['QUERY_STRING'] != "") {
	$pasteid = urldecode($_SERVER['QUERY_STRING']);
	if(strpos($pasteid, "?") != -1) {
		$pasteid = str_replace("?", "", $pasteid);
	}
	
	if($pasteid == '_showindex') {
		if(!$pengine->pasteindex_available()) {
			header('Location: ?');
		}
		$content = $pengine->create_index();
	} else {
		$content = $pengine->create_pasteview($pasteid);
		if ($config->usetextfile) {
			$tl_links .= "<span class=\"tl_links\"><a href=\"?" . htmlentities(urldecode($_SERVER['QUERY_STRING'])) . ".txt\">Download as text</a></span>";
		}
	}
} else {
	if (isset ($_POST['paste']) && ($_POST['paste'] != "") || is_uploaded_file($_FILES['sourcefile']['tmp_name'])) {
		# If wanted load geshi
		if (is_file("classes/geshi.php") && is_readable("classes/geshi.php")) {
			include ("classes/geshi.php");
		} else
		die("ERROR: Highlight-engine not found. Please get a newer copy of K-Nopaste!");
		
		$paste = $_POST['paste'];
		if(is_uploaded_file($_FILES['sourcefile']['tmp_name'])) {
			$paste .= "\n\n" . file_get_contents($_FILES['sourcefile']['tmp_name']);
		}
		
		$pengine->create_paste($_POST['lang'], ltrim($paste));
	} else {
		$content = $pengine->create_pasteform();
	}
}

# Set version of the script and create the sitetitle
$version = "K-Nopaste 3.5.0";
$url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$title = $config->sitetitle . " ($version)";
$copy = "<a href=\"http://github.com/Luzifer/knopaste/\">$version</a> &copy; 2005 - 2009 by K. Ahlers - <a href=\"http://blog.knut.me\">Knuts Blog</a>";

# Insert content to template and display the site
$template = file_get_contents("templates/" . $config->template . ".html");
$template = str_replace("%title%", $title, $template);
$template = str_replace("%tl_links%", $tl_links, $template);
$template = str_replace("%mainlayer%", $content, $template);
$template = str_replace("%VERSION%", $version, $template);
$template = str_replace("%COPY%", $copy, $template);

echo $template;
?>
