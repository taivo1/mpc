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

include_once $API_core.'conf_file.php';
include_once $base_plugin.'php/paths.php';
include_once $base_plugin.'php/display_certs.php';
include_once $base_plugin.'php/display_users.php';
include_once $base_plugin.'php/display_servers.php';
include_once $base_plugin.'php/display_clients.php';
include_once $base_plugin.'php/certs.php';

function make_radius ()
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $section;
    global $plugin;
    global $url_plugin;

    $list ='
    <div class="title">FreeRADIUS Manager</div>
    <div id="output"></div><div id="debug"></div>
    ';

    $list .= make_certificates_panel ();

    $list .= '<div id="radius_config">';
    $list .= make_radius_config_panel ();
    $list .='
    </div><!-- ID: RADIUS_CONFIG -->

    <script>
      var php_section=\''.$section.'\';
      var php_plugin=\''.$plugin.'\';
      var php_url_plugin=\''.$url_plugin.'\';';
    
    if ( are_cert_and_key_valid($paths, $key_pass='') )
    {
        $list .= 'var radius_config=true;';
    }
    else
    {
        $list .= 'var radius_config=false;';
    }

    $list .='
    </script>
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

function get_auth_servers()
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $auth_servers = load_conf_file ($paths['auth_servers']);
    if ( empty($auth_servers) )
    {
        $auth_servers = array (
            'vs0' => array ('port' => '18120',
                             'wpa_eap' => array('tls','ttls','peap')),
	    'vs1' => array ('port' => '18121',
                             'wpa_eap' => array('tls','ttls','peap'))
        );
        save_conf_file ($paths['auth_servers'], $auth_servers);
    }
    return $auth_servers;
}
/* ------------------------------------------------------------------------ */

function get_acct_servers()
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $acct_servers = load_conf_file ($paths['acct_servers']);
    if ( empty($acct_servers) )
    {
        $acct_servers = array (
            'default' => array ('port' => '18130',
                                'pass' => '123456')
        );
        save_conf_file ($paths['acct_servers'], $acct_servers);
    }
    return $acct_servers;
}
/* ------------------------------------------------------------------------ */

