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
// Check for logout
if ($_GET['logout']=='true')
{
    session_start();
	unset($_SESSION['logged_user']);
	session_write_close();
    if (file_exists('/usr/local/sbin/remountro'))
        exec ('sudo /usr/local/sbin/remountro');
    header('Location: login.php');
    flush();
    exit();
}
// First is important to know what whe should display. If main index, a section
// index or a plugin screen.
// To archieve that, parse the $_GET is needed.
$initial_index_page=false;
// Default title for pages
$_main_title='Meshlium Manager System';
$base_dir=dirname($_SERVER['SCRIPT_FILENAME']).'/';
include_once $base_dir."core/functions/load_plugin_common_vars.php";
include_once $base_dir."core/functions/check_functions.php";

$plugin_display_content='core/functions/load_plugin.php';
$list_sections_display_content='core/functions/list_sections.php';
$list_plugins_display_content='core/functions/list_plugins.php';


// Load plugin display content. Variables modified by plugin will be set here.
// Plugin may redefine some variables used on the header. That is why plugin is
// executed before generating the body.
include_once $plugin_display_content;

//////////////////
// HEADER
//////////////////

// Send to the client the header.
include_once 'core/structure/header.php';
echo $html_title;
flush();
unset ($html_title);

//////////////////
// TOP MENU
//////////////////

// Top logo and menu
include_once 'core/structure/top_menu.php';
echo $core_top_menu;
flush();
unset ($core_top_menu);

//////////////////
// SECTIONS LIST
//////////////////

// Load the list of sections
include_once $list_sections_display_content;
echo $core_sections_menu;
flush();
unset ($core_sections_menu);


//////////////////
// PLUGINS LIST
//////////////////

if(empty($section))
{
   $initial_index_page=true;
}

// Load the list of plugins of the active section.
include_once $list_plugins_display_content;
echo $core_plugins_menu;
flush();
unset ($core_plugins_menu);


//////////////////
// PLUGIN
//////////////////
if ((is_section($base_section))&&(is_plugin($base_plugin)))
{
    $plugin_main_div='<div id="plugin_main_div">';
    $plugin_main_div_end='</div>';
    // plugin main display content
    echo $plugin_main_div;
    echo $html;
    flush();
    echo $plugin_main_div_end;
    unset ($html);
}
elseif(!empty($section))
{
    // Load default plugin image or leave void.
    echo '<div id="not_selected_plugin">
            <table cellspacing=10><tbody><tr>
                <td><img alt="Information icon" src="'.$base_url.'core/images/icono-i.png" /></td>
                <td>Click on a plugin to load</td>
            </tr></tbody></table></div>';
}

//////////////////
// FOOTER
//////////////////

// Send to the client the footer.
include_once 'core/structure/footer.php';
echo $html;
flush();
unset ($html);

?>
