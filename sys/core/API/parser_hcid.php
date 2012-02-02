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
function parse_hcid($read_file='')
{
    global $base_plugin;
    $file=array ();
    $hcid_configuration=Array();
    if (($read_file=='')||(!file_exists($read_file)))
    {        
        $read_file=$base_plugin.'data/hcid.conf';
    }
    if (file_exists($read_file))
    {
        $file=file($read_file);
    }
    foreach ($file as $line)
    {
        $line = trim( $line );
        if (($line[0]=='#')||($line[0]==''))
        {
            continue;
        }
        $data=explode(' ',trim($line),2);
        switch ($data[0])
        {
            case 'autoinit':
                $value=explode(';',trim($data[1]));
                $hcid_configuration['autoinit']=$value[0];
                break;
            case 'security':
                $value=explode(';',trim($data[1]));
                $hcid_configuration['security']=$value[0];
                break;
            case 'pairing':
                $value=explode(';',trim($data[1]));
                $hcid_configuration['pairing']=$value[0];
                break;
            case 'passkey':
                $value=explode(';',trim($data[1]));
                $hcid_configuration['passkey']=$value[0];
                break;
            case 'name':
                $value=explode(';',trim($data[1]));
                $hcid_configuration['name']=$value[0];
                break;
            case 'class':
                $value=explode(';',trim($data[1]));
                $hcid_configuration['class']=$value[0];
                break;
            case 'iscan':
                $value=explode(';',trim($data[1]));
                $hcid_configuration['iscan']=$value[0];
                // Should check for pscan because usually share same line.
                $value2=explode(' ',trim($value[1]));
                if ($value2[0]=='pscan')
                {
                    $value3=explode(';',trim($value2[1]));
                    $hcid_configuration['pscan']=$value3[0];
                }
                break;
            case 'pscan':
                $value=explode(';',trim($data[1]));
                $hcid_configuration['pscan']=$value[0];
                // Should check for iscan because usually share same line.
                $value2=explode(' ',trim($value[1]));
                if ($value2[0]=='iscan')
                {
                    $value3=explode(';',trim($value2[1]));
                    $hcid_configuration['iscan']=$value3[0];
                }
                break;
            case 'lm':
                $value=explode(';',trim($data[1]));
                $hcid_configuration['lm']=$value[0];
                break;
            case 'lp':
                $value=explode(';',trim($data[1]));
                // Should check for several values that share line.
                $value2=explode(',',trim($value[0]));
                foreach($value2 as $option)
                {
                    switch ($option)
                    {
                        case 'none':
                            $hcid_configuration['lp']['none']='true';
                            break;
                        case 'rswitch':
                            $hcid_configuration['lp']['rswitch']='rswitch';
                            break;
                        case 'hold':
                            $hcid_configuration['lp']['hold']='hold';
                            break;
                        case 'sniff':
                            $hcid_configuration['lp']['sniff']='sniff';
                            break;
                        case 'park':
                            $hcid_configuration['lp']['park']='park';
                            break;
                    }
                }
                break;
        }
        unset ($value);
        unset ($value2);
        unset ($value3);
    }
    return $hcid_configuration;
}
?>