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
include_once $API_core.'parser_olsrd.php';
include_once $API_core.'save_olsrd.php';
include_once $API_core.'form_fields_check.php';

if ($_POST['type']=="complex")
{
    $olsr_data=jsondecode($_POST['form_fields']);
    $fields_check_types = Array (
        'HelloInterval'  => Array ('ms_float'),
        'HelloInterval'  => Array ('ms_float'),
        'HelloValidityTime'  => Array ('ms_float'),
        'TcInterval'  => Array ('ms_float'),
        'TcValidityTime'  => Array ('ms_float'),
        'MidInterval'  => Array ('ms_float'),
        'MidValidityTime'  => Array ('ms_float'),
        'HnaInterval'  => Array ('ms_float'),
        'HnaValidityTime'  => Array ('ms_float'),
        'Weight'  => Array ('ms_numerical'),
        'TosValue'  => Array ('ms_numerical'),
        'Willingness'  => Array ('ms_numerical'),
        'HystScaling'  => Array ('ms_float'),
        'HysThrLow'  => Array ('ms_float'),
        'HystThrHigh'  => Array ('ms_float'),
        'Pollrate'  => Array ('ms_float'),
        'NicChgsPollInt'  => Array ('ms_float'),
        'MprCoverage'  => Array ('ms_numerical'),
        'LinkQualityWinSize'  => Array ('ms_numerical'),
        'LinkQualityDijkstraLimit1'  => Array ('ms_numerical'),
        'LinkQualityDijkstraLimit2'  => Array ('ms_float'),
        'IpcConnect_MaxConnections'  => Array ('ms_numerical'),
        'ipchost0'  => Array ('ms_ip'),
        'IpcConnect_netaddress1'  => Array ('ms_subnet'),
        'IpcConnect_netmask1'  => Array ('ms_ip'),
        'hna4_netaddress0'  => Array ('ms_subnet'),
        'hna4_netmask0'  => Array ('ms_ip'),
        'hna6_netaddress1'  => Array ('ms_subnet'),
        'hna6_netmask1'  => Array ('ms_ip'),
        'olsrd_httpinfo.so.0.1_port'  => Array ('ms_numerical'),
        'olsrd_httpinfo.so.0.1_net1'  => Array ('ms_subnet'),
        'olsrd_httpinfo.so.0.1_net2'  => Array ('ms_subnet')
        );
    if(are_form_fields_valid ($olsr_data, $fields_check_types))
    {
        save_olsrd($olsr_data);
        exec('sudo cp '.$base_plugin.'data/new_olsd.conf /etc/olsrd/olsrd.conf');
        if($_POST['action']=="saveandrestart")
        {
            exec('sudo /etc/init.d/olsrd stop');
            exec('sudo /etc/init.d/olsrd start');
        }
        response_additem('script', "alert('Data saved')");
    }
    response_return();
}

?>
