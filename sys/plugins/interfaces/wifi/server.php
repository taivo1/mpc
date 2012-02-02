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
include_once $API_core.'save_interfaces.php';
include_once $API_core.'parser_interfaces.php';
include_once $API_core.'modify_mac_filter.php';
include_once $API_core.'auto_code_generators.php';
include_once $base_plugin.'php/display_mac_filter.php';
include_once $base_plugin.'php/display_wifi_info.php';
//response_additem("return", '<pre>'.print_r($_POST,true).'</pre>');
if ($_POST['type']=="complex")
{
    switch($_POST['action'])
    {
        case 'default':
            $post_data=jsondecode($_POST['form_fields']);

            if($post_data['iface_sel']=='static')
            {
                 $fields_check_types = Array (
                    'address'  => Array ('ms_ip','ms_mandatory'),
                    'netmask'  => Array ('ms_ip','ms_mandatory'),                    
                    'DNS1'  => Array ('ms_ip'),
                    'DNS2'  => Array ('ms_ip'),
                    'broadcast'  => Array ('ms_ip'),
                    'essid' => Array ('ms_mandatory'),
                    'frag' => Array ('ms_alnum'),
                    );
                if($post_data['mode']=='managed')
                {
                    $fields_check_types['gateway']=Array ('ms_ip');
                    $fields_check_types['mac_essid_i']=Array ('ms_mac');
                }
            }
            else
            {
                $fields_check_types = Array (
                    'essid' => Array ('ms_mandatory'),
                    'frag' => Array ('ms_alnum'),
                    );
                if($post_data['mode']=='managed')
                {
                    $fields_check_types['mac_essid_i']=Array ('ms_mac');
                }
            }
            if(are_form_fields_valid ($post_data, $fields_check_types))
            {
                saveInterfaces($_POST['interface'],$post_data,"/etc/network/interfaces","/etc/network/interfaces");
                if(save_security_config ($_POST['interface'], $post_data, $post_data['mode']))
                {
                    response_additem("script", 'alert("Data saved.");data_changed=false;');
                }
            }
            break;        
        case 'add_mac':
            $post_data=jsondecode($_POST['form_fields']);
            if (is_mac($post_data['mac_filter_add_'.$_POST['interface']]))
            {
                add_mac_filter($post_data['mac_filter_add_'.$_POST['interface']],$_POST['interface'],$post_data['mac_list_type_'.$_POST['interface']]);
                response_additem("option", $post_data['mac_filter_add_'.$_POST['interface']],'mac_list_'.$_POST['interface']);
            }
            else
            {
                response_additem("script",'alert("MAC malformed")');
            }
            break;
        case 'del_mac':
            $post_data=jsondecode($_POST['form_fields']);
            if (is_mac($post_data['mac_list_'.$_POST['interface']]))
            {
                del_mac_filter($post_data['mac_list_'.$_POST['interface']],$_POST['interface'],$post_data['mac_list_type_'.$_POST['interface']]);
            }
            response_additem("html",make_mac_filter($_POST['interface'],'yes'),'mac_filter_div');
            response_additem("script",'check_conditions()');
            break;
        case 'modify_mac':
            //change_list_type($type,$interface)
            $post_data=jsondecode($_POST['form_fields']);
            change_list_type($post_data['mac_list_type_'.$_POST['interface']],$_POST['interface']);
            response_additem("html",make_mac_filter($_POST['interface'],'yes'),'mac_filter_div');
            break;
        case 'load_interface':
            $_POST['interface']=trim($_POST['interface']);
            if (!empty($_POST['interface']))
            {
                $html=make_wireless('/etc/network/interfaces',$_POST['interface'],false);
            }
            else
            {
                $html=' ';
            }
            response_additem("html",$html,'interface_info');
            response_additem("script",'check_conditions()');
            break;
    }
    response_return();
}

/* ------------------------------------------------------------------------ */

