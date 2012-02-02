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
function save_values($data)
{
    global $base_plugin;
    if (!empty($data['set_time']))
    {
        //Set the time for the meshlium.
        exec('sudo date -s "'.$data['set_time'].'"');
    }
    if ($data['ntp_server']=='on')
    {
        //Save ntp_time.conf values.
        $fp=fopen($base_plugin.'data/ntp_time.conf','w');
        fwrite($fp,"ntp_mode=on\n");
        fclose($fp);
    }
    else
    {
        //Remove ntp_time.conf values.
        $fp=fopen($base_plugin.'data/ntp_time.conf','w');
        fclose($fp);
    }
    exec('sudo cp '.$base_plugin.'data/ntp_time.conf /etc/ntp_time.conf');

    if ($data['gps_time_server']=='on')
    {
        //Save gps_time.conf values.
        $fp=fopen($base_plugin.'data/gps_time.conf','w');
        fwrite($fp,"gps_mode=on\n");
        fclose($fp);
    }
    else
    {
        //Remove gps_time.conf values.
        $fp=fopen($base_plugin.'data/gps_time.conf','w');
        fclose($fp);
    }
    exec('sudo cp '.$base_plugin.'data/gps_time.conf /etc/gps_time.conf');
}
?>