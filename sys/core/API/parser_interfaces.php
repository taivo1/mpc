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
// This file just implements a parser for meshlium /etc/network/interfaces file
function parse_interfaces ( $filepath ) {
	// In the ini var we put the file
    $ini = file( $filepath );
    // Check for an empty file && if empty just leave.
    if ( count( $ini ) == 0 ) { return array(); echo "vacio";}
	//Make an array with the values in /etc/network/interfaces
    $interfaces = array();
    $configInterfaces = array();
    $configInterfaces[num]=0;
    $last_interface=none;
	// Start parser
	// for each line we are goin to parse the file
        
    foreach( $ini as $line )
    {
        // with trim just take out unwanted characters
        $line = trim( $line );
        // if first character is a # we know is a comment and pass through
		if ( $line == '' || $line{0} == '#' ) 
		{ 
			continue;
		}
        // We split a line in four slices because of interfaces configuration        
        $data= explode( ' ', $line,4);
        // If the line is not empty we are going to insert it in data structure.
        if($data[0]=='auto'){
            $last_interface=$data[1];
            // Update common data
            $configInterfaces[num]+=1;
            $configInterfaces[listainterfaces]=$configInterfaces[listainterfaces].$data[1]." ";            
            
        }
        elseif(($data[0]=='iface')&&($last_interface!='none')){
            // we will be able to remember if static or dhcp is chossen
            $configInterfaces[$last_interface]['iface']=$data[3];
        }
        elseif(($data[0]=='allow-hotplug')&&($last_interface!='none')){
            // allow-hotplug option
            $configInterfaces[$last_interface]['allow']='yes';
        }
        elseif(($data[0]=='address')&&($last_interface!='none')){
            // we save address data
            $configInterfaces[$last_interface]['address']=$data[1];
        }
        elseif(($data[0]=='netmask')&&($last_interface!='none')){
            // we save netmask data
            $configInterfaces[$last_interface]['netmask']=$data[1];
        }
        elseif(($data[0]=='gateway')&&($last_interface!='none')){
            // we save gateway data
            $configInterfaces[$last_interface]['gateway']=$data[1];
        }
        elseif(($data[0]=='dns-nameservers')&&($last_interface!='none')){
            // we save dns-nameservers data
            // only two nameservers are allowed right now.
            $configInterfaces[$last_interface]['dns_primario']=$data[1];
            $configInterfaces[$last_interface]['dns_secundario']=$data[2];
        }
        elseif(($data[0]=='broadcast')&&($last_interface!='none')){
            // we save broadcast data
            $configInterfaces[$last_interface]['broadcast']=$data[1];
        }
    	elseif(($data[0]=='hwaddress')&&($last_interface!='none')){
            // we save mac data
            $configInterfaces[$last_interface]['hw-address']=$data[2];
        }
        elseif((($data[0]=='pre-up')||($data[0]=='up')||($data[0]=='post-up')||($data[0]=='pre-down')||($data[0]=='down')||($data[0]=='post-down'))&&($last_interface!='none')){
            // we will manage pre-up rules here
            // $data[0] has the load rule
            // $data[1] has the name of the script
            // $data[2] and $data[3] has the options of the script
            // just need to save data[3] because data[2] is the name of the interface
            // and update pre-up number of rules avaible.                        
            if (($data[0]=='pre-up')&&($data[1]=='wlanconfig'))
            {
                $configInterfaces[$last_interface][$data[0]][$data[1]]['script']=$data[1];
                //$configInterfaces[$last_interface]['pre-up'][$data[1]]['interface']=$data[2]; // We allready know this value.
                $opciones=explode(' ',$data[3]);
                if ($opciones[0]=='create'){
                    $configInterfaces[$last_interface][$data[0]][$data[1]]['type']=$opciones[0];
                    $configInterfaces[$last_interface][$data[0]][$data[1]]['BaseDevice']=$opciones[2];
                    $configInterfaces[$last_interface][$data[0]][$data[1]]['mode']=$opciones[4];
                    if($opciones[5]){
                        $configInterfaces[$last_interface][$data[0]][$data[1]]['bssid']=$opciones[5];
                    }
                    if($opciones[6]){
                        $configInterfaces[$last_interface][$data[0]][$data[1]]['nosbeacon']=$opciones[6];
                    }
                }                
                else{
                	$configInterfaces[$last_interface][$data[0]]['num']+=1; // The first time it will have a 1 value. 
                	$aux=$configInterfaces[$last_interface][$data[0]]['num'];
                	$configInterfaces[$last_interface][$data[0]][$aux]=$data[0]." ".$data[1]." ".$data[2]." ".$data[3];
                }
                
            }
            elseif (($data[0]=='post-up')&&$data[1]=='iwconfig')
            {
            	$opciones=explode(' ',$data[3]);
                if (($opciones[0]=='essid')||($opciones[0]=='ap')||($opciones[0]=='mode')||($opciones[0]=='channel')||($opciones[0]=='txpower')||($opciones[0]=='rate')||($opciones[0]=='frag'))
                {
	            	// we just get a couple option -- value of iwconfig script.    	                            
        	        $configInterfaces[$last_interface][$data[0]][$data[1]][$opciones[0]]=$opciones[1];
                }
            	elseif ((($opciones[0]=='key')&&(($opciones[1]!='on')||($opciones[1]!='off')))||(($opciones[0]=='enc')&&(($opciones[1]!='on')||($opciones[1]!='off'))))
                {
	            	// we just get a couple option -- value of iwconfig script.    	                            
        	        $configInterfaces[$last_interface][$data[0]][$data[1]][$opciones[0]]=substr($opciones[1],2);
                }                
            	else
                {
                	$configInterfaces[$last_interface][$data[0]]['num']+=1; // The first time it will have a 1 value. 
                	$aux=$configInterfaces[$last_interface][$data[0]]['num'];
                	$configInterfaces[$last_interface][$data[0]][$aux]=$data[0]." ".$data[1]." ".$data[2]." ".$data[3];
                }

            }
            elseif (($data[0]=='up')&&($data[1]=='iwpriv'))
            {
                $opciones=explode(' ',$data[3],2);
                if ($opciones[0]=='hide_ssid')
                {                
                	$configInterfaces[$last_interface][$data[0]][$data[1]][$opciones[0]]=$opciones[1];
                }
                elseif ($opciones[0]=='authmode')
                {
                	$configInterfaces[$last_interface][$data[0]][$data[1]][$opciones[0]]=$opciones[1];
                }
                elseif($opciones[0]=='mode')
                {
                	if ($opciones[1][2]=='a')
                	{
                		$configInterfaces[$last_interface][$data[0]][$data[1]][$opciones[0]]=3;
                	}
                	elseif ($opciones[1][2]=='b')
                	{
						$configInterfaces[$last_interface][$data[0]][$data[1]][$opciones[0]]=1;
                	}
                	else
                	{
						$configInterfaces[$last_interface][$data[0]][$data[1]][$opciones[0]]=2; 
                	}
                	//$configInterfaces[$last_interface][$data[0]][$data[1]][$opciones[0]]=$opciones[1];
                	/*
                	 * * iwpriv ath0 mode 11a To lock to 11a only.
					   * iwpriv ath0 mode 11b To lock to 11b only.
					   * iwpriv ath0 mode 11g To lock to 11g only.
                	 */
                }
                else
                {
                	$configInterfaces[$last_interface][$data[0]]['num']+=1; // The first time it will have a 1 value. 
                	$aux=$configInterfaces[$last_interface][$data[0]]['num'];
                	$configInterfaces[$last_interface][$data[0]][$aux]=$data[0]." ".$data[1]." ".$data[2]." ".$data[3];
                }
            }
            elseif (($data[0]=='up')&&($data[1]=='interfaces_plus_'.$last_interface.'.sh'))
            {
            	$configInterfaces[$last_interface][$data[0]][$data[1]]='yes';
            }
        	elseif (($data[0]=='post-up')&&($data[1]=='/sbin/wpa_supplicant'))
            {
            	$configInterfaces[$last_interface][$data[0]][substr($data[1],-14)]=$data[2].' '.$data[3];
            }
        	elseif (($data[0]=='post-up')&&($data[1]=='/usr/sbin/hostapd'))
            {
            	$configInterfaces[$last_interface][$data[0]][substr($data[1],-7)]=$data[2].' '.$data[3];
            }
            else
            {
                $configInterfaces[$last_interface][$data[0]]['num']+=1; // The first time it will have a 1 value. 
                $aux=$configInterfaces[$last_interface][$data[0]]['num'];
                $configInterfaces[$last_interface][$data[0]][$aux]=$data[0]." ".$data[1]." ".$data[2]." ".$data[3];                
                continue;
            }
        }
        else
        {
	        $configInterfaces[$last_interface]['num']+=1; // The first time it will have a 1 value. 
	        $aux=$configInterfaces[$last_interface]['num'];
	        $configInterfaces[$last_interface][$aux]=$data[0]." ".$data[1]." ".$data[2]." ".$data[3];
        }
    }
    return $configInterfaces;
}
//echo "<pre>".print_r(parse_interfaces ("/etc/network/interfaces"),true)."</pre>";
?>