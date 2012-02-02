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
function make_backup($name)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

    $files_to_backup=Array("/etc/network/interfaces","/etc/wvdial.conf","/etc/mms/join_interfaces.conf","/etc/olsrd/olsrd.conf","/etc/dnsmasq.more.conf","/etc/bluetooth/hcid.conf","/etc/ntp.conf");

    exec("mkdir -p $base_plugin/data/$name");
    foreach ($files_to_backup as $file)
    {
        exec ("sudo cp -f $file $base_plugin/data/$name/");
    }
    exec("chown -R www-data:www-data $base_plugin/data/$name/");
}
function restore_backup($name)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

    $files_to_backup=Array("interfaces"=>"/etc/network/interfaces",
"wvdial.conf"=>"/etc/wvdial.conf",
"join_interfaces.conf"=>"/etc/mms/join_interfaces.conf",
"olsr.conf"=>"/etc/olsrd/olsr.conf",
"dnsmasq.more.conf"=>"/etc/dnsmasq.more.conf",
"hcid.conf"=>"/etc/bluetooth/hcid.conf",
"ntp.conf"=>"/etc/ntp.conf");

    $files_to_restore = scandir($base_plugin.'data/'.$name);
    foreach ($files_to_restore as $file)
    {
        if (array_key_exists($file, $files_to_backup))
        {
        // Uncomment to have it functional
        exec ("cp -f $base_plugin/data/$name/$file ".$files_to_backup[$file]);
        //echo "\ncp -f $base_plugin/data/$name/$file ".$files_to_backup[$file];
        }
    }
}
function delete_backup($name)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;
    exec("rm -rf $base_plugin/data/$name");
    exec("rm -f $base_plugin/data/$name.tgz");
}
function make_link($name)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;
    if(file_exists($base_plugin.'data/'.$name))
    {
        exec('cd '.$base_plugin.'data/;tar zcvf '.$name.'.tgz '.$name);
    }
    return ($url_plugin.'data/'.$name.'.tgz');
}
function get_url($url)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;
    if(is_url($url))
    {
        exec(' cd '.$base_plugin.'data ;wget '.$url.' -O get_url_tmp.tgz ;tar zxvf get_url_tmp.tgz ; rm get_url_tmp.tgz');
    }
}
?>