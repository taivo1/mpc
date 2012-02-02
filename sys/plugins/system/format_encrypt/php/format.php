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

function save_mount_user($mountpoint,$option='yes',$writepath='')
{
    global $base_plugin;

    $device_path='/dev/hda3';

    if($writepath=='')
    {
        $writepath=$base_plugin.'data/mount_user.sh';
    }

    $fp=fopen($writepath,'w');
    fwrite($fp,"#!/bin/bash\n");
    if ($option=='yes')
    {
        fwrite($fp,"mount $device_path $mountpoint \n");
    }
    fclose($fp);
    unset($fp);
    $fp=fopen($base_plugin.'data/mountpoint_name.conf','w');
    fwrite($fp,$mountpoint);
    fclose($fp);
}
function mount_partition_startup($mountpoint)
{
    global $base_plugin;
    save_mount_user($mountpoint);
    exec('sudo cp '.$base_plugin.'data/mount_user.sh /etc/init.d/mount_user.sh');
    exec('sudo chmod +x /etc/init.d/mount_user.sh;sudo update-rc.d mount_user.sh defaults 99');
    return true;
}

function format_user_partition($post_data)
{
    global $base_plugin;
    // Change $deactivated to false to enable this plugin.
    $deactivated=true;
    if(!$deactivated)
    {
        exec('sudo '.$base_plugin.'bin/mkfsext3.sh '.$post_data['extra_storage_mountpoint'].' 2>&1');
        mount_partition_startup($post_data['extra_storage_mountpoint']);
        response_additem("script", 'alert("Partition formated and mounted on '.$post_data['extra_storage_mountpoint'].'.")');

    }
    else
    {
        response_additem("script", 'alert("Function disabled. You can enable it looking at source code. This function has been disabled because it can harm your hard disk.")');
    }
}
?>