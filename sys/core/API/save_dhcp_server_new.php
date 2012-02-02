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

function update_dhcp($dhcp_configuration)
{
    $new_dhcp_configuration=parse_dhcp_server();
    // Actualize eth0, ath0 and ath1 information.
    if ($dhcp_configuration['dhcp_server_eth0']=='on')
    {
        $new_dhcp_configuration['eth0']['start']=$dhcp_configuration['dhcp_start_eth0'];
        $new_dhcp_configuration['eth0']['end']=$dhcp_configuration['dhcp_end_eth0'];
        $new_dhcp_configuration['eth0']['expire']=$dhcp_configuration['dhcp_expire_eth0'];
    }
    else
    {
        unset ($new_dhcp_configuration['eth0']);
    }
    if ($dhcp_configuration['dhcp_server_ath0']=='on')
    {
        $new_dhcp_configuration['ath0']['start']=$dhcp_configuration['dhcp_start_ath0'];
        $new_dhcp_configuration['ath0']['end']=$dhcp_configuration['dhcp_end_ath0'];
        $new_dhcp_configuration['ath0']['expire']=$dhcp_configuration['dhcp_expire_ath0'];
    }
    else
    {
        unset ($new_dhcp_configuration['ath0']);
    }
    if ($dhcp_configuration['dhcp_server_ath1']=='on')
    {
        $new_dhcp_configuration['ath1']['start']=$dhcp_configuration['dhcp_start_ath1'];
        $new_dhcp_configuration['ath1']['end']=$dhcp_configuration['dhcp_end_ath1'];
        $new_dhcp_configuration['ath1']['expire']=$dhcp_configuration['dhcp_expire_ath1'];
    }
    else
    {
        unset ($new_dhcp_configuration['ath1']);
    }
    return $new_dhcp_configuration;
}
function save_dhcp_server($dhcp_configuration,$save_file='')
{
	global $base_plugin;

    if ($save_file=='')
    {
        $save_file=$base_plugin.'data/dnsmasq.more.conf';
    }
    
    $fp=fopen($save_file,"w");

	$save_configuration=update_dhcp($dhcp_configuration);
    foreach($save_configuration as $interface =>$interface_values)
	{
		fwrite($fp, "dhcp-range=".$interface.",".$interface_values['start'].",".$interface_values['end'].",".$interface_values['expire']."h\n");
	}
    fwrite($fp,"dhcp-leasefile=/var/tmp/dnsmasq.leases\n");
    fclose($fp);
}
?>