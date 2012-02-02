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

include_once $API_core.'json_api.php';
include_once $API_core.'complex_ajax_return_functions.php';

include_once $API_core.'conf_file.php';
include_once $base_plugin.'php/paths.php';

$output = array(); // -- DEBUG VARIABLE: EXECUTED PROGRAMS OUTPUTS --

/* ------------------------------------------------------------------------ */
if ( $_POST['type']=="nv" )
{
    if ( isset($_POST['form_fields']) )
    {
        $post_data=jsondecode ($_POST['form_fields']);
    }

    switch ($_POST['action'])
    {
        case 'restart_radius':
            restart_radius ();
            break;

        /* Certificates panel */
        case 'delete_cert_files':
            delete_cert_files ();
            break;
        case 'save_cert_pass':
            save_cert_pass ($post_data);
            break;

        /* Users panel */
        case 'create_user':
            create_user ($post_data);
            break;

        case 'update_user':
            update_user ($post_data);
            break;

        case 'get_user_info':
            get_user_info ($_POST['username']);
            break;

        case 'delete_user':
            delete_user ($_POST['username']);
            break;

        /* Auth servers panel */
        case 'create_auth_server':
            create_auth_server ($post_data);
            break;

        case 'update_auth_server':
            update_auth_server ($_POST['servername'], $post_data);
            break;

        case 'get_auth_server_info':
            get_auth_server_info ($_POST['servername']);
            break;

        case 'delete_auth_server':
            delete_auth_server ($_POST['servername']);
            break;

        /* Acct servers panel */
        case 'update_acct_server':
            update_acct_server ($_POST['servername'], $post_data);
            break;

        case 'get_acct_server_info':
            get_acct_server_info ($_POST['servername']);
            break;

        /* Clients panel */
        case 'create_client':
            create_client ($post_data);
            break;

        case 'update_client':
            update_client ($_POST['clientname'], $post_data);
            break;

        case 'get_client_info':
            get_client_info ($_POST['clientname']);
            break;

        case 'delete_client':
            delete_client ($_POST['clientname']);
            break;
    }

    /* DEBUG OUTPUT */
    //response_additem ("html", "<pre>".print_r($post_data,true)."</pre>", "debug");
    //response_additem ("html", "<pre>".print_r($output,true)."</pre>", "debug");
    response_return ();
}
/* ------------------------------------------------------------------------ */

function execute ($cmd)
/* ------------------------------------------------------------------------ */
{
    global $output;
    exec ("sudo ".$cmd, $return);
    $output = array_merge ($output, $return); // DEBUG LOG
}
/* ------------------------------------------------------------------------ */

function error_msg ($msg)
/* ------------------------------------------------------------------------ */
{
    //response_additem ("html", "<fieldset><h2>'.$msg.'</h2></fieldset>" ,"output");
    response_additem ("script", "alert('".$msg."')");
}
/* ------------------------------------------------------------------------ */

function restart_radius ()
/* ------------------------------------------------------------------------ */
{
    execute ('sudo /etc/init.d/freeradius restart');
    response_additem ("html", "" ,"output");
}
/* ------------------------------------------------------------------------ */

function delete_cert_files ()
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $base_plugin;

    execute ('rm '.$paths['cacert']);
    execute ('rm '.$paths['server_cert']);
    execute ('rm '.$paths['server_key']);

    response_additem ("script", "cert_files_deleted()");
}
/* ------------------------------------------------------------------------ */

function is_cert_pass_valid ($pass)
/* ------------------------------------------------------------------------ */
{
    $pass_len = strlen($pass);
    return ($pass_len >= 4 && $pass_len <= 8191);
}
/* ------------------------------------------------------------------------ */

function is_password_valid ($password, $uploadfile)
/* ------------------------------------------------------------------------ */
{
   exec ("sudo /etc/ssl/sh/mod_key.sh $uploadfile $password", $ret);
   return ($ret[0] == "VALID_FILE");
}
/* ------------------------------------------------------------------------ */

function is_cert_pass_form_valid ($post_data, $uploadfile)
/* ------------------------------------------------------------------------ */
{
    $is_valid = false;

    if ( !is_cert_pass_valid ($post_data['cert_pass']) )
    {
        response_additem ("script", "cert_set_alert('cert_pass')");
    }
    elseif ( $post_data['cert_pass'] != $post_data['cnf_cert_pass'] )
    {
        error_msg ('Password missmatch.');
    }
    elseif ( !is_password_valid ($post_data['cert_pass'], $uploadfile) )
    {
        error_msg ('Private key file does not have that password.');
    }
    else
    {
        $is_valid = true;
    }

    return $is_valid;
}
/* ------------------------------------------------------------------------ */

function check_certs_files($key_pass)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    include_once $base_plugin.'php/certs.php';

    if ( exists_certificates ($paths) )
    {
        if ( are_cert_and_key_valid($paths, $key_pass) )
        {
            restart_radius ();
            response_additem ("script", "$('#radius_config').show()");
        }
        else
        {
            response_additem ("script", "alert('Certificate and private key mismatch.')");
        }
    }
}
/* ------------------------------------------------------------------------ */

