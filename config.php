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
 * PHP-Script to assign the config values
 */

$config->database = "file|./pastebin/"; # The database-engine with their parameters (Examples see below)
$config->template = "knopaste"; # Name of the template-file without .html-sufix
$config->usetextfile = true; # Create downloadable textfiles? (true / false)
$config->pastetime = 24; # How long should i keep pastes? (Default 24h)
$config->sitetitle = "Knutshome nopaste"; # Title of your nopaste

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
 */

?>
