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
function save_diversity($diversity_configuration,$interface,$writepath='')
{
    global $section;
    global $plugin;
    global $base_plugin;

    if($writepath=='')
    {
        $writepath=$base_plugin.'data/diversity.sh';
    }
    
    $diversity=parse_diversity();

    $fp=fopen($writepath,'w');

    

    if($interface=='ath0')
    {
        if($diversity_configuration['wifi0_manual']=='on')
        {
            fwrite($fp,"/usr/bin/cambia_diversidad.sh 0 0 ".$diversity_configuration['wifi0_0']." ".$diversity_configuration['wifi0_1']."\n");
        }
    }
    else
    {
        if(!empty($diversity[0]))
        {
            fwrite($fp,"/usr/bin/cambia_diversidad.sh 0 0 ".$diversity[0]['rx']." ".$diversity[0]['tx']."\n");
        }
    }

    
    if($interface=='ath1')
    {
        if($diversity_configuration['wifi1_manual']=='on')
        {
            fwrite($fp,"/usr/bin/cambia_diversidad.sh 1 0 ".$diversity_configuration['wifi1_0']." ".$diversity_configuration['wifi1_1']."\n");
        }
    }
    else
    {
        if(!empty($diversity[1]))
        {
            fwrite($fp,"/usr/bin/cambia_diversidad.sh 1 0 ".$diversity[1]['rx']." ".$diversity[1]['tx']."\n");
        }
    }
    
    fclose($fp);
}
?>