function save_cert_pass ($post_data)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $base_plugin;

    $uploadfile = $base_plugin.'data/server_key';
    if ( is_cert_pass_form_valid ($post_data, $uploadfile) )
    {
        exec("sudo mv ".$uploadfile." ".$paths['server_key']);
        exec("sudo chown root:root ".$paths['server_key']);

        $sed = 'sed \'/private_key_password/c\\\t\t\tprivate_key_password = '.
               $post_data['cert_pass']."' ".$paths['fr_eap']." > ".
               $base_plugin."data/temp_eap";

        execute ($sed);

        execute ("mv ".$base_plugin."data/temp_eap ".$paths['fr_eap']);
        execute ("chown root:freerad ".$paths['fr_eap']);

        response_additem ("script", "reset_cert_pass_form()");
        check_certs_files ($post_data['cert_pass']);
    }
    response_additem ("html", "" ,"output");
}
/* ------------------------------------------------------------------------ */

function get_user_info ($username)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $users = load_conf_file ($paths['users']);
    $info = $users[$username];

    /* Pre: the form has been reset */
    response_additem ("value", $username ,"username");
    response_additem ("value", $info['Cleartext-Password'] ,"password");
    response_additem ("value", $info['Cleartext-Password'] ,"cnf_password");
    response_additem ("script", "$('#username').attr('readonly','true')");
    response_additem ("script", "$('#username').addClass('readonly')");
    
    if ( isset($info['Session-Timeout']) ) {
        response_additem ("script", "$('#timeout_ckb').attr('checked','true')");
        response_additem ("script", "$('#session_timeout').val('".$info['Session-Timeout']."')");
        response_additem ("script", "onchange_timeout_ckb()");
    }

    if ( isset($info['Login-Time']) )
    {
        $login_time = $info['Login-Time'];
        $slot = strtok ($login_time, ",");
        while ($slot !== false)
        {
            $dweek = substr($slot, 0, 2);
            $hour_start = substr($slot, 2, 2);
            $min_start = substr($slot, 4, 2);
            $hour_end = substr($slot, 7, 2);
            $min_end = substr($slot, 9, 2);

            response_additem ("script", "add_time_slot('".$dweek."', '".
                $hour_start."', '".$min_start."', '".$hour_end."', '".$min_end."')");

            $slot = strtok (",");
        }
    }
}
/* ------------------------------------------------------------------------ */

function delete_user ($username)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $base_plugin;

    $users = load_conf_file ($paths['users']);

    if ( isset($users[$username]) )
    {
       unset($users[$username]);
       save_conf_file ($paths['users'], $users);

       include_once $base_plugin.'php/write_fr_users.php';
       write_fr_users ($paths['fr_users'], $users);
       
       execute ('sudo /etc/init.d/freeradius restart');
       response_additem ("script", "$('#user_".$username."').remove()");
       response_additem ("html", "" ,"output");
    } else {
       error_msg ('User does not exist.');
    }
}
/* ------------------------------------------------------------------------ */

function is_time_slot_valid ($field, $idx, $min, $max, $post_data)
/* ------------------------------------------------------------------------ */
{
    $is_valid = (isset($post_data['ts_'.$field.'_'.$idx]) &&
                 preg_match('/^[0-9]+$/', trim($post_data['ts_'.$field.'_'.$idx])) );

    if ($is_valid)
    {
        $value = intval($post_data['ts_'.$field.'_'.$idx]);
        $is_valid = $value >= $min && $value <= $max;
    }

    return $is_valid;
}
/* ------------------------------------------------------------------------ */

function are_time_slots_valid ($post_data)
/* ------------------------------------------------------------------------ */
{
    $are_valid = true;

    $nslots = intval($_POST['nslots']);
    for ($i=0; $i<$nslots && $are_valid; $i++)
    {
        if ($post_data['dweek_'.$i])
        {
            if (!is_time_slot_valid ('hour_start', $i, 0, 23, $post_data))
            {
               $are_valid = false;
            }
            elseif (!is_time_slot_valid ('min_start', $i, 0, 59, $post_data))
            {
               $are_valid = false;
            }
            elseif (!is_time_slot_valid ('hour_end', $i, 0, 23, $post_data))
            {
               $are_valid = false;
            }
            elseif (!is_time_slot_valid ('min_end', $i, 0, 59, $post_data))
            {
               $are_valid = false;
            }
        }
    }

    return $are_valid;
}
/* ------------------------------------------------------------------------ */

