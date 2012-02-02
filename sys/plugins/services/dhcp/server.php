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
include_once $API_core.'parser_dhcp_server_new.php';
include_once $API_core.'save_dhcp_server_new.php';
include_once $API_core.'common_validators.php';
include_once $API_core.'auto_code_generators.php';
include_once $base_plugin.'php/interface_generator.php';

function check_data($dhcp_configuration)
{
    global $error;
    if (($dhcp_configuration['dhcp_server_eth0'])&&((!is_ip($dhcp_configuration['dhcp_start_eth0']))||(!is_ip($dhcp_configuration['dhcp_end_eth0']))||(!is_numeric($dhcp_configuration['dhcp_expire_eth0']))))
    {
        unset ($dhcp_configuration['dhcp_server_eth0']);
        $error=true;
    }
    if (($dhcp_configuration['dhcp_server_ath0'])&&((!is_ip($dhcp_configuration['dhcp_start_ath0']))||(!is_ip($dhcp_configuration['dhcp_end_ath0']))||(!is_numeric($dhcp_configuration['dhcp_expire_ath0']))))
    {
        unset ($dhcp_configuration['dhcp_server_ath0']);
        $error=true;
    }
    if (($dhcp_configuration['dhcp_server_ath1'])&&((!is_ip($dhcp_configuration['dhcp_start_ath1']))||(!is_ip($dhcp_configuration['dhcp_end_ath1']))||(!is_numeric($dhcp_configuration['dhcp_expire_ath1']))))
    {
        unset ($dhcp_configuration['dhcp_server_ath1']);
        $error=true;
    }
    return $dhcp_configuration;
}
function merge($new_configuration,$old_configuration,$current_interface)
{
    foreach($old_configuration as $interface =>$array)
    {
        $return['dhcp_server_'.$interface] = 'on';
        $return['dhcp_start_'.$interface] = $old_configuration[$interface]['start'];
        $return['dhcp_end_'.$interface] = $old_configuration[$interface]['end'];
        $return['dhcp_expire_'.$interface] = $old_configuration[$interface]['expiration'];
    }
    $return['dhcp_server_'.$current_interface] = $new_configuration['dhcp_server_'.$current_interface] ;
    $return['dhcp_start_'.$current_interface] = $new_configuration['dhcp_start_'.$current_interface] ;
    $return['dhcp_end_'.$current_interface] = $new_configuration['dhcp_end_'.$current_interface] ;
    $return['dhcp_expire_'.$current_interface] = $new_configuration['dhcp_expire_'.$current_interface] ;
    return $return;
}
if (($_POST['type']=="save")||($_POST['type']=="save_restart"))
{
    $error=false;
    $entries=parse_dhcp_server($_POST['interface']);
    $dhcp_configuration=jsondecode($_POST['form_fields']);    
    $dhcp_configuration=check_data($dhcp_configuration);
    $dhcp_configuration=merge($dhcp_configuration,$entries,$_POST['interface']);
    //response_additem("html",$_POST['interface']."<pre>".print_r($entries,true).print_r($dhcp_configuration,true).print_r($dhcp_configuration2,true)."</pre>",'interface');
    if (!$error)
    {
        save_dhcp_server($dhcp_configuration);
        exec ('sudo cp '.$base_plugin.'data/dnsmasq.more.conf /etc/dnsmasq.more.conf');
        if ($_POST['type']=="save_restart")
        {
            exec ('sudo /etc/init.d/dnsmasq restart');
        }
        response_additem("script", 'alert("Data saved");data_changed=false;');
    }
    else
    {
        response_additem("script", 'alert("Configuration is not saved due to invalid values.")');
    }
    response_return();
}
elseif ($_POST['type']=="load_interface")
{
    $_POST['interface']=trim($_POST['interface']);
    if(!empty($_POST['interface']))
    {
        $entries=parse_dhcp_server($_POST['interface']);
        response_additem("html", make_input($_POST['interface'],$entries),'interface');
    }
    else
    {
        response_additem("html", '','interface');
    }
    response_return();
}
?>