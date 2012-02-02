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

function add_mac_filter($mac,$interface,$type)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

	$return=modify_mac_filter($interface,$mac,'add','');
    exec('sudo iwpriv '.$interface.' addmac '.$mac.' >/dev/null');
	if ($type=='2')
	{
		exec('sudo iwpriv '.$interface.' kickmac '.$mac.' >/dev/null');
	}
	return $return;
}

function del_mac_filter($mac,$interface,$type)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

	$return= modify_mac_filter($interface,$mac,'del','');
	exec('sudo iwpriv '.$interface.' delmac '.$mac.' >/dev/null');
	if ($type=='1')
	{
		exec('sudo iwpriv '.$interface.' kickmac '.$mac.' >/dev/null');
	}
	return $return;
}

function change_list_type($type,$interface)
{	
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

	$return= modify_mac_filter($interface,'','change',$type);
	exec('iwpriv '.$interface.' maccmd 4 >/dev/null');
	return $return;
}

function modify_mac_filter($interface,$data,$action,$type,$readpath='',$writepath='')
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

    if ($readpath=='')
    {
        $readpath=$base_plugin.'data/interfaces_plus_'.$interface;
    }
	if ($writepath=='')
    {
        $writepath=$base_plugin.'data/interfaces_plus_'.$interface.'.tmp';
    }
	$add=0;
	$it=1;
	$fp=fopen($writepath,"w");
	
	if (file_exists($readpath))
	{
		$ini = file( $readpath );
	}
    else
    {
        $ini=Array();
    }

	
	foreach ($ini as $line)
	{
		$line = trim( $line );
		if ( $line == '' || $line{0} == '#' )
		{
			continue;
		}		
		$values= explode( ' ', $line,4);
		$values[1]=trim($values[1]);
		$values[2]=trim($values[2]);
		$values[3]=trim($values[3]);
		
		if ($it==1)
		{
			if ($action=='change')
			{
				fwrite($fp, "iwpriv ".$interface." maccmd ".$type."\n");
			}
			else
			{
				if ($values[2]=='maccmd')
				{
					fwrite($fp,$line."\n");
				}
				else
				{
					fwrite($fp, "iwpriv ".$interface." maccmd 2 \n"); // if no list type, we put blacklist so new clients may connect
				}
			}
		}
		
		$it++;
		
		if ($values[2]=='addmac')
		{
			if ($values[3]==$data)
			{
				if ($action=='add')
				{
					$add=1;
					fwrite($fp, "iwpriv ".$interface." addmac ".$data."\n");
				}	
				else
				{
					continue;
				}
			}
			else
			{
				fwrite($fp,$line."\n");
			}
		}
	}
	if (($add==0)&&($action=='add'))
	{
		fwrite($fp, "iwpriv ".$interface." addmac ".$data."\n");
	}
	
	fclose($fp);
    exec("mv $writepath $readpath");
    return true;
}
?>