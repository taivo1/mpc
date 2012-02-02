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

include_once $API_core.'json_api.php';
include_once $API_core.'complex_ajax_return_functions.php';

include_once $base_plugin.'php/paths.php';


$output = array(); // -- DEBUG VARIABLE: EXECUTED PROGRAMS OUTPUTS --

/* ------------------------------------------------------------------------ */
if ( $_POST['type']=="nv" )
{
    if ( isset($_POST['form_fields']) )
    {
        $post_data=jsondecode ($_POST['form_fields']);
    }

    switch ($_POST['action'])
    {
        case 'get_dir_info':
            get_dir_info($_POST['directory']);
            break;
        case 'set_global':
            set_global($_POST['directory']);
            break;
        case 'update_dir_config':
            update_dir_config ($_POST['directory'], $post_data);
            break;
        case 'restart_apache':
            response_additem ("html", "", "output");
            restart_apache2 ();
            break;

        /* Certificates panel */
        case 'delete_cert_files':
            delete_cert_files ();
            break;
        case 'save_cert_pass':
            save_cert_pass ($post_data);
            break;
    }

    /* DEBUG OUTPUT */
    //response_additem ("html", "<pre>".print_r($post_data,true)."</pre>", "debug");
    //response_additem ("html", "<pre>".print_r($output,true)."</pre>", "debug");
    response_return ();
}
/* ------------------------------------------------------------------------ */

function execute ($cmd)
/* ------------------------------------------------------------------------ */
{
    global $output;
    exec ("sudo ".$cmd, $return, $code);
    // DEBUG LOG
    $output[] = "# ".$cmd." --> ".$code;
    $output = array_merge ($output, $return);
}
/* ------------------------------------------------------------------------ */

function save_msg ($msg)
/* ------------------------------------------------------------------------ */
{
    //response_additem ("html", "<fieldset><h2>'.$msg.'</h2></fieldset>" ,"output");
    response_additem ("script", "alert('".$msg."')");
}
/* ------------------------------------------------------------------------ */

function error_msg ($msg)
/* ------------------------------------------------------------------------ */
{
    //response_additem ("html", "<fieldset><h2>'.$msg.'</h2></fieldset>" ,"output");
    response_additem ("script", "alert('".$msg."')");
}
/* ------------------------------------------------------------------------ */

function delete_policies_files()
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $www_dir = opendir($paths['ap2_root']);

    while ($item = readdir($www_dir))
    {
        if ( is_dir($paths['ap2_root'].$item) && $item != '..')
        {
            if ($item == '.') $item = '';
            execute ("rm -f ".$paths['ap2_policies'].$item);
        }
    }

    closedir($www_dir);
}
/* ------------------------------------------------------------------------ */

function delete_cert_files ()
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $base_plugin;

    delete_policies_files();
    //execute ('rm '.$paths['ap2_ssl_link']);
    execute ('a2dissite default-ssl');
    execute ('rm '.$paths['server_cert']);
    execute ('rm '.$paths['server_key']);

    include_once $base_plugin.'php/display_apache_info.php';

    response_additem ("html", make_global_http_panel(), "global_setting");
    response_additem ("html", make_individual_http_panel(), "individual_settings");

    response_additem ("script", "cert_files_deleted()");
}
/* ------------------------------------------------------------------------ */

function is_cert_pass_valid ($pass)
/* ------------------------------------------------------------------------ */
{
    $pass_len = strlen($pass);
    return ($pass_len >= 4 && $pass_len <= 8191);
}
/* ------------------------------------------------------------------------ */

function is_password_valid ($password, $uploadfile)
/* ------------------------------------------------------------------------ */
{
   exec ("sudo /etc/ssl/sh/mod_key.sh $uploadfile $password", $ret);
   return ($ret[0] == "VALID_FILE");
}
/* ------------------------------------------------------------------------ */

function is_cert_pass_form_valid ($post_data, $uploadfile)
/* ------------------------------------------------------------------------ */
{
    $is_valid = false;

    if ( !is_cert_pass_valid ($post_data['cert_pass']) )
    {
        response_additem ("script", "set_alert('cert_pass')");
    }
    elseif ( $post_data['cert_pass'] != $post_data['cnf_cert_pass'] )
    {
        error_msg ('Password missmatch.');
    }
    elseif ( !is_password_valid ($post_data['cert_pass'], $uploadfile) )
    {
        error_msg ('Private key file does not have that password.');
    }
    else
    {
        $is_valid = true;
    }

    return $is_valid;
}
/* ------------------------------------------------------------------------ */

function check_certs_files($key_pass)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    include_once $base_plugin.'php/certs.php';

    if ( exists_certificates ($paths) )
    {
        if ( are_cert_and_key_valid($paths, $key_pass) )
        {
            execute ('a2ensite default-ssl');
            restart_apache2 ();
            response_additem ("script", "$('#http_ssl_config').show()");
        }
        else
        {
            response_additem ("script", "alert('Certificate and private key mismatch.')");
        }
    }
}
/* ------------------------------------------------------------------------ */

function save_cert_pass ($post_data)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $base_plugin;

    $uploadfile = $base_plugin.'data/server_key';
    if ( is_cert_pass_form_valid ($post_data, $uploadfile) )
    {
        exec("sudo mv ".$uploadfile." ".$paths['server_key']);
        exec("sudo chown root:root ".$paths['server_key']);
        
        $sed = 'sed \'/echo/c\\echo "'.
               $post_data['cert_pass'].'"\' '.$paths['server_pass']." > ".
               $base_plugin."data/temp_passphrase";

        execute ($sed);
        execute ("mv ".$base_plugin."data/temp_passphrase ".$paths['server_pass']);
        execute ("chown root:root ".$paths['server_pass']);

        response_additem ("script", "reset_cert_pass_form()");
        check_certs_files ($post_data['cert_pass']);
    }
    response_additem ("html", "" ,"output");
}
/* ------------------------------------------------------------------------ */


function get_dir_info($dir)
/* ------------------------------------------------------------------------ */
{
     global $paths;

     include_once $base_plugin.'php/policies.php';

     $http_radio = policy($paths, $dir);

     $row = '
        <div id="edit_panel_'.$dir.'">
        <form id="edit_dir_form" name="edit_dir_form">
        <table class="edit_entry radius_border">
        <tr>
           <td class="directory"></td>
           <td class="http">
           <input type="checkbox" id="'.$dir.'_http" name="http"
     ';

     if ($http_radio == 'http' || $http_radio == 'both')
     {
         $row .= ' checked  />';
     }
     else
     {
         $row .= ' />';
     }

     $row .= '
           </td>
           <td class="https">
           <input type="checkbox" id="'.$dir.'_https" name="https"
     ';

     if ($http_radio == 'https' || $http_radio == 'both')
     {
         $row .= ' checked />';
     }
     else
     {
         $row .= ' />';
     }

     $row .= ' 
           </td>
           <td class="buttons">
               <input type="button" onclick="update_dir(\''.$dir.'\')" value="update">
               <input type="button" onclick="cancel_edit(\''.$dir.'\')" value="cancel">
        ';

    if ($dir != '')
    {
        $row .= '
             <input type="button" onclick="set_global(\''.$dir.'\')" value="set default">
        ';
    }

    $row .= '
           </td>
        </tr>
        </table>
        </form>
        </div>
        ';
    
    response_additem ("after", $row, "dir_".$dir);
}
/* ------------------------------------------------------------------------ */

function is_dir_form_valid ($post_data)
/* ------------------------------------------------------------------------ */
{
    if ( isset($post_data['http']) || isset($post_data['https']) )
    {
        return true;
    }
    else
    {
        $msg = 'At least one option must be selected.';
        response_additem ("script", "alert('".$msg."')");
        return false;
    }
}
/* ------------------------------------------------------------------------ */

function update_dir_config ($dir, $post_data)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $url_plugin;

    if ( is_dir_form_valid ($post_data) )
    {
        $img_check = $url_plugin.'images/check.png';
        $img_cross = $url_plugin.'images/cross.png';
        $filepath = $paths['ap2_policies'].$dir;

        if ( isset($post_data['http']) && isset($post_data['https']) )
        {
            write_policy_both ($dir, $filepath);
            $script = "show_http_update('$dir','$img_check','$img_check')";
            response_additem ("script", $script);
        }
        else
        {
            write_policy_one ($dir, $filepath, $post_data);
            if ( isset($post_data['http']) )
            {
                $script = "show_http_update('$dir','$img_check','$img_cross')";
                response_additem ("script", $script);
            }
            else
            {
                $script = "show_http_update('$dir','$img_cross','$img_check')";
                response_additem ("script", $script);
            }
        }
    }

    /*
    $href = "window.location.href = 'http";
    if ($post_data['http_radio_manager_system'] == "https") $href .= 's';
    $href .= "://".$_SERVER["SERVER_NAME"];
    $href .= $_SERVER["REQUEST_URI"];
    $href .= "?section=".$section."&plugin=".$plugin."';";
    */

    restart_apache2 ();
    //response_additem ("script", $href);
    response_additem ("html", "", "output");
}
/* ------------------------------------------------------------------------ */

function set_global($dir)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $url_plugin;

    $filepath = $paths['ap2_policies'].$dir;
    execute ("rm -f ".$filepath);

    $img = $url_plugin.'images/default.png';
    $script = "show_global_update('$dir','$img')";
    response_additem ("script", $script);

    restart_apache2 ();
    response_additem ("html", "", "output");
}
/* ------------------------------------------------------------------------ */

function write_policy_one ($item, $policy_path, $post_data)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    if ( isset($post_data['http']) )
    {
        $http = 'http';
        $port = '443';
    }
    else
    {
        $http = 'https';
        $port = '80';
    }

    $sed = "bash -c \"sed -e 's/__dir__/".$item."/' -e 's/__port__/".$port."/'".
           " -e 's/__http__/".$http."/' \\\n".$paths['policy_skeleton'].
           "\\\n > ".$policy_path.'"';

    execute ($sed);
    execute ("chown root:root ".$policy_path);
}
/* ------------------------------------------------------------------------ */

function write_policy_both ($item, $policy_path)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    if ($item != '')
    {
        $sed = "bash -c \"sed -e 's/On/Off/' -e 's/__dir__/".$item."/' ".
               "-e '/__port__/d' -e '/__http__/d' -e '/RewriteBase/d' \\\n".
               $paths['policy_skeleton']."\\\n > ".$policy_path.'"';

        execute ($sed);
        execute ("chown root:root ".$policy_path);
    }
    else
    {
        execute ("rm -f ".$policy_path);
    }
}
/* ------------------------------------------------------------------------ */

function restart_apache2 ()
/* ------------------------------------------------------------------------ */
{
    execute ('sudo /etc/init.d/apache2 force-reload');
}
/* ------------------------------------------------------------------------ */

?>