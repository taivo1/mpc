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

// Doctype and start html tag

$html_title='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
        <html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">             
            <style>
                @import "core/css/reset.css";
                @import "core/css/main.css";';
// Load user defined css
if(!empty($_plugin_css))
{
    foreach ($_plugin_css as $css_item)
    {
        $html_title.='
                    @import "plugins/'.$section.'/'.$plugin.'/css/'.$css_item.'";';
    }
}

$html_title.="
            </style>
";



// Check for plugin title

$html_title.='
            <title>'.$_main_title.'</title>
';

// End header and start body tag

$html_title.='
        </head>
        <body>';

// Global menu

$html_title.='<div id="main_content">';
?>
