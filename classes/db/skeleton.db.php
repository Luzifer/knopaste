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
	public $type = "skeleton"; # Database-Type - The type "skeleton" will cause an error

	public function save_paste($tp, $p_hl, $p_t, $name, $description) {
		/*
		 * This function gets the paste-data and saves them to the database after assigning an idn
		 * $tp = true / false (Generate a text-only-paste)
		 * $p_hl = pasted text with hilights
		 * $p_t = pasted text in plain-text-mode
		 */
		$pastename = $this->generate_idn();

		# Put your code here!

		return $pastename;
		
	}

	public function load_paste($paste_idn) {
		/*
		 * This function returns the paste after reading it from the database
		 */
		
	}

	public function delete_paste($refdate) {
		/*
		 * This function will get an timestamp in unix-format. All pastes older than the stamp should be deleted
		 */
		
	}

	public function can_create_pasteindex() {
		/*
		 * This engine reports whether the engine is able to create a paste index
		 */
		
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
		
	}

	private function generate_idn() {
		/*
		 * This function generates an unique and short identifier which can be assigned to the paste for
		 * loading it with http://nopaste-url.tld/?<idn>
		 */
		
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
		
	}
}

?>
