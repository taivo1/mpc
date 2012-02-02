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

function make_input()
{
    global $url_plugin;
    global $section;
    global $plugin;
    // THE ENTRIES FOR THE fresnel SERVER
    $list='<div class="title">Fresnel parameters</div>
            <div class="plugin_content">
                <table><tbody>
                    <tr>
                        <td colspan="4">
                            <div class="fresnel_data">
                                <label class="formula btext">Distance (km)</label>
                                <input size="8" type="text" class="btext ms_float" id="fresnel_distance" name="fresnel_distance" value="0" maxlength=8 />
                                <input type="button" value="Calculate" onclick="fresnel_calc()" />
                            </div>
                            <div class="fresnel_data">
                                <table cellspacing=0 cellpadding=0><tbody>
                                    <tr>
                                        <td class="t1">
                                            2.4 Ghz
                                        </td>
                                        <td class="t1">
                                            5 Ghz
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="t2">
                                            <span id="24b" class="btext">0 m</span>
                                        </td>
                                        <td class="t2">
                                            <span id="5b" class="btext">0 m</span>
                                        </td>
                                    </tr>
                                </tbody></table>
                            </div>
                        </td>
                        <td colspan="4">
                            <div class="fresnel_images">
                                <img src="'.$url_plugin.'images/fresnel_help_image.png">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        
                    </tr>
                </tbody></table>
                ';
    $list.='</div>';
    return $list;
}

function make_interface()
{
    global $url_plugin;
    global $section;
    global $plugin;
    $list.=make_input();
    return $list;
}
?>