include_once $API_core.'conf_file.php';
include_once $base_plugin.'php/paths.php';

/* ------------------------------------------------------------------------ */

function execute ($cmd)
/* ------------------------------------------------------------------------ */
{
    //global $output;
    exec ("sudo ".$cmd, $return);
    //$output = array_merge ($output, $return); // DEBUG LOG
}
/* ------------------------------------------------------------------------ */

function save_msg ($msg)
/* ------------------------------------------------------------------------ */
{
    //response_additem ("html", "<fieldset><h2>'.$msg.'</h2></fieldset>" ,"output");
    response_additem ("script", "alert('".$msg."')");
}
/* ------------------------------------------------------------------------ */

function error_msg ($msg)
/* ------------------------------------------------------------------------ */
{
    //response_additem ("html", "<fieldset><h2>'.$msg.'</h2></fieldset>" ,"output");
    response_additem ("script", "alert('".$msg."')");
}
/* ------------------------------------------------------------------------ */

function save_security_config ($iface, $post_data, $mode)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $API_core;

    include_once $API_core.'parser_interfaces.php';
    $interfaces = parse_interfaces ($paths['interfaces']);

    if ($mode == 'ad-hoc')
    {
        $post_data['protocol'] = 'none';
    }

    $hostapd_file = $paths['hostapd']."_".$iface.".conf";

    $security = load_conf_file ($paths['security']);
    $security[$iface] = array();
    $security[$iface]['protocol'] = $post_data['protocol'];

    $valid_request = false;

    switch ($post_data['protocol'])
    {
        case 'none':
            $interfaces=remove_wep ($iface, $interfaces, $mode);
            $interfaces=remove_wpa ($iface, $hostapd_file, $interfaces, $mode);
            include_once $API_core.'write_interfaces.php';
            write_interfaces ($paths['interfaces'], $interfaces);
            restart_interface ($iface);
            $valid_request = true;
            break;

        case 'wep':
            if ( is_wep_pass_valid ($post_data) )
            {
                $interfaces=remove_wpa ($iface, $hostapd_file, $interfaces, $mode);
                $security = add_wep ($iface, $post_data, $security, $interfaces, $mode);
                restart_interface ($iface);
                $valid_request = true;
            }
            break;

        case 'wpa':
            if ( is_wpa_form_valid ($iface, $post_data, $interfaces) )
            {
                $interfaces=remove_wep ($iface, $interfaces, $mode);
                $security = add_wpa ($iface, $post_data, $security, $hostapd_file, $interfaces, $mode);
                $valid_request = true;
            }
            break;
    }

    if ($valid_request)
    {
        save_conf_file ($paths['security'], $security);
        //response_additem ("html", "<fieldset><h2>Changes applied.</h2></fieldset>" ,"output");
        //save_msg ("Changes applied.");
        return true;
    }
    return false;
}
/* ------------------------------------------------------------------------ */

function is_wpa_pass_valid ($post_data)
/* ------------------------------------------------------------------------ */
{
    $len_wpa_pass = strlen ($post_data['psk_pass']);
    return ($len_wpa_pass >= 8 && $len_wpa_pass <= 63);
}
/* ------------------------------------------------------------------------ */

function is_wpa_psk_form_valid ($post_data)
/* ------------------------------------------------------------------------ */
{
    $is_valid = false;

    if ( !is_wpa_pass_valid ($post_data) )
    {
        error_msg("Invalid WPA PSK password long.");
    }
    elseif ( $post_data['psk_pass'] != $post_data['cnf_psk_pass'] )
    {
        error_msg("WPA PSK password missmatch.");
    }
    else
    {
        $is_valid = true;
    }

    return $is_valid;
}
/* ------------------------------------------------------------------------ */

