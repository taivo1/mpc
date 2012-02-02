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

function save_long_range_link($data, $interface)
{
    global $section;
    global $plugin;
    global $base_plugin;
    
    $saved_data=parse_long_range_link();
    //response_additem("append", '<pre>'.print_r($saved_data,true).'</pre>','interface');
    if($data['permanent_changes']=='on')
    {
        $saved_data[$interface]['permanent_changes']='1';
        $saved_data[$interface]['input_method']=$data['input_method'];
        $saved_data[$interface]['distance_value']=$data['distance_value'];
        $saved_data[$interface]['acktimeout']=$data['acktimeout'];
        $saved_data[$interface]['ctstimeout']=$data['ctstimeout'];
        $saved_data[$interface]['slottime']=$data['slottime'];
    }
    else
    {
        unset($saved_data[$interface]);
    }

    //response_additem("append", '<pre>'.print_r($saved_data,true).'</pre>','interface');
    save_ini_file($saved_data);
    
    return $saved_data;
}
function save_files($data,$interface)
{

    global $section;
    global $plugin;
    global $base_plugin;

    $writepath=$base_plugin.'data/long_range_link.sh';
   
    $fp=fopen($writepath,'w');
    // Header
    fwrite($fp,"#/bin/bash\n");
    $long_range_data=save_long_range_link($data,$interface);
    if(($interface=='ath0')&&($data['permanent_changes']=='on'))
    {        
        if($data['input_method']=='Auto')
        {
            fwrite($fp,"#Ath0 distance provided by user.\n");
            $dist=$data['distance_value']*1000;
            // Set distance
            fwrite($fp,'athctrl -i wifi0 -d '.$dist."\n");
            // Modify slottime for better performance on point-multipoint.
            // Read acktiemout
            fwrite($fp,'acktimeout=`cat /proc/sys/dev/wifi0/acktimeout`'."\n");
            // Set the acktimeout value to slottime
            fwrite($fp,'echo $acktimeout >/proc/sys/dev/wifi0/slottime'."\n");
            unset($dist);
        }
        else
        {
            fwrite($fp,"#Ath0 manual values provided by user.\n");
            // Set values for acktimeout
            fwrite($fp,'sysctl -w dev.wifi0.acktimeout='.$data['acktimeout']."\n");
            // Set values for ctstimeout
            fwrite($fp,'sysctl -w dev.wifi0.ctstimeout='.$data['ctstimeout']."\n");
            // Set values for slotime
            fwrite($fp,'sysctl -w dev.wifi0.slotime='.$data['slottime']."\n");
        }
    }
    elseif ($long_range_data['ath0']['permanent_changes']=='1')
    {
        // We have to use $long_range_data to include old values on the script.
        if($long_range_data['ath0']['input_method']=='Auto')
        {
            fwrite($fp,"#Ath0 distance provided by user.\n");
            $dist=$long_range_data['ath0']['distance_value']*1000;
            // Set distance
            fwrite($fp,'athctrl -i wifi0 -d '.$dist."\n");
            // Modify slottime for better performance on point-multipoint.
            // Read acktiemout
            fwrite($fp,'acktimeout=`cat /proc/sys/dev/wifi0/acktimeout`'."\n");
            // Set the acktimeout value to slottime
            fwrite($fp,'echo $acktimeout >/proc/sys/dev/wifi0/slottime'."\n");
            unset($dist);
        }
        else
        {
            fwrite($fp,"#Ath0 manual values provided by user.\n");
            // Set values for acktimeout
            fwrite($fp,'sysctl -w dev.wifi0.acktimeout='.$long_range_data['ath0']['acktimeout']."\n");
            // Set values for ctstimeout
            fwrite($fp,'sysctl -w dev.wifi0.ctstimeout='.$long_range_data['ath0']['ctstimeout']."\n");
            // Set values for slotime
            fwrite($fp,'sysctl -w dev.wifi0.slotime='.$long_range_data['ath0']['slottime']."\n");
        }
    }
    if(($interface=='ath1')&&($data['permanent_changes']=='on'))
    {
        if($data['input_method']=='Auto')
        {
            fwrite($fp,"#Ath1 distance provided by user.\n");
            $dist=$data['distance_value']*1000;
            // Set distance
            fwrite($fp,'athctrl -i wifi1 -d '.$dist."\n");
            // Modify slottime for better performance on point-multipoint.
            // Read acktiemout
            fwrite($fp,'acktimeout=`cat /proc/sys/dev/wifi1/acktimeout`'."\n");
            // Set the acktimeout value to slottime
            fwrite($fp,'echo $acktimeout >/proc/sys/dev/wifi1/slottime'."\n");
            unset($dist);
        }
        else
        {
            fwrite($fp,"#Ath1 manual values provided by user.\n");
            // Set values for acktimeout
            fwrite($fp,'sysctl -w dev.wifi1.acktimeout='.$data['acktimeout']."\n");
            // Set values for ctstimeout
            fwrite($fp,'sysctl -w dev.wifi1.ctstimeout='.$data['ctstimeout']."\n");
            // Set values for slotime
            fwrite($fp,'sysctl -w dev.wifi1.slotime='.$data['slottime']."\n");
        }
    }
    elseif ($long_range_data['ath1']['permanent_changes']=='1')
    {
        // We have to use $long_range_data to include old values on the script.
        if($long_range_data['ath1']['input_method']=='Auto')
        {
            fwrite($fp,"#ath1 distance provided by user.\n");
            $dist=$long_range_data['ath1']['distance_value']*1000;
            // Set distance
            fwrite($fp,'athctrl -i wifi1 -d '.$dist."\n");
            // Modify slottime for better performance on point-multipoint.
            // Read acktiemout
            fwrite($fp,'acktimeout=`cat /proc/sys/dev/wifi1/acktimeout`'."\n");
            // Set the acktimeout value to slottime
            fwrite($fp,'echo $acktimeout >/proc/sys/dev/wifi1/slottime'."\n");
            unset($dist);
        }
        else
        {
            fwrite($fp,"#ath1 manual values provided by user.\n");
            // Set values for acktimeout
            fwrite($fp,'sysctl -w dev.wifi1.acktimeout='.$long_range_data['ath1']['acktimeout']."\n");
            // Set values for ctstimeout
            fwrite($fp,'sysctl -w dev.wifi1.ctstimeout='.$long_range_data['ath1']['ctstimeout']."\n");
            // Set values for slotime
            fwrite($fp,'sysctl -w dev.wifi1.slotime='.$long_range_data['ath1']['slottime']."\n");
        }
    }

    // Move long_range_link.sh to /etc/init.d to make changes applied on each boot.
}

