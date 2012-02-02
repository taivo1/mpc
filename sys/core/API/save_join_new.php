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
function write_rule($join,$base,$fp)
{
    if (isset($join[$base])&&isset($join[($base+2)])&&($join[$base]!='blank')&&($join[($base+2)]!='blank'))
	{
		if($join[($base+1)]=='Right')
		{
            fwrite($fp,"if [ -n \"$".$join[$base]."\" ] ; then\n");
            fwrite($fp,"\t/usr/local/sbin/nat.sh ".$join[$base]." ".$join[($base+2)].' $'.$join[$base]."/24\n");
            fwrite($fp,"fi\n");
		}
		if($join[($base+1)]=='Bidirectional')
		{
            fwrite($fp,"if [ -n \"$".$join[$base]."\" ] ; then\n");
            fwrite($fp,"\t/usr/local/sbin/nat.sh ".$join[$base]." ".$join[($base+2)].' $'.$join[$base]."/24\n");
            fwrite($fp,"fi\n");

            fwrite($fp,"if [ -n \"$".$join[($base+2)]."\" ] ; then\n");
            fwrite($fp,"\t/usr/local/sbin/nat.sh ".$join[($base+2)]." ".$join[$base].' $'.$join[($base+2)]."/24\n");
            fwrite($fp,"fi\n");
		}
		if($join[($base+1)]=='Left')
		{
            fwrite($fp,"if [ -n \"$".$join[($base+2)]."\" ] ; then\n");
            fwrite($fp,"\t/usr/local/sbin/nat.sh ".$join[($base+2)]." ".$join[$base].' $'.$join[($base+2)]."/24\n");
            fwrite($fp,"fi\n");
        }
        fwrite($fp,"\n");
	}
}

function save_join($join,$conf_file='',$rules_file='')
{
    global $base_plugin;
    
    $interfaces=parse_interfaces('/etc/network/interfaces');

    if ($conf_file=='')
    {
        $conf_file=$base_plugin.'data/join.conf';
    }
    if ($rules_file=='')
    {
        $rules_file=$base_plugin.'data/join_rules.conf';
    }
	
	$fp=fopen($conf_file,"a");

    fwrite($fp,$join."\n");
	fclose($fp);
    unset($fp);

    $rules=file($conf_file);
    $fp=fopen($rules_file,"w");
    $init_rule="#!/bin/bash\n
eth0=$(ifconfig eth0 2>/dev/null | egrep -o '([0-9]{1,3}\.){3}[0-9]{1,3}' | egrep -v '255|(127\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})')\n
ath0=$(ifconfig ath0 2>/dev/null | egrep -o '([0-9]{1,3}\.){3}[0-9]{1,3}' | egrep -v '255|(127\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})')\n
ath1=$(ifconfig ath1 2>/dev/null | egrep -o '([0-9]{1,3}\.){3}[0-9]{1,3}' | egrep -v '255|(127\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})')\n
ppp0=$(ifconfig ppp0 2>/dev/null | egrep -o '([0-9]{1,3}\.){3}[0-9]{1,3}' | egrep -v '255|(127\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})')\n
";
    fwrite($fp,$init_rule);
    foreach($rules as $rule)
    {
        $join_array=explode('|',trim($rule));
        write_rule($join_array,'0',$fp);
    }
    fclose($fp);
}

function delete_join_rule($rule_number,$conf_file='',$rules_file='')
{
    global $base_plugin;
    $interfaces=parse_interfaces('/etc/network/interfaces');

    if ($conf_file=='')
    {
        $conf_file=$base_plugin.'data/join.conf';
    }
    if ($rules_file=='')
    {
        $rules_file=$base_plugin.'data/join_rules.conf';
    }

    // Load old config file and unset rule number.
    $rules=file($conf_file);
    unset($rules[$rule_number]);

    // Rewrite the configuration file without rule number.
    $fp=fopen($conf_file,"w");
    foreach($rules as $rule)
    {
        fwrite($fp,$rule);
    }
	fclose($fp);
    unset($fp);

    $fp=fopen($rules_file,"w");
    $init_rule="#!/bin/bash\n
eth0=$(ifconfig eth0 2>/dev/null | egrep -o '([0-9]{1,3}\.){3}[0-9]{1,3}' | egrep -v '255|(127\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})')\n
ath0=$(ifconfig ath0 2>/dev/null | egrep -o '([0-9]{1,3}\.){3}[0-9]{1,3}' | egrep -v '255|(127\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})')\n
ath1=$(ifconfig ath1 2>/dev/null | egrep -o '([0-9]{1,3}\.){3}[0-9]{1,3}' | egrep -v '255|(127\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})')\n
ppp0=$(ifconfig ppp0 2>/dev/null | egrep -o '([0-9]{1,3}\.){3}[0-9]{1,3}' | egrep -v '255|(127\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})')\n
";
    fwrite($fp,$init_rule);
    foreach($rules as $rule)
    {
        $join_array=explode('|',trim($rule));
        write_rule($join_array,'0',$fp);
    }
    fclose($fp);
}
?>