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
function save_join($join,$arrow)
{	
	$Response = new xajaxResponse();
	$fp2=fopen('data/join.conf',"w");
	fwrite($fp2,$join[1]."\n");
	fwrite($fp2,$join[2]."\n");
	fwrite($fp2,$join[3]."\n");
	fwrite($fp2,$join[4]."\n");
	fwrite($fp2,$join[9]."\n");
	fwrite($fp2,$join[10]."\n");
	fwrite($fp2,$join[11]."\n");
	fwrite($fp2,$join[12]."\n");	
	fwrite($fp2,$arrow[1]."\n");
	fwrite($fp2,$arrow[2]."\n");
	fwrite($fp2,$arrow[3]."\n");
	fwrite($fp2,$arrow[4]."\n");
	fclose($fp2);
	include_once(BASE_PATH.'API/parser_interfaces.php');
	$interfaces=parse_interfaces('/etc/network/interfaces');
	//$Response->alert(print_r($join,true).print_r($arrow,true));
	$fp=fopen('data/nat.sh',"w");
	if (isset($join[1])&&isset($join[3])&&($join[1]!='blank')&&($join[3]!='blank'))
	{
		if($arrow[1]=='Right')
		{
			if (isset($interfaces[$join[1]]['address']))
			{
				fwrite($fp,"/usr/local/sbin/nat.sh ".$join[1]." ".$join[9].' '.$interfaces[$join[1]]['address']."/24\n");
			}			
		}
		if($arrow[1]=='Bidirectional')
		{
			if (isset($interfaces[$join[1]]['address']))
			{
				fwrite($fp,"/usr/local/sbin/nat.sh ".$join[1]." ".$join[3].' '.$interfaces[$join[1]]['address']."/24\n");
			}
				if (isset($interfaces[$join[3]]['address']))
			{
				fwrite($fp,"/usr/local/sbin/nat.sh ".$join[3]." ".$join[1].' '.$interfaces[$join[3]]['address']."/24\n");
			}
		}
		if($arrow[1]=='Left')
		{
			if (isset($interfaces[$join[9]]['address']))
			{
				fwrite($fp,"/usr/local/sbin/nat.sh ".$join[9]." ".$join[1].' '.$interfaces[$join[9]]['address']."/24\n");
			}
		}
	}
	if (isset($join[2])&&isset($join[10])&&($join[10]!='blank')&&($join[2]!='blank'))
	{
		if ($join[10]=='gprs')
		{
			$join[10]='ppp0';
		}
		if($arrow[2]=='1')
		{
			if (isset($interfaces[$join[2]]['address']))
			{
				fwrite($fp,"/usr/local/sbin/nat.sh ".$join[2]." ".$join[10].' '.$interfaces[$join[2]]['address']."/24\n");
			}
		}
		if($arrow[2]=='2')
		{
			if (isset($interfaces[$join[2]]['address']))
			{
				fwrite($fp,"/usr/local/sbin/nat.sh ".$join[2]." ".$join[10].' '.$interfaces[$join[2]]['address']."/24\n");
			}
			if (isset($interfaces[$join[10]]['address']))
			{
				fwrite($fp,"/usr/local/sbin/nat.sh ".$join[10]." ".$join[2].' '.$interfaces[$join[10]]['address']."/24\n");
			}
		}
		if($arrow[2]=='3')
		{
			if (isset($interfaces[$join[10]]['address']))
			{
				fwrite($fp,"/usr/local/sbin/nat.sh ".$join[10]." ".$join[2].' '.$interfaces[$join[10]]['address']."/24\n");
			}
		}
	}
	if (isset($join[3])&&isset($join[11])&&($join[11]!='blank')&&($join[3]!='blank'))
	{
		if ($join[11]=='gprs')
		{
			$join[11]='ppp0';
		}
		if($arrow[3]=='1')
		{
			if (isset($interfaces[$join[3]]['address']))
			{
				fwrite($fp,"/usr/local/sbin/nat.sh ".$join[3]." ".$join[11].' '.$interfaces[$join[3]]['address']."/24\n");
			}
		}
		if($arrow[3]=='2')
		{
			if (isset($interfaces[$join[3]]['address']))
			{
				fwrite($fp,"/usr/local/sbin/nat.sh ".$join[3]." ".$join[11].' '.$interfaces[$join[3]]['address']."/24\n");
			}
			if (isset($interfaces[$join[11]]['address']))
			{
				fwrite($fp,"/usr/local/sbin/nat.sh ".$join[11]." ".$join[3].' '.$interfaces[$join[11]]['address']."/24\n");
			}
		}
		if($arrow[3]=='3')
		{
			if (isset($interfaces[$join[11]]['address']))
			{
				fwrite($fp,"/usr/local/sbin/nat.sh ".$join[11]." ".$join[3].' '.$interfaces[$join[11]]['address']."/24\n");
			}
		}
	}
	if (isset($join[4])&&isset($join[12])&&($join[4]!='blank')&&($join[12]!='blank'))
	{
		if ($join[12]=='gprs')
		{
			$join[12]='ppp0';
		}
		if($arrow[4]=='1')
		{
			if (isset($interfaces[$join[4]]['address']))
			{
				fwrite($fp,"/usr/local/sbin/nat.sh ".$join[4]." ".$join[12].' '.$interfaces[$join[4]]['address']."/24\n");
			}
		}
		if($arrow[4]=='2')
		{
			if (isset($interfaces[$join[4]]['address']))
			{
				fwrite($fp,"/usr/local/sbin/nat.sh ".$join[4]." ".$join[12].' '.$interfaces[$join[4]]['address']."/24\n");
			}
			if (isset($interfaces[$join[12]]['address']))
			{
				fwrite($fp,"/usr/local/sbin/nat.sh ".$join[12]." ".$join[4].' '.$interfaces[$join[12]]['address']."/24\n");
			}
		}
		if($arrow[4]=='3')
		{
			if (isset($interfaces[$join[12]]['address']))
			{
				fwrite($fp,"/usr/local/sbin/nat.sh ".$join[12]." ".$join[4].' '.$interfaces[$join[12]]['address']."/24\n");
			}
		}
	}
	fclose($fp);	
	$Response->script("stop_alert('".MESSAGE_AFTER_SAVING."')");
	$Response->script("change_me('join','AP2')");
	return $Response;
}
?>