<?php
/*
 * Copyright (C) 2008 Libelium Comunicaciones Distribuidas S.L.
 *
 * This file is part of N-vio.
 * N-vio will be released as free software; until then you cannot redistribute it
 * without express permission by libelium. 
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * 
 *
 * Version 1.0 
 *  Author: Octavio Benedi Sanchez
 */

function login($user,$pass)
{
    if (file_exists('core/globals/users.php'))
    {
        include 'core/globals/users.php';
    }
    else
    {
        echo 'Manager system integrity damaged.';
        exit();
    }
    if (isset($authorized_users[$user]))
    {
        if ((crypt($pass, $authorized_users[$user]) == $authorized_users[$user]))
        {
            session_register('logged_user');
            session_start();
            $_SESSION['logged_user']=$user;
            session_write_close();
            // mount read-write
            if (file_exists('/usr/local/sbin/remountrw'))
 	        exec ('sudo /usr/local/sbin/remountrw');
            header('Location:index.php');
            flush();
            exit();
        }
    }
}
login($_POST['username'],$_POST['passwd']);
$main_title='Meshlium Manager System';
$html_title='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
        <html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <style>
                @import "core/css/reset.css";
                @import "core/css/login.css";
            </style>
            <title>'.$main_title.'</title>
        </head>
        <body>';
echo $html_title;
echo '
<div id="main_div">
    <div class="login_menu">
        <form method="post" action="#" name="login">
            <div id="login_form">
                <div id="login_username">
                    <input name="username">
                </div>
                <div  id="login_password">
                    <input name="passwd" type="password">
                </div>
                <button id="login_button" type="submit"\>
            </div>
        </form>
    </div>
</div>';


include_once 'core/structure/footer.php';
echo $html;
?>
