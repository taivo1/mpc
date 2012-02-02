<?php
/*
 * Copyright (C) 2008 Libelium Comunicaciones Distribuidas S.L.
 *
 * This file is part of Meshlium Manager System.
 * Meshlium Manager System will be released as free software; until then you cannot redistribute it
 * without express permission by libelium. 
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * 
 *
 * Version 0.1.0 
 *  Author: Octavio Benedi Sanchez
 */
function save_gprs($post,$gprs_usb='ttyUSB0',$write_path='')
{
    global $base_plugin;

    if ($write_path=='')
    {
        $write_path=$base_plugin.'data/wvdial.conf';
    }
	
	$fp=fopen($write_path,"w");
	
    // first we must put our comment to know this is a known operator.
    if ($post['country_operators']!='other')
    {
        fwrite($fp,"#operator ".$post['country_operators']."\n");
    }
    if ($post['country_list']!='other')
    {
        fwrite($fp,"#country ".$post['country_list']."\n");
    }
    fwrite($fp,"[Dialer Defaults]\n");
    // This should be changed with an autodetect option.
    fwrite($fp,"Modem = /dev/".$gprs_usb."\n");
    //fwrite($fp,"Modem Name = HILO\n");
    fwrite($fp,"Modem Type = Analog Modem\n");
    fwrite($fp,"Baud = 115200\n");
    fwrite($fp,"ISDN = 0\n");
    fwrite($fp,"Stupid mode = 1\n");
    // Don't close connection due to inactivity.
    fwrite($fp,"Idle Seconds = 0\n");
    //Here goes the options that contains the form values.
    if ($post['PIN'])
    {
    fwrite($fp,"Init1 = AT+CPIN=\"".$post['PIN']."\"\n");
    }
    $post['init1']=str_replace("AT CGDCONT","AT+CGDCONT",$post['init1']);
    fwrite($fp,"Init2 = ATZ\n");
    fwrite($fp,"Init4 = ".$post['init1']."\n");

    if(empty($post['password']))
    {
        $post['password']='" "';
    }

    fwrite($fp,"Password = ".$post['password']."\n");
    fwrite($fp,"Phone = ".$post['phone']."\n");

    if(empty($post['username']))
    {
        $post['username']='" "';
    }

    fwrite($fp,"Username = ".$post['username']."\n");
    fwrite($fp,"Dial Command = ".strtoupper($post['dial'])."\n");

    // Let's close the file descriptor.
    fclose($fp);
	// To avoid direct access of www-data to system files, we will indirect the access.
	// To do that we first write the new interfaces in a temp file and call with sudo a script that
	// will move the data to the correct place. Doing so will slow action but secure it a bit.
	// Now we call the script that will move $write_path to $read_path.
    
}

if(file_exists($filename))
if($_SERVER['QUERY_STRING'])
?>