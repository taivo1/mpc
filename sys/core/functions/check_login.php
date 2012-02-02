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
function validate_user($user)
{
    if (file_exists('core/globals/users.php'))
    {
        include 'core/globals/users.php';
    }
    else
    {
        echo 'Manager system integrity damaged.';
        exit();
    }
    if (isset($authorized_users[$user]))
    {
		return true;
    }
    return false;
}
session_start();
if(!validate_user($_SESSION['logged_user']))
{
    header('Location: login.php');
    flush();
    exit();
}
session_write_close();
?>