function is_wpa_eap_remote_form_valid ($post_data)
/* ------------------------------------------------------------------------ */
{
    global $API_core;
    include_once $API_core.'common_validators.php';

    $is_valid = false;

    if ( !is_ip($post_data['radius_addr']) )
    {
        error_msg("RADIUS IP address is invalid.");
    }
    elseif (intval($post_data['radius_port']) < 1025 || intval($post_data['radius_port']) > 65535)
    {
        error_msg("RADIUS port is invalid.");
    }
    elseif ( strlen($post_data['radius_pass']) == 0 )
    {
        error_msg("RADIUS password invalid.");
    }
    elseif ( $post_data['radius_pass'] != $post_data['cnf_radius_pass'])
    {
        error_msg("RADIUS password missmatch.");
    }
    else
    {
        $is_valid = true;
    }

    return $is_valid;
}
/* ------------------------------------------------------------------------ */

function is_wpa_form_valid ($iface, $post_data, $interfaces)
/* ------------------------------------------------------------------------ */
{
    $is_valid = false;

    $wpa_checked = ($post_data['wpa_psk_ckb'] || $post_data['wpa_eap_ckb']);

    if ( !$wpa_checked )
    {
        error_msg("At least one WPA method must be selected.");
    }
    elseif ( $post_data['wpa_psk_ckb'] && !is_wpa_psk_form_valid ($post_data) )
    {}
    elseif ( $post_data['wpa_eap_ckb'] && $post_data['radius_connection'] == "remote" &&
             !is_wpa_eap_remote_form_valid ($post_data) )
    {}
    else
    {
        $is_valid = true;
    }

    return $is_valid;
}
/* ------------------------------------------------------------------------ */

function add_iface_data ($iface, $interfaces, $hostapd)
/* ------------------------------------------------------------------------ */
{
    $essid = $interfaces[$iface]['post-up']['iwconfig']['essid'];
    $hw_mode = $interfaces[$iface]['up']['iwpriv']['mode'];

    switch ($hw_mode)
    {
        case 1: $hw_mode = 'b'; break;
        case 2: $hw_mode = 'g'; break;
        case 3: $hw_mode = 'a'; break;
    }
    $channel = $interfaces[$iface]['post-up']['iwconfig']['channel'];

    $hostapd['interface'] = $iface;
    $hostapd['ssid'] = $essid;
    $hostapd['hw_mode'] = $hw_mode;
    $hostapd['channel'] = $channel;

    return $hostapd;
}
/* ------------------------------------------------------------------------ */

function restart_hostapd ($hostapd_file)
/* ------------------------------------------------------------------------ */
{
    exec ("sudo ps ax | grep ".$hostapd_file." | grep -v grep | awk '{print $1;}'", $pids);
    if ( count($pids) > 0 )
    {
        execute ('kill -1 '.$pids[0]);
    } else {
        execute ('/usr/sbin/hostapd -B '.$hostapd_file);
    }
}

/* ------------------------------------------------------------------------ */

function add_wpa_psk ($iface, $post_data, $security, $hostapd)
/* ------------------------------------------------------------------------ */
{
    $hostapd['wpa_key_mgmt'] = "WPA-PSK ";
    // wpa_passphrase essid password
    exec('wpa_passphrase '.$hostapd['ssid'].' '.$post_data['psk_pass'], $return);
    $hostapd['wpa_psk'] = substr ($return[3], 5);

    $security[$iface]['wpa_psk'] = $post_data['psk_pass'];
    $security[$iface]['wpa_mgmt'][] = 'psk';

    return array ($security, $hostapd);
}
/* ------------------------------------------------------------------------ */