function is_user_form_valid ($username, $post_data)
/* ------------------------------------------------------------------------ */
{
    $is_valid = false;

    if ( !preg_match('/^[ a-z0-9_]{1,253}$/i', $username) )
    { // FreeRADIUS username can be up to 253 characters in length
        error_msg ('Username is invalid.');
    } 
    elseif ( strlen($post_data['password']) == 0 )
    {
        error_msg ('Password is invalid.');
    }
    elseif ( $post_data['password'] != $post_data['cnf_password'] )
    {
        error_msg ('Password missmatch.');
    }
    elseif ( $post_data['timeout_ckb'] && intval($post_data['session_timeout']) <= 0 )
    {
        error_msg ('Session timeout is invalid.');
    }
    elseif ( !are_time_slots_valid($post_data) )
    {
        error_msg ('Time slot is invalid.');
    }
    else
    {
        $is_valid = true;
    }

    return $is_valid;
}
/* ------------------------------------------------------------------------ */

function add_user_data ($username, $post_data, $users)
/* ------------------------------------------------------------------------ */
{
    unset ($users[$username]['Session-Timeout']);
    unset ($users[$username]['Login-Time']);

    $users[$username]['Cleartext-Password'] = $post_data['password'];

    if ( isset($post_data['timeout_ckb']) ) {
       $users[$username]['Session-Timeout'] = intval($post_data['session_timeout']);
    }

    $nslots = intval($_POST['nslots']);
    for ($i=0; $i<$nslots; $i++)
    {
        if ($post_data['dweek_'.$i])
        {
            $dweek = $post_data['dweek_'.$i];
            $hour_start = intval($post_data['ts_hour_start_'.$i]);
            $min_start = intval($post_data['ts_min_start_'.$i]);
            $hour_end = intval($post_data['ts_hour_end_'.$i]);
            $min_end = intval($post_data['ts_min_end_'.$i]);

            $users[$username]['Login-Time'] .=
                $dweek.sprintf("%02d", $hour_start).sprintf("%02d", $min_start)."-".
                sprintf("%02d", $hour_end).sprintf("%02d", $min_end).",";
                response_additem ("script", "remove_time_slot(".$idx.")");
        }
    }

    $users[$username]['Login-Time'] =
     substr($users[$username]['Login-Time'], 0, -1); // Remove last ','

    return $users;
}
/* ------------------------------------------------------------------------ */

function create_usr_show_tip($username, $info)
/* ------------------------------------------------------------------------ */
{
    response_additem ("script", "user_logtime['".$username."']=[]");

    $dweek_str = array (
        'Al' => 'All', 'Wk' => 'Monday-Friday', 'Mo' => 'Monday',
        'Tu' => 'Tuesday', 'We' => 'Wednesday', 'Th' => 'Thursday',
        'Fr' => 'Friday', 'Sa' => 'Saturday', 'Su' => 'Sunday'
    );

    $login_time = $info;
    $slot = strtok ($login_time, ",");
    while ($slot !== false)
    {
        $dweek = substr($slot, 0, 2);
        $hour_start = substr($slot, 2, 2);
        $min_start = substr($slot, 4, 2);
        $hour_end = substr($slot, 7, 2);
        $min_end = substr($slot, 9, 2);

        $logtime = '<b>'.$dweek_str[$dweek].'</b>, '.$hour_start.':'.
                   $min_start.'-'.$hour_end.':'.$min_end;

        response_additem ("script", "user_logtime['".$username."'].push('$logtime')");

        $slot = strtok (",");
    }

    response_additem ("script", "create_user_tip('".$username."')");
}
/* ------------------------------------------------------------------------ */

function add_user ($username, $post_data, $users, $is_new)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $base_plugin;

    if ( is_user_form_valid ($username, $post_data) )
    {
        $users = add_user_data ($username, $post_data, $users);

        save_conf_file ($paths['users'], $users);

        include_once $base_plugin.'php/write_fr_users.php';
        write_fr_users ($paths['freeradius'].'users', $users);
        execute ('sudo /etc/init.d/freeradius restart');

        response_additem ("script", "reset_user_form()");

        response_additem ("html", "" ,"output");
        include_once $base_plugin.'php/display_users.php';
        if ( $is_new ) {
            response_additem ("script", "cancel_new_user()");
            response_additem ("append", '<table id="user_'.$username.'"> '.
                              'class="usr_entry radius_border"'.
                              make_user_info_row ($username, $users[$username],
                              false).'</table>' ,"list_users");
        } else {
            response_additem ("script", "cancel_edit_usr('$username')");
            response_additem ("html", make_user_info_row ($username, $users[$username],
                              false), "user_".$username);
        }
        create_usr_show_tip($username, $users[$username]['Login-Time']);
    }
}
/* ------------------------------------------------------------------------ */

function create_user ($post_data)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $username = $post_data['username'];
    $users = load_conf_file ($paths['users']);

    if ( isset($users[$username]) )
    {
        error_msg ('User already exists.');
    }
    else
    {
        add_user ($username, $post_data, $users, true);
    }
}
/* ------------------------------------------------------------------------ */


function update_user ($post_data)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $username = $post_data['username'];
    $users = load_conf_file ($paths['users']);

    if ( !isset($users[$username]) )
    {
        error_msg ('User does not exist.');
    }
    else
    {
        add_user ($username, $post_data, $users, false);
    }

/* ------------------------------------------------------------------------ */
}

