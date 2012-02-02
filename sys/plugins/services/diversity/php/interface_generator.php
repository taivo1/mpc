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

function make_options($wifi,$diversity)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;
    
    $list.='<div class="title2">Ath'.$wifi.'</div>
            <div class="plugin_content">
            <form id="diversity">
            <table><tbody><tr><td colspan="2">
            <input type="checkbox" name="wifi'.$wifi.'_manual" id="wifi'.$wifi.'_manual"';
            if(!empty($diversity[$wifi]))
            {
                $list.=' checked ';
            }
    $list.='
             />
            <label>Activate manual configuration for ath'.$wifi.'?</label>
            </td></tr><tr><td class="nl">
            <label>RX</label>
            </td><td>
            <select name="wifi'.$wifi.'_0">';
            if($diversity[$wifi]['rx']=='2')
            {
                $list.='
                <option value="0">Auto</option>
                <option value="1">Antenna 1</option>
                <option value="2" selected="yes">Antenna 2</option>';
            }
            elseif ($diversity[$wifi]['rx']=='1')
            {
                $list.='
                <option value="0">Auto</option>
                <option value="1" selected="yes">Antenna 1</option>
                <option value="2">Antenna 2</option>';
            }
            else
            {
                $list.='
                <option value="0" selected="yes">Auto</option>
                <option value="1">Antenna 1</option>
                <option value="2">Antenna 2</option>';
            }
    $list.='
            </select>
                </td></tr><tr><td class="nl">
                <label>TX</label>
            </td><td>
                <select name="wifi'.$wifi.'_1">
                    ';
                if($diversity[$wifi]['tx']=='2')
                {
                    $list.='
                    <option value="0">Auto</option>
                    <option value="1">Antenna 1</option>
                    <option value="2" selected="yes">Antenna 2</option>';
                }
                elseif ($diversity[$wifi]['tx']=='1')
                {
                    $list.='
                    <option value="0">Auto</option>
                    <option value="1" selected="yes">Antenna 1</option>
                    <option value="2">Antenna 2</option>';
                }
                else
                {
                    $list.='
                    <option value="0" selected="yes">Auto</option>
                    <option value="1">Antenna 1</option>
                    <option value="2">Antenna 2</option>';
                }
    $list.='
            </select>
            </td></tr></tbody></table></form>
            <div class="right_align">
                <input type="button" class="bsave" value="Save" onclick="complex_ajax_call(\'diversity\',\'output\',\''.$section.'\',\''.$plugin.'\',\'save\')"/>
                <input type="button" class="bsave" value="Save & Apply" onclick="complex_ajax_call(\'diversity\',\'output\',\''.$section.'\',\''.$plugin.'\',\'saveandrestart\')"/>
            </div></div>';
    return $list;
}
function make_interface($interface)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

    if($interface=='ath0')
    {
        $do_interface='0';
    }
    else
    {
        $do_interface='1';
    }

    $diversity=parse_diversity();
        
    $list.=make_options($do_interface,$diversity);
    
    return $list;
}
function select_interface()
{
    global $section;
    global $plugin;
    
    $options=array(' ','ath0','ath1');
    $list='
        <div class="title">Antenna diversity</div>
        <div class="title2">Interface configuration</div>
            <div class="plugin_content">
            <label class="btext">Select interface </label>';
    $list.=make_select('interface_selector',$options,' ');
    $list.='<input type="hidden" id="plugin" value="'.$plugin.'" />
            <input type="hidden" id="section" value="'.$section.'" />
            </div>';
    $list.='<div id="interface">';
    $list.='</div>';
    return $list;
}
?>