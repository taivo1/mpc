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
function make_interface()
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;
	$list='<input type="hidden" value="'.$url_plugin.'" id="url_plugin" />
			<table style="text-align: left;" border="0" cellpadding="2" cellspacing="2">
			  <tbody>
			  	<tr>
			      <td colspan=3 rowspan=1>Make backup</td>
		        </tr>
		      	<tr>
			      <td class="nl">Name</td>
				  </td>
				  <td>
				  	<input type="text" id="backup_name" name="backup_name">
				  </td>
			      <td><input type="button" value="Save" onclick="execute(\'make_backup\',$(\'#backup_name\').val(),\''.$section.'\',\''.$plugin.'\')"></td>
			    </tr>
                <tr>
                    <td></td>
                    <td colspan="2"><div id="backup_name_ms_cte"></div></td>
                </tr>
			    <tr>
			      <td colspan=3 rowspan=1 class="ss">Manage configuration files</td>
		        </tr>
			    <tr>
			      <td class="nl">Upload a backup file</td>
                  <td></td>
			      <td>
			      	<div>
			      		<input id="upload_file" name="upload_file" type="submit" value="Upload File" class="pointer"/>
					</div>
			      </td>
			    </tr>
			    <tr>
			      <td class="nl">Get a backup file from an URL</td>
			      <td>
			      	<input  type="text" id="url_download" name="url_download">
			      </td>
			      <td>
			      	<input type="button" value="Upload" onclick="execute(\'get_backup\',$(\'#url_download\').val(),\''.$section.'\',\''.$plugin.'\')">
			      </td>
			    </tr>
                <tr>
                    <td></td>
                    <td colspan="2"><div id="url_download_ms_cte"></div></td>
                </tr>
			    <tr>
			    	<td class="nl">Download backup file</td>
			    	<td>';
	$list.=choose_dir($base_plugin.'data/','make_link');
	$list.='</td>
			    	<td><input type="button" value="Get link"  onclick="execute(\'make_link_backup\',$(\'#make_link\').val(),\''.$section.'\',\''.$plugin.'\')"></td>
			    </tr>
			    <tr>
			    	<td colspan="3" rowspan="1">
			    		<div id="give_url"></div>
			    	</td>
			    </tr>
                <tr>
			      <td colspan=3 rowspan=1 class="ss">Backup actions</td>
		        </tr>
			    <tr>
			      <td class="nl">Choose backup</td>
			      <td>';
	$list.=choose_dir($base_plugin.'data/','backup_actions');
	$list.='</td>
			      <td><input type="button" value="Restore" onclick="execute(\'restore_backup\',$(\'#backup_actions\').val(),\''.$section.'\',\''.$plugin.'\')"></td>
                  <td><input type="button" value="Delete" onclick="execute(\'delete_backup\',$(\'#backup_actions\').val(),\''.$section.'\',\''.$plugin.'\')"></td>
			    </tr>
			  </tbody>
			</table>';
    
    return $list;
}
?>