function make_radius_config_panel ()
/* ------------------------------------------------------------------------ */
{
    global $paths;

    if ( !file_exists($paths['radius_conf_dir']) )
    {
        exec ("sudo mkdir -p ".$paths['radius_conf_dir']);
    }

    $auth_servers = get_auth_servers();

    $list = make_auth_servers_panel ($auth_servers);

    $acct_servers = get_acct_servers();

    $list .= make_acct_servers_panel ($acct_servers);

    $list .= make_clients_panel ($auth_servers);

    $list .= make_users_panel ();

    $list .= '
    <div class="right_align">
    <input class="bsave" onclick="restart_radius()" type="button" value="restart service">
    </div>
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

function exists_cert ($filepath)
/* ------------------------------------------------------------------------ */
{
    exec ("sudo ls ".$filepath, $return);
    return ( $return[0] == $filepath );
}
/* ------------------------------------------------------------------------ */

function make_certificates_panel ()
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $list ='
    <div class="title2">FreeRADIUS certificates</div>
    <div class="plugin_content">

    <div id="certs_panel">
    <table class="cert_table">
      <tbody>
      <tr>
        <td>CA certificate</td>
        <td id="upload_cacert"></td>
        <td id="upload_cacert_garbage" style="visibility:hidden"></td>
      </tr>
      <tr>
        <td>Server certificate</td>
        <td id="upload_server_cert"></td>
        <td id="upload_server_cert_garbage" style="visibility:hidden"></td>
      </tr>
      <tr>
        <td>Server key</td>
        <td id="upload_server_key"></td>
        <td id="upload_server_key_garbage" style="visibility:hidden"></td>
      </tr>
      </tbody>
    </table>

    <div id="key_password_panel" class="nl">
    The private key has a password.<br>
    You have to input it so that Apache can use it.
    <form id="cert_pass_form" name="cert_pass_form">
    <table>
      <tbody>
      <tr>
        <td>Password:</td>
        <td><input type="password" name="cert_pass" id="cert_pass" /></td>
      </tr>
      <tr>
          <td></td>
          <td><div id="cert_pass_ms_cte"></div></td>
      </tr>
      <tr>
        <td>Confirm password:</td>
        <td><input type="password" name="cnf_cert_pass" id="cnf_cert_pass" /></td>
      </tr>
      <tr>
          <td></td>
          <td><div id="cnf_cert_pass_ms_cte"></div></td>
      </tr>
      <tr>
        <td></td>
        <td style="text-align: right">
          <input onclick="cancel_cert_pass()" type="button" value="cancel">
          <input onclick="save_cert_pass()" type="button" value="ok">
        </td>
      </tr>
    </table>

    </form>
    </div> <!-- ID: KEY_PASSWORD_PANEL -->
    </div> <!-- ID: CERTS_PANEL -->
    <div id="delete_btn_panel">
    <input id="delete_btn" name="delete_btn" type="button" value="Delete files"';

    $exists_cacert = exists_cert ($paths['cacert']);
    $exists_server_cert = exists_cert ($paths['server_cert']);
    $exists_server_key = exists_cert ($paths['server_key']);

    if ($exists_cacert || $exists_server_cert || $exists_server_key)
    {
        $list .= '/>';
    }
    else
    {
        $list .= 'class="disabled" disabled />';
    }

    $list .='
    </div><!-- ID: DELETE_BTN_PANEL -->
    ';

    $list .= make_script_vars($exists_cacert, $exists_server_cert,
                              $exists_server_key);

    $list .='
    </div><!-- ID: PLUGIN_CONTENT -->
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_script_vars($exists_cacert, $exists_server_cert, $exists_server_key)
/* ------------------------------------------------------------------------ */
{
    $list = '<script>';

    if ($exists_cacert)
    {
        $list .="exists_cacert=true;\n";
    }
    else
    {
        $list .="exists_cacert=false;\n";
    }

    if ($exists_server_cert)
    {
        $list .="exists_server_cert=true;\n";
    }
    else
    {
        $list .="exists_server_cert=false;\n";
    }

    if ($exists_server_key)
    {
        $list .="exists_server_key=true;\n";
    }
    else
    {
        $list .="exists_server_key=false;\n";
    }

    $list .='
    </script>
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_auth_servers_panel ($auth_servers)
/* ------------------------------------------------------------------------ */
{
    global $url_plugin;
    global $paths;

    $list = '
    <div class="title2">Virtual Auth Servers</div>
    <div class="plugin_content">
      <table class="table_header">
        <tr>
          <td class="avs_name">Name</td>
          <td class="avs_port">Port</td>
          <td class="avs_tls">TLS</td>
          <td class="avs_ttls">TTLS</td>
          <td class="avs_peap">PEAP</td>
        </tr>
      </table>

      <div id="list_auth_servers">
    ';

    foreach ($auth_servers as $avs_name => $avs_data)
    {
        $list .= '<table id="avs_'.$avs_name.'" class="avs_entry radius_border">';
        $list .= make_auth_server_info_row($avs_name, $avs_data, $url_plugin);
        $list .= '</table>';
    }

    $list .= '
      </div><!-- ID: LIST_AUTH_SERVERS -->
      <input type="button" onclick="show_new_auth_server_form()" value="new" />
    ';

    $list .= make_auth_server_form_panel ();

    $list .= '
    </div><!-- ID: PLUGIN_CONTENT -->
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_auth_server_form_panel ()
/* ------------------------------------------------------------------------ */
{
   $list = '
    <div id="auth_server_form_panel">
    <form id="auth_server_form" name="auth_server_form">
    <div id="auth_server_form_title" class="pl">New virtual auth server</div>
    <div class="nl">

     <table>
      <tbody>
      <tr>
       <td>Name:</td>
       <td><input type="text" name="as_name" id="as_name"
                  class="ms_mandatory ms_alnum" /></td>
      </tr>
      <tr>
          <td></td>
          <td><div id="as_name_ms_cte"></div></td>
      </tr>
      <tr>
        <td>Port:</td>
        <td><input type="text" name="as_port" id="as_port"
                   class="ms_mandatory ms_numerical" /></td>
      </tr>
      <tr>
          <td></td>
          <td><div id="as_port_ms_cte"></div></td>
      </tr>
      <tr>
        <td colspan="2" class="right">
            TLS<input type="checkbox" name="as_tls" id="as_tls" />
            TTLS<input type="checkbox" name="as_ttls" id="as_ttls" />
            PEAP<input type="checkbox" name="as_peap" id="as_peap" />
        </td>
      </tr>
      <tr><td></td><td></td></tr>
      <tr>
        <td></td>
        <td class="right">
          <input id="auth_server_cancel_form_btn" type="button" value="cancel"
                 onclick="cancel_new_auth_server()" />
          <input id="auth_server_create_form_btn" type="button" value="create"
                 onclick="create_auth_server()" />
        </td>
      </tr>
      </tbody>
     </table>
    </div><!-- ID: <Anonymous> -->
    </form>

    </div><!-- ID: AUTH_SERVER_FORM_PANEL -->
   ';

   return $list;
}
/* ------------------------------------------------------------------------ */

function make_acct_servers_panel ($acct_servers)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $list = '
    <div class="title2">Virtual Acct Server</div>
    <div class="plugin_content">
      <table class="table_header">
        <tr>
          <td class="acvs_name">Name</td>
          <td class="acvs_port">Port</td>
        </tr>
      </table>

      <div id="list_acct_servers">
    ';

    foreach ($acct_servers as $acvs_name => $acvs_data)
    {
        $list .= '<table id="acvs_'.$acvs_name.'" class="acvs_entry radius_border">';
        $list .= make_acct_server_info_row($acvs_name, $acvs_data);
        $list .= '</table>';
    }

    $list .= '
      </div><!-- ID: LIST_ACCT_SERVERS -->
    ';

    $list .= '
    </div><!-- ID: PLUGIN_CONTENT -->
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_clients_panel ($auth_servers)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $list = '
    <div class="title2">Clients</div>
    <div class="plugin_content">
    ';

    $list .= '
      <table class="table_header">
        <tr>
          <td class="clt_name">Name</td>
          <td class="clt_addr">Address/es</td>
          <td class="clt_vas">Virtual Auth Servers</td>
        </tr>
      </table>

      <div id="list_clients">
    ';

    $clients = load_conf_file ($paths['clients']);
    if ( empty($clients) )
    {
        $clients = array (
            'localhost' => array ('addr' => '127.0.0.1',
                                  'pass' => '123456',
                                  'auth_servers' => array('vs0', 'vs1'))
        );
        save_conf_file ($paths['clients'], $clients);
    }

    $list .= '<script>var client_vas = {};</script>'."\n";
    foreach ($clients as $client_name => $client_data)
    {
        $list .= '<table id="client_'.$client_name.'" class="clt_entry radius_border">';
        $list .= make_client_info_row($client_name, $client_data);
        $list .= '</table>';
        $list .= "<script>client_vas['".$client_name."'] = [];\n";
        foreach ($client_data['auth_servers'] as $auth_server)
        {
            $list .= "client_vas['".$client_name."'].push('$auth_server');\n";
        }
        $list .= '</script>'."\n";
    }

    $list .= '
      </div><!-- ID: LIST_CLIENTS -->
      <input type="button" onclick="show_new_client_form()" value="new" />
    ';

    $list .= make_client_form_panel ($auth_servers);

    $list .= '
    </div><!-- ID: PLUGIN_CONTENT -->
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_client_form_panel ($auth_servers)
/* ------------------------------------------------------------------------ */
{
   $list = '
    <div id="client_form_panel">
    <form id="client_form" name="client_form">
    <div id="client_form_title" class="pl">New client</div>
    <div class="nl">

     <table>
      <tbody>
      <tr>
       <td>Name:</td>
       <td><input type="text" name="client_name" id="client_name"
                  class="ms_mandatory ms_alnum" /></td>
       <td rowspan="8" valign="top">

        <table>
          <tbody>
          <tr>
            <td>Available virtual auth servers</td>
            <td></td>
            <td>Selected virtual auth servers</td>
          </tr>

          <tr>
            <td>
                <select class="as_list" id="available_servers" name="available_servers" multiple>';

        foreach ($auth_servers as $avs_name => $avs_data)
        {
            $list .= '<option id="cas_'.$avs_name.'" value="'.$avs_name.'">'.$avs_name.'</option>';
        }

        $list .= '
                </select>
            </td>
            <td>
                <input class="ar_btn" type="button" value="Add >>" onclick="add_auth_server()" /><br />
                <input class="ar_btn" type="button" value="<< Remove" onclick="remove_auth_server()" />
            </td>
            <td>
                <select class="as_list" id="client_servers" name="client_servers" multiple>
                </select>
            </td>
          </tr>
          </tbody>
         </table>

       </td>
      </tr>
      <tr>
          <td></td>
          <td><div id="client_name_ms_cte"></div></td>
      </tr>
      <tr>
        <td>Address/es:</td>
        <td><input type="text" name="client_addr" id="client_addr"
                   class="ms_mandatory ms_subnet" /></td>
      </tr>
      <tr>
          <td></td>
          <td><div id="client_addr_ms_cte"></div></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input type="password" name="client_pass" id="client_pass"
                   class="ms_mandatory" /></td>
      </tr>
      <tr>
          <td></td>
          <td><div id="client_pass_ms_cte"></div></td>
      </tr>
      <tr>
        <td>Confirm password:</td>
        <td><input type="password" name="cnf_client_pass" id="cnf_client_pass"
                   class="ms_mandatory" /></td>
      </tr>
      <tr>
          <td></td>
          <td><div id="cnf_client_pass_ms_cte"></div></td>
      </tr>
      <tr>
         <td colspan="3" class="right">
             <input id="client_cancel_form_btn" type="button" value="cancel"
                     onclick="cancel_new_client()" />
              <input id="client_create_form_btn" type="button" value="create"
                     onclick="create_client()" />
         </td>
       </tr>
      </tbody>
     </table>

    </div><!-- ID: <Anonymous> -->
    </form>

    </div><!-- ID: CLIENT_FORM_PANEL -->
   ';

   return $list;
}
/* ------------------------------------------------------------------------ */

function create_login_time_data($username, $info)
/* ------------------------------------------------------------------------ */
{
    $list = '';

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

        $list .= "user_logtime['".$username."'].push('$logtime');\n";

        $slot = strtok (",");
    }

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_users_panel ()
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $list = '
    <div class="title2">Users</div>
    <div class="plugin_content">
    ';

    $list .= '
      <table class="table_header">
        <tr>
          <td class="usr_name">Name</td>
          <td class="usr_timeout">Session timeout</td>
          <td class="usr_logtime">Login time</td>
          <td class="usr_online">On-Line</td>
        </tr>
      </table>

      <div id="list_users">
    ';

    $users = load_conf_file ($paths['users']);

    exec ('sudo radwho -r', $radwho);
    $online = array();
    foreach ($radwho as $line)
    {
      $online[] = strtok($line, ',');
    }

    $list .= '<script>var user_logtime = {};</script>'."\n";
    foreach ($users as $username => $user_data)
    {
        $list .= '<div id="user_'.$username.'_panel">';
        $list .= '<table id="user_'.$username.'" class="usr_entry radius_border">';
        $list .= make_user_info_row($username, $user_data, in_array($username, $online));
        $list .= '</table></div>';
        $list .= "<script>user_logtime['".$username."'] = [];\n";
        if ( isset($user_data['Login-Time']) )
        {
            $list .= create_login_time_data($username, $user_data['Login-Time']);
        }
        $list .= '</script>'."\n";
    }

    $list .= '
      </div><!-- ID: LIST_USERS -->
      <input type="button" onclick="show_new_user_form()" value="new" />
    ';

    $list .= make_user_form_panel ();

    $list .= '
    </div><!-- ID: PLUGIN_CONTENT -->
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_user_form_panel ()
/* ------------------------------------------------------------------------ */
{
   $list = '
    <div id="user_form_panel">
    <div id="user_form_title" class="pl">New user</div>
    <div id="user_form_container" class="nl">

    <div id="user_form_content">
     <form id="user_form" name="user_form">
     <table>
      <tbody id="user_data">
      <tr>
       <td>Username:</td>
       <td><input type="text" name="username" id="username" maxlength="253"
                  class="ms_mandatory ms_alnum" /></td>
      </tr>
      <tr>
          <td></td>
          <td><div id="username_ms_cte"></div></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input type="password" name="password" id="password"
                   class="ms_mandatory" /></td>
      </tr>
      <tr>
          <td></td>
          <td><div id="password_ms_cte"></div></td>
      </tr>
      <tr>
        <td>Confirm password:</td>
        <td><input type="password" name="cnf_password" id="cnf_password"
                   class="ms_mandatory" /></td>
      </tr>
      <tr>
          <td></td>
          <td><div id="cnf_password_ms_cte"></div></td>
      </tr>
      <tr>
        <td>Session timeout:</td>
        <td>
          <input type="checkbox" name="timeout_ckb" id="timeout_ckb" onchange="onchange_timeout_ckb()" />
          <input type="text" name="session_timeout" id="session_timeout"
                 class="readonly ms_numerical" readonly /> seconds
        </td>
      </tr>
      <tr>
          <td></td>
          <td><div id="session_timeout_ms_cte"></div></td>
      </tr>
      <tr>
        <td valign="top">Login time:</td>
        <td id="list_time_slots">
          <div id="add_ts_btn" class="ref" onclick="add_time_slot()">Add time slot</div>
        </td>
      </tr>
      <tr>
        <td colspan="3" class="right">
             <input id="user_cancel_form_btn" type="button" value="cancel" />
             <input id="user_create_form_btn" type="button" value="create" />
         </td>
      </tr>
      </tbody>
     </table>
     </form>
    </div><!-- ID: USER_FORM_CONTENT -->

    </div><!-- ID: <Anonymous> -->
    </div><!-- ID: USER_FORM_PANEL -->
   ';

   return $list;
}
/* ------------------------------------------------------------------------ */

?>
