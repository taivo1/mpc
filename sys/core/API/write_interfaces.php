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
function write_interfaces ( $filepath,$input,$writepath='')
{
    global $base_plugin;

    if ($writepath=='')
    {
        $writepath=$base_plugin.'data/temp_interfaces';
    }
    $fp=fopen($writepath,"w");
    // for each interface in the list we will search for data and rules and will write them
    // to file given in $filepath    
    $input['listainterfaces']=trim($input[listainterfaces]);        
    $temp=explode(' ',$input[listainterfaces]);
    foreach($temp as $interfaz){    	
        fwrite($fp,"auto ".$interfaz."\n");
        if ($input[$interfaz]['allow']){
            fwrite($fp, "\tallow-hotplug ".$interfaz."\n");
        }    
        if ($input[$interfaz]['iface']){
            fwrite($fp, "\tiface ".$interfaz." inet ".$input[$interfaz][iface]."\n");            
        }            
        if ($input[$interfaz]['address'])
        {
            fwrite($fp, "\taddress ".$input[$interfaz][address]."\n");
        }    
        if ($input[$interfaz]['netmask']){
            fwrite($fp, "\tnetmask ".$input[$interfaz][netmask]."\n");
        }    
        if ($input[$interfaz]['gateway']){
            fwrite($fp, "\tgateway ".$input[$interfaz][gateway]."\n");
        }    
        if ($input[$interfaz]['dns_primario']){
            fwrite($fp, "\tdns-nameservers ".$input[$interfaz][dns_primario]);
            if ($input[$interfaz]['dns_secundario']){
                fwrite($fp, " ".$input[$interfaz][dns_secundario]);
            }
            fwrite($fp, "\n");
        }
        if ($input[$interfaz]['broadcast']){
            fwrite($fp, "\tbroadcast ".$input[$interfaz][broadcast]."\n");
        }
        //MAC ADDRESS
        if (isset($input[$interfaz]['hw-address']))
        {
        	fwrite($fp, "\thwaddress ether ".$input[$interfaz]['hw-address']."\n");                
        }        
        // FIRST WE MANAGE THE ATTRIBUTES WE MAY HAVE CHANGED IN WEB INTERFACE.
        // wlanconfig
        if (isset($input[$interfaz]['pre-up']['wlanconfig']))
        {
        	fwrite($fp, "\tpre-up wlanconfig ".$interfaz." ".$input[$interfaz]['pre-up']['wlanconfig']['type']);
                fwrite($fp, " wlandev ".$input[$interfaz]['pre-up']['wlanconfig']['BaseDevice']);
                fwrite($fp, " wlanmode ".$input[$interfaz]['pre-up']['wlanconfig']['mode']."\n");
        }        	
        // ESSID
        if (isset($input[$interfaz]['post-up']['iwconfig']['essid']))
        {
        	fwrite($fp, "\tpost-up iwconfig ".$interfaz." essid ".$input[$interfaz]['post-up']['iwconfig']['essid']."\n");                
        }
        //HIDE IS IN UP.
        if (isset($input[$interfaz]['up']['iwpriv']['hide_ssid']))
        {
        	fwrite($fp, "\tup iwpriv ".$interfaz." hide_ssid ".$input[$interfaz]['up']['iwpriv']['hide_ssid']."\n");                
        }
        
        // ESSID MAC ADDRESS
        if (isset($input[$interfaz]['post-up']['iwconfig']['ap']))
        {
        	fwrite($fp, "\tpost-up iwconfig ".$interfaz." ap ".$input[$interfaz]['post-up']['iwconfig']['ap']."\n");                
        }
        //MODE
        if (isset($input[$interfaz]['post-up']['iwconfig']['mode']))
        {
        	fwrite($fp, "\tpost-up iwconfig ".$interfaz." mode ".$input[$interfaz]['post-up']['iwconfig']['mode']."\n");                
        }
        // FRECUENCY
        // Fecuency depends on the channel we use. So we may know the frecuency by the channel value.
        // CHANNEL
        if (isset($input[$interfaz]['post-up']['iwconfig']['channel']))
        {
        	fwrite($fp, "\tpost-up iwconfig ".$interfaz." channel ".$input[$interfaz]['post-up']['iwconfig']['channel']."\n");                
        }
        // MODE
    	if (isset($input[$interfaz]['up']['iwpriv']['mode']))
        {
        	if ($input[$interfaz]['up']['iwpriv']['mode']=='3')
        	{
        		fwrite($fp, "\tup iwpriv ".$interfaz." mode 11a\n");
        	}
        	elseif ($input[$interfaz]['up']['iwpriv']['mode']=='1')
        	{
        		fwrite($fp, "\tup iwpriv ".$interfaz." mode 11b\n");
        	}
        	else
        	{
        		fwrite($fp, "\tup iwpriv ".$interfaz." mode 11g\n");
        	}        	                
        }
        
        //TX POWER
        if (isset($input[$interfaz]['post-up']['iwconfig']['txpower']))
        {
        	fwrite($fp, "\tpost-up iwconfig ".$interfaz." txpower ".$input[$interfaz]['post-up']['iwconfig']['txpower']."\n");                
        }
        
        // FRAGMENTATION
    	if (isset($input[$interfaz]['post-up']['iwconfig']['frag']))
        {
        	fwrite($fp, "\tpost-up iwconfig ".$interfaz." frag ".$input[$interfaz]['post-up']['iwconfig']['frag']."\n");                
        }
        
        //RATE
        if (isset($input[$interfaz]['post-up']['iwconfig']['rate']))
        {
        	fwrite($fp, "\tpost-up iwconfig ".$interfaz." rate ".$input[$interfaz]['post-up']['iwconfig']['rate']."\n");                
        }
        // NOW WE HAVE TO COPY ALL THE LINES WE HAVE IN INTERFACES TO KEEP USER MODIFICATIONS
        if ($input[$interfaz]['pre-up']){	
            for($vuelta=1;$vuelta<=$input[$interfaz]['pre-up']['num'];$vuelta++){
            	fwrite($fp, "\t".$input[$interfaz]['pre-up'][$vuelta]."\n");
            }
        }    
        if ($input[$interfaz]['up']){	
            for($vuelta=1;$vuelta<=$input[$interfaz]['up']['num'];$vuelta++){
            	fwrite($fp, "\t".$input[$interfaz]['up'][$vuelta]."\n");
            }
        }    
        if ($input[$interfaz]['post-up']){	
            for($vuelta=1;$vuelta<=$input[$interfaz]['post-up']['num'];$vuelta++){
            	fwrite($fp, "\t".$input[$interfaz]['post-up'][$vuelta]."\n");
            }
        }
    	if ($input[$interfaz]['pre-down']){	
            for($vuelta=1;$vuelta<=$input[$interfaz]['pre-down']['num'];$vuelta++){
            	fwrite($fp, "\t".$input[$interfaz]['pre-down'][$vuelta]."\n");
            }
        }    
        if ($input[$interfaz]['down']){	
            for($vuelta=1;$vuelta<=$input[$interfaz]['down']['num'];$vuelta++){
            	fwrite($fp, "\t".$input[$interfaz]['down'][$vuelta]."\n");
            }
        }    
        if ($input[$interfaz]['post-down']){	
            for($vuelta=1;$vuelta<=$input[$interfaz]['post-down']['num'];$vuelta++){
            	fwrite($fp, "\t".$input[$interfaz]['post-down'][$vuelta]."\n");
            }
        }
    	if ($input[$interfaz]['num']){
            for($vuelta=1;$vuelta<=$input[$interfaz]['num'];$vuelta++){
            	fwrite($fp, "\t".$input[$interfaz][$vuelta]."\n");
            }
        }
                
        // START SECURITY
        // AUTHMODE IS DEFINED
    	if (isset($input[$interfaz]['up']['iwpriv']['authmode']))
        {
        	fwrite($fp, "\tup iwpriv ".$interfaz." authmode ".$input[$interfaz]['up']['iwpriv']['authmode']."\n");                
        }
    	if (isset($input[$interfaz]['post-up']['iwconfig']['key']))
        {
        	fwrite($fp, "\tpost-up iwconfig ".$interfaz." key s:".$input[$interfaz]['post-up']['iwconfig']['key']."\n");                
        }
    	if (isset($input[$interfaz]['post-up']['iwconfig']['enc']))
        {
        	fwrite($fp, "\tpost-up iwconfig ".$interfaz." enc s:".$input[$interfaz]['post-up']['iwconfig']['enc']."\n");                
        }
        if ($input[$interfaz]['post-up']['iwconfig']['mode']=='managed')
        {
	    	if (isset($input[$interfaz]['post-up']['wpa_supplicant']))
	        {
	        	fwrite($fp, "\tpost-up /sbin/wpa_supplicant ".$input[$interfaz]['post-up']['wpa_supplicant']."\n");
	        }
        }
        elseif ($input[$interfaz]['post-up']['iwconfig']['mode']=='master')
        {
        	if (isset($input[$interfaz]['post-up']['hostapd']))
	        {
        		fwrite($fp, "\tpost-up /usr/sbin/hostapd ".$input[$interfaz]['post-up']['hostapd']."\n");
	        }
        }
        // END SECURITY
        fwrite($fp,"\n");
        }
                
    fclose($fp);
    exec('sudo cp '.$writepath.' '.$filepath);
}    
?>