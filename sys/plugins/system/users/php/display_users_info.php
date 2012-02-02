<?php
/*
 *  Copyright (C) 2009 Libelium Comunicaciones Distribuidas S.L.
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
 *  Author: Daniel Larraz
 */

include_once $base_plugin.'php/paths.php';
include_once $base_plugin.'php/display_users.php';

function make_users_panel ()
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $section;
    global $plugin;
    global $url_plugin;

    $list ='
    <div class="title">Users Manager</div>
    <div id="output"></div><div id="debug"></div>
    <div class="plugin_content">
    <table class="table_header">
      <tr><td>Name</td></tr>
    </table>
    <div id="list_users">
    ';

    include $paths['users'];

    foreach ( array_keys($authorized_users) as $name)
    {
        $list .= '<table id="user_'.$name.'" class="user radius_border">';
        $list .= make_user_info_row($name);
        $list .= '</table>';
    }

    $list .='
    </div><!-- ID: LIST_USERS -->
    <input type="button" onclick="show_new_user_form()" value="new" />
    ';

    $list .= make_user_form_panel ();

    $list .=  '</div><!-- ID: PLUGIN_CONTENT -->';

    $list .='
    <script>
      var php_section=\''.$section.'\';
      var php_plugin=\''.$plugin.'\';
      var php_url_plugin=\''.$url_plugin.'\';
    </script>
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_user_form_panel ()
/* ------------------------------------------------------------------------ */
{
   $list = '
    <div id="user_form_panel">
    <form id="user_form" name="user_form">
    <div id="user_form_title" class="pl">New user</div>
    <div class="nl">

     <table>
      <tbody>
      <tr>
       <td>Name:</td>
       <td>
         <input type="text" class="ms_mandatory ms_alnum" name="username" id="username" />
       </td>
      </tr>
      <tr>
          <td></td>
          <td><div id="username_ms_cte"></div></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input type="password" class="ms_mandatory" name="password" id="password" /></td>
      </tr>
      <tr>
          <td></td>
          <td><div id="password_ms_cte"></div></td>
      </tr>
      <tr>
        <td>Confirm password:</td>
        <td><input type="password" class="ms_mandatory" name="cnf_password" id="cnf_password" /></td>
      </tr>
      <tr>
          <td></td>
          <td><div id="cnf_password_ms_cte"></div></td>
      </tr>
      <tr>
        <td></td>
        <td class="right">
          <input id="user_form_cancel_btn" onclick="cancel_new()"
                 type="button" value="cancel">
          <input id="user_form_create_btn" onclick="create_user()"
                 type="button" value="create">
        </td>
      </tr>
      </tbody>
     </table>

    </div><!-- ID: <Anonymous> -->
    </form>

    </div><!-- ID: USER_FORM_PANEL -->
   ';

   return $list;
}
/* ------------------------------------------------------------------------ */

?>