function get_auth_server_info ($servername)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $servers = load_conf_file ($paths['auth_servers']);
    $info = $servers[$servername];

    $row = '
        <div id="edit_avs_'.$servername.'">
        <form id="edit_avs_form" name="edit_avs_form">
        <table class="edit_avs_entry radius_border">
        <tr>
           <td class="avs_name"></td>
           <td class="avs_port">
           <input type="text" value="'.$info['port'].'" name="as_port" id="as_port"
                  class="ms_mandatory ms_numerical" size="5" />
           </td>
           <td class="avs_tls">
             <input type="checkbox" id="'.$servername.'_tls" name="as_tls"
        ';
        if ( in_array('tls', $info['wpa_eap']) )
        {
            $row .= ' checked ';
        }
        $row .= ' />
           </td>
           <td class="avs_ttls">
             <input type="checkbox" id="'.$servername.'_ttls" name="as_ttls"
        ';
        if ( in_array('ttls', $info['wpa_eap']) )
        {
            $row .= ' checked ';
        }
        $row .= ' />
           </td>
           <td class="avs_peap">
             <input type="checkbox" id="'.$servername.'_ttls" name="as_peap"
        ';
        if ( in_array('peap', $info['wpa_eap']) )
        {
            $row .= ' checked ';
        }
        $row .= ' />
            </td>
            <td class="buttons">
                <input type="button" value="update" 
                       onclick="update_auth_server(\''.$servername.'\')" />
                <input type="button" value="cancel"
                       onclick="cancel_edit_avs(\''.$servername.'\')" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><div id="as_port_ms_cte"></div></td>
        </tr>
        </table>
        </div>
    ';

    response_additem ("after", $row, "avs_".$servername);
}
/* ------------------------------------------------------------------------ */

function remove_auth_server_clients ($servername, $servers)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $clients = load_conf_file ($paths['clients']);
    foreach ( array_keys($clients) as $name )
    {
        $pos = array_search($servername, $clients[$name]['auth_servers']);
        if ($pos !== false)
        {
            unset($clients[$name]['auth_servers'][$pos]);
            if ( empty($clients[$name]['auth_servers']) )
            {
                $clients[$name]['auth_servers'][] = 'vs0';
                response_additem ("script", "client_vas['$name'].push('vs0')");
            }
        }
    }
    save_conf_file ($paths['clients'], $clients);

    include_once $base_plugin.'php/write_fr_clients.php';
    write_fr_clients ($paths['fr_clients'], $clients, $servers);
}
/* ------------------------------------------------------------------------ */

function delete_auth_server ($servername)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $base_plugin;

    $servers = load_conf_file ($paths['auth_servers']);

    if ( isset($servers[$servername]) )
    {
       unset($servers[$servername]);
       save_conf_file ($paths['auth_servers'], $servers);

       execute ('rm '.$paths['freeradius'].'sites-enabled/'.$servername);

       remove_auth_server_clients ($servername, $servers);

       execute ('sudo /etc/init.d/freeradius restart');

       response_additem ("script", "$('#avs_".$servername."').remove()");
       response_additem ("script", "$('#cas_".$servername."').remove()");
       response_additem ("script", "delete_clt_as('".$servername."')");
       response_additem ("html", "" ,"output");
    } else {
       error_msg ('Virtual auth server does not exist.');
    }
}
/* ------------------------------------------------------------------------ */

function exists_port ($servername, $port, $servers)
/* ------------------------------------------------------------------------ */
{
    $exists = false;

    $names = array_keys($servers);
    for ($i=0; $i<count($servers) && !$exists; $i++)
    {
        $exists = ($servers[$names[$i]]['port'] == $port) && ($names[$i] != $servername);
    }

    return $exists;
}
/* ------------------------------------------------------------------------ */

function is_auth_server_form_valid ($servername, $post_data, $servers)
/* ------------------------------------------------------------------------ */
{
    $is_valid = true;
    // Validate servername. It must have a value and must be alfanumerical
    if ( !empty($servername))
    {
        if ( !preg_match('/^[a-z0-9_]+$/i', $servername) )
        {
            response_additem ("script", "set_alert('as_name', 'ms_alnum')");
            $is_valid = false;
        }
    }
    else
    {
        response_additem ("script", "set_alert('as_name', 'ms_mandatory')");
        $is_valid = false;
    }
    
    if ( in_array($servername, Array("default", "inner-tunnel") ))
    {
        $msg = "This name is reserved.";
        response_additem ("script", "set_customized_alert('as_name', '$msg')");
        $is_valid = false;
    }

    if ( intval($post_data['as_port']) < 1025 || intval($post_data['as_port']) > 65535 )
    {
        $msg = "Server port invalid.";
        response_additem ("script", "set_customized_alert('as_port', '$msg')");
        $is_valid = false;
    }

    if ( exists_port($servername, $post_data['as_port'], $servers) )
    {
        $msg = "Server port already exists.";
        response_additem ("script", "set_customized_alert('as_port','$msg')");
        $is_valid = false;
    }

    if ( !isset($post_data['as_tls']) && !isset($post_data['as_ttls']) && !isset($post_data['as_peap']) )
    {
        error_msg ('At least one EAP method must be selected.');
        $is_valid = false;
    }

    return $is_valid;
}
/* ------------------------------------------------------------------------ */