function save_data($data, $interface)
{
    global $section;
    global $plugin;
    global $base_plugin;
    /*
     * Ah, ok, me había despistado lo de las "zonas fresnel". En efecto, para adaptar
     *  la MAC a la distancia hay que modificar una cosa que es el tiempo de propagación
     *  estimado. Como eso no es un parámetro en sí mismo, lo que podemos modificar
     *  son los tres parámetros principales que dependen del tiempo de propagación:
     *  SlotTime, ACKTimeout y CTSTimeout.
     *  athctrl modifica los tres, pero el ajuste que hace para el SlotTime es bueno sólo
     *  para enlaces punto a punto. Para punto a multipunto es preferible seguir
     *  una regla sencilla: SlotTime = 2 x Tiempo_propagacion_máximo + SlotTime_std,
     *  donde el Tiempo_propagacion_máximo es el tiempo que tarda la señal en llegar
     *  (a la velocidad de la luz) entre el punto de acceso y la estación más alejada,
     *  y el SloTime_std son 9us en 11a/g y 20us en 11b.

     *  Puestos a hacer una chapucilla sencilla, para punto a punto usad athctrl, y para
     *  punto a multipunto (puede ser una opción en el configurador) leed el valor
     *  de ACKTimeout después de que athctrl lo ha modificado, y escribid ese mismo
     *  valor en SlotTime.

     *  El contenido de esas variables está en /proc/sys/dev/wifi0/acktimeout,
     *  /proc/sys/dev/wifi0/ctstimeout y /proc/sys/dev/wifi0/slottime, y se puede
     *  ver con 'cat' y escribir con 'echo <valor> > /proc/sys/dev/wifi0/slottime' o similar.
     */
    // Save new configuration.
    save_files($data,$interface);
    // Apply new configuration
    exec('sudo /etc/init.d/long_range_link.sh');
   
}
?>