function add_wpa_eap ($iface, $post_data, $security, $hostapd)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $hostapd['wpa_key_mgmt'] .= "WPA-EAP";
    $security[$iface]['wpa_mgmt'][] = 'eap';
    $security[$iface]['radius_connection'] = $post_data['radius_connection'];
    if ($post_data['radius_connection'] == "remote")
    {
        $hostapd['auth_server_addr'] = $post_data['radius_addr'];
        $hostapd['auth_server_port'] = $post_data['radius_port'];
        $hostapd['auth_server_shared_secret'] = $post_data['radius_pass'];
        $security[$iface]['radius_addr'] = $post_data['radius_addr'];
        $security[$iface]['radius_port'] = $post_data['radius_port'];
        $security[$iface]['radius_pass'] = $post_data['radius_pass'];
    }
    else
    {
        $security[$iface]['virtual_server'] = $post_data['virtual_server'];
        $servers = load_conf_file ($paths['auth_servers']);
        $info = $servers[$post_data['virtual_server']];
        $hostapd['auth_server_port'] = $info['port'];
        $hostapd['auth_server_addr'] = '127.0.0.1';
        $clients = load_conf_file ($paths['clients']);
        $hostapd['auth_server_shared_secret'] = $clients['localhost']['pass'];
    }

    return array ($security, $hostapd);
}
/* ------------------------------------------------------------------------ */

function add_wpa ($iface, $post_data, $security, $hostapd_file, $interfaces, $mode)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $API_core;

    if ($mode == "master")
    {
        include_once $API_core.'parser_hostapd.php';
        $hostapd = parse_hostapd ($paths['hostapd_skeleton']);

        $hostapd = add_iface_data ($iface, $interfaces, $hostapd);

        if ($post_data['wpa_psk_ckb'])
        {
            list ($security, $hostapd) = add_wpa_psk ($iface, $post_data, $security, $hostapd);
        }

        if ($post_data['wpa_eap_ckb'])
        {
            list ($security, $hostapd) = add_wpa_eap ($iface, $post_data, $security, $hostapd);
        }

        // Write hostapd configuration file
        include_once $API_core.'write_hostapd.php';
        write_hostapd ($hostapd_file, $hostapd);

        //Write interfaces
        $interfaces[$iface]['post-up']['hostapd'] = '-B '.$hostapd_file;
        include_once $API_core.'write_interfaces.php';
        write_interfaces ($paths['interfaces'], $interfaces);

        // Apply changes
        stop_wpa_supplicant('/etc/wpa_supplicant_'.$iface);
        stop_hostapd($hostapd_file);
        restart_interface($iface);
    }
    else // mode == 'managed'
    {
        $wpa_supplicant_file='/etc/wpa_supplicant_'.$iface;

        if ($post_data['wpa_psk_ckb'])
        {
            list ($security, $hostapd) = add_wpa_psk ($iface, $post_data, $security, $hostapd);
        }

        // Create wpa_supplicant configuration file
        include_once $API_core.'write_wpa_supplicant.php';
        write_wpa_supplicant ($wpa_supplicant_file, $post_data['essid'], $post_data['psk_pass'], $post_data['hide']);

        // Write interfaces
        $interfaces[$iface]['post-up']['wpa_supplicant'] = "-B -i".$iface." -c".$wpa_supplicant_file." &";
        include_once $API_core.'write_interfaces.php';
        write_interfaces ($paths['interfaces'], $interfaces);

        // Apply changes
        stop_hostapd($hostapd_file);
        stop_wpa_supplicant($wpa_supplicant_file);
        restart_interface($iface);
    }

    return $security;
}
/* ------------------------------------------------------------------------ */

function is_wep_pass_valid ($post_data)
/* ------------------------------------------------------------------------ */
{
    $len_pass = strlen($post_data['wep_pass']);

    switch ($post_data['key_size'])
    {
        case "40":
            $is_valid =  $len_pass == 5;
            break;

        case "104":
            $is_valid = $len_pass == 13;
            break;

        default:
            $is_valid = false;
    }

    if ( !$is_valid )
    {
        error_msg("Invalid WEP password long.");
    }
    else
    {
        if ( $post_data['wep_pass'] != $post_data['cnf_wep_pass'] )
        {
            error_msg("WEP password missmatch.");
        }
    }

    return $is_valid;
}
/* ------------------------------------------------------------------------ */

