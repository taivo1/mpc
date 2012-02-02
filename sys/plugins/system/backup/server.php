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
include_once $API_core.'form_fields_check.php';
include_once $base_plugin.'php/backup_actions.php';
include_once $base_plugin.'php/interface_generator.php';
include_once $base_plugin.'php/choose_dir_file.php';

if (!empty($_POST['action']))
{
    response_additem ("script", "clear_test_alerts()");
    switch ($_POST['action'])
    {
        case "make_backup":
            if(!empty($_POST['args'])&&ctype_alnum($_POST['args']))
            {
                make_backup($_POST['args']);
                response_additem("script", 'alert("Backup done")');                
                $interface=make_interface();
                response_additem("html",$interface,"backup_interface");
            }
            else
            {
                if (empty($_POST['args']))
                {
                    response_additem ("script", "set_alert('backup_name', 'ms_mandatory')");
                }
                else
                {
                    response_additem ("script", "set_alert('backup_name', 'ms_alnum')");
                }
            }
            response_return();
            break;
        case "restore_backup":
            restore_backup($_POST['args']);
            response_additem("script", 'alert("Backup restored.")');
            //response_additem("return", '<pre>'.print_r($_POST,true).'</pre>');
            response_return();
            break;
        case "delete_backup":
            delete_backup($_POST['args']);
            response_additem("script", 'alert("Backup deleted.")');
            $interface=make_interface();
            response_additem("html",$interface,"backup_interface");
            response_return();
            break;
        case "get_backup":
            $url=get_url($_POST['args']);            
            if(!empty($_POST['args'])&&filter_var($url, FILTER_VALIDATE_URL))
            {
                $interface=make_interface();
                response_additem("html",$interface,"backup_interface");
            }
            else
            {
                if (empty($_POST['args']))
                {
                    response_additem ("script", "set_alert('url_download', 'ms_mandatory')");
                }
                else
                {
                    response_additem ("script", "set_alert('url_download', 'ms_alnum')");
                }
            }
            response_return();
            break;
        case "make_link_backup":
            $url=make_link($_POST['args']);
            response_additem("html", 'Click <a href="'.$url.'">here</a> to download the backup file.',"give_url");
            //response_additem("return", '<pre>'.print_r($_POST,true).'</pre>');
            response_return();
            break;
        default:
            break;
    }    
}
?>