function add_auth_server_data ($servername, $post_data, $servers, $freeradius_sk)
/* ------------------------------------------------------------------------ */
{
    $servers[$servername]['port'] = $post_data['as_port'];
    $freeradius_sk['listen']['port'] = $post_data['as_port'];
    $freeradius_sk['listen']['clients'] = $servername;

    if ($post_data['as_tls']) {
       $servers[$servername]['wpa_eap'][] = 'tls';
    } else {
       $freeradius_sk['authorize']['forbid_tls'] = '';
    }

    if ($post_data['as_ttls']) {
       $servers[$servername]['wpa_eap'][] = 'ttls';
    } else {
       $freeradius_sk['authorize']['forbid_ttls'] = '';
    }

    if ($post_data['as_peap']) {
       $servers[$servername]['wpa_eap'][] = 'peap';
    } else {
       $freeradius_sk['authorize']['forbid_peap'] = '';
    }

    return array($servers, $freeradius_sk);
}
/*------------------------------------------------------------------------- */

function update_hostapd_auth_port ($servername, $port)
/* ------------------------------------------------------------------------ */
{
    global $API_core;
    global $paths;

    if ( file_exists($paths['hostapd_conf_dir']) )
    {
        $security = load_conf_file ($paths['security']);
        foreach ($security as $iface => $data)
        {
            if ($data['radius_connection'] == "local" &&
                $data['virtual_server'] == $servername)
            {
                $hostapd_file = $paths['hostapd']."_".$iface.".conf";
                include_once $API_core.'parser_hostapd.php';
                $hostapd = parse_hostapd ($hostapd_file);

                $hostapd['auth_server_port'] = $port;

                include_once $API_core.'write_hostapd.php';
                write_hostapd ($hostapd_file, $hostapd);
            }
        }
    }
}
/* ------------------------------------------------------------------------ */

function update_freeradius_clients($servers)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $base_plugin;
    
    $clients = load_conf_file ($paths['clients']);
    include_once $base_plugin.'php/write_fr_clients.php';
    write_fr_clients ($paths['fr_clients'], $clients, $servers);
}
/* ------------------------------------------------------------------------ */

function add_auth_server ($servername, $post_data, $servers, $is_new)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $base_plugin;
    global $url_plugin;

    if ( is_auth_server_form_valid ($servername, $post_data, $servers) )
    {
        include_once $base_plugin.'php/parser_fr_conf.php';
        $freeradius_sk = parse_fr_conf ($paths['fr_ath_skeleton']);

        unset($servers[$servername]['wpa_eap']);
        list($servers, $freeradius_sk) = add_auth_server_data
           ($servername, $post_data, $servers, $freeradius_sk);

        save_conf_file ($paths['auth_servers'], $servers);

        $freeradius['server '.$servername] = $freeradius_sk;
        include_once $base_plugin.'php/write_fr_conf.php';
        write_fr_conf ($paths['freeradius'].'sites-enabled/'.$servername, $freeradius);
        update_freeradius_clients($servers);

        execute ('sudo /etc/init.d/freeradius restart');

        response_additem ("script", "reset_auth_server_form()");

        response_additem ("html", "" ,"output");
        include_once $base_plugin.'php/display_servers.php';
        if ( $is_new ) {
            response_additem ("script", "cancel_new_auth_server()");
            response_additem ("append", '<table id="avs_'.$servername.'" '.
                              'class="avs_entry radius_border">'.
                              make_auth_server_info_row ($servername, $servers[$servername],
                              $url_plugin).'</table>', "list_auth_servers");
            response_additem ("append", '<option id="cas_'.$servername.
                              '" value="'.$servername.'">'.$servername.'</option>', "available_servers");
        } else {
            update_hostapd_auth_port ($servername, $servers[$servername]['port']);
            response_additem ("script", "cancel_edit_avs('$servername')");
            response_additem ("html", make_auth_server_info_row ($servername, $servers[$servername],
                              $url_plugin), "avs_".$servername);
        }
    }
}
/* ------------------------------------------------------------------------ */

function create_auth_server ($post_data)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $servername = $post_data['as_name'];
    $servers = load_conf_file ($paths['auth_servers']);

    if ( isset($servers[$servername]) )
    {
        error_msg ('Auth server already exists.');
    }
    else
    {
        add_auth_server ($servername, $post_data, $servers, true);
    }
}
/* ------------------------------------------------------------------------ */

function update_auth_server ($servername, $post_data)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $servers = load_conf_file ($paths['auth_servers']);

    if ( !isset($servers[$servername]) )
    {
        error_msg ('Auth server does not exist.');
    }
    else
    {
        add_auth_server ($servername, $post_data, $servers, false);
    }
}
/* ------------------------------------------------------------------------ */

