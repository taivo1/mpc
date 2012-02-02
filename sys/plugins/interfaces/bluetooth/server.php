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

// Predefined variables:
// $section contains the section folder name.
// echo "section=".$section."<br>";
// $plugin contains the plugin folder name.
// echo "plugin=".$plugin."<br>";
// $section and $plugin can be used to make a link to this plugin by just reference
// echo "<a href=\"index.php?section=$section&plugin=$plugin\">This plugin</a>"."<br>";
// $base_plugin contains the path that must be used as start to includes for
// plugin includes that need the local path.
// example: include_once $base_plugin.'php/my_include.php';
// echo "base_plugin=".$base_plugin."<br>";
// $url_plugin contains the url base that must be used to include html items
// such as images.
// example: <img src="'.url_plugin.'images/my_image.png">
// echo "url_plugin=".$url_plugin."<br>";
// $API_core contains the path to the core API folder.
// example: include_once $API_core.'is_active.php';
// echo "API_core=".$API_core."<br>";

// Plugin server produced data will returned to the ajax call that made the
// request.
include_once $API_core.'complex_ajax_return_functions.php';
include_once $API_core.'json_api.php';
include_once $API_core.'save_hcid.php';
include_once $API_core.'parser_hcid.php';

if ($_POST['type']=="save")
{
    $hcid_configuration=jsondecode($_POST['form_fields']);
    save_hcid($hcid_configuration,$base_plugin.'data/hcid.conf');
    exec('sudo cp '.$base_plugin.'data/hcid.conf /etc/bluetooth/hcid.conf');
    //response_additem("return", '<pre>'.print_r($hcid_configuration,true).'</pre>');
    response_additem("script", 'alert("Data saved.");');
    response_return();
}
elseif ($_POST['type']=="save_restart")
{
    $hcid_configuration=jsondecode($_POST['form_fields']);
    save_hcid($hcid_configuration,$base_plugin.'data/hcid.conf');
    exec('sudo cp '.$base_plugin.'data/hcid.conf /etc/bluetooth/hcid.conf');
    exec('sudo /etc/init.d/bluetooth restart');
    //response_additem("return", '<pre>'.print_r($hcid_configuration,true).'</pre>');
    response_additem("script", 'alert("Data saved.");');
    response_return();
}

?>