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
function make_interface()
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;
    
    // Check the ntp status
    if (file_exists('/etc/ntp_time.conf'))
    {
        $ntp_file=file('/etc/ntp_time.conf');
        foreach($ntp_file as $ntp_conf_line)
        {
            $ntp_active=explode('=',trim($ntp_conf_line));
            if (($ntp_active[0]=='ntp_mode')&&($ntp_active[1]=='on'))
            {
                $ntp_status='checked';
            }
        }
        unset($ntp_file);
        unset($ntp_conf_line);
        unset($ntp_active);
    }

    // Check the gps time status
    if (file_exists('/etc/gps_time.conf'))
    {
        $gps_file=file('/etc/gps_time.conf');
        foreach($gps_file as $gps_conf_line)
        {
            $gps_active=explode('=',trim($gps_conf_line));
            if (($gps_active[0]=='gps_mode')&&($gps_active[1]=='on'))
            {
                $gps_status='checked';
            }
        }
        unset($gps_file);
        unset($gps_conf_line);
        unset($gps_active);
    }
    $date=exec("date");
	$list='
            <div class="title">time synchronization</div>
            <div class="plugin_content">
            <div class="title2">'.$date.'</div>
            <form id="set_date_meshlium" class="ss">
                <label>Date and hour for meshlium</label>
                <input type="text" name="set_time" id="set_time"/>                
                <input type="button" value="Ok" onclick="complex_ajax_call(\'set_date_meshlium\',\'output\',\''.$section.'\',\''.$plugin.'\',\'save\')"/>
            </form>
            <form id="time_servers" class="ss">
                <table><tbody>
                    <tr>
                        <td>
                            <input type="checkbox" name="ntp_server" id="ntp_server" '.$ntp_status.'/>
                        </td>
                        <td class="table_label">
                            <label> Enable ntp server for meshlium time</label>
                        </td>
                        <td>
                            <span class="advice">(Meshlium needs internet access in order to update current time with ntp server)</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <input type="checkbox" name="gps_time_server" id="gps_time_server" '.$gps_status.'/>
                        </td>
                        <td>
                        <label> Enable gps as time server for meshlium</label>
                        </td>
                        <td>
                        <span class="advice">(Meshlium needs a gps module in order to update current time to the time served by gps satellites)</span>
                        </td>
                    </tr>
                </tbody></table>
            </form>
            <div class="right_align ss">
                <input type="button" class="bsave" value="Save" onclick="complex_ajax_call(\'time_servers\',\'output\',\''.$section.'\',\''.$plugin.'\',\'save\')"/>
                <input type="button" class="bsave" value="Save & Apply" onclick="complex_ajax_call(\'time_servers\',\'output\',\''.$section.'\',\''.$plugin.'\',\'save_restart\')"/>
            </div>';
    return $list;
}
?>