function add_wep ($iface, $post_data, $security, $interfaces, $mode)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $API_core;

    if ($mode == "master")
    {
        $interfaces[$iface]['up']['iwpriv']['authmode'] = 1;
        $interfaces[$iface]['post-up']['iwconfig']['enc'] = $post_data['wep_pass'];
        unset($interfaces[$iface]['post-up']['iwconfig']['key']);

        include_once $API_core.'write_interfaces.php';
        write_interfaces ($paths['interfaces'], $interfaces);

        $security[$iface]['wep_pass'] = $post_data['wep_pass'];
    }
    else
    {
        $interfaces[$iface]['up']['iwpriv']['authmode'] = 1;
        $interfaces[$iface]['post-up']['iwconfig']['key'] = $post_data['wep_pass'];
        unset($interfaces[$iface]['post-up']['iwconfig']['enc']);

        include_once $API_core.'write_interfaces.php';
        write_interfaces ($paths['interfaces'], $interfaces);

        $security[$iface]['wep_pass'] = $post_data['wep_pass'];
    }

    return $security;
}
/* ------------------------------------------------------------------------ */

function remove_wep ($iface, $interfaces, $mode)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $API_core;

    if ( isset ($interfaces[$iface]['up']['iwpriv']['authmode']) )
    {
        unset ($interfaces[$iface]['up']['iwpriv']['authmode']);
        unset ($interfaces[$iface]['post-up']['iwconfig']['enc']);
        unset ($interfaces[$iface]['post-up']['iwconfig']['key']);

        //if ($mode == "master")
        //{
        //    include_once $API_core.'write_interfaces.php';
        //    write_interfaces ($paths['interfaces'], $interfaces);
        //}
    }
    return $interfaces;
}
/* ------------------------------------------------------------------------ */

function remove_wpa ($iface, $hostapd_file, $interfaces, $mode)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $API_core;

    // Remove hostapd instances
    exec ("sudo ps ax | grep ".$hostapd_file." | grep -v grep | awk '{print $1;}'", $pids);

    foreach ($pids as $pid)
    {
        execute ('kill -9 '.$pid);
    }

    unset($interfaces[$iface]['post-up']['hostapd']);

    // Remove wpa_supplicant instances
    exec ("sudo ps ax | grep wpa_supplicant_$iface | grep -v grep | awk '{print $1;}'", $pids);

    foreach ($pids as $pid)
    {
        execute ('kill -9 '.$pid);
    }
    unset($interfaces[$iface]['post-up']['wpa_supplicant']);
    //response_additem ("html", print_r($interfaces[$iface]), "output");
    //if ($mode == "master")
    //{
    //    include_once $API_core.'write_interfaces.php';
    //    write_interfaces ($paths['interfaces'], $interfaces);
    //}
    return $interfaces;
}
/* ------------------------------------------------------------------------ */

function restart_interface ($iface)
/* ------------------------------------------------------------------------ */
{
    execute ('ifdown '.$iface);
    execute ('wlanconfig '.$iface.' destroy');
    execute ('ifup '.$iface.' >/dev/null 2>&1 &');
}
/* ------------------------------------------------------------------------ */
/* ------------------------------------------------------------------------ */

function stop_hostapd ($hostapd_file)
/* ------------------------------------------------------------------------ */
{
    exec ("sudo ps ax | grep ".$hostapd_file." | grep -v grep | awk '{print $1;}'", $pids);
    foreach ($pids as $pid)
    {
        execute ('kill -9 '.$pid);
    }
}
/* ------------------------------------------------------------------------ */

function stop_wpa_supplicant ($wpa_supplicant_file)
/* ------------------------------------------------------------------------ */
{
    exec ("sudo ps ax | grep ".$wpa_supplicant_file." | grep -v grep | awk '{print $1;}'", $pids);
    foreach ($pids as $pid)
    {
        execute ('kill -9 '.$pid);
    }
}
?>
