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
$server_iperf_status=false;
if(file_exists($base_plugin.'data/iperf_server.conf'))
{
    $configuration=file($base_plugin.'data/iperf_server.conf');
    $server_iperf_parser=explode('=',trim($configuration[0]));
    //$html='<pre>'.print_r($server_iperf_parser,true).'</pre>';
    if(($server_iperf_parser[0]=='server_status')&&($server_iperf_parser[1]=='on'))
    {
        $server_iperf_status=true;
    }
}
//$html.='<pre>'.print_r($server_iperf_status,true).'</pre>';
$html.='<div class="title">iperf</div>
        <div class="title2">Iperf server configuration</div>
        <div class="plugin_content">
            <form name="iperf_server" id="iperf_server" class="inline" onsubmit="return false;">
                <input type="checkbox" name="activate_server" ';
                if($server_iperf_status)
                {
                    $html.='checked';
                }
                $html.=' />
                <label>Activate iperf server on this machine</label>
             </form>
            <input type="button" class="bsave mright" value="Save" onclick="complex_ajax_call(\'iperf_server\',\'output\',\''.$section.'\',\''.$plugin.'\',\'save\')" />
            <input type="button" class="bsave" value="Save & Apply" onclick="complex_ajax_call(\'iperf_server\',\'output\',\''.$section.'\',\''.$plugin.'\',\'save_restart\')" />
            
        </div>
        <div class="title2">Iperf test</div>
        <div class="plugin_content">
            <div id="iperf_test_div">
                <form name="iperf_test" id="iperf_test" onsubmit="return false;" >
                <table><tbody><tr><td>
                        <label>Select interface</label>
                    </td><td>
                        <select id="interface" name="interface">
                            <option value="eth0">eth0</option>
                            <option value="ath0">ath0</option>
                            <option value="ath1">ath1</option>
                            <option value="ppp0">ppp0</option>
                        </select>
                    </td></tr><tr><td>
                        <label>Destination Host</label>
                    </td><td>
                        <input type="text" id="ip_address" name="ip_address" class="ms_mandatory ms_host" />
                    </td><td>
                        <input type="button" class="bsave" value="Do test" onclick="complex_ajax_call(\'iperf_test\',\'iperf_test_output\',\''.$section.'\',\''.$plugin.'\',\'do_test\')" />
                    </td></tr>
                    <tr>
                        <td></td>                
                        <td colspan="2">
                            <div id="ip_address_ms_cte"></div>
                        </td>
                    </tr>
                    </tbody></table>
                </form>
                <div id="iperf_test_output"></div>
            </div>           
        </div>';
?>