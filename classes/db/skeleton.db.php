<?
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
 * Sampleclass for saving and loading pastes
*/

class paste_database {
	public $version = "1"; # Database-Version - You might work with this to check whether the format is deprecated
	public $type = "skeleton"; # Database-Type - The type "skeleton" will cause an error

	public function save_paste($tp, $p_hl, $p_t) {
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
