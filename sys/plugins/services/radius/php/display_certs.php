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

function make_upload_cert_btn ($file_idx, $enabled)
/* ------------------------------------------------------------------------ */
{
    $list = ' <input id="upload_'.$file_idx.'_btn" name="upload_'.$file_idx.'_btn" '.
            ' type="button" value="Upload" ';

    if (!$enabled)
    {
        $list .= 'class="disabled" disabled />';
    }
    else
    {
        $list .= '/>';
    }

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_delete_cert_btn ($file_idx, $enabled)
/* ------------------------------------------------------------------------ */
{
    $list = ' <input id="delete_'.$file_idx.'_btn" name="delete_'.$file_idx.'_btn" '.
            ' type="button"'.
            ' onclick="delete_cert_file(\''.$file_idx.'\')"  value="Delete" ';

    if (!$enabled)
    {
        $list .= 'class="disabled" disabled />';
    }
    else
    {
        $list .= '/>';
    }

    return $list;
}
/* ------------------------------------------------------------------------ */

?>
