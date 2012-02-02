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

function make_user_info_row ($username, $userdata, $online)
/* ------------------------------------------------------------------------ */
{
    $list = '
      <tr>
      <td class="usr_name">'.$username.'</td>
    ';

    if ( isset($userdata['Session-Timeout']) )
    {
        $list .= '<td class="usr_timeout">'.$userdata['Session-Timeout'].'s</td>';
    }
    else
    {
        $list .= '<td class="usr_timeout">-</td>';
    }

    $list .= '
      <td class="usr_logtime">
        <a href="" id="'.$username.'_show_logtime">Show</a>
      </td>
    ';

    if ($online)
    {
        $list .= '<td class="usr_online">YES</td>';
    }
    else
    {
        $list .= '<td class="usr_online">NO</td>';
    }

    $list .= '
      <td><input type="button" id="edit_usr_'.$username.'_btn"
                 name="edit_usr_'.$username.'_btn"
                 onclick="edit_user(\''.$username.'\')" value="edit"></td>
      <td><input type="button" id="delete_usr_'.$username.'_btn"
                             name="delete_usr_'.$username.'_btn"
                             onclick="delete_user(\''.$username.'\')"
                             value="delete"></td>';

    $list .= '</tr>';

    return $list;
}
/* ------------------------------------------------------------------------ */

?>