function get_acct_server_info ($servername)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $servers = load_conf_file ($paths['acct_servers']);
    $info = $servers[$servername];

    $row = '
        <div id="edit_acvs_'.$servername.'">
        <form id="edit_acvs_form" name="edit_acvs_form">
        <table class="edit_acvs_entry radius_border">
        <tr>
           <td class="acvs_name"></td>
           <td class="acvs_port">
           <input type="text" value="'.$info['port'].'" name="acs_port" id="acs_port"
                  class="ms_mandatory ms_numerical" size="5" />
           </td>
           <td class="buttons">
                <input type="button" value="update"
                       onclick="update_acct_server(\''.$servername.'\')" />
                <input type="button" value="cancel"
                       onclick="cancel_edit_acvs(\''.$servername.'\')" />
           </td>
        </tr>
        <tr>
            <td></td>
            <td><div id="acs_port_ms_cte"></div></td>
        </tr>
        </table>
        </div>
    ';

    response_additem ("after", $row, "acvs_".$servername);
}
/* ------------------------------------------------------------------------ */

function is_acct_server_form_valid ($servername, $post_data, $servers)
/* ------------------------------------------------------------------------ */
{
    $is_valid = false;

    if ( !preg_match('/^[a-z0-9_]+$/i', $servername) )
    {
        error_msg ('Servername is invalid.');
    }
    if ( intval($post_data['acs_port']) < 1025 || intval($post_data['acs_port']) > 65535 )
    {
        error_msg ('Server port is invalid.');
    }
    else
    {
        $is_valid = true;
    }

    return $is_valid;
}
/* ------------------------------------------------------------------------ */

function add_acct_server_data ($servername, $post_data, $servers, $freeradius)
/* ------------------------------------------------------------------------ */
{
    $servers[$servername]['port'] = $post_data['acs_port'];
    $freeradius['listen']['port'] = $post_data['acs_port'];

    return array($servers, $freeradius);
}
/*------------------------------------------------------------------------- */

function update_hostapd_acct_info ($servername, $serverdata)
/* ------------------------------------------------------------------------ */
{
    global $API_core;
    global $paths;

    if ( file_exists($paths['hostapd_conf_dir']) )
    {
        $security = load_conf_file ($paths['security']);
        foreach ($security as $iface => $data)
        {
            if ($data['radius_connection'] == "local" &&
                $data['virtual_server'] == $servername)
            {
                $hostapd_file = $paths['hostapd']."_".$iface.".conf";
                include_once $API_core.'parser_hostapd.php';
                $hostapd = parse_hostapd ($hostapd_file);

                $hostapd['acct_server_port'] = $serverdata['port'];
                $hostapd['acct_server_shared_secret'] = $serverdata['pass'];

                include_once $API_core.'write_hostapd.php';
                write_hostapd ($hostapd_file, $hostapd);
            }
        }
    }
}
/* ------------------------------------------------------------------------ */

function add_acct_server ($servername, $post_data, $servers, $is_new)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $base_plugin;

    if ( is_acct_server_form_valid ($servername, $post_data, $servers) )
    {
        include_once $base_plugin.'php/parser_fr_conf.php';
        $freeradius = parse_fr_conf ($paths['fr_acct_listen']);

        list($servers, $freeradius) = add_acct_server_data
           ($servername, $post_data, $servers, $freeradius);

        save_conf_file ($paths['acct_servers'], $servers);

        include_once $base_plugin.'php/write_fr_conf.php';
        write_fr_conf ($paths['fr_acct_listen'], $freeradius);
        execute ('sudo /etc/init.d/freeradius restart');

        response_additem ("html", "" ,"output");
        include_once $base_plugin.'php/display_servers.php';

        update_hostapd_acct_info ($servername, $servers[$servername]);
        
        response_additem ("script", "cancel_edit_acvs('$servername')");
        response_additem ("html", make_acct_server_info_row ($servername, $servers[$servername]),
                         "acvs_".$servername);
    }
}
/* ------------------------------------------------------------------------ */

function update_acct_server ($servername, $post_data)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $servers = load_conf_file ($paths['acct_servers']);

    if ( !isset($servers[$servername]) )
    {
        error_msg ('Acct server does not exist.');
    }
    else
    {
        add_acct_server ($servername, $post_data, $servers, false);
    }
}
/* ------------------------------------------------------------------------ */

