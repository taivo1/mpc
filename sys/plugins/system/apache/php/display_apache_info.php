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
include_once $base_plugin.'php/policies.php';

//-------------------------------------------------------------------------------------------

function make_apache ()
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $section;
    global $plugin;
    global $url_plugin;
    
    $list ='
    <div class="title">Apache Manager</div>
    <div id="output"></div><div id="debug"></div>
    ';

    $list .= make_certs_panel ();

    $list .= '<div id="http_ssl_config">';
    $list .= make_http_panel ();
    $list .='</div><!-- ID: HTTP_SSL_CONFIG -->';

    $list .= '
    <div class="right_align">
    <input class="bsave" onclick="restart_apache()" type="button" value="restart">
    </div>
    ';

    $list .='
    <script>
      var php_section=\''.$section.'\';
      var php_plugin=\''.$plugin.'\';
      var php_url_plugin=\''.$url_plugin.'\';';

    if ( is_ssl_enabled ($paths) )
    {
        $list .= 'var http_ssl_config=true;';
    }
    else
    {
        $list .= 'var http_ssl_config=false;';
    }

    $list .='
    </script>
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

function is_ssl_enabled($paths)
/* ------------------------------------------------------------------------ */
{
    exec ("sudo ls ".$paths['ap2_ssl_link'], $ls);
    return ($ls[0] == $paths['ap2_ssl_link']);
}
/* ------------------------------------------------------------------------ */

function exists_cert ($filepath)
/* ------------------------------------------------------------------------ */
{
    exec ("sudo ls ".$filepath, $return);
    return ( $return[0] == $filepath );
}
/* ------------------------------------------------------------------------ */

function make_certs_panel ()
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $list ='
    <div class="title2">HTTPS certificates</div>
    <div class="plugin_content">

    <div id="certs_panel">
    <table class="cert_table">
      <tbody>
      <tr>
        <td>Server certificate</td>
        <td id="upload_server_cert"></td>
        <td id="upload_server_cert_garbage" style="visibility:hidden"></td>
      </tr>
      <tr>
        <td>Server key</td>
        <td id="upload_server_key"></td>
        <td id="upload_server_key_garbage" style="visibility:hidden"></td>
      </tr>
      </tbody>
    </table>

    <div id="key_password_panel" class="nl">
    The private key has a password.<br>
    You have to input it so that Apache can use it.
    <form id="cert_pass_form" name="cert_pass_form">
    <table>
      <tbody>
      <tr>
        <td>Password:</td>
        <td><input type="password" name="cert_pass" id="cert_pass" /></td>
      </tr>
      <tr>
          <td></td>
          <td><div id="cert_pass_ms_cte"></div></td>
      </tr>
      <tr>
        <td>Confirm password:</td>
        <td><input type="password" name="cnf_cert_pass" id="cnf_cert_pass" /></td>
      </tr>
      <tr>
          <td></td>
          <td><div id="cnf_cert_pass_ms_cte"></div></td>
      </tr>
      <tr>
        <td></td>
        <td style="text-align: right">
          <input onclick="cancel_cert_pass()" type="button" value="cancel">
          <input onclick="save_cert_pass()" type="button" value="ok">
        </td>
      </tr>
    </table>

    </form>
    </div> <!-- ID: KEY_PASSWORD_PANEL -->
    </div> <!-- ID: CERTS_PANEL -->
    <div id="delete_btn_panel">
    <input id="delete_btn" name="delete_btn" type="button" value="Delete files"';

    $exists_server_cert = exists_cert ($paths['server_cert']);
    $exists_server_key = exists_cert ($paths['server_key']);

    if ($exists_server_cert || $exists_server_key)
    {
        $list .= '/>';
    }
    else
    {
        $list .= 'class="disabled" disabled />';
    }

    $list .='
    </div><!-- ID: DELETE_BTN_PANEL -->
    ';

    $list .= make_script_vars($exists_server_cert, $exists_server_key);

    $list .='
    </div><!-- ID: PLUGIN_CONTENT -->
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_script_vars($exists_server_cert, $exists_server_key)
/* ------------------------------------------------------------------------ */
{
    $list = '<script>';

    if ($exists_server_cert)
    {
        $list .="exists_server_cert=true;\n";
    }
    else
    {
        $list .="exists_server_cert=false;\n";
    }

    if ($exists_server_key)
    {
        $list .="exists_server_key=true;\n";
    }
    else
    {
        $list .="exists_server_key=false;\n";
    }

    $list .='
    </script>
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_http_panel ()
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $list = '
    <div class="title2">HTTP security configuration</div>
    <div class="plugin_content">

    <div id="global_setting_title" class="pl1">Default setting</div>
    <div id="global_setting" class="nl">
    '.make_global_http_panel().'
    </div>

    <div id="individual_settings_title" class="pl">Individual settings</div>
    <div id="individual_settings" class="nl">
    '.make_individual_http_panel().'
    </div><!-- ID: PLUGIN_CONTENT -->
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_global_http_panel()
/* ------------------------------------------------------------------------ */
{
    $list = '
      <table class="table_header">
        <tr>
          <td class="directory">Directory</td>
          <td class="http">HTTP</td>
          <td class="https">HTTPS</td>
        </tr>
      </table>
    ';

    $list .= make_dir_row('');

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_individual_http_panel()
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $list = '
      <table class="table_header">
        <tr>
          <td class="directory">Directory</td>
          <td class="http">HTTP</td>
          <td class="https">HTTPS</td>
        </tr>
      </table>

      <div id="list_directories">
    ';

    $www_dir = opendir($paths['ap2_root']);

    while ($item = readdir($www_dir))
    {
        if ( is_dir($paths['ap2_root'].$item) && $item != '..' && $item != '.')
        {
            $list .= make_dir_row($item);
        }
    }

    closedir($www_dir);

    $list .= '
      </div><!-- ID: LIST_DIRECTORIES -->
    ';

    return $list;
}
/* ------------------------------------------------------------------------ */

function make_dir_row($item)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $url_plugin;

    $http_radio = policy($paths, $item);

    $list = '
       <table id="dir_'.$item.'" class="dir_entry radius_border">
       <tr>
          <td class="directory">/'.$item.'</td>
    ';

    if ($http_radio == "global")
    {
        $img = $url_plugin.'images/default.png';
        $list .= '<td class="global"><img src="'.$img.'" /></td>';
    }
    else
    {
        if ($http_radio == "http" || $http_radio == "both")
        {
            $img = $url_plugin.'images/check.png';
        }
        else
        {
            $img = $url_plugin.'images/cross.png';
        }
        $list .= '<td class="http"><img src="'.$img.'" /></td>';


        if ($http_radio == "https" || $http_radio == "both")
        {
            $img = $url_plugin.'images/check.png';
        }
        else
        {
            $img = $url_plugin.'images/cross.png';
        }
        $list .= '<td class="https"><img src="'.$img.'" /></td>';
    }


    $list .= '
          <td><input id="edit_btn_'.$item.'" name="edit_btn_'.$item.'" type="button"
                     onclick="edit_dir(\''.$item.'\')" value="edit">
      </tr>
      </table>
    ';
   
    return $list;
}
/* ------------------------------------------------------------------------ */

?>
