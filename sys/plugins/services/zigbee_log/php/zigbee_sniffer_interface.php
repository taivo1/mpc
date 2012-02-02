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
$status=exec($base_plugin."bin/log_status.sh");
if (file_exists($base_plugin.'data/zigbee_log.conf'))
{
    $config=file($base_plugin.'data/zigbee_log.conf');
}

$html='<div class="title">Zigbee sniffer</div>
            <div class="plugin_content">
                <div id="log_filename_div">
                    <form name="zigbee_log" id="zigbee_log" onsubmit="return false;" >
                        <table cellspacing=0 cellpadding=0><tbody>
                            <tr>
                                <td>
                                    <label>Log filename</label>
                                </td><td>
                                    <input class="ms_path" type="text" id="filename" name="filename" value="'.$config[0].'"/>
                                </td><td>
                                    <input type="button" class="bsave" value="Save and apply" onclick="complex_ajax_call(\'zigbee_log\',\'output\',\''.$section.'\',\''.$plugin.'\')" />
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="2"><div id="filename_ms_cte"></div></td>
                            </tr>
                        </tbody></table>
                    </form>
                </div>
                    <div id="status" class="ss">
                        <label>Zigbee sniffer status</label>
            ';
if ($status=='0')
{
    $html.='<input type="button" value="Start log" onclick="start_zigbee_log(\''.$section.'\',\''.$plugin.'\')" />';
}
else
{
    $html.='<input type="button" value="Stop log" onclick="stop_zigbee_log(\''.$section.'\',\''.$plugin.'\')" />';
}
$html.='</div>
        <div class="ss">
            <label>Last data logged</label>
            <input type="button" value="Show data" onclick="load_zigbee_data(\'zigbee_log\',\'zigbee_log_content\',\''.$section.'\',\''.$plugin.'\')" />
        </div>
        <div id="zigbee_log_content"></div>
    </div>
';
?>