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
function save_olsrd($post,$write_path='',$read_path='')
{
    global $base_plugin;

    if ($write_path=='')
    {
        $write_path=$base_plugin.'data/new_olsd.conf';
    }
    if ($read_path=='')
    {
        $read_path='/etc/olsrd/olsrd.conf';
    }
	
	$data=parse_olsrd($read_path);
	
	$fp=fopen($write_path,"w");
	
	// Here the interface options.
	fwrite($fp,"DebugLevel 0 \n");
	
	if (($post['eth0']=='on')||($post['ath0']=='on')||($post['ath1']=='on'))
	{
		fwrite($fp,"Interface");
        if ($post['eth0']=='on')
		{
			fwrite($fp," \"eth0\"");
		}
		if ($post['ath0']=='on')
		{
			fwrite($fp," \"ath0\"");
		}
		if ($post['ath1']=='on')
		{
			fwrite($fp," \"ath1\"");
		}
		fwrite($fp,"\n{\n");
		
		if ($post['HelloInterval']!='')
		{
			fwrite($fp,"HelloInterval ".$post['HelloInterval']."\n");
		}
		
		if ($post['HelloValidityTime']!='')
		{
			fwrite($fp,"HelloValidityTime ".$post['HelloValidityTime']."\n");
		}
		
		if ($post['TcInterval']!='')
		{
			fwrite($fp,"TcInterval ".$post['TcInterval']."\n");
		}
		
		if ($post['TcValidityTime']!='')
		{
			fwrite($fp,"TcValidityTime ".$post['TcValidityTime']."\n");
		}
			
		if ($post['MidInterval']!='')
		{
			fwrite($fp,"MidInterval ".$post['MidInterval']."\n");
		}
		
		if ($post['MidValidityTime']!='')
		{
			fwrite($fp,"MidValidityTime ".$post['MidValidityTime']."\n");
		}
		
		if ($post['HnaInterval']!='')
		{
			fwrite($fp,"HnaInterval ".$post['HnaInterval']."\n");
		}
		
		if ($post['HnaValidityTime']!='')
		{
			fwrite($fp,"HnaValidityTime ".$post['HnaValidityTime']."\n");
		}
		
		if ($post['Weight']!='')
		{
			fwrite($fp,"Weight ".$post['Weight']."\n");
		}
		fwrite($fp,"}\n");
	}
	
	
	// Here we write the simple configuration.
	
	if ($post['IpVersion']!='')
	{
		fwrite($fp,"IpVersion ".$post['IpVersion']."\n");
	}
	
	if ($post['AllowNoInt']!='')
	{
		fwrite($fp,"AllowNoInt ".$post['AllowNoInt']."\n");
	}
	
	if ($post['TosValue']!='')
	{
		fwrite($fp,"TosValue ".$post['TosValue']."\n");
	}
	
	if ($post['Willingness']!='')
	{
		fwrite($fp,"Willingness ".$post['Willingness']."\n");
	}
	
	if ($post['UseHysteresis']!='')
	{
		fwrite($fp,"UseHysteresis ".$post['UseHysteresis']."\n");
	}
	
	if ($post['HystScaling']!='')
	{
		fwrite($fp,"HystScaling ".$post['HystScaling']."\n");
	}
	
	if ($post['HystThrHigh']!='')
	{
		fwrite($fp,"HystThrHigh ".$post['HystThrHigh']."\n");
	}
	
	if ($post['HysThrLow'])
	{
		fwrite($fp,"HysThrLow ".$post['HysThrLow']."\n");
	}
	
	if ($post['Pollrate']!='')
	{
		fwrite($fp,"Pollrate ".$post['Pollrate']."\n");
	}
	
	if ($post['NicChgsPollInt']!='')
	{
		fwrite($fp,"NicChgsPollInt ".$post['NicChgsPollInt']."\n");
	}
	
	if ($post['TcRedundancy']!='')
	{
		fwrite($fp,"TcRedundancy ".$post['TcRedundancy']."\n");
	}
	
	if ($post['MprCoverage']!='')
	{
		fwrite($fp,"MprCoverage ".$post['MprCoverage']."\n");
	}
	
	if ($post['LinkQualityLevel']!='')
	{
		fwrite($fp,"LinkQualityLevel ".$post['LinkQualityLevel']."\n");
	}
	
	if ($post['LinkQualityWinSize']!='')
	{
		fwrite($fp,"LinkQualityWinSize ".$post['LinkQualityWinSize']."\n");
	}
	
	if ($post['LinkQualityFishEye']!='')
	{
		fwrite($fp,"LinkQualityFishEye ".$post['LinkQualityFishEye']."\n");
	}
	
	if ($post['LinkQualityDijkstraLimit1']!='')
	{
		fwrite($fp,"LinkQualityDijkstraLimit ".$post['LinkQualityDijkstraLimit1']." ".$post['LinkQualityDijkstraLimit2']."\n");
	}
		
	// Now the other block options... the hardest ones so i will wait 'till end.
	//Hna4
	$aux='';
	for ($i=0;$i<=$post['hna4count'];$i++)
	{		
		if ($post['hna4_netaddress'.$i]!='')
		{
			$aux.="\t".$post['hna4_netaddress'.$i]." ".$post['hna4_netmask'.$i]."\n";
		}
		
	}
	
	if ($aux!='')
	{
		fwrite($fp,"Hna4\n{\n");
		fwrite($fp,$aux);
		fwrite($fp,"}\n");
	}
	
	//Hna6
	$aux='';
	for ($i=0;$i<=$post['hna6count'];$i++)
	{		
		if ($post['hna6_netaddress'.$i]!='')
		{
			$aux.="\t".$post['hna6_netaddress'.$i]." ".$post['hna6_netmask'.$i]."\n";
		}
		
	}
	
	if ($aux!='')
	{
		fwrite($fp,"Hna6\n{\n");
		fwrite($fp,$aux);
		fwrite($fp,"}\n");
	}
	
	//IpcConnect
	
	$aux='';
	for ($i=0;$i<=$post['ipchcount'];$i++)
	{		
		if ($post['ipchost'.$i]!='')
		{
			$aux.="\tHost ".$post['ipchost'.$i]."\n";
		}
		
	}
	for ($i=0;$i<=$post['ipcncount'];$i++)
	{		
		if (($post['IpcConnect_netaddress'.$i]!='')&&($post['IpcConnect_netmask'.$i]!=''))
		{
			$aux.="\tNet ".$post['IpcConnect_netaddress'.$i]." ".$post['IpcConnect_netmask'.$i]."\n";
		}
		
	}
	
	if ($aux!='')
	{
		fwrite($fp,"IpcConnect\n{\n");
		if ($post['IpcConnect_MaxConnections']!='')
		{
			fwrite($fp,"\tMaxConnections ".$post['IpcConnect_MaxConnections']."\n");
		}
		fwrite($fp,$aux);
		fwrite($fp,"}\n");
	}
	
	//LoadPluggin
	//olsrd_httpinfo.so.0.1
	
	$aux='';
	
	if ($post['olsrd_httpinfo.so.0.1_port']!='')
	{
		$aux.="\tPlParam \"port\" \"".$post['olsrd_httpinfo.so.0.1_port']."\"\n";
	}
	
	if (($post['olsrd_httpinfo.so.0.1_net1']!='')&&($post['olsrd_httpinfo.so.0.1_net1']!=''))
	{
		$aux.="\tPlParam \"Net\" \"".$post['olsrd_httpinfo.so.0.1_net1']." ".$post['olsrd_httpinfo.so.0.1_net2']."\"\n";
	}
	
	if ($aux!='')
	{
		fwrite($fp,"LoadPlugin \"olsrd_httpinfo.so.0.1\"\n{\n");
		fwrite($fp,$aux);
		fwrite($fp,"}\n");
	}
	
	//olsrd_txtinfo.so.0.1
	/*
	$aux='';
	
	if (($post['olsrd_txtinfo.so.0.1_net1']!='')&&($post['olsrd_txtinfo.so.0.1_net2']!=''))
	{
		$aux.="\tPlParam \"accept\" \"".$post['olsrd_txtinfo.so.0.1_net1']." ".$post['olsrd_txtinfo.so.0.1_net2']."\"\n";
	}
	
	if ($aux!='')
	{
		fwrite($fp,"LoadPlugin \"olsrd_txtinfo.so.0.1\"\n{\n");
		fwrite($fp,$aux);
		fwrite($fp,"}\n");
	}
	//*/
	
	
	$aux="\tPlParam \"accept\" \"127.0.0.1\"\n";
		
	fwrite($fp,"LoadPlugin \"olsrd_txtinfo.so.0.1\"\n{\n");
	fwrite($fp,$aux);
	fwrite($fp,"}\n");

	
	
	//olsrd_secure.so.0.5
	
	$aux='';
	
	
	if ($post['olsrd_secure.so.0.5_key']!='')
	{
		$aux.="\tPlParam \"Keyfile\" \"/etc/olsr-secret\" \n";
		$fp2=fopen("/tmp/olsr-secret","w");
		fwrite($fp2,$post['olsrd_secure.so.0.5_key']);
		fclose($fp2);
	}
	else
	{
		$fp2=fopen("/tmp/olsr-secret","w");
		fclose($fp2);
	}
	
	if ($aux!='')
	{
		fwrite($fp,"LoadPlugin \"olsrd_secure.so.0.5\"\n{\n");
		fwrite($fp,$aux);
		fwrite($fp,"}\n");
	}	
	
	// Other plugin not recognized.
	
	for ($i=0;$i<$data['LoadPlugin']['count'];$i++)
	{
		fwrite($fp,"LoadPlugin ".$data['LoadPlugin'][$i]['plugin-name']."\n{\n");
		for ($it=0;$it<$data['LoadPlugin'][$i]['count'];$it++)
		{
			fwrite($fp,"PlParam ".$data['LoadPlugin'][$i]['PlParam'][$it]."\n");
		}
		fwrite($fp,"}\n");
	}
	
	//fwrite($fp,print_r($post,true));
	
    // Let's close the file descriptor.
    fclose($fp);
}
?>