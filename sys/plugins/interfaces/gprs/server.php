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
include_once $API_core.'list_operators.php';
include_once $API_core.'parser_wvdial.php';
include_once $API_core.'json_api.php';
include_once $API_core.'save_gprs.php';
include_once $base_plugin.'php/refresh_gprs.php';
include_once $base_plugin.'php/display_gprs_info.php';
include_once $API_core.'form_fields_check.php';

$operators_file_path=$base_plugin.'data/operators.txt';
$data=jsondecode($_POST['form_fields']);

if ($_POST['action']=='country')
{
    $str=print_r($data,true);
    //str_replace("\\n", "", $str);
    //response_additem("return", '<pre>'.$str.'</pre>');
    //response_additem("script", 'alert("'.$data['country_list'].'")');
    refresh_gprs($data['country_list'],'');
}
elseif ($_POST['action']=='operator')
{
    $str=print_r($data,true);
    //response_additem("return", '<pre>'.$str.'</pre>');
    refresh_gprs($data['country_list'],$data['country_operators']);
}
elseif (($_POST['action']=='save')||($_POST['action']=='save_restart'))
{

    $fields_check_types = Array (
        'phone'  => Array ('ms_mandatory'),
        'init1'  => Array ('ms_mandatory'),
        'PIN'  => Array ('ms_numerical')
        );
    if(are_form_fields_valid ($data, $fields_check_types))
    {
        save_gprs($data);
        exec('sudo cp '.$base_plugin.'data/wvdial.conf /etc/wvdial.conf');
        //response_additem("return", '<pre>'.print_r($data,true).'</pre>');
        if ($_POST['action']=='save_restart')
        {
            exec('sudo /etc/init.d/wvdial.sh &');
        }
        response_additem("script", 'alert("Data saved.")');
    }
}

// Return the response to javascript
response_return();

?>
