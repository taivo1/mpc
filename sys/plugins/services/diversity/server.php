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
include_once $base_plugin.'php/save_diversity.php';
include_once $base_plugin.'php/interface_generator.php';
include_once $base_plugin.'php/parse_diversity.php';

if (!empty($_POST['action']))
{
    switch ($_POST['action'])
    {
        case "save":
            $_POST['interface']=trim($_POST['interface']);
            if(!empty($_POST['interface']))
            {
                $diversity_configuration=jsondecode($_POST['form_fields']);
                save_diversity($diversity_configuration,$_POST['interface']);
                exec('sudo cp '.$base_plugin.'data/diversity.sh /etc/init.d/diversity.sh');
                exec('sudo chmod +x /etc/init.d/diversity.sh;sudo update-rc.d diversity.sh defaults 99');
                response_additem("script", 'alert("Data saved.");data_changed=false;');
                response_return();
            }
            else
            {
                response_additem("script", 'alert("Unexpected error!")');
            }
            break;
        case "saveandrestart":
            $_POST['interface']=trim($_POST['interface']);
            if(!empty($_POST['interface']))
            {
                $diversity_configuration=jsondecode($_POST['form_fields']);
                save_diversity($diversity_configuration,$_POST['interface']);
                exec('sudo cp '.$base_plugin.'data/diversity.sh /etc/init.d/diversity.sh');
                exec('sudo chmod +x /etc/init.d/diversity.sh;sudo update-rc.d diversity.sh defaults 99');
                exec('sudo /etc/init.d/diversity.sh');
                response_additem("script", 'alert("Data saved.");data_changed=false;');
                response_return();
            }
            else
            {
                response_additem("script", 'alert("Unexpected error!")');
            }
            break;
        case "load_interface":            
            $_POST['interface']=trim($_POST['interface']);
            if(!empty($_POST['interface']))
            {
                response_additem("html", make_interface($_POST['interface']),'interface');
            }
            else
            {
                response_additem("html", '','interface');
            }
            response_return();
            break;
        default:
            break;
    }
}

?>