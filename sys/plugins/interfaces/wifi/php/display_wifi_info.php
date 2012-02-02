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
 *  Author: Daniel Larraz
 */

include_once $base_plugin."php/display_security_info.php";

function make_wireless($path, $interface,$initial=true)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;
    
	$input=parse_interfaces($path);
    $list.='<form id="'.$interface.'" name="'.$interface.'">';
    $list.='<div class="title2">Network</div>
                <div id="network_plugin_content" class="plugin_content">
                    <table cellpadding="0" cellspacing="0"><tbody>';
    $list.='<tr><td>
                Choose IP method</td><td> <select onchange="check_conditions();" name="iface_sel" id="iface_sel" >';
    if ($input[$interface]['iface']=='dhcp')
    {
    	$list.='<option value="static">Static</option>';
    	$list.='<option selected="yes" value="dhcp">DHCP</option>';
    	$list.="</select>";
    }
    else
    {
    	$list.='<option selected="yes" value="static">Static</option>';
    	$list.='<option value="dhcp">DHCP</option>';
    	$list.='</select></td></tr>';
    }
    $list.="<tr><td>";
	$list.="<a id=address_lab>Address</a></td><td> <input type=\"text\" class=\"ms_ip\" name=\"address\" id=\"address\"";
    if ($input[$interface]['address']){
        $list.=" value=".$input[$interface]['address'];
    }
    else
    {
    	$list.=" value='10.1.20.1'";
    }
    $list.=" size=16 maxlength=15></td><td><div id=\"address_ms_cte\"></div></td></tr>";

    $list.="<tr><td>";
    $list.="<a id=netmask_lab>Netmask</a></td><td> <input type=\"text\" class=\"ms_ip\" name=\"netmask\" id=\"netmask\"";
    if ($input[$interface]['netmask']){
        $list.=" value=".$input[$interface][netmask];
    }
	else
    {
    	$list.=" value='255.255.255.0'";
    }
    $list.=" size=16 maxlength=15></td><td><div id=\"netmask_ms_cte\"></div></td>";

    $list.="</tr><tr><td>";
    $list.="<a id=gateway_lab>Gateway</a></td><td> <input type=\"text\" class=\"ms_ip\" name=\"gateway\" id=\"gateway\"";
    if ($input[$interface]['gateway']){
        $list.=" value=".$input[$interface]['gateway'];
    }
    $list.=" size=16 maxlength=15></td><td><div id=\"gateway_ms_cte\"></div></td>";

    $list.="</tr><tr><td>";
    $list.="<a id=broadcast_lab>Broadcast</a></td><td> <input type=\"text\" class=\"ms_ip\" name=\"broadcast\" id=\"broadcast\"";
    if ($input[$interface]['broadcast']){
            $list.=" value=".$input[$interface]['broadcast'];
    }
	$list.=" size=16 maxlength=15></td><td><div id=\"broadcast_ms_cte\"></div></td>";

    $list.="</tr><tr><td>";
    $list.="<a id=DNS1_lab>Primary DNS</a></td><td> <input type=\"text\" class=\"ms_ip\" name=\"DNS1\" id=\"DNS1\"";
    if ($input[$interface]['dns_primario']){
        $list.=" value=".$input[$interface]['dns_primario'];
    }
    $list.=" size=16 maxlength=15></td><td><div id=\"DNS1_ms_cte\"></div></td>";
    $list.="</tr><tr><td>";
    $list.="<a id=DNS2_lab>Secondary DNS</a></td><td> <input type=\"text\" class=\"ms_ip\" name=\"DNS2\" id=\"DNS2\"";
    if ($input[$interface]['dns_secundario']){
        $list.=" value=".$input[$interface]['dns_secundario'];
    }
    $list.=" size=16 maxlength=15></td><td><div id=\"DNS2_ms_cte\"></div></td></tr>";
    $list.='</tbody></table></div>';

    // Second block of options.

    $list.='
            <div class="title2">Radio</div>
            <div id="radio_plugin_content" class="plugin_content">
            <table cellpadding="0" cellspacing="0"><tbody>
            <tr><td>';
    $list.="ESSID</td><td><input type=\"text\" class=\"ms_mandatory\" name=\"essid\" id=\"essid\" size=16";
    if ($input[$interface]['post-up']['iwconfig']['essid'])
    {
    	$list.=" value=".$input[$interface]['post-up']['iwconfig']['essid'];
    }
    $list.="></td><td>Hide? ";
    $list.="<input name=\"hide\" id=\"hide\" type=\"checkbox\"";
    if ($input[$interface]['up']['iwpriv']['hide_ssid']=='1')
    {
    	$list.=" checked";
    }
    $list.=">";
    $list.="</td><td><div id=\"essid_ms_cte\"></div></td></tr>";

    $list.="<tr><td><div id='mac_essid'>";
    $list.="MAC Address</div></td><td><div id='mac_essid2' > <input type=\"text\" class=\"ms_mac\" name='mac_essid_i' id='mac_essid_i'";
    if ($input[$interface]['post-up']['iwconfig']['ap']){
            $list.=" value=".$input[$interface]['post-up']['iwconfig']['ap'];
    }
    $list.=" size=16 maxlength=17></div></td><td></td><td><div id=\"mac_essid_i_ms_cte\"></div></td></tr>";
    $list.="<tr><td>";

    if ($input[$interface]['post-up']['iwconfig']['mode']=='managed')
    {
    	$list.="Mode</td><td> <select onchange=\"check_conditions();\" name=\"mode\" id=\"mode\">";
    	$list.="<option value=\"master\">Manager</option>";
    	$list.="<option value=\"ad-hoc\">Ad-hoc</option>";
    	$list.="<option selected='yes' value=\"managed\">Managed</option>";
    	$list.="</select></td></tr>";
    }
	elseif ($input[$interface]['post-up']['iwconfig']['mode']=='ad-hoc')
	{
    	$list.="Mode</td><td> <select onchange=\"check_conditions();\" name=\"mode\" id=\"mode\">";
    	$list.="<option selected='yes' value=\"master\">Manager</option>";
    	$list.="<option selected='yes' value=\"ad-hoc\">Ad-hoc</option>";
    	$list.="<option value=\"managed\">Managed</option>";
    	$list.="</select></td></tr>";
    }
	else
	{
    	$list.="Mode</td><td> <select onchange=\"check_conditions();\" name=\"mode\" id=\"mode\">";
    	$list.="<option selected='yes' value=\"master\">Manager</option>";
    	$list.="<option value=\"ad-hoc\">Ad-hoc</option>";
    	$list.="<option value=\"managed\">Managed</option>";
    	$list.="</select></td></tr>";
    }

    $list.="<tr><td>";
    $list.="Frequency</td><td><div style=\"display:inline;\"> <select onchange=\"check_conditions();\" name=\"freq\" id=\"freq\" >";
    if (isset($input[$interface]['post-up']['iwconfig']['channel'])&&($input[$interface]['post-up']['iwconfig']['channel']>14))
    {
    	$list.="<option value=\"2\">2.4GHz</option>";
    	$list.="<option selected='yes' value=\"5\">5GHz</option>";
    }
    else
    {
    	$list.="<option selected='yes' value=\"2\">2.4GHz</option>";
    	$list.="<option value=\"5\">5GHz</option>";
    }
    $list.="</select></div>";

    $list.="</td></tr><tr><td>";
    $list.="Channel</td><td> <select onchange=\"check_conditions();\" name=\"channel2\" id=\"channel2\">";
    for($vuelta=1;$vuelta<=13;$vuelta++)
    {
    	$list.="<option ";
    	if ($input[$interface]['post-up']['iwconfig']['channel']==$vuelta)
    	{ $list.=" selected='yes' ";}
    	$list.="value=".$vuelta.">".$vuelta."</option>";
    }
    $list.="</select>";
    $list.="<select onchange=\"check_conditions();\" name=\"channel5\" id=\"channel5\" >";
    for($vuelta=34;$vuelta<=56;$vuelta+=2)
    {
    	$list.="<option ";
    	if ($input[$interface]['post-up']['iwconfig']['channel']==$vuelta)
    	{ $list.=" selected='yes' ";}
    	$list.="value=".$vuelta.">".$vuelta."</option>";
    }
    $vuelta=60;
    $list.="<option ";
    if ($input[$interface]['post-up']['iwconfig']['channel']==$vuelta)
    { $list.=" selected='yes' ";}
    $list.="value=".$vuelta.">".$vuelta."</option>";
    $vuelta=64;
    $list.="<option ";
    if ($input[$interface]['post-up']['iwconfig']['channel']==$vuelta)
    { $list.=" selected='yes' ";}
    $list.="value=".$vuelta.">".$vuelta."</option>";
    $vuelta=149;
    $list.="<option ";
    if ($input[$interface]['post-up']['iwconfig']['channel']==$vuelta)
    { $list.=" selected='yes' ";}
    $list.="value=".$vuelta.">".$vuelta."</option>";
    $vuelta=153;
    $list.="<option ";
    if ($input[$interface]['post-up']['iwconfig']['channel']==$vuelta)
    { $list.=" selected='yes' ";}
    $list.="value=".$vuelta.">".$vuelta."</option>";
    $vuelta=157;
    $list.="<option ";
    if ($input[$interface]['post-up']['iwconfig']['channel']==$vuelta)
    { $list.=" selected='yes' ";}
    $list.="value=".$vuelta.">".$vuelta."</option>";
    $vuelta=161;
    $list.="<option ";
    if ($input[$interface]['post-up']['iwconfig']['channel']==$vuelta)
    { $list.=" selected='yes' ";}
    $list.="value=".$vuelta.">".$vuelta."</option>";
    $list.="</select></div></td></tr>";

    $list.="<tr><td><div id=iwpriv_mode>";
    $list.="Protocol</div></td>";
    // Two selections b/g for 2.4 and a for 5GHz
    $list.="<td><div id=bg_dat> <select onchange=\"check_conditions();\" name=\"mode-abg\" id=\"mode-abg\">";
    if ($input[$interface]['up']['iwpriv']['mode']=='3')
    {
    	$list.="<option selected=\"yes\" value=3>a</option>";
    	$list.="<option value=1>b</option>";
    	$list.="<option value=2>g</option>";
    }
    elseif ($input[$interface]['up']['iwpriv']['mode']=='1')
    {
    	$list.="<option value=3>a</option>";
    	$list.="<option selected=\"yes\" value=1>b</option>";
    	$list.="<option value=2>g</option>";
    }
    else
    {
    	$list.="<option value=3>a</option>";
    	$list.="<option value=1>b</option>";
    	$list.="<option selected=\"yes\" value=2>g</option>";
    }
    $list.="</select>";
    $list.="</div></td><td>";

    $list.="<tr><td>";
    $tx_power_values=array('auto'=>'auto','0'=>'0 dB','1'=>'1 dB','2'=>'2 dB','3'=>'3 dB','4'=>'4 dB','5'=>'5 dB','6'=>'6 dB','7'=>'7 dB','8'=>'8 dB','9'=>'9 dB','10'=>'10 dB','11'=>'11 dB','12'=>'12 dB','13'=>'13 dB','14'=>'14 dB','15'=>'15 dB','16'=>'16 dB','17'=>'17 dB','18'=>'18 dB','19'=>'19 dB');
    if(!empty($input[$interface]['post-up']['iwconfig']['txpower']))
    {
        $default_tx_power=$input[$interface]['post-up']['iwconfig']['txpower'];
    }
    else
    {
        $default_tx_power='auto';
    }
    $list.="Tx power</td><td>".make_select_detailed('tx_power',$tx_power_values,$default_tx_power);
    $list.='</td></tr>';
    $list.="<tr><td>";
    $rate_values=array('auto','1Mbps','2Mbps','6Mbps','9Mbps','11Mbps','12Mbps','18Mbps','24Mbps','36Mbps','48Mbps','54Mbps');
    $list.="Rate</td><td>".make_select('rate',$rate_values,$input[$interface]['post-up']['iwconfig']['rate']);
    $list.='</td></tr>';
	$list.="<tr><td>";
    $list.="Fragmentation</td><td> <input type=\"text\" class=\"ms_alnum\" name=\"frag\" id=\"frag\"";
    if ($input[$interface]['post-up']['iwconfig']['frag']){
            $list.=" value=".$input[$interface]['post-up']['iwconfig']['frag'];
    }
	$list.=" size=16 maxlength=4></td><td>[256-2346] or off </td><td><div class=\"nl\" id=\"frag_ms_cte\"></div></td></tr>";

$list.='</tbody></table></div>';

    // Third block of options.
    $list.='<div id="security_div">';
    $list .= make_security ($interface);
    $list.='</div>';
    
    // Forth blok of options
    
    $list.='<div id="mac_filter_div">';
    $list.=make_mac_filter($interface,$input[$interface]['up']['interfaces_plus.sh']);
    $list.='</div>';
    
    $list.='
            <div class="right_align"><input type="button" class="bsave" onclick="complex_ajax_call(\''.$interface.'\',\'output\',\''.$section.'\',\''.$plugin.'\',\'default\')" value="save and apply"></div>
        <div id="output"></div>';
	
	return $list;
}

function make_selector()
{
    global $plugin;
    global $section;
    $options=array(' ','ath0','ath1');
    $list='
        <div class="title">WIFI</div>
        <div class="title2">Interface configuration</div>
            <div class="plugin_content">
            <label class="btext">Select interface </label>';
    $list.=     make_select('interface_selector',$options,' ');
    $list.='
            <input type="hidden" id="plugin" value="'.$plugin.'" />
            <input type="hidden" id="section" value="'.$section.'" />
            </div>
        <div id="interface_info"></div>';
    return $list;

}
?>