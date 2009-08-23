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
 * Sampleclass for saving and loading pastes
*/

class paste_database {
	public $version = "1"; # Database-Version - You might work with this to check whether the format is deprecated
	public $type = "mysql"; # Database-Type - The type "skeleton" will cause an error
	private $connection = null;

	public function save_paste($tp, $p_hl, $p_t) {
		/*
		 * This function gets the paste-data and saves them to the database after assigning an idn
		 * $tp = true / false (Generate a text-only-paste)
		 * $p_hl = pasted text with hilights
		 * $p_t = pasted text in plain-text-mode
		 */
		$pastename = $this->generate_idn();

		$now = time();
		$sql = "INSERT INTO ".$this->table." (idn, timestamp, hlp, tp) VALUES ('$pastename', $now, '".addslashes($p_hl)."', '".addslashes($p_t)."');";
		if(!mysql_query($sql))
			die("Paste not successfull");

		return $pastename;
		
	}

	public function load_paste($paste_idn) {
		/*
		 * This function returns the paste after reading it from the database
		 */
		$sql = "SELECT * FROM ".$this->table." WHERE idn='".str_replace(".txt", "", $paste_idn)."'";
		$tmp = mysql_query($sql);
		if(mysql_num_rows($tmp) == 0)
			return "This paste does not longer exist.";
		$paste = mysql_fetch_assoc($tmp);
		if(substr(trim($paste_idn), -4) == ".txt")
			return stripslashes($paste["tp"]);
		else
			return $paste["hlp"];
	}

	public function delete_paste($refdate) {
		/*
		 * This function will get an timestamp in unix-format. All pastes older than the stamp should be deleted
		 */
		$sql = "DELETE FROM ".$this->table." WHERE timestamp < $refdate";
		if(!mysql_query($sql))
			die("DELETE failed: ".mysql_error($this->table));
	}

	public function can_create_pasteindex() {
		/*
		 * This engine reports whether the engine is able to create a paste index
		 */
		return true;
	}
	
	public function get_index() {
		/*
		 * Generates an two dimensional array of pastes.
		 * Fields: pasteid, pastename, pastedescription
		 * Minimum requirement: Return an empty array
		 */ 
		$result = array();
		
		$sql = "SELECT idn as pasteid, idn as pastename, SUBSTRING(tp,1,200) as pastedescription FROM " . $this->table . " ORDER BY timestamp DESC";
		$res = mysql_query($sql);
		while($paste = mysql_fetch_assoc($res)) {
			$result[] = $paste;
		}
		
		return $result;
	}

	private function init_paste_environment() {
		/*
		 * Here you generate the paste-environment such as the tables in a mysql-database. This function is only
		 * for internal use. The class itself MUST check whether it's nessesary to run this function!
		 */
		# Table: idn varchar(6), timestamp bigint, highlighted paste text, textpaste text
		$sql = "CREATE TABLE ".$this->table." ("
		    . " idn VARCHAR(6),"
			. " timestamp BIGINT,"
			. " hlp LONGTEXT,"
			. " tp LONGTEXT"
			. ");";
		if( mysql_num_rows( mysql_query("SHOW TABLES LIKE '".$this->table."'")) == 0) {
			if(!mysql_query($sql))
				die("Not able to construct table: ".mysql_error($this->table));
		}
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
		if($setting_parts[0] != "mysql") {
			die("Error: Wrong settingsline!");
		}
		if(!preg_match("/^mysql|.+:.+@.+\/.+\/.+$/", $setting))
			die("Settingsline does not match requirements");
		$tmp = explode("@", $setting_parts[1]);
		$userdata = explode(":", $tmp[0]);
		$hostdata = explode("/", $tmp[1]);
		$this->connection = mysql_connect($hostdata[0], $userdata[0], $userdata[1]);
		mysql_select_db($hostdata[1], $this->connection);
		$this->table = $hostdata[2];
		$this->init_paste_environment();
	}
}

?>
