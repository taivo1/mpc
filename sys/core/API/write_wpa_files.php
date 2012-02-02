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
function make_wpa_files($hide,$pass,$mode,$ssid,$interface,$channel,$abg)
{
	
	switch ($mode)
	{
		case 'master':
			exec('sudo '.EXEC_PATH.'wpa_modules.sh 2>&1 >/dev/null');
			if ($pass!='keep')
			{
				exec('wpa_passphrase '.$ssid.' '.$pass,$aux);
				$skeleton=file('data/hostapd_skeleton');
				$fp=fopen('/tmp/hostapd.conf',"w");
				
				foreach ($skeleton as $line)
				{
					
					// with trim just take out unwanted characters
			        $line = trim( $line );
			        // if first character is a # we know is a comment and pass through
					if ( $line == '' || $line{0} == '#' ) 
					{ 
						fwrite($fp,$line."\n");
						continue;
					}
					
					$data=explode('=',$line,2);
					if ($data[0]=='interface')
					{
						fwrite($fp,"interface=".$interface."\n");
					}
					elseif ($data[0]=='driver')
					{
						fwrite($fp,"driver=madwifi\n");
					}
					elseif ($data[0]=='ssid')
					{
						fwrite($fp,"ssid=".$ssid."\n");
					}
					elseif ($data[0]=='hw_mode')
					{
						if ($abg=='1')
						{
							fwrite($fp,"hw_mode=b\n");
						}
						elseif ($abg=='2')
						{
							fwrite($fp,"hw_mode=g\n");
						}
						elseif ($abg=='3')
						{
							fwrite($fp,"hw_mode=a\n");
						}
					}
					elseif ($data[0]=='channel')
					{
						fwrite($fp,"channel=".$channel."\n");
					}
					elseif ($data[0]=='wpa_psk')
					{
						fwrite($fp,"wpa_".trim($aux[3])."\n");
					}
					else
					{
						fwrite($fp,$line."\n");
					}
					
				}
				fclose($fp);
				exec('sudo '.EXEC_PATH.'move /tmp/hostapd.conf /etc/hostapd/hostapd.conf');
			}			
			break;
		case 'managed':
			exec('sudo '.EXEC_PATH.'wpa_modules.sh 2>&1 >/dev/null');
			exec('wpa_passphrase '.$ssid.' '.$pass,$aux);
			$fp=fopen('/tmp/wpa_supplicant.conf',"w");
			fwrite($fp,"ctrl_interface=/var/run/wpa_supplicant\n");
			fwrite($fp,"eapol_version=2\n");
			fwrite($fp,"ap_scan=1\n");
			fwrite($fp,"network={\n");
			fwrite($fp,"\tssid=\"".$ssid."\"\n");
			if ($hide=='1')
			{
				fwrite($fp,"\tscan_ssid=1\n");
			}
			fwrite($fp,"\tpriority=5\n");
			fwrite($fp,"\tproto=WPA\n");
			fwrite($fp,"\tkey_mgmt=WPA-PSK\n");
			fwrite($fp,"\tpairwise=TKIP\n");
			fwrite($fp,"\tgroup=TKIP\n");
			fwrite($fp,$aux[3]."\n");
			fwrite($fp,"}\n");				
			fclose($fp);
			exec('sudo '.EXEC_PATH.'move /tmp/wpa_supplicant.conf /etc/wpa_supplicant.conf');
			break;
		default:
			break;		
	}
	return true;	
}
?>