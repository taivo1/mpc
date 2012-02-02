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


// This code instead will use current crontab configuration as source.
exec('sudo crontab -l',$crontab_file);
foreach($crontab_file as $line)
{
    $crontab.=trim($line)."\n";
}
$crontab_test=trim($crontab);
if (empty($crontab_test))
{
    $crontab="# m h dom mon dow command\n";
}
$html='<div class="title">Cron</div>
        <div id="plugin_content">
        <form name="crontab_editor" id="crontab_editor" onsubmit="return false;" >
            <table><tbody><tr><td>
            <label>Edit crontab configuration file</label>
            </td></tr><tr><td>
            <textarea id="crontab" name="crontab" class="editor">'.$crontab.'</textarea>
            </td></tr></tbody></table>
        </form>
        <div class="right_align">
         <input type="button" class="bsave" value="Save and apply" onclick="complex_ajax_call(\'crontab_editor\',\'output\',\''.$section.'\',\''.$plugin.'\')" />
        </div>
       </div>';
?>
