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
    exec ("sudo ls ".$paths['server_cert'], $sc_cmd);
    exec ("sudo ls ".$paths['server_key'], $sk_cmd);

    return $sc_cmd[0] == $paths['server_cert'] &&
           $sk_cmd[0] == $paths['server_key'];
}
/* ------------------------------------------------------------------------ */

function are_cert_and_key_valid($paths, $key_pass='')
/* ------------------------------------------------------------------------ */
{
    $are_valid = false;

    $cert_path =  $paths['server_cert'];
    $key_path = $paths['server_key'];
    $pass_path = $paths['server_pass'];

    exec ("sudo /etc/ssl/sh/mod_crt.sh $cert_path", $cert);
    if ($cert[0] == 'VALID_FILE')
    {
        // It's OK although it does not need key_pass
        if ($key_pass == '')
        {
            exec ("sudo awk '/echo/ {print $2}' ".$pass_path, $awk);
            $key_pass = substr($awk[0], 1, -1);
        }

        exec ("sudo /etc/ssl/sh/mod_key.sh $key_path $key_pass", $key);
        if ($key[0] == 'VALID_FILE')
        {
            $are_valid = $cert[1] == $key[1];
        }
    }
    return $are_valid;
}
/* ------------------------------------------------------------------------ */

?>
