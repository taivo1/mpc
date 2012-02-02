<?php
/*
 *  Copyright (C) 2009 Libelium Comunicaciones Distribuidas S.L.
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
 *  Author: Daniel Larraz
 */

function write_wpa_supplicant ($filepath, $ssid,$pass,$hide, $writepath='')
/* ------------------------------------------------------------------------ */
{
    global $base_plugin;

    if ($writepath=='')
    {
        $writepath=$base_plugin.'data/temp_wpa_supplicant';
    }
    exec('wpa_passphrase '.$ssid.' '.$pass,$aux);
    $fp=fopen ($writepath,"w");
    fwrite($fp,"ctrl_interface=/var/run/wpa_supplicant\n");
    fwrite($fp,"eapol_version=2\n");
    fwrite($fp,"ap_scan=1\n");
    fwrite($fp,"network={\n");
    fwrite($fp,"\tssid=\"".$ssid."\"\n");
    if ($hide=='1')
    {
        fwrite($fp,"\tscan_ssid=1\n");
    }
    fwrite($fp,"\tpriority=5\n");
    fwrite($fp,"\tproto=WPA\n");
    fwrite($fp,"\tkey_mgmt=WPA-PSK\n");
    fwrite($fp,"\tpairwise=TKIP\n");
    fwrite($fp,"\tgroup=TKIP\n");
    fwrite($fp,$aux[3]."\n");
    fwrite($fp,"}\n");
    fclose($fp);
    exec ('sudo mv '.$writepath.' '.$filepath);
    exec ('sudo chown root:root '.$filepath);
}
/* ------------------------------------------------------------------------ */

?>
