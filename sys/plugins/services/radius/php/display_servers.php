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

function make_auth_server_info_row ($avs_name, $avs_data, $url_plugin)
/* ------------------------------------------------------------------------ */
{
    $list = '
      <tr>
      <td class="avs_name">'.$avs_name.'</td>
      <td class="avs_port">'.$avs_data['port'].'</td>
    ';

    if ( in_array ('tls', $avs_data['wpa_eap']) )
    {

        $img = $url_plugin.'images/check.png';
    }
    else
    {
        $img = $url_plugin.'images/cross.png';
    }
    $list .= '<td class="avs_tls"><img src="'.$img.'" /></td>';

    if ( in_array ('ttls', $avs_data['wpa_eap']) )
    {

        $img = $url_plugin.'images/check.png';
    }
    else
    {
        $img = $url_plugin.'images/cross.png';
    }
    $list .= '<td class="avs_ttls"><img src="'.$img.'" /></td>';

    if ( in_array ('peap', $avs_data['wpa_eap']) )
    {

        $img = $url_plugin.'images/check.png';
    }
    else
    {
        $img = $url_plugin.'images/cross.png';
    }
    $list .= '<td class="avs_peap"><img src="'.$img.'" /></td>';

    $list .= '
      <td><input type="button" id="edit_avs_'.$avs_name.'_btn"
                 name="edit_avs_'.$avs_name.'_btn"
                 onclick="edit_auth_server(\''.$avs_name.'\')" value="edit"></td>
    ';

    if ($avs_name != 'vs0')
    {
        $list .= '<td><input type="button" id="delete_avs_'.$avs_name.'_btn" 
                             name="delete_avs_'.$avs_name.'_btn"
                             onclick="delete_auth_server(\''.$avs_name.'\')" 
                             value="delete"></td>';
    }

    $list .= '</tr>';

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_acct_server_info_row ($acvs_name, $acvs_data)
/* ------------------------------------------------------------------------ */
{
    $list = '
      <tr>
      <td class="acvs_name">'.$acvs_name.'</td>
      <td class="acvs_port">'.$acvs_data['port'].'</td>
      <td><input type="button" id="edit_acvs_'.$acvs_name.'_btn"
                 name="edit_acvs_'.$acvs_name.'_btn"
                 onclick="edit_acct_server(\''.$acvs_name.'\')" value="edit"></td>
      </tr>
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

?>
