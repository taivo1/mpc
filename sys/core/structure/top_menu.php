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
// Just close body and html tags.
$hostname=exec("hostname");
$core_top_menu='
<div id="top_menu">
    <div id="application_banner" onclick="window.location=\'index.php\'"></div>
    <div id="top_navigation_menu_hostname">
        '.$hostname.'
    </div>
    <div id="top_navigation_menu">        
        <a class="top_navigation_menu_item" href="index.php">Home</a>
        <a class="top_navigation_menu_item" href="index.php?logout=true"> | Logout</a>
    </div>
</div>
';
?>
