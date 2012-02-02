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
function save_data($data, $interface)
{
    if($interface=='ath0')
    {
        if($data['use_formula_ath0']=='yes')
        {
            $dist=$data['fresnel_distance_ath0']*1000;
            // Set distance
            exec('sudo athctrl -i wifi0 -d '.$dist);
            unset($dist);
        }
        else
        {
            // Set values for acktimeout
            exec('sudo sysctl -w dev.wifi0.acktimeout='.$data['acktimeout_ath0']);
            // Set values for ctstimeout
            exec('sudo sysctl -w dev.wifi0.ctstimeout='.$data['ctstimeout_ath0']);
            // Set values for slotime
            exec('sudo sysctl -w dev.wifi0.slotime='.$data['slottime_ath0']);
        }
    }
    else
    {
        if($data['use_formula_ath1']=='yes')
        {
            $dist=$data['fresnel_distance_ath1']*1000;
            // Set distance
            exec('sudo athctrl -i wifi1 -d '.$dist);
            unset($dist);
        }
        else
        {
            // Set values for acktimeout
            exec('sudo sysctl -w dev.wifi1.acktimeout='.$data['acktimeout_ath1']);
            // Set values for ctstimeout
            exec('sudo sysctl -w dev.wifi1.ctstimeout='.$data['ctstimeout_ath1']);
            // Set values for slotime
            exec('sudo sysctl -w dev.wifi1.slotime='.$data['slottime_ath1']);
        }
    }
}
?>
