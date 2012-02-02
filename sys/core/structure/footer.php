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
// Just close body and html tags.
$html='<br clear="both" /></div>';
$html.='<script src="core/javascript/core_javascript.js" type="text/javascript"></script>';
// Load plugin defined javascript:
if(!empty($_plugin_javascript))
{
    foreach ($_plugin_javascript as $javascript_item)
    {
         //<script type="text/javascript" src="/userprefs.js"></script>

        $html.='
                <script type="text/javascript" src="plugins/'.$section.'/'.$plugin.'/javascript/'.$javascript_item.'"></script>
    ';
    }
}
$html.='</body></html>';
?>
