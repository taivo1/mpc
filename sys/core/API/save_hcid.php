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
function save_hcid($hci_config,$save_file='')
{
    global $base_plugin;
    
    if ($save_file=='')
    {
        $save_file=$base_plugin.'data/hcid.conf';
    }
    $fp=fopen($save_file,'w');
    fwrite($fp,"options {\n");
    if($hci_config['autoinit'] == 'on')
    {
        fwrite($fp,"\tautoinit yes;\n");
    }
    if(!empty($hci_config['security']))
    {
        fwrite($fp,"\tsecurity ".$hci_config['security'].";\n");
    }
    if(!empty($hci_config['pairing']))
    {
        fwrite($fp,"\tpairing ".$hci_config['pairing'].";\n");
    }
    if(!empty($hci_config['passkey']))
    {
        fwrite($fp,"\tpasskey ".$hci_config['passkey'].";\n");
    }
    fwrite($fp,"}\n");
    fwrite($fp,"device {\n");
    if(!empty($hci_config['name']))
    {
        fwrite($fp,"\tname ".$hci_config['name'].";\n");
    }

    fwrite($fp,"\tclass 0x000100;\n");
    
    if($hci_config['iscan'] == 'on')
    {
        fwrite($fp,"\tiscan enable;\n");
    }
    if($hci_config['pscan'] == 'on')
    {
        fwrite($fp,"\tpscan enable;\n");
    }

    if(!empty($hci_config['lm']))
    {
        fwrite($fp,"\tlm ".$hci_config['lm'].";\n");
    }

    $lp='';

    if($hci_config['rswitch'] == 'on')
    {
        $lp.='rswitch';
    }
    if($hci_config['hold'] == 'on')
    {
        if ($lp!='')
        {
            $lp.=',hold';
        }
        else
        {
            $lp.='hold';
        }

    }
    if($hci_config['sniff'] == 'on')
    {
        if ($lp!='')
        {
            $lp.=',sniff';
        }
        else
        {
            $lp.='sniff';
        }
    }
    if($hci_config['park'] == 'on')
    {
        if ($lp!='')
        {
            $lp.=',park';
        }
        else
        {
            $lp.='park';
        }
    }
    if ($lp=='')
    {
        $lp='none';
    }
    fwrite($fp,"\tlp ".$lp.";\n");
    fwrite($fp,"}\n");
    fclose($fp);
}
?>