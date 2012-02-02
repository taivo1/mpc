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
function encrypt_user_partition($post_data)
{
    global $base_plugin;
    // Change $deactivated to false to enable this plugin.
    $deactivated=true;

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

    if(!$deactivated)
    {
        if (($post_data['encrypt_user_partition_key']==$post_data['encrypt_user_partition_key2'])&&($post_data['encrypt_user_partition_key']!=''))
        {
            exec('sudo '.$base_plugin.'bin/encrypt-user_part.sh '.$post_data['encrypt_user_partition_key'].' '.$base_plugin.' '.$mountpoint_dir.' >/dev/null');
            response_additem("script", 'alert("Partition encrypted.")');
        }
        else
        {
            response_additem("script", 'alert("Password missmatch.")');
        }
    }
    else
    {
        response_additem("script", 'alert("Function disabled. You can enable it looking at source code. This function has been disabled because it can harm your hard disk.")');
    }
}
?>