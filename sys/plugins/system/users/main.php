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

$_main_title="Users Manager";
$_plugin_css=Array("basic.css");
$_plugin_javascript=Array("jquery-1.3.2.min.js","jquery.json-1.3.min.js","json_encode.js",
                          "ajax.js", "users_plugin.js","form_fields_check.js");


include_once $base_plugin.'php/display_users_info.php';


$html = make_users_panel();

// $html will be printed by core if $html is defined. But you can uncomment following
// lines if you know what you are doing.
// echo $html;
// unset($html);
?>