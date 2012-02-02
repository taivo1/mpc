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

function make_inputs($hcid_conf)
{
    global $base_plugin;
    
    //$list.="<pre>".print_r($hcid_conf,true)."</pre>";
    $list.='
            <div class="pl1">Options</div>
            <div class="nl">
                <table>
                <tbody>
                <tr>
                <td>Autoinit</td><td><input type="checkbox" name="autoinit"';
        if($hcid_conf['autoinit']=='yes')
        {
            $list.='checked';
        }
        $list.='></td>
                </tr>
                <tr><td>
                Security</td><td>
    ';
        $options=array('none','auto','user');
        $list.=make_select('security',$options,$hcid_conf['security']);
        $list.='</td></tr>
                <tr><td>
                Pairing</td><td>
    ';
        $options=array('none','multi','once');
        $list.=make_select('pairing',$options,$hcid_conf['pairing']);
        $list.='
                </td></tr>
                <tr><td>
                Passkey </td><td><input type="text" name="passkey" value="'.htmlentities($hcid_conf['passkey'],ENT_QUOTES).'" />
                </td></tr>
                </tbody></table>
            </div>
            <div class="pl">Device</div>
            <div class="nl">
                <table>
                <tbody>
                <tr><td>
                    <span>Name</span>
                </td><td><input type="text" name="name" value="'.htmlentities($hcid_conf['name'],ENT_QUOTES).'" />
              </td></tr>
                <tr><td class="ss">
                    <span>Inquiry mode</span>
                </td></tr>
                <tr><td>
                    <span class="nl">iscan</span>
                </td><td>
                    <input name="iscan" type="checkbox" ';
        if($hcid_conf['iscan']=='enable')
        {
            $list.='checked';
        }
        $list.='/>
                </td></tr>
                <tr><td>
                    <span class="nl">pscan</span>
                </td><td>
                    <input name="pscan" type="checkbox" ';
        if($hcid_conf['pscan']=='enable')
        {
            $list.='checked';
        }
        $list.=' />
                </td></tr>
                <tr><td class="ss">
                <span>Link mode</span>
                </td></tr>
                <tr><td>
                    <span class="nl">pairing</span>
                </td><td>
    ';
        $options=array('none','accept','master');
        $list.=make_select('lm',$options,$hcid_conf['lm']);
        $list.='
                </td></tr>
                <tr><td class="ss">
                <span>Link policy</span>
                </td></tr>
                <tr><td>
                    <span class="nl">rswitch</span>
                </td><td><input name="rswitch" type="checkbox" ';
        if($hcid_conf['lp']['rswitch']=='rswitch')
        {
            $list.='checked';
        }
        $list.=' />
                </td></tr>
                <tr><td>
                    <span class="nl">hold</span>
                </td><td><input name="hold" type="checkbox" ';
        if($hcid_conf['lp']['hold']=='hold')
        {
            $list.='checked';
        }
        $list.=' />
                </td></tr>
                <tr><td>
                    <span class="nl">sniff</span>
                </td><td><input name="sniff" type="checkbox" ';
        if($hcid_conf['lp']['sniff']=='sniff')
        {
            $list.='checked';
        }
        $list.=' />
                </td></tr>
                <tr><td>
                    <span class="nl">park</span>
                </td><td><input name="park" type="checkbox" ';
        if($hcid_conf['lp']['park']=='park')
        {
            $list.='checked';
        }
        $list.=' />
                </td></tr>
                </tbody></table>
            </div>';
    return $list;
}


function make_interface()
{
    global $url_plugin;
    global $base_plugin;
    global $section;
    global $plugin;

    $list='<div class="title">Bluetooth</div>';
    $list.='<div id="plugin_content">';
    $list.='<form id="hcid_configuration" name="hcid_configuration">';
    $hcid_conf=parse_hcid('/etc/bluetooth/hcid.conf');
    $list.=make_inputs($hcid_conf);
    $list.='</form>';
    $list.='<div class="right_align">';
    $list.='<input type="button" class="bsave" onclick="complex_ajax_call(\'hcid_configuration\',\'output\',\''.$section.'\',\''.$plugin.'\',\'save\');" value="Save" />';
    $list.='<input type="button" class="bsave" onclick="complex_ajax_call(\'hcid_configuration\',\'output\',\''.$section.'\',\''.$plugin.'\',\'save_restart\');" value="Save & Apply" />';
    $list.='</div></div>';
    return $list;
}
?>