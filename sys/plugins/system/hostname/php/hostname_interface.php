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
$hostname=exec("hostname");
$html=' <div class="title">Hostname</div>
        <div id="plugin_content">
            <form name="hostname" id="hostname" onsubmit="return false;" >
                <table><tbody><tr><td>
                <label>Meshlium\'s hostname</label>
                </td><td>
                <input type="text" class="ms_alnum ms_mandatory" id="hostname_value" name="hostname_value" value="'.$hostname.'"/>
                </td><td>
                <input type="button" class="bsave" value="Save and apply" onclick="complex_ajax_call(\'hostname\',\'output\',\''.$section.'\',\''.$plugin.'\')" />
                </td></tr>
                <tr><td></td><td>
                <div id="hostname_value_ms_cte"></div>
                </td></tr>
                </tbody></table>
            </form>
        </div>';
?>