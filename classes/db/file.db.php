<?
#
# K-Nopaste - Free Nopaste-System
# Copyright (C) 2005-2009  Knut Ahlers
#
# This program is free software: you can redistribute it and/or modify it under the terms of the GNU General 
# Public License as published by the Free Software Foundation, either version 3 of the License, or (at your 
# option) any later version.
# 
# This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the 
# implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for 
# more details.
# 
# You should have received a copy of the GNU General Public License along with this program.
# If not, see <http://www.gnu.org/licenses/>.
#

/*
 * Class for saving and loading pastes to/from files
*/

class paste_database {
	public $version = "1"; # Database-Version - You might work with this to check whether the format is deprecated
	public $type = "file"; # Database-Type - The type "skeleton" will cause an error
	private $pastedir = "";

	public function save_paste($tp, $p_hl, $p_t, $name, $description) {
		/*
		 * This function gets the paste-data and saves them to the database after assigning an idn
		 * $tp = true / false (Generate a text-only-paste)
		 * $p_hl = pasted text with hilights
		 * $p_t = pasted text in plain-text-mode
		 */
		$thispastename = $this->generate_idn();
		if ($tp) {
			file_put_contents($this->pastedir . "$thispastename.txt", rtrim(stripcslashes($p_t)));
		}
		file_put_contents($this->pastedir . "$thispastename", $p_hl);
		return $thispastename;
	}

	public function load_paste($paste_idn) {
		/*
		 * This function returns the paste after reading it from the database
		 */
		$pdir = dir($this->pastedir);
		$availfiles = array ();
		while ($file = $pdir->read()) {
				$availfiles[] = $file;
		}
		if(in_array($paste_idn, $availfiles))
			return file_get_contents($this->pastedir.$paste_idn);
		else
			return "This paste does not longer exist.";
	}

	public function delete_paste($refdate) {
		/*
		 * This function will get an timestamp in unix-format. All pastes older than the stamp should be deleted
		 */
		$pdir = dir($this->pastedir);
		while ($file = $pdir->read()) {
			if((filemtime($this->pastedir.$file) < $refdate) && !is_dir($this->pastedir.$file))
				unlink($this->pastedir.$file); # Delete pastes which are too old
		}
	}
	
	public function can_create_pasteindex() {
		/*
		 * This engine is not able to create an index of the pastes.
		 */
		return false;
	}

	public function get_index() {
		/*
		 * Generates an two dimensional array of pastes.
		 * Fields: pasteid, pastename, pastedescription
		 * Minimum requirement: Return an empty array
		 */ 
		return array();
	}

	public function provide_meta() {
		/*
		 * Returns true if the storage engine is capable to use name and description
		 */
		return false;
	}

	private function init_paste_environment() {
		/*
		 * Here you generate the paste-environment such as the tables in a mysql-database. This function is only
		 * for internal use. The class itself MUST check whether it's nessesary to run this function!
		 */
		# Not used in this class!
	}

	private function generate_idn() {
		/*
		 * This function generates an unique and short identifier which can be assigned to the paste for
		 * loading it with http://nopaste-url.tld/?<idn>
		 */
		return substr(base64_encode(md5(time())), 0, 6);
	}

	public function __construct($setting) { 
		/*
		 * This function is called as constructor which seperates the settings-string and assigns it to the
		 * different local variables. The string has got the following format:
		 * handlertype|user:passwd@host/database/table|other-settings|... - which can be for example:
		 * file|./pastebin
		 * mysql|root:pwd@localhost/knopaste/pastes
		 * This function sets also the connection to the database up (if nessesary)
		 */
		$setting_parts = explode("|", $setting);
		if($setting_parts[0] != "file") {
			die("Error: Wrong settingsline!");
		}
		if(!is_dir($setting_parts[1]) || !is_writable($setting_parts[1])) {
			die("Error: $setting_parts[1] is no directory or not writable. It must have chmod 777!");
		}
		$this->pastedir = $setting_parts[1];
	}
}
?>
