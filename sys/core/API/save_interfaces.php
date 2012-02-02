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
function saveInterfaces($interface,$post,$read_path,$write_path)
{
    global $API_core;
    global $base_plugin;

	include_once $API_core.'parser_interfaces.php';
    include_once $API_core.'write_interfaces.php';
    include_once $API_core.'save_dhcp_server.php';
    	
	$input=parse_interfaces($read_path); // HERE IS THE ACTUAL INTERFACES        
	
	// WE CHECK IF THE INTERFACE IS IN THE LIST, IF DON'T WE ADD IT.
	$interfaceslist=trim($input['listainterfaces']);
    $temp=explode(' ',$interfaceslist);
    $ok=0;
    foreach($temp as $temp2){
    	if ($interface==$temp2) {$ok=1;}   	
    }  
    if ($ok==0)
    {
    	$input['listainterfaces']=$input['listainterfaces'].$interface;
    	$input['num']=$input['num']+1;
    }
    

	// WE NEED TO UPDATE CHANGES MADE BY THE USER IN $INPUT SO WE SHOULD CALL WRITE_INTERFACES
	switch ($interface)
	{
		case 'eth0':
				if ($post['allow'])
				{
					$input[$interface]['allow']='yes';
				}
				else
				{
					unset($input[$interface]['allow']);
				}				
				if ($post['iface_sel']=='static')
				{
					$input[$interface]['iface']=$post['iface_sel'];				
					if ($post['address'])
					{ $input[$interface]['address']=$post['address']; }
					else
					{ unset($input[$interface]['address']); }					
					if ($post['netmask'])
					{ $input[$interface]['netmask']=$post['netmask']; }
					else
					{ unset($input[$interface]['netmask']); }					
					if ($post['gateway'])
					{ $input[$interface]['gateway']=$post['gateway']; }
					else
					{ unset($input[$interface]['gateway']); }					
					if ($post['DNS1'])
					{ $input[$interface]['dns_primario']=$post['DNS1']; }
					else
					{ unset($input[$interface]['dns_primario']); }					
					if ($post['DNS2'])
					{ $input[$interface]['dns_secundario']=$post['DNS2']; }
					else
					{ unset($input[$interface]['dns_secundario']); }					
					if ($post['broadcast'])
					{ $input[$interface]['broadcast']=$post['broadcast']; }
					else
					{ unset($input[$interface]['broadcast']); 		}
				}
				else
				{
					$input[$interface]['iface']='dhcp';
					unset($input[$interface]['address']);
					unset($input[$interface]['netmask']);
					unset($input[$interface]['gateway']);
					unset($input[$interface]['dns_primario']);
					unset($input[$interface]['dns_secundario']);
					unset($input[$interface]['broadcast']);
				}
			
			break;
		case 'ath0':
			// First we take all parameters, and later we unset the ones we don't need
			// instead of making a big if then else structure.
			
			// We will have to manage the mode of wlanconfig in function of the mode choosen.
			
			switch ($post['mode'])
			{
				case 'managed':
					$input[$interface]['pre-up']['wlanconfig']['type']='create';
					$input[$interface]['pre-up']['wlanconfig']['BaseDevice']='wifi0';
					$input[$interface]['pre-up']['wlanconfig']['mode']='sta';
					break;
				case 'ad-hoc':
					$input[$interface]['pre-up']['wlanconfig']['type']='create';
					$input[$interface]['pre-up']['wlanconfig']['BaseDevice']='wifi0';
					$input[$interface]['pre-up']['wlanconfig']['mode']='adhoc';					
					break;
				case 'master':
					if ($post['dhcp_server']=='on')
					{
						save_dhcp_server($interface, $post['dhcp_init'],$post['dhcp_end'],$post['dhcp_expire'],'save');
					}
					else
					{
						save_dhcp_server($interface, $post['dhcp_init'],$post['dhcp_end'],$post['dhcp_expire'],'delete');
					}
					$input[$interface]['pre-up']['wlanconfig']['type']='create';
					$input[$interface]['pre-up']['wlanconfig']['BaseDevice']='wifi0';
					$input[$interface]['pre-up']['wlanconfig']['mode']='ap';					
					break;
			}
			
			$input[$interface]['iface']=$post['iface_sel'];
			
			if ($post['essid'])
			{ $input[$interface]['post-up']['iwconfig']['essid']=$post['essid']; }
			else
			{ unset($input[$interface]['post-up']['iwconfig']['essid']);}
			
			if ($post['hide']=='on')
			{ $input[$interface]['up']['iwpriv']['hide_ssid']='1'; }
			else
			{ $input[$interface]['up']['iwpriv']['hide_ssid']='0'; }
			
			if ($post['mac_essid_i'])
				{ $input[$interface]['post-up']['iwconfig']['ap']=$post['mac_essid_i']; }
			else
			{ unset($input[$interface]['post-up']['iwconfig']['ap']);}
						
			// Select fields allways has a value in it.
			$input[$interface]['post-up']['iwconfig']['mode']=$post['mode'];
			
			
			if ($post['freq']=='2')
			{ 
				$input[$interface]['post-up']['iwconfig']['channel']=$post['channel2'];
				$input[$interface]['up']['iwpriv']['mode']=$post['mode-abg'];				
			}
			else
			{ 
				$input[$interface]['post-up']['iwconfig']['channel']=$post['channel5'];
				$input[$interface]['up']['iwpriv']['mode']='3';
			}					
			
			$input[$interface]['post-up']['iwconfig']['txpower']=$post['tx_power'];
			

			// FRAGMENTATION
			if (($post['frag']=='off')||(($post['frag']>'255')&&($post['frag']<'2347')))
			{ $input[$interface]['post-up']['iwconfig']['frag']=$post['frag']; }
			else
			{ unset($input[$interface]['post-up']['iwconfig']['frag']); }
			
			
			// SECURITY 
						
                        /*if($post['mode']=='managed')
                        {
                            switch ($post['protocol'])
				{
					case 'wep':
						$input[$interface]['up']['iwpriv']['authmode']='1';
                                                if(!empty($post['wep_pass'])&&($post['wep_pass']==$post['cnf_wep_pass']))
                                                {
                                                    $input[$interface]['post-up']['iwconfig']['key']=$post['wep_pass'];
						    //$input[$interface]['post-up']['iwconfig']['enc']=$post['wep_pass'];
                                                }
                                                // Borramos
						unset($input[$interface]['post-up']['wpa_supplicant']);
						unset($input[$interface]['post-up']['wpa-key']);
						unset($input[$interface]['post-up']['hostapd']);
						break;
                                            case 'wpa':
						$input[$interface]['post-up']['wpa_supplicant']='-Dmadwifi -iath0 -c/etc/wpa_supplicant.conf';						
						//$input[$interface]['post-up']['hostapd']='-B /etc/hostapd/hostapd.conf';
						
                                                if(!empty($post['psk_pass'])&&($post['psk_pass']==$post['cnf_psk_pass']))
                                                {
							$input[$interface]['post-up']['wpa-key']=$post['psk_pass'];
						}

						if ($post['hide'])
						{
							$input[$interface]['post-up']['wpa-hide']=$post['hide'];
						}
						else
						{
							unset($input[$interface]['post-up']['wpa-hide']);
						}
                                                unset($input[$interface]['post-up']['hostapd']);
						unset($input[$interface]['post-up']['iwconfig']['key']);
						unset($input[$interface]['post-up']['iwconfig']['enc']);
						unset($input[$interface]['up']['iwpriv']['authmode']);
						break;
					case 'none':
						unset($input[$interface]['up']['iwpriv']['authmode']);
						unset($input[$interface]['post-up']['iwconfig']['key']);
						unset($input[$interface]['post-up']['iwconfig']['enc']);
						unset($input[$interface]['post-up']['wpa_supplicant']);
						unset($input[$interface]['post-up']['wpa-key']);
						unset($input[$interface]['post-up']['hostapd']);
						break;		
                        }
                        else
			{
				unset($input[$interface]['up']['iwpriv']['authmode']);
				unset($input[$interface]['post-up']['iwconfig']['key']);
				unset($input[$interface]['post-up']['iwconfig']['enc']);
				unset($input[$interface]['post-up']['wpa_supplicant']);
				unset($input[$interface]['post-up']['wpa-key']);
				unset($input[$interface]['post-up']['hostapd']);
			}*/

			/*if ($post['security'])
			{
				switch ($post['security'])
				{
					case 'WEP40':
						$input[$interface]['up']['iwpriv']['authmode']='1';
						if ($post['passwd']){
							$input[$interface]['post-up']['iwconfig']['key']=$post['passwd'];
							$input[$interface]['post-up']['iwconfig']['enc']=$post['passwd'];
						}
						unset($input[$interface]['post-up']['wpa_supplicant']);
						unset($input[$interface]['post-up']['wpa-key']);
						unset($input[$interface]['post-up']['hostapd']);
						break;
					case 'WEP104':
						$input[$interface]['up']['iwpriv']['authmode']='1';
						if ($post['passwd']){
							$input[$interface]['post-up']['iwconfig']['key']=$post['passwd'];
							$input[$interface]['post-up']['iwconfig']['enc']=$post['passwd'];
						}
						unset($input[$interface]['post-up']['wpa_supplicant']);;
						unset($input[$interface]['post-up']['wpa-key']);
						unset($input[$interface]['post-up']['hostapd']);
						break;
					case 'WPA':						
						$input[$interface]['post-up']['wpa_supplicant']='-Dmadwifi -iath0 -c/etc/wpa_supplicant.conf';						
						$input[$interface]['post-up']['hostapd']='-B /etc/hostapd/hostapd.conf';
						if ($post['passwd'])
						{
							$input[$interface]['post-up']['wpa-key']=$post['passwd'];
						}
						if ($post['hide'])
						{
							$input[$interface]['post-up']['wpa-hide']=$post['hide'];
						}
						else
						{
							unset($input[$interface]['post-up']['wpa-hide']);
						}
						unset($input[$interface]['post-up']['iwconfig']['key']);
						unset($input[$interface]['post-up']['iwconfig']['enc']);
						unset($input[$interface]['up']['iwpriv']['authmode']);
						break;
					case 'none':
						unset($input[$interface]['up']['iwpriv']['authmode']);
						unset($input[$interface]['post-up']['iwconfig']['key']);
						unset($input[$interface]['post-up']['iwconfig']['enc']);
						unset($input[$interface]['post-up']['wpa_supplicant']);
						unset($input[$interface]['post-up']['wpa-key']);
						unset($input[$interface]['post-up']['hostapd']);
						break;						
				}
			}
			else
			{
				unset($input[$interface]['up']['iwpriv']['authmode']);
				unset($input[$interface]['post-up']['iwconfig']['key']);
				unset($input[$interface]['post-up']['iwconfig']['enc']);
				unset($input[$interface]['post-up']['wpa_supplicant']);
				unset($input[$interface]['post-up']['wpa-key']);
				unset($input[$interface]['post-up']['hostapd']);
			}*/
			
			if ($post['rate']=='auto')
			{ $input[$interface]['post-up']['iwconfig']['rate']=$post['rate'];}
			else
			{$input[$interface]['post-up']['iwconfig']['rate']=$post['rate_val'];}
			
			
			if ($post['address'])
			{ $input[$interface]['address']=$post['address']; }
			else
			{ unset($input[$interface]['address']); }					
			
			if ($post['netmask'])
			{ $input[$interface]['netmask']=$post['netmask']; }
			else
			{ unset($input[$interface]['netmask']); }					
			
			if ($post['gateway'])
			{ $input[$interface]['gateway']=$post['gateway']; }
			else
			{ unset($input[$interface]['gateway']); }					
			
			if ($post['DNS1'])
			{ $input[$interface]['dns_primario']=$post['DNS1']; }
			else
			{ unset($input[$interface]['dns_primario']); }					
			
			if ($post['DNS2'])
			{ $input[$interface]['dns_secundario']=$post['DNS2']; }
			else
			{ unset($input[$interface]['dns_secundario']); }					
			
			if ($post['broadcast'])
			{ $input[$interface]['broadcast']=$post['broadcast']; }
			else
			{ unset($input[$interface]['broadcast']); 		}
			
			if ($post['mac_filter_check'])
			{ 
				$input[$interface]['up']['interfaces_plus_ath0.sh']='yes';				 
			}
			else
			{ 
				unset($input[$interface]['up']['interfaces_plus_ath0.sh']);				
			}
			
			if ($post['mac'])
				{ $input[$interface]['hw-address']=$post['mac']; }
			else
			{ unset($input[$interface]['hw-address']);}
			
			// WE WILL UNSET UNNECESSARY OPTIONS
			
			if ($post['iface_sel']=='dhcp')
			{
				unset($input[$interface]['address']);
				unset($input[$interface]['netmask']);
				unset($input[$interface]['gateway']);
				unset($input[$interface]['dns_primario']);
				unset($input[$interface]['dns_secundario']);
				unset($input[$interface]['broadcast']);
				
			}
			
			switch ($post['mode'])
			{
				case 'managed':
					unset($input[$interface]['up']['interfaces_plus.sh']);
					unset($input[$interface]['post-up']['iwconfig']['enc']);
					break;
				case 'ad-hoc':
					unset($input[$interface]['up']['interfaces_plus.sh']);
					unset($input[$interface]['post-up']['iwconfig']['ap']);
					unset($input[$interface]['post-up']['iwconfig']['key']);
					unset($input[$interface]['post-up']['iwconfig']['enc']);
					break;
				case 'master':
					unset($input[$interface]['gateway']);
					unset($input[$interface]['post-up']['iwconfig']['ap']);
					unset($input[$interface]['post-up']['iwconfig']['key']);
					break;
			}
						
			if ($post['acktimeout'])
			{
				exec('sudo '.EXEC_PATH.'set_acktimeout.sh 0 '.$post['acktimeout']);
			}
			if ($post['ctstimeout'])
			{
				exec('sudo '.EXEC_PATH.'set_ctstimeout.sh 0 '.$post['ctstimeout']);
			}
			if ($post['slottime'])
			{
				exec('sudo '.EXEC_PATH.'set_slottime.sh 0 '.$post['slottime']);
			}
			//$temp_file=fopen(BASE_PATH.'/data/fresnel_0.conf',"w");
			//fwrite ($temp_file,$post['acktimeout']." ".$post['ctstimeout']." ".$post['slottime']."\n");
			//fclose($temp_file);
			
			break;
		case 'ath1':		 	
			
			// First we take all parameters, and later we unset the ones we don't need
			// instead of making a big if then else structure.
			
			// We will have to manage the mode of wlanconfig in function of the mode choosen.
			
			switch ($post['mode'])
			{
				case 'managed':
					$input[$interface]['pre-up']['wlanconfig']['type']='create';
					$input[$interface]['pre-up']['wlanconfig']['BaseDevice']='wifi1';
					$input[$interface]['pre-up']['wlanconfig']['mode']='sta';
					break;
				case 'ad-hoc':
					$input[$interface]['pre-up']['wlanconfig']['type']='create';
					$input[$interface]['pre-up']['wlanconfig']['BaseDevice']='wifi1';
					$input[$interface]['pre-up']['wlanconfig']['mode']='adhoc';					
					break;
				case 'master':
					$input[$interface]['pre-up']['wlanconfig']['type']='create';
					$input[$interface]['pre-up']['wlanconfig']['BaseDevice']='wifi1';
					$input[$interface]['pre-up']['wlanconfig']['mode']='ap';
					
					if ($post['dhcp_server']=='on')
					{
						save_dhcp_server($interface, $post['dhcp_init'],$post['dhcp_end'],$post['dhcp_expire'],'save');
					}
					else
					{
						save_dhcp_server($interface, $post['dhcp_init'],$post['dhcp_end'],$post['dhcp_expire'],'delete');
					}
					
					break;
			}
			
			$input[$interface]['iface']=$post['iface_sel'];
			
			if ($post['essid'])
			{ $input[$interface]['post-up']['iwconfig']['essid']=$post['essid']; }
			else
			{ unset($input[$interface]['post-up']['iwconfig']['essid']);}
			
			if ($post['hide']=='yes')
			{ $input[$interface]['up']['iwpriv']['hide_ssid']='1'; }
			else
			{ $input[$interface]['up']['iwpriv']['hide_ssid']='0'; }
			
			if ($post['mac_essid_i'])
				{ $input[$interface]['post-up']['iwconfig']['ap']=$post['mac_essid_i']; }
			else
			{ unset($input[$interface]['post-up']['iwconfig']['ap']);}
						
			// Select fields allways has a value in it.
			$input[$interface]['post-up']['iwconfig']['mode']=$post['mode'];
			
			
			if ($post['freq']=='2')
			{ 
				$input[$interface]['post-up']['iwconfig']['channel']=$post['channel2'];
				$input[$interface]['up']['iwpriv']['mode']=$post['mode-abg'];				
			}
			else
			{ 
				$input[$interface]['post-up']['iwconfig']['channel']=$post['channel5'];
				$input[$interface]['up']['iwpriv']['mode']='3';
			}
						
			
			$input[$interface]['post-up']['iwconfig']['txpower']=$post['tx_power'];
			

			// FRAGMENTATION
			if (($post['frag']=='off')||(($post['frag']>'255')&&($post['frag']<'2347')))
			{ $input[$interface]['post-up']['iwconfig']['frag']=$post['frag']; }
			else
			{ unset($input[$interface]['post-up']['iwconfig']['frag']); }
			
				
	// SECURITY 
						
			/*if ($post['security'])
			{
				switch ($post['security'])
				{
					case 'WEP40':
						$input[$interface]['up']['iwpriv']['authmode']='1';
						if ($post['passwd']){
							$input[$interface]['post-up']['iwconfig']['key']=$post['passwd'];
							$input[$interface]['post-up']['iwconfig']['enc']=$post['passwd'];
						}
						unset($input[$interface]['post-up']['wpa_supplicant']);
						unset($input[$interface]['post-up']['wpa-key']);
						unset($input[$interface]['post-up']['hostapd']);
						break;
					case 'WEP104':
						$input[$interface]['up']['iwpriv']['authmode']='1';
						if ($post['passwd']){
							$input[$interface]['post-up']['iwconfig']['key']=$post['passwd'];
							$input[$interface]['post-up']['iwconfig']['enc']=$post['passwd'];
						}
						unset($input[$interface]['post-up']['wpa_supplicant']);;
						unset($input[$interface]['post-up']['wpa-key']);
						unset($input[$interface]['post-up']['hostapd']);
						break;
					case 'WPA':						
						$input[$interface]['post-up']['wpa_supplicant']='-Dmadwifi -iath0 -c/etc/wpa_supplicant.conf';						
						$input[$interface]['post-up']['hostapd']='-B /etc/hostapd/hostapd.conf';
						if ($post['passwd'])
						{
							$input[$interface]['post-up']['wpa-key']=$post['passwd'];
						}
						if ($post['hide'])
						{
							$input[$interface]['post-up']['wpa-hide']=$post['hide'];
						}
						else
						{
							unset($input[$interface]['post-up']['wpa-hide']);
						}
						unset($input[$interface]['post-up']['iwconfig']['key']);
						unset($input[$interface]['post-up']['iwconfig']['enc']);
						unset($input[$interface]['up']['iwpriv']['authmode']);
						break;
					case 'none':
						unset($input[$interface]['up']['iwpriv']['authmode']);
						unset($input[$interface]['post-up']['iwconfig']['key']);
						unset($input[$interface]['post-up']['iwconfig']['enc']);
						unset($input[$interface]['post-up']['wpa_supplicant']);
						unset($input[$interface]['post-up']['wpa-key']);
						unset($input[$interface]['post-up']['hostapd']);
						break;						
				}
			}
			else
			{
				unset($input[$interface]['up']['iwpriv']['authmode']);
				unset($input[$interface]['post-up']['iwconfig']['key']);
				unset($input[$interface]['post-up']['iwconfig']['enc']);
				unset($input[$interface]['post-up']['wpa_supplicant']);
				unset($input[$interface]['post-up']['wpa-key']);
				unset($input[$interface]['post-up']['hostapd']);
			}*/
			
			if ($post['rate']=='auto')
			{ $input[$interface]['post-up']['iwconfig']['rate']=$post['rate'];}
			else
			{$input[$interface]['post-up']['iwconfig']['rate']=$post['rate_val'];}
			
			
			if ($post['address'])
			{ $input[$interface]['address']=$post['address']; }
			else
			{ unset($input[$interface]['address']); }					
			
			if ($post['netmask'])
			{ $input[$interface]['netmask']=$post['netmask']; }
			else
			{ unset($input[$interface]['netmask']); }					
			
			if ($post['gateway'])
			{ $input[$interface]['gateway']=$post['gateway']; }
			else
			{ unset($input[$interface]['gateway']); }					
			
			if ($post['DNS1'])
			{ $input[$interface]['dns_primario']=$post['DNS1']; }
			else
			{ unset($input[$interface]['dns_primario']); }					
			
			if ($post['DNS2'])
			{ $input[$interface]['dns_secundario']=$post['DNS2']; }
			else
			{ unset($input[$interface]['dns_secundario']); }					
			
			if ($post['broadcast'])
			{ $input[$interface]['broadcast']=$post['broadcast']; }
			else
			{ unset($input[$interface]['broadcast']); 		}
			
			if ($post['mac_filter_check'])
			{ $input[$interface]['up']['interfaces_plus_ath1.sh']='yes'; }
			else
			{ unset($input[$interface]['up']['interfaces_plus_ath1.sh']);}
			
			if ($post['mac'])
				{ $input[$interface]['hw-address']=$post['mac']; }
			else
			{ unset($input[$interface]['hw-address']);}
			
			
			// WE WILL UNSET UNNECESSARY OPTIONS
			
			if ($post['iface_sel']=='dhcp')
			{
				unset($input[$interface]['address']);
				unset($input[$interface]['netmask']);
				unset($input[$interface]['gateway']);
				unset($input[$interface]['dns_primario']);
				unset($input[$interface]['dns_secundario']);
				unset($input[$interface]['broadcast']);
				
			}
			
			switch ($post['mode'])
			{
				case 'managed':
					unset($input[$interface]['up']['interfaces_plus.sh']);
					unset($input[$interface]['post-up']['iwconfig']['enc']);
					break;
				case 'ad-hoc':
					//unset($input[$interface]['post-up']['iwconfig']['essid']);
					//unset($input[$interface]['up']['iwpriv']['hide_ssid']);
					unset($input[$interface]['up']['interfaces_plus.sh']);
					unset($input[$interface]['post-up']['iwconfig']['ap']);
					unset($input[$interface]['post-up']['iwconfig']['key']);
					unset($input[$interface]['post-up']['iwconfig']['enc']);
					break;
				case 'master':
					unset($input[$interface]['gateway']);
					unset($input[$interface]['post-up']['iwconfig']['ap']);
					unset($input[$interface]['post-up']['iwconfig']['key']);
					break;
			}
			
			if ($post['acktimeout'])
			{
				exec('sudo '.EXEC_PATH.'set_acktimeout.sh 1 '.$post['acktimeout']);
			}
			if ($post['ctstimeout'])
			{
				exec('sudo '.EXEC_PATH.'set_ctstimeout.sh 1 '.$post['ctstimeout']);
			}
			if ($post['slottime'])
			{
				exec('sudo '.EXEC_PATH.'set_slottime.sh 1 '.$post['slottime']);
			}
			/*
			$temp_file=fopen(BASE_PATH.'/data/fresnel_1.conf',"w");
			fwrite ($temp_file,$post['acktimeout']." ".$post['ctstimeout']." ".$post['slottime']."\n");
			fclose($temp_file);
			*/
			break;
		default:
			// For other interfaces not defined below no changes will be saved until the rules get maked.
			break;	
	}
	
	// To avoid direct access of www-data to system files, we will indirect the access.
	// To do that we first write the new interfaces in a temp file and call with sudo a script that
	// will move the data to the correct place. Doing so will slow action but secure it a bit.

	write_interfaces($write_path,$input);

    //echo "<pre>".print_r($input,true)."</pre>";
    return $input;
}
?>