function get_client_info ($clientname)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $clients = load_conf_file ($paths['clients']);
    $info = $clients[$clientname];

    $row = '
        <div id="edit_clt_'.$clientname.'">
        <form id="edit_clt_form" name="edit_clt_form">
        <table class="edit_clt_entry radius_border">
        <tr>
           <td class="clt_name"></td>
           <td class="clt_addr">
           <table>
           <tr>
             <td><input type="text" value="'.$info['addr'].'" name="client_addr"
                  id="client_addr_'.$clientname.'"
                  class="ms_mandatory ms_subnet" size="15" /></td>
           </tr>
           <tr>
                <td><div id="client_addr_'.$clientname.'_ms_cte"></div></td>
             </tr>
           </table>
           </td>
           <td rowspan="2" valign="top">
              <table>
              <tbody>
              <tr>
                <td>Available virtual auth servers</td>
                <td></td>
                <td>Selected virtual auth servers</td>
              </tr>

              <tr>
                <td>
                    <select class="as_list" id="available_servers_'.$clientname.'"
                            name="available_servers" multiple>';

            $auth_servers = load_conf_file ($paths['auth_servers']);

            foreach ($auth_servers as $avs_name => $avs_data)
            {
                $row .= '<option id="cas_'.$avs_name.'_'.$clientname.'"
                                 value="'.$avs_name.'">'.$avs_name.'</option>';
            }

            $row .= '
                    </select>
                </td>
                <td>
                    <input class="ar_btn" type="button" value="Add >>" 
                           onclick="add_auth_server(\''.$clientname.'\')" /><br />
                    <input class="ar_btn" type="button" value="<< Remove" 
                           onclick="remove_auth_server(\''.$clientname.'\')" />
                </td>
                <td>
                    <select class="as_list" id="client_servers_'.$clientname.'"
                            name="client_servers" multiple>
                    </select>
                </td>
              </tr>
              </table>
           </td>
        </tr>

         <tr>
           <td colspan="2" valign="top">
           <table>
             <tr>
               <td>Password</td>
               <td><input type="password" name="client_pass" 
                          id="client_pass_'.$clientname.'" class="ms_mandatory"
                          value="'.$info['pass'].'" /></td>
             </tr>
             <tr>
                <td></td>
                <td><div id="client_pass_'.$clientname.'_ms_cte"></div></td>
             </tr>
             <tr>
               <td>Confirm password</td>
               <td><input type="password" name="cnf_client_pass" 
                          id="cnf_client_pass_'.$clientname.'" class="ms_mandatory"
                          value="'.$info['pass'].'" /></td>
             </tr>
             <tr>
                <td></td>
                <td><div id="cnf_client_pass_'.$clientname.'_ms_cte"></div></td>
             </tr>
           </table>
           </td>
        </tr>
        <tr>
           <td colspan="5" class="right">
                <input type="button" value="update"
                       onclick="update_client(\''.$clientname.'\')" />
                <input type="button" value="cancel"
                       onclick="cancel_edit_clt(\''.$clientname.'\')" />
           </td>
        </tr>
        <tr>
            <td></td>
            <td><div id="client_addr_'.$clientname.'_ms_cte"></div></td>
        </tr>
        </table>
        </div>
    ';

    response_additem ("after", $row, "client_".$clientname);
    if ($clientname == "localhost")
    {
        response_additem ("script", "$('#client_addr_".$clientname."')".
                                    ".attr('readonly', 'true')");
        response_additem ("script", "$('#client_addr_".$clientname."')".
                                    ".addClass('readonly')");
    }
    
    foreach ($info['auth_servers'] as $auth_server)
    {
        response_additem ("script", "$('#cas_".$auth_server.'_'.$clientname.
                          "').remove().appendTo('#client_servers_".$clientname."')");
    }
}
/* ------------------------------------------------------------------------ */

function delete_client ($clientname)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $base_plugin;

    $clients = load_conf_file ($paths['clients']);

    if ( isset($clients[$clientname]) )
    {
       unset($clients[$clientname]);
       save_conf_file ($paths['clients'], $clients);

       include_once $base_plugin.'php/write_fr_clients.php';
       $servers = load_conf_file ($paths['auth_servers']);
       write_fr_clients ($paths['fr_clients'], $clients, $servers);

       execute ('sudo /etc/init.d/freeradius restart');
       response_additem ("script", "$('#client_".$clientname."').remove()");
       response_additem ("html", "" ,"output");
    } else {
       error_msg ('Client does not exist.');
    }
}
/* ------------------------------------------------------------------------ */

function exist_addr ($clientname, $addr, $clients)
/* ------------------------------------------------------------------------ */
{
    $exist = false;

    $names = array_keys($clients);
    for ($i=0; $i<count($clients) && !$exist; $i++)
    {
        $exist = ($clients[$names[$i]]['addr'] == $addr) && ($names[$i] != $clientname);
    }

    return $exist;
}
/* ------------------------------------------------------------------------ */

function is_client_form_valid ($clientname, $post_data, $clients)
/* ------------------------------------------------------------------------ */
{
    $ip_regexp = '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}'.
    '(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(?:\/(?:3[0-2]|[1-2]?[0-9]))?$/';

    $is_valid = false;

    if ( !preg_match('/^[a-z0-9_]+$/i', $clientname) )
    {
        error_msg ('Client name is invalid.');
    }
    elseif ( !preg_match($ip_regexp, $post_data['client_addr']) )
    {
        error_msg ('Client address/es is invalid.');
    }
    elseif ( exist_addr ($clientname, $post_data['client_addr'], $clients) )
    {
        error_msg ('Client address/es already exist/s.');
    }
    elseif ( strlen($post_data['client_pass']) == 0 )
    {
        error_msg ('Password is invalid.');
    }
    elseif ( $post_data['client_pass'] != $post_data['cnf_client_pass'] )
    {
        error_msg ('Password missmatch.');
    }
    elseif ( empty($post_data['client_servers']) )
    {
        error_msg ('At least one virtual auth server must be selected.');
    }
    else
    {
        $is_valid = true;
    }

    return $is_valid;
}
/* ------------------------------------------------------------------------ */

