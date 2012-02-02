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
function load_rule($rule,$rule_number)
{
    global $url_plugin;
    global $base_plugin;
    global $section;
    global $plugin;

    $output=' <div class="border rule">';
    $output.='<table><tbody><tr>';
    $output.='<td>';
    $output.=addimage($rule[0]);
    $output.='</td>';
    $output.='<td>';
    $output.=addimage($rule[1]);
    $output.='</td>';
    $output.='<td>';
    $output.=addimage($rule[2]);
    $output.='</td>';
    $output.='<td class="delete">';
    $output.='<input type="button" value="Delete" onclick="delete_rule(\''.$section.'\',\''.$plugin.'\',\''.$rule_number.'\');">';
    $output.='</td>';
    $output.='</tr></tbody></table>';
    $output.='</div>';
    return $output;
}
function addimage($item)
{
    global $url_plugin;
    global $base_plugin;
    global $section;
    global $plugin;
    
    switch ($item)
    {
        case 'ppp0':
            return '<img title="GPRS" alt="GPRS" src="'.$url_plugin.'images/j_gprsb.png"/> ';
            break;
        case 'eth0':
            return '<img title="GPRS" alt="GPRS" src="'.$url_plugin.'images/j_ethernetb.png"/> ';
            break;
        case 'ath0':
            return '<img title="GPRS" alt="GPRS" src="'.$url_plugin.'images/j_wifi1b.png"/> ';
            break;
        case 'ath1':
            return '<img title="GPRS" alt="GPRS" src="'.$url_plugin.'images/j_wifi2b.png"/> ';
            break;
        case 'Left':
            return '<img title="GPRS" alt="GPRS" src="'.$url_plugin.'images/j_izda2.png"/> ';
            break;
        case 'Bidirectional':
            return '<img title="GPRS" alt="GPRS" src="'.$url_plugin.'images/j_flecha2.png"/> ';
            break;
        case 'Right':
            return '<img title="GPRS" alt="GPRS" src="'.$url_plugin.'images/j_dcha2.png"/> ';
            break;
    }
}
?>