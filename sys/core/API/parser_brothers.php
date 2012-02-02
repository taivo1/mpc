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
function parse_brothers_info($path)
{
	unset($brothers);
	unset ($file);
	unset ($files);	
	unset ($i);
	unset($aux);	

	if (file_exists($path))
	{
		$ini=file($path);
		foreach( $ini as $line)
		{
			$aux=explode('=',$line,2);
			$aux[0]=trim($aux[0]);
			$aux[1]=trim($aux[1]);
			$brothers[$aux[0]]=$aux[1];
		}		
	}
	
	return $brothers;
}
//$ret=parse_brothers_info(BASE_PATH.'/data/adquired_data/uno/.last');
//echo '<pre>'.print_r($ret,true).'</pre>';

/*
 * Example output
Array
(
    [gps_status] => 1
    [latitude] => 41.6815
    [longitude] => -0.8884
    [version] => meshlium-2008.106-09
    [google_key] => no
    [adquire] => 1
    [send_server] => on
    [send_server_int] => 5
    [send_brothers] => off
    [send_brothers_int] => 3
    [allow_send_info] => on
    [cypher_use] => 
    [interconection_0] => eth0-->ath0
    [interconection_1] => ath0<->ath1
    [interconection_2] => ath1<--gprs
    [interconection_3] => 
    [eth0_up] => 0
    [eth0_down] => 0
    [ppp0_up] => 
    [ppp0_down] => 
    [ath0_up] => 0
    [ath0_down] => 0
    [ath1_up] => 0
    [ath1_down] => 0
    [eth0] => up
    [eth0_ip] => 192.168.1.64
    [eth0_gateway] => 192.168.1.1
    [ath0] => up
    [ath0_ip] => 10.10.10.1
    [ath0_gateway] => 
    [ath0_mode] => ad-hoc
    [ath0_channel] => 6
    [ath0_abg] => 2
    [ath1] => up
    [ath1_ip] => 11.11.11.1
    [ath1_gateway] => 
    [ath1_mode] => ad-hoc
    [ath1_channel] => 48
    [ath1_abg] => 3
    [ath0_see_nets] => num=0 //
    [ath1_see_nets] => num=0 //
    [current_cell_ath0_essid] => meshlium2
    [current_cell_ath0_ap] => 
    [current_cell_ath0_channel] => 6
    [current_cell_ath1_essid] => meshlium5
    [current_cell_ath1_ap] => 
    [current_cell_ath1_channel] => 48
    [clients_ath0] => ADDR               AID CHAN RATE RSSI  DBM IDLE  TXSEQ  RXSEQ CAPS ACAPS ERP    STATE     MODE//00:15:6d:63:a6:45    0    6  36M    7  -88    0   3049  45376 I            0        1   Normal//00:90:96:ac:c9:19    0    6  36M    4  -91    0     49  22880              0        1   Normal//00:1b:9e:7d:28:ec    0    6  36M   12  -83   15    244  33888              0        1   Normal//
    [clients_ath1] => ADDR               AID CHAN RATE RSSI  DBM IDLE  TXSEQ  RXSEQ CAPS ACAPS ERP    STATE     MODE//00:15:6d:63:0d:b2    0   48  36M   24  -71  300      6   9728 I            0        1   Normal//00:1b:9e:7d:28:ec    0   48  54M   25  -70  150      6  26944              0        1   Normal//
    [ppp0] => down
    [ppp0_operator] => 
    [ppp0_country] => 
    [bluetooth_num] => 0
    [zigbee] => down
    [zigbee_file_size] => 
    [olsrd_DebugLevel] => 0
    [olsrd_IpVersion] => 4
    [olsrd_AllowNoInt] => yes
    [olsrd_TosValue] => 
    [olsrd_Willingness] => 7
    [olsrd_UseHysteresis] => no
    [olsrd_HystScaling] => 
    [olsrd_HystThrHigh] => 
    [olsrd_HysThrLow] => 
    [olsrd_Pollrate] => 
    [olsrd_NicChgsPollInt] => 
    [olsrd_TcRedundancy] => 2
    [olsrd_MprCoverage] => 
    [olsrd_LinkQualityLevel] => 0
    [olsrd_LinkQualityWinSize] => 
    [olsrd_LinkQualityFishEye] => 1
    [olsrd_LinkQualityDijkstraLimit] => 3 3.0
    [olsrd_interfaces] => ath0
    [olsrd_Interface_AutoDetectChanges] => 
    [olsrd_Interface_Ip4Broadcast] => 
    [olsrd_Interface_Ip6AddrType] => 
    [olsrd_Interface_Ip6MulticastSite] => 
    [olsrd_Interface_Ip6MulticastGlobal] => 
    [olsrd_Interface_HelloInterval] => 2.0
    [olsrd_Interface_HelloValidityTime] => 10.0
    [olsrd_Interface_TcInterval] => 2.0
    [olsrd_Interface_TcValidityTime] => 10.0
    [olsrd_Interface_MidInterval] => 10.0
    [olsrd_Interface_MidValidityTime] => 50.0
    [olsrd_Interface_HnaInterval] => 10.0
    [olsrd_Interface_HnaValidityTime] => 50.0
    [olsrd_brothers] => 11.11.11.2 10.10.10.2
)
 */
?>