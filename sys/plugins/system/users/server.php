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
include_once $API_core.'form_fields_check.php';
include_once $base_plugin.'php/paths.php';


/* ------------------------------------------------------------------------ */
if ( $_POST['type']=="nv" )
{

    if ( isset($_POST['form_fields']) )
    {
        $post_data=jsondecode ($_POST['form_fields']);
    }

    switch ($_POST['action'])
    {
        // Users panel
        case 'create_user':
            create_user ($post_data);
            break;

        case 'update_user':
            update_user ($_POST['username'], $post_data);
            break;

        case 'delete_user':
            delete_user ($_POST['username']);
            break;
    }

    /* DEBUG OUTPUT */
    //response_additem ("html", "<pre>".print_r($post_data,true)."</pre>", "debug");
    response_return ();
}
/* ------------------------------------------------------------------------ */

function execute ($cmd)
/* ------------------------------------------------------------------------ */
{
    global $output;
    exec ("sudo ".$cmd, $return);
    $output = array_merge ($output, $return); // DEBUG LOG
}
/* ------------------------------------------------------------------------ */

function error_msg ($msg)
/* ------------------------------------------------------------------------ */
{
    response_additem ("script", "alert('".$msg."')");
}
/* ------------------------------------------------------------------------ */

function delete_user ($username)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $base_plugin;

    include $paths['users'];
    $users = $authorized_users;

    if ( isset($users[$username]) )
    {
       unset($users[$username]);
       include_once $base_plugin.'php/write_users.php';
       write_users ($paths['users'], $users);

       response_additem ("script", "$('#user_".$username."').remove()");
       response_additem ("html", "" ,"output");
    } else {
       error_msg ('User does not exist.');
    }
}
/* ------------------------------------------------------------------------ */

function is_user_form_valid ($username, $post_data, $is_new)
/* ------------------------------------------------------------------------ */
{    
    if ( $post_data['password'] != $post_data['cnf_password'] )
    {
        response_additem ("script", "alert('Password missmatch')");
        return false;
    }
    else
    {
        // Mandatory check should be the last one for coherency with js.
        // But you can priorize a check alert checking it last.
        $fields_check_types = Array (
          'username'  => Array ('ms_alnum','ms_mandatory'),
          'password' => Array ('ms_mandatory'),
          'cnf_password' => Array ('ms_mandatory')
        );

        if ( !$is_new )
        {
            unset ($fields_check_types['username']);
            foreach (array_keys($fields_check_types) as $id)
            {
                $fileds_ms_ctes[$id] = "$username_$id";
            }
        }
        return are_form_fields_valid ($post_data, $fields_check_types,
                                      $fileds_ms_ctes);
    }    
}
/* ------------------------------------------------------------------------ */

function add_user ($username, $post_data, $users, $is_new)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $base_plugin;

    if ( is_user_form_valid ($username, $post_data, $is_new) )
    {
        $users[$username] = crypt($post_data['password']);
        include_once $base_plugin.'php/write_users.php';
        write_users ($paths['users'], $users);

        response_additem ("html", "" ,"output");
        include_once $base_plugin.'php/display_users.php';
        if ( $is_new ) {
            response_additem ("script", "cancel_new()");
            response_additem ("append", '<table id="user_'.$username.'" '.
                              'class="user radius_border">'.
                              make_user_info_row ($username, $users[$username],
                              false).'</table>' ,"list_users");
        } else {
            response_additem ("script", "cancel_edit('$username')");
            /*response_additem ("html", make_user_info_row ($username, $users[$username],
                              false), "user_".$username);*/
        }
        
    }
}
/* ------------------------------------------------------------------------ */

function create_user ($post_data)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    $username = $post_data['username'];
    include $paths['users'];

    if ( isset($authorized_users[$username]) )
    {
        error_msg ('User already exists.');
    }
    else
    {
        add_user ($username, $post_data, $authorized_users, true);
    }
}
/* ------------------------------------------------------------------------ */

function update_user ($username, $post_data)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    include $paths['users'];

    if ( !isset($authorized_users[$username]) )
    {
        error_msg ('User does not exist.');
    }
    else
    {
        add_user ($username, $post_data, $authorized_users, false);
    }

/* ------------------------------------------------------------------------ */
}

?>