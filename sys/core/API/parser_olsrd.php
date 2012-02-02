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
// This file just implements a parser for meshlium /etc/hosts files
function parse_olsrd ( $filepath ) {
    $result= array();
    if (file_exists($filepath))
    {
        // The number of host/net items in ipcconnect.
        $ipchit=0;
        $ipcnit=0;
        // In the ini var we put the file
        $ini = file( $filepath );
        // Check for an empty file
        if ( count( $ini ) == 0 ) { return array(); }
        
        $i = 0;
        // Start parser
        // for each line we are goin to parse the file

        // Parser control variables
        $expect_block='no'; // yes if we expect a { no otherwise
        $block='none'; // $block contains the name of the active block option
        $inABlock='no'; // Yes only between '{' and '}'
        $hna4count=0; // This var count the number of hosts declared in hna4
        $hna6count=0; // This var count the number of hosts declared in hna6
        $LoadPlugincount=0; // This var count the number of plugins declared in LoadPlugin
        $PluginParam=0; //// This var count the number of params declared for each Plugin

        foreach( $ini as $line ){
            // with trim just take out unwanted characters
            $line = trim( $line );
            // if first character is a # we know is a comment and pass through
            if ( $line == '' || $line{0} == '#' )
            {
                continue;
            }
            // If we don't find a comment we have to take ip address and hosts
            // We will have as many rows of data as lines with data in /etc/hosts
            $values= explode( ' ', $line,2);
            $values[0]=trim($values[0]);
            $values[1]=trim($values[1]);
            // SINGLE OPTIONS
            if ($inABlock=='no')
            {
                switch($values[0]){
                case "DebugLevel":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "IpVersion":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "AllowNoInt":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "TosValue":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "Willingness":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "UseHysteresis":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "HystScaling":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "HystThrHigh":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "HysThrLow":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "Pollrate":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "NicChgsPollInt":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "TcRedundancy":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "MprCoverage":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "LinkQualityLevel":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "LinkQualityWinSize":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "LinkQualityFishEye":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "LinkQualityDijkstraLimit":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                case "ClearScreen":
                    $expect_block='no';
                    $result[$values[0]]=$values[1];
                break;
                // OPTION BLOCKS
                case "IpcConnect":
                    $expect_block='yes';
                    $block='IpcConnect';
                break;
                case "Hna4":
                    $expect_block='yes';
                    $block='Hna4';
                break;
                case "Hna6":
                    $expect_block='yes';
                    $block='Hna6';
                break;
                case "LoadPlugin":
                    $expect_block='yes';
                    $block='LoadPlugin';
                    if (($values[1]=='"olsrd_httpinfo.so.0.1"')||($values[1]=='"olsrd_txtinfo.so.0.1"')||($values[1]=='"olsrd_secure.so.0.5"'))
                    {
                        $values[1]=substr($values[1],1);
                        $values[1]=substr($values[1],0,-1);
                        //$result[$values[0]][$values[1]]=$values[1];
                        $plugin=$values[1];
                        $PluginParam=0;
                    }
                    else
                    {
                        $result[$values[0]][$LoadPlugincount]['plugin-name']=$values[1];
                        $LoadPlugincount++;
                        $result[$values[0]]['count']=$LoadPlugincount;
                        $PluginParam=0;
                    }

                break;
                case "Interface":
                    $expect_block='yes';
                    $block='Interface';
                    if ($values[1])
                    {
                        $aux1=explode(' ',trim($values[1]));
                        foreach ($aux1 as $aux2)
                        {
                            $aux2=substr($aux2,1,4);
                            $result[$values[0]]['interfaces'][$aux2]=$aux2;
                        }
                    }
                    unset($aux1);
                    unset($aux2);
                break;
                // Special elements {  and }
                case "{":
                    // Just check if last option was a block one.
                    // if not, just discard and continue
                    if ($expect_block=='yes')
                    {
                        $inABlock='yes';
                    }
                break;
                case "}":
                    // Just check if we are in an open  block.
                    // if not, just discard and continue.
                    if ($inABlock=='yes')
                    {
                        $inABlock='no'; // if all goes right you'll never be there.
                    }
                break;
                }
            }
            else
            {
                // If you are here, you are inside a block of options
                // in $block is the bock type
                if ($values[0]=="}")
                { // This should be the last one we check. But not mandatory.
                    // Just check if we are in an open  block.
                    // if not, just discard and continue.
                    if ($inABlock=='yes')
                    {
                        $inABlock='no';
                    }

                    continue;
                }
                else
                {
                    switch($block)
                    {
                    case "IpcConnect":
                        switch($values[0])
                        {
                        case 'MaxConnections':
                            $result[$block][$values[0]]=$values[1];
                        break;
                        case 'Host':
                            $result[$block][$values[0]][$ipchit]=$values[1];
                            $ipchit++;
                            $result[$block]['ipchit']=$ipchit;
                        break;
                        case 'Net':
                            $aux2=explode(' ',$values[1]);
                            $aux2[0]=trim($aux2[0]);
                            $aux2[1]=trim($aux2[1]);
                            $result[$block][$values[0]][$ipcnit]['0']=$aux2[0];
                            $result[$block][$values[0]][$ipcnit]['1']=$aux2[1];
                            $ipcnit++;
                            $result[$block]['ipcnit']=$ipcnit;
                        break;
                        }

                    break;
                    case "Hna4":
                        $result[$block]['netaddress'][$hna4count]=$values[0];
                        $result[$block]['netmask'][$hna4count]=$values[1];
                        $hna4count++;
                        $result[$block]['count']=$hna4count;
                    break;
                    case "Hna6":
                        $result[$block]['netaddress'][$hna6count]=$values[0];
                        $result[$block]['prefix_value'][$hna6count]=$values[1];
                        $hna6count++;
                        $result[$block]['count'][$hna6count]=$hna6count;
                    break;
                    case"LoadPlugin":
                        if ($plugin=='olsrd_httpinfo.so.0.1')
                        {
                            $aux2=explode(' ',trim($values[1]),2);
                            $aux2[0]=trim($aux2[0]);
                            $aux2[1]=trim($aux2[1]);

                            $aux2[0]=substr($aux2[0],1);
                            $aux2[0]=substr($aux2[0],0,-1);
                            $aux2[1]=substr($aux2[1],1);
                            $aux2[1]=substr($aux2[1],0,-1);

                            $result[$block][$plugin]['PlParam'][$aux2[0]]=$aux2[1];
                            $PluginParam++;
                            $result[$block][$plugin]['count']=$PluginParam;
                        }
                        elseif ($plugin=='olsrd_txtinfo.so.0.1')
                        {
                            $aux2=explode(' ',trim($values[1]),2);
                            $aux2[0]=trim($aux2[0]);
                            $aux2[1]=trim($aux2[1]);

                            $aux2[0]=substr($aux2[0],1);
                            $aux2[0]=substr($aux2[0],0,-1);
                            $aux2[1]=substr($aux2[1],1);
                            $aux2[1]=substr($aux2[1],0,-1);

                            $result[$block][$plugin]['PlParam'][$aux2[0]]=$aux2[1];
                            $PluginParam++;
                            $result[$block][$plugin]['count']=$PluginParam;
                        }
                        elseif ($plugin=='olsrd_secure.so.0.5')
                        {
                            $aux2=explode(' ',trim($values[1]),2);
                            $aux2[0]=trim($aux2[0]);
                            $aux2[1]=trim($aux2[1]);

                            $aux2[0]=substr($aux2[0],1);
                            $aux2[0]=substr($aux2[0],0,-1);
                            $aux2[1]=substr($aux2[1],1);
                            $aux2[1]=substr($aux2[1],0,-1);

                            $result[$block][$plugin]['PlParam']['pass']=$aux2[0];
                            $PluginParam++;
                            $result[$block][$plugin]['count']=$PluginParam;
                        }
                        else
                        {
                            $result[$block][$LoadPlugincount-1]['PlParam'][$PluginParam]=$values[1];
                            $PluginParam++;
                            $result[$block][$LoadPlugincount-1]['count']=$PluginParam;
                        }
                    break;
                    case "Interface":
                        switch($values[0]){
                        case 'AutoDetectChanges':
                            $result[$block][$values[0]]=trim($values[1]);
                        break;
                        case 'Ip4Broadcast':
                            $result[$block][$values[0]]=trim($values[1]);
                        break;
                        case 'Ip6AddrType':
                            $result[$block][$values[0]]=trim($values[1]);
                        break;
                        case 'Ip6MulticastSite':
                            $result[$block][$values[0]]=trim($values[1]);
                        break;
                        case 'Ip6MulticastGlobal':
                            $result[$block][$values[0]]=trim($values[1]);
                        break;
                        case 'HelloInterval':
                            $result[$block][$values[0]]=trim($values[1]);
                        break;
                        case 'HelloValidityTime':
                            $result[$block][$values[0]]=trim($values[1]);
                        break;
                        case 'TcInterval':
                            $result[$block][$values[0]]=trim($values[1]);
                        break;
                        case 'TcValidityTime':
                            $result[$block][$values[0]]=trim($values[1]);
                        break;
                        case 'MidInterval':
                            $result[$block][$values[0]]=trim($values[1]);
                        break;
                        case 'MidValidityTime':
                            $result[$block][$values[0]]=trim($values[1]);
                        break;
                        case 'HnaInterval':
                            $result[$block][$values[0]]=trim($values[1]);
                        break;
                        case 'HnaValidityTime':
                            $result[$block][$values[0]]=trim($values[1]);
                        break;
                        case 'Weight':
                            $result[$block][$values[0]]=trim($values[1]);
                        break;
                        }
                    break;
                    }
                }
            }
            $i++;
        }
    }
    return $result;
}
?>