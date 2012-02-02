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
function parse_diversity()
{
    global $section;
    global $plugin;
    global $base_plugin;

    $ret=Array();
    $file_path=$base_plugin.'data/diversity.sh';
    if(file_exists($file_path))
    {
        $file=file($file_path);
        foreach ($file as $line)
        {
            $data=explode(' ',trim($line));
            if ($data[0]=='/usr/bin/cambia_diversidad.sh')
            {
                $ret[$data[1]]['status']=$data['2'];
                $ret[$data[1]]['rx']=$data['3'];
                $ret[$data[1]]['tx']=$data['4'];
            }
        }
    }
    return $ret;
}
?>