<?php


#
# K-Nopaste - Free Nopaste-System
# Copyright (C) 2005-2007  Knut Ahlers
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
 * Class to handle pastes and to generate the pasteform
 */

class pastehandler {
	private $config;
	private $dbhandler;

	public function __construct($conf) {
		/*
		 * Assign global config-object to the local version
		 */
		$this->config = $conf;
		$this->dbhandler = new paste_database($this->config->database);
	}

	public function create_pasteview($pastename) {
		/*
		 * Generates a html-output from the pastefiles
		 */
		
		# If the pastetime is greater than 0 (infinite store time) delete old pastes.
		if($this->config->pastetime > 0) {
			$this->dbhandler->delete_paste(time() - ($this->config->pastetime * 3600));
		}

		if (preg_match("/^.*\.txt$/", $pastename)) {
			# If the textversion of the paste is wanted show it!
			header("Content-Type: text/plain");
			echo $this->dbhandler->load_paste($pastename);
			die();
		} else {
			# Put out the html-version of the paste
			return $this->dbhandler->load_paste($pastename);
		}
	}
	public function create_paste($language, $paste) {
		/*
		 * Generates the pastefile from input
		 */
		$entry = "";
		$paste = rtrim(stripcslashes($paste));
		$path = "classes/geshi/";
		$geshi = new GeSHi($paste, $language, $path);
		$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
		$entry = $geshi->parse_code();
		$entry = str_replace("</span></div>", "</span>&nbsp;</div>", $entry);
		$pastename = $this->dbhandler->save_paste($this->config->usetextfile, $entry, $paste);
		header("Location: ?$pastename");
	}

	public function create_pasteform() {
		/*
		 * Returns html-code for the pasteform
		 */
		$form = "<form action=\"?\" method=\"post\" enctype=\"multipart/form-data\">" .
		"<table style=\"width: 100%;\">";

		$form .= "<tr><td style=\"width: 50px;\">Syntax:</td><td><select name=\"lang\" size=\"1\">";
		$langdir = dir("./classes/geshi/");
		$langarray = array ();
		while ($file = $langdir->read()) {
			if (preg_match("/^.*\.php$/", $file)) {
				$lang = substr($file, 0, strlen($file) - 4);
				$langarray[] = $lang;
			}
		}
		sort($langarray); # ...sort them...
		foreach ($langarray as $lang) {
			if ($lang != "text")
				$form .= "<option value=\"$lang\">$lang</option>\n"; # ...and put them into a listbox
			else
				$form .= "<option value=\"$lang\" selected>$lang</option>\n"; # ...and put them into a listbox
		}
		$form .= "</select></td></tr>";

		$form .= "<tr><td>Paste:</td><td>" .
		"<textarea rows=\"25\" cols=\"10\" name=\"paste\" style=\"width: 100%; height: 100%;\">" .
		"</textarea></td></tr>" .
		"<tr><td>Sourcefile:</td><td><input type=\"file\" name=\"sourcefile\" /> (Max. Size: ".ini_get('post_max_size').")</td></tr>" .
		"<tr><td></td><td><input type=\"submit\" value=\"Submit\" /></td></tr>" .
		"</table>" .
		"</form>";
		return $form;
	}
}
?>
