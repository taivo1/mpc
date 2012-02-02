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

function exists_certificates ($paths)
/* ------------------------------------------------------------------------ */
{
    exec ("sudo ls ".$paths['cacert'], $ca_cmd);
    exec ("sudo ls ".$paths['server_cert'], $sc_cmd);
    exec ("sudo ls ".$paths['server_key'], $sk_cmd);

    return $ca_cmd[0] == $paths['cacert'] &&
           $sc_cmd[0] == $paths['server_cert'] &&
           $sk_cmd[0] == $paths['server_key'];
}
/* ------------------------------------------------------------------------ */

function are_cert_and_key_valid($paths, $key_pass='')
/* ------------------------------------------------------------------------ */
{
    $are_valid = false;

    $ca_path = $paths['cacert'];
    $cert_path =  $paths['server_cert'];
    $key_path = $paths['server_key'];

    exec ("sudo /etc/ssl/sh/mod_crt.sh $cert_path", $cert);
    if ($cert[0] == 'VALID_FILE')
    {
        // It's OK although it does not need key_pass
        if ($key_pass == '')
        {
            exec ("sudo awk '/private_key_password/ {print $3}' ".$paths['fr_eap'], $awk);
            $key_pass = $awk[0];
        }

        exec ("sudo /etc/ssl/sh/mod_key.sh $key_path $key_pass", $key);
        if ($key[0] == 'VALID_FILE')
        {
            if ($cert[1] == $key[1])
            {
                exec ("sudo openssl verify -CAfile $ca_path $cert_path", $ssl);
                $ssl_tmp=explode(':',end($ssl));
                $are_valid = (( trim($ssl_tmp[0])== "OK")||( trim($ssl_tmp[1])== "OK"));                
            }
        }
    }
    return $are_valid;
}
/* ------------------------------------------------------------------------ */

?>
