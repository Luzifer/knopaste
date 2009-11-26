<?php


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
 * PHP-Script to assign the config values
 */

$config->database = "file|./pastebin/"; # The database-engine with their parameters (Examples see below)
$config->template = "knopaste"; # Name of the template-file without .html-sufix
$config->usetextfile = true; # Create downloadable textfiles? (true / false)
$config->pastetime = 24; # How long should i keep pastes? (Default 24h) / 0 for no deletion
$config->sitetitle = "Knutshome nopaste"; # Title of your nopaste
$config->pasteindex = true; # Enable indexing of pastes? (true / false)

/*
 * Examples for using the database-engine:
 * 
 * File-Stored-Pastes:
 * $config->database = "file|<storepath>";
 *   the only parameter is the storepath - it has to be in the format like "./pastebin/" The directory has to
 *   have chmod 777!
 * $config->database = "mysql|<user>:<pass>@<host>/<database>/<table>";
 *   user     = mysql-database-user
 *   pass     = pass of user
 *   host     = databasehost mostly localhost
 *   database = name of the database to use (must exist)
 *   table    = name of the table to use (will be created if not exist)
 *   And: Please remove all < and > you see in the example. ;)
 */

?>
