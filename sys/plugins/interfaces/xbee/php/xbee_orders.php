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
function get_xbee($port='S0',$guess_speed='3')
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

    if (empty ($port))
    {
        $port='S0';
    }
    if (empty ($guess_speed))
    {
        $guess_speed='3';
    }

    $done=false;
    $xbee_configuration=Array();
    for($speed=$guess_speed;$speed<8;$speed++)
    {
        if (!$done)
        {
            //echo $base_plugin."bin/get_xbee $port $speed 2>&1".'<br>';
            exec('sudo '.$base_plugin."bin/get_xbee $port $speed 2>&1",$ret);
            if (count($ret)>2)
            {
                $done=true;
                $xbee_configuration['port']=$port;
                foreach($ret as $line)
                {
                    if(($line[0]=='a')&&($line[1]=='t'))
                    {
                        $data=explode(":", $line);
                        $xbee_configuration[$data[0]]=$data[1];
                        unset ($data);
                    }
                }
            }
            //echo "<pre>".print_r($ret,true)."</pre>";
            unset($ret);
            sleep(1);
        }
    }
    if (!$done)
    {
        for($speed='0';$speed<$guess_speed;$speed++)
        {
            if (!$done)
            {
                //echo $base_plugin."bin/get_xbee $port $speed 2>&1".'<br>';
                exec('sudo '.$base_plugin."bin/get_xbee $port $speed 2>&1",$ret);
                if (count($ret)>2)
                {
                    $done=true;
                    $xbee_configuration['port']=$port;
                    foreach($ret as $line)
                    {
                        if(($line[0]=='a')&&($line[1]=='t'))
                        {
                            $data=explode(":", $line);
                            $xbee_configuration[$data[0]]=$data[1];
                            unset ($data);
                        }
                    }
                }
                //echo "<pre>".print_r($ret,true)."</pre>";
                unset($ret);
                sleep(1);
            }
        }
    }
    return $xbee_configuration;
}
//$base_plugin='/home/octavio/www/manager-system-2.0/plugins/interfaces/zigbee/';
//echo "<pre>".print_r(get_xbee($kk,$kk),true)."</pre>";
function set_xbee($values)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;
    
    $allowedports = array("S0", "S1", "USB0","USB1");
    if(in_array($values['port'], $allowedports))
    {
        if (is_numeric($values['atbd'])&&('0'<=$values['atbd'])&&($values['atbd']<='7'))
        {
            $save_orders='sudo '.$base_plugin.'bin/exec_xbee '.$values['port'].' '.$values['old_speed'];
            $fp=fopen($base_plugin.'data/xbee.conf','w');
            fwrite($fp,"<?php\n");
            fwrite($fp,'$configuration["port"]="'.$values['port']."\";\n");
            foreach ($values as $key=>$value)
            {
                if (($key[0]=='a')&&($key[1]=='t'))
                {
                    fwrite($fp,'$configuration["'.$key.'"]="'.$value."\";\n");
                    $save_orders.=' "'.$key.$value.'"';
                }
            }
            fwrite($fp,"?>");
            fclose($fp);
            $save_orders.=' "atwr" 2>&1';
            exec($save_orders,$ret);
            //return $save_orders;
            foreach($ret as $linea)
            {
                $response.=$linea."<br/>";
            }
            //return $save_orders.'<br /><fieldset>'.$response.'</fieldset>';
            return '<br /><fieldset>'.$response.'</fieldset>';
        }
    }
}
function exec_xbee($values)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

    $allowedports = array("S0", "S1", "USB0","USB1");
    if(in_array($values['port2'], $allowedports))
    {
        if (is_numeric($values['speed'])&&('0'<=$values['speed'])&&($values['speed']<='7'))
        {
            $save_orders='sudo '.$base_plugin.'bin/exec_xbee '.$values['port2'].' '.$values['speed'];
            foreach ($values as $key=>$value)
            {
                if (($key[0]=='o')&&($key[1]=='w')&&($key[2]=='n'))
                {
                    $save_orders.=' "'.$value.'"';
                }
            }
            $save_orders.=' 2>&1';
            exec($save_orders,$ret);
            foreach($ret as $linea)
            {
                $response.=$linea."<br/>";
            }
            //return $save_orders.'<br /><fieldset>'.$response.'</fieldset>';
            return '<br /><fieldset>'.$response.'</fieldset>';
        }
    }
}
?>