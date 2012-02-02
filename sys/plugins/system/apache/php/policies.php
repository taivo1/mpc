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

function policy($paths, $item)
/* ------------------------------------------------------------------------ */
{
    $filepath = $paths['ap2_policies'].$item;
    exec ("sudo ls ".$filepath, $ls);
    if ($ls[0] == $filepath)
    {
        exec ("sudo awk '/RewriteEngine/ {print $2}' ".$filepath, $awk1);
        if ($awk1[0] == 'On')
        {
            exec ("sudo awk '/RewriteCond/ {print $3}' ".$filepath, $awk2);
            if ($awk2[0] == '^80$') return 'https';
            else return 'http';
        }
        else
        {
            return 'both';
        }
    }
    else
    {
        if ($item != '') return 'global';
        else return 'both';
    }
}
/* ------------------------------------------------------------------------ */

?>