function add_client_data ($clientname, $post_data, $clients)
/* ------------------------------------------------------------------------ */
{
    $clients[$clientname]['addr'] = $post_data['client_addr'];
    $clients[$clientname]['pass'] = $post_data['client_pass'];
    $clients[$clientname]['auth_servers'] = $post_data['client_servers'];

    return $clients;
}
/*------------------------------------------------------------------------- */

function check_security_config ($new_client)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $warning = false;
    if ( file_exists($paths['hostapd_conf_dir']) )
    {
        $security = load_conf_file ($paths['security']);
        foreach ($security as $iface => $data)
        {
            $auth_server = $data['virtual_server'];
            $warning = $warning ||
                       ($data['radius_connection'] == "local" &&
                       !in_array($auth_server, $new_client['auth_servers']));
        }
    }
    if ($warning)
    {
        response_additem ("script", "alert('¡¡WARNING!! A virtual server ".
                          "deleted was used by a local WiFi interface with ".
                          "WPA EAP protocol.')");
    }
}
/* ------------------------------------------------------------------------ */

function update_hostapd_auth_password ($password)
/* ------------------------------------------------------------------------ */
{
    global $API_core;
    global $paths;

    if ( file_exists($paths['hostapd_conf_dir']) )
    {
        $security = load_conf_file ($paths['security']);
        foreach ($security as $iface => $data)
        {
            if ($data['radius_connection'] == "local")
            {
                $hostapd_file = $paths['hostapd']."_".$iface.".conf";
                include_once $API_core.'parser_hostapd.php';
                $hostapd = parse_hostapd ($hostapd_file);

                $hostapd['auth_server_shared_secret'] = $password;

                include_once $API_core.'write_hostapd.php';
                write_hostapd ($hostapd_file, $hostapd);
            }
        }
    }
}
/* ------------------------------------------------------------------------ */

function create_clt_show_tip($clientname, $auth_servers)
/* ------------------------------------------------------------------------ */
{
    response_additem ("script", "client_vas['".$clientname."']=[]");
    foreach ($auth_servers as $auth_server)
    {
       response_additem ("script", "client_vas['".$clientname."'].push('$auth_server')");
    }
    response_additem ("script", "create_client_tip('".$clientname."')");
}
/* ------------------------------------------------------------------------ */

function add_client ($clientname, $post_data, $clients, $is_new)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $base_plugin;

    if ( is_client_form_valid ($clientname, $post_data, $clients) )
    {
        $clients = add_client_data ($clientname, $post_data, $clients);
        save_conf_file ($paths['clients'], $clients);

        include_once $base_plugin.'php/write_fr_clients.php';
        $servers = load_conf_file ($paths['auth_servers']);
        write_fr_clients ($paths['fr_clients'], $clients, $servers);

        execute ('sudo /etc/init.d/freeradius restart');

        response_additem ("script", "reset_client_form()");

        response_additem ("html", "" ,"output");
        include_once $base_plugin.'php/display_clients.php';
        if ( $is_new ) {
            response_additem ("script", "cancel_new_client()");
            response_additem ("append", '<table id="client_'.$clientname.'" '.
                              'class="clt_entry radius_border">'.
                              make_client_info_row ($clientname, $clients[$clientname]).
                              '</table>', "list_clients");
            response_additem ("script", "create_tip('".$clientname."')");
        } else {
            if ($clientname == 'localhost') {
                update_hostapd_auth_password ($clients['localhost']['pass']);
                check_security_config ($clients['localhost']);
            }
            response_additem ("script", "cancel_edit_clt('$clientname')");
            response_additem ("html", make_client_info_row ($clientname, $clients[$clientname]),
                              "client_".$clientname);
        }
        create_clt_show_tip($clientname, $clients[$clientname]['auth_servers']);
    }
}
/* ------------------------------------------------------------------------ */

function create_client ($post_data)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $clientname = $post_data['client_name'];
    $clients = load_conf_file ($paths['clients']);

    if ( isset($clients[$clientname]) )
    {
        error_msg ('Client already exists.');
    }
    else
    {
        add_client ($clientname, $post_data, $clients, true);
    }
}
/* ------------------------------------------------------------------------ */

function update_client ($clientname, $post_data)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $clients = load_conf_file ($paths['clients']);

    if ( !isset($clients[$clientname]) )
    {
        error_msg ('Client does not exist.');
    }
    else
    {
        add_client ($clientname, $post_data, $clients, false);
    }
}
/* ------------------------------------------------------------------------ */

?>