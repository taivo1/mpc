<?php
/*
 *  Copyright (C) 2009 Libelium Comunicaciones Distribuidas S.L.
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
 *  Author: Daniel Larraz
 */

$_main_title="FreeRADIUS Manager";
$_plugin_css=Array("basic.css");
$_plugin_javascript=Array("jquery-1.3.2.min.js","jquery.json-1.3.min.js","json_encode.js",
    "jquery.simpletip-1.3.1.min.js", "ajax.js", "jquery.ocupload-1.1.2.packed.js", 
    "certs_panel.js", "users_panel.js", "auth_servers_panel.js", "acct_servers_panel.js",
    "clients_panel.js", "form_fields_check.js", "radius_plugin.js");

include_once $base_plugin.'php/display_radius_info.php';

$html = make_radius();

// $html will be printed by core if $html is defined. But you can uncomment following
// lines if you know what you are doing.
// echo $html;
// unset($html);
?>