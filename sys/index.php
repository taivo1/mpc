<?php
/*
 *  Copyright (C) 2008 Libelium Comunicaciones Distribuidas S.L.
 *  http://www.libelium.com
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  Version 0.1
 *  Author: Octavio Benedi Sanchez
 */

// Check for an authorized and logged user.

// Check for server mode or display mode. If we get any post income we assume that
// a server action is required. If there is no post info display mode is used as
// default.

include_once 'core/functions/check_login.php';

// Global version variable.
$manager_system_version="2.0.1";

if(!empty($_POST))
{
    // Servidor mode
    include_once 'core/main_server.php';
}
else
{
    // Display mode
    include_once 'core/main_display.php';
}

?>