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
include_once $base_plugin.'php/save.php';

if (!empty($_POST['action']))
{
    switch ($_POST['action'])
    {
        case "save":
            $post_data=jsondecode($_POST['form_fields']);
            save_values($post_data);
            response_additem("script", 'alert("Data saved.")');
            response_additem("html", exec('date'),'current_date');
            //response_additem("return", '<pre>'.print_r($_POST,true).'</pre>');
            response_return();
            break;
        case "save_restart":
            $post_data=jsondecode($_POST['form_fields']);
            save_values($post_data);
            exec('sudo /etc/init.d/gps_date.sh &');
            exec('sudo /etc/init.d/ntpdate.sh &');
            response_additem("script", 'alert("Data saved.")');
            response_additem("html", exec('date'),'current_date');
            //response_additem("return", '<pre>'.print_r($_POST,true).'</pre>');
            response_return();
            break;
        default:
            break;
    }
}

?>
