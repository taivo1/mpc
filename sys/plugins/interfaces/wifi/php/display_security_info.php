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

//-------------------------------------------------------------------------------------------

function make_security ($iface)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    
    /*$list ='
    <div id="output"></div><div id="debug"></div>
    ';*/

    if ( !file_exists($paths['hostapd_conf_dir']) )
    {
        exec ("sudo mkdir -p ".$paths['hostapd_conf_dir']);
    }

    $security = load_conf_file ($paths['security']);
    if ( empty($security) ) 
    {
        $security = array (
            'ath0' => array ('protocol' => 'none'),
	    'ath1' => array ('protocol' => 'none')
        );
        save_conf_file ($paths['security'], $security);
    }
    //print_r ($security);

    $list .= make_protocol_panel ($iface, $security);

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_protocol_panel ($iface, $security)
/* ------------------------------------------------------------------------ */
{
    $list = '
    <div class="title2">Security</div>
    <div class="plugin_content">
    <form id="security_config" name="security_config">

      <table>
      <tbody>
      <tr>
       <td>Protocol:</td>
       <td>
         <select name="protocol" id="protocol" onchange="onchange_protocol()" >
           <option value="none">None</option>';


    if ( $security[$iface]['protocol'] == 'wep' )
    {
        $list .= '       <option value="wep" selected>WEP</option>';
    } else {
        $list .= '       <option value="wep">WEP</option>';
    }

    if ( $security[$iface]['protocol'] == 'wpa' )
    {
        $list .= '       <option value="wpa" selected">WPA</option>';
    } else {
        $list .= '       <option value="wpa">WPA</option>';
    }

    $list .=
    '    </select>
       </td>
      </tr>
      </tbody>
      </table>';

    $list .= make_wep_panel ($iface, $security);

    $list .= make_wpa_panel ($iface, $security);

    $list .= '
      </form>
    </div><!-- ID: PLUGIN_CONTENT -->';

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_wep_panel ($iface, $security)
/* ------------------------------------------------------------------------ */
{
    $list = '
    <div id="wep">
      <div class="pl">WEP</div>
      <div class="nl">
      <table>
      <tbody>
      <tr>
       <td>Key size:</td>
       <td>
         <select name="key_size" id="key_size" onchange="onchange_key_size()" >';

    if ( $security[$iface]['protocol'] == 'wep' )
    {
        if ( strlen ($security[$iface]['wep_pass']) == 5 )
        {
            $list .= '      <option value="40" selected>40 bits</option>'.
                     '      <option value="104">104 bits</option>';
        } else {
            $list .= '      <option value="40">40 bits</option>'.
                     '      <option value="104" selected>104 bits</option>';
        }
    } else {
        $list .= '      <option value="40">40 bits</option>'.
                 '      <option value="104">104 bits</option>';
    }

    $list .=
    '     </select>
       </td>
      </tr>

      <tr>
       <td>Password:</td>
       <td>
         <input type="password" name="wep_pass" id="wep_pass"';

    if ($security[$iface]['protocol'] == 'wep') {
        $list .= ' value="'.$security[$iface]['wep_pass'].'" ';
    }

    $list .=
    '      maxlength="13" />
       </td>
       <td>
            <div id="wep_pass_ms_cte"></div>
       </td>
      </tr>

      <tr>
       <td>Confirm password:</td>
       <td>
         <input type="password" name="cnf_wep_pass" id="cnf_wep_pass"';

    if ($security[$iface]['protocol'] == 'wep') {
        $list .= ' value="'.$security[$iface]['wep_pass'].'" ';
    }

    $list .=
    '  />       
       <td>
            <div id="cnf_wep_pass_ms_cte"></div>
       </td>
      </tr>

      <tr>
       <td></td>
       <td id="wep_pass_msg"></td>
      </tr>

      </tbody>
      </table>
      </div> <!-- ID: <Anonymous> -->
      </div> <!-- ID: WEP -->';

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_wpa_panel ($iface, $security)
/* ------------------------------------------------------------------------ */
{
    $list = '
    <div id="wpa">
      <div class="pl"><input type="checkbox" name="wpa_psk_ckb" id="wpa_psk_ckb"
                         onchange="onchange_ckb_set(\'wpa_psk\')"';

    if ( $security[$iface]['protocol'] == 'wpa' &&
         in_array ('psk', $security[$iface]['wpa_mgmt']) )
    {
        $list .= ' checked ';
    }

    $list .=
    '/>WPA Personal (PSK)</div>
      <div id="wpa_psk" class="nl ss">
      <table>
      <tbody>
      <tr>
       <td>Password:</td>
       <td>
         <input type="password" name="psk_pass" id="psk_pass"';

    if ($security[$iface]['protocol'] == 'wpa' && in_array ('psk', $security[$iface]['wpa_mgmt']) ) {
        $list .= ' value="'.$security[$iface]['wpa_psk'].'" ';
    }

    $list .=
    '/>
       </td>
       <td>
            <div id="psk_pass_ms_cte"></div>
       </td>
      </tr>

      <tr>
       <td>Confirm password:</td>
       <td>
         <input type="password" name="cnf_psk_pass" id="cnf_psk_pass"';

    if ($security[$iface]['protocol'] == 'wpa' && in_array ('psk', $security[$iface]['wpa_mgmt']) ) {
        $list .= ' value="'.$security[$iface]['wpa_psk'].'" ';
    }
    
    $list .=
    '/>
       <td>
            <div id="cnf_psk_pass_ms_cte"></div>
       </td>
      </tr>

      <tr>
       <td></td>
       <td>*8 to 63 characters</td>
      </tr>

      </tbody>
      </table>
      </div> <!-- ID: WPA_PSK -->

      <div id="wpa_eap_ckb_div" class="pl"><input type="checkbox" name="wpa_eap_ckb" id="wpa_eap_ckb"
                         onchange="onchange_ckb_set(\'wpa_eap\')"';

    if ( $security[$iface]['protocol'] == 'wpa' && in_array ('eap', $security[$iface]['wpa_mgmt']) )
    {
        $list .= ' checked ';
    }

    $list .=
    '/>WPA Enterprise (EAP)</div>

      <div id="wpa_eap" class="nl ss">';

    $list .= make_eap_panel ($iface, $security);

    $list .= '
      </div><!-- ID: WPA_EAP -->
      </div><!-- ID: WPA -->';

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_eap_panel ($iface, $security)
/* ------------------------------------------------------------------------ */
{
    $list =
    ' <table>
      <tbody>
      <tr>
       <td>RADIUS connection:</td>
       <td>
         <select name="radius_connection" id="radius_connection" onchange="onchange_connection()" >';

    if ( $security[$iface]['radius_connection'] == 'local' )
    {
        $list .= '       <option value="local" selected>Local</option>';
    } else {
        $list .= '       <option value="local">Local</option>';
    }

    if ( $security[$iface]['radius_connection'] == 'remote' )
    {
        $list .= '       <option value="remote" selected">Remote</option>';
    } else {
        $list .= '       <option value="remote">Remote</option>';
    }

    $list .=
    '    </select>
       </td>
      </tr>
      </tbody>
      </table>
      ';

    $list .= make_local_connection_panel ($iface, $security);
    
    $list .= make_remote_connection_panel ($iface, $security);

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_local_connection_panel ($iface, $security)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $list = '
      <div id="radius_local_connection">
      <div class="pl">Local</div>
        <div class="nl">
          <table>
          <tbody>
            <tr>
              <td>Virtual auth server:</td>
              <td>
                <select name="virtual_server" id="virtual_server">';

    $avs_selected = $iface;
    if ( $security[$iface]['radius_connection'] == 'local' )
    {
        $avs_selected = $security[$iface]['virtual_server'];
    }

    if ( !file_exists($paths['radius_conf_dir']) )
    {
        exec ("sudo mkdir -p ".$paths['radius_conf_dir']);
    }

    $clients = load_conf_file ($paths['clients']);
    if ( empty($clients) ) $clients['localhost']['auth_servers'] = array ("vs0");

    foreach ($clients['localhost']['auth_servers'] as $avs_name)
    {
        $list .= '<option value="'.$avs_name.'"';
        if ($avs_name == $avs_selected) $list .= ' selected ';
        $list .='>'.$avs_name.'</option>';
    }

    $list .=
    '         </select>
              </td>
            </tr>
          </tbody>
          </table>
        </div><!-- ID: Anonymous -->
      </div><!-- ID: RADIUS_LOCAL_CONNECTION -->
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_remote_connection_panel ($iface, $security)
/* ------------------------------------------------------------------------ */
{
    $list = '
      <div id="radius_remote_connection">
      <div class="pl">Remote</div>
        <div class="nl">
          <table>
          <tbody>
            <tr>
              <td>IP address:</td>
              <td><input type="text" name="radius_addr" id="radius_addr" maxlength="15"';

    if ( $security[$iface]['radius_connection'] == 'remote' )
    {
        $list .= ' value="'.$security[$iface]['radius_addr'].'"';
    }

    $list .= 
            ' /></td>
               <td>
                    <div id="radius_addr_ms_cte"></div>
               </td>
            </tr>
            <tr>
              <td>Port:</td>
              <td><input type="text" name="radius_port" id="radius_port" maxlength="5"';

    if ( $security[$iface]['radius_connection'] == 'remote' )
    {
        $list .= ' value="'.$security[$iface]['radius_port'].'"';
    }

    $list .= 
            ' /></td>
               <td>
                    <div id="radius_port_ms_cte"></div>
               </td>
            </tr>
            <tr>
              <td>Password:</td>
              <td><input type="password" name="radius_pass" id="radius_pass"';

    if ( $security[$iface]['radius_connection'] == 'remote' )
    {
        $list .= ' value="'.$security[$iface]['radius_pass'].'"';
    }

    $list .=
            ' /></td>
               <td>
                    <div id="radius_pass_ms_cte"></div>
               </td>
            </tr>

            <tr>
              <td>Confirm password:</td>
              <td><input type="password" name="cnf_radius_pass" id="cnf_radius_pass"';

    if ( $security[$iface]['radius_connection'] == 'remote' )
    {
        $list .= ' value="'.$security[$iface]['radius_pass'].'"';
    }

    $list .=
            ' />
               <td>
                    <div id="cnf_radius_pass_ms_cte"></div>
               </td>
           </tr>
          </tbody>
          </table>
        </div><!-- ID: Anonymous -->
      </div><!-- ID: RADIUS_REMOTE_CONNECTION -->
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

?>
