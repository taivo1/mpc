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

function make_client_info_row ($client_name, $client_data)
/* ------------------------------------------------------------------------ */
{
    $list = '
      <tr>
      <td class="clt_name">'.$client_name.'</td>
      <td class="clt_addr">'.$client_data['addr'].'</td>
      <td class="clt_vas">
        <a href="" id="'.$client_name.'_show_vas">Show</a>
      </td>
      <td><input type="button" id="edit_clt_'.$client_name.'_btn"
                 name="edit_clt_'.$client_name.'_btn"
                 onclick="edit_client(\''.$client_name.'\')" value="edit"></td>
    ';

    if ($client_name != 'localhost')
    {
        $list .= '<td><input type="button" id="delete_clt_'.$client_name.'_btn"
                             name="delete_clt_'.$client_name.'_btn"
                             onclick="delete_client(\''.$client_name.'\')"
                             value="delete"></td>';
    }

    $list .= '</tr>';

    return $list;
}
/* ------------------------------------------------------------------------ */

?>
