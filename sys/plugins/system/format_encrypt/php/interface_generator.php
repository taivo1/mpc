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

    if(file_exists($base_plugin.'data/mountpoint_name.conf'))
    {
        $mountpoint=file($base_plugin.'data/mountpoint_name.conf');
        $mountpoint_dir=trim($mountpoint[0]);
        unset($mountpoint);
    }
    else
    {
        $mountpoint_dir='/mnt/user';
    }

    $list='
            <div class="title">HD format and encryption</div>
            <div class="title2">Encrypt user partition configuration</div>
            <div class="plugin_content">
                    <form id="encrypt_partition">
                        <table><tbody>
                            <tr><td colspan="2">
                                Encrypt user partition
                            </td></tr>
                            <tr><td class="first_row">
                                <label class="nl" >Key to encrypt partition</label>
                            </td><td>
                                <input type="password" name="encrypt_user_partition_key" id="encrypt_user_partition_key">
                            </td><td rowspan="4">
                                <input type="button" value="encrypt" onclick="complex_ajax_call(\'encrypt_partition\',\'output\',\''.$section.'\',\''.$plugin.'\',\'encrypt\')"/>
                            </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><div id="encrypt_user_partition_key_ms_cte"></div></td>
                            </tr>
                            <tr><td>
                            <label class="nl" >Confirm key</label>
                            </td><td>
                                <input type="password" name="encrypt_user_partition_key2" id="encrypt_user_partition_key2">
                            </td></tr>
                            <tr>
                                <td></td>
                                <td><div id="encrypt_user_partition_key2_ms_cte"></div></td>
                            </tr></tbody></table>
                        </form>
                        <form id="mount_encrypted_partition">
                            <table><tbody>
                            <tr><td colspan="2" class="ss">
                                Mount a previously encrypted partition
                            </td></tr>
                            <tr><td class="first_row">
                                <label class="nl">Key to mount partition</label>
                            </td><td>
                                <input type="password" name="mount_encrypted_user_partition_key" id="mount_encrypted_user_partition_key">
                            </td><td>
                                <input type="button" value="Mount" onclick="complex_ajax_call(\'mount_encrypted_partition\',\'output\',\''.$section.'\',\''.$plugin.'\',\'mount\')"/>
                            </td></tr>
                            <tr>
                                <td></td>
                                <td><div id="mount_encrypted_user_partition_key_ms_cte"></div></td>
                            </tr>
                        </tbody></table>
                    </form>
                </div>
                <div class="title2 ss">
                    Format extra storage
                </div>
                <div class="plugin_content">
                    <form id="extra_storage_actions">
                        <table><tbody><tr><td colspan="4">
                             Format extra storage
                            </td></tr><tr><td>
                            <label class="nl">Mountpoint</label>
                            </td><td>
                            <input type="text" name="extra_storage_mountpoint" id="extra_storage_mountpoint" value="'.$mountpoint_dir.'">
                            </td><td colspan="2">
                            <input type="button" value="Format" onclick="complex_ajax_call(\'extra_storage_actions\',\'output\',\''.$section.'\',\''.$plugin.'\',\'format\')"/>
                            </td></tr>';
    $list.='
                            <tr>
                                <td></td>
                                <td><div id="extra_storage_mountpoint_ms_cte"></div></td>
                            </tr>
                        </tbody></table>
                    </form>
            </div>';
    return $list;
}
?>