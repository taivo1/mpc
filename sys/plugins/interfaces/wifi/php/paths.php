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

//-- PATHS ----------------------------------------------------------------------------------

$paths['radius_conf_dir'] = '/etc/manager_system/radius/';
$paths['hostapd_conf_dir'] = '/etc/manager_system/hostapd/';

$paths['security'] = $paths['hostapd_conf_dir'].'security';
$paths['clients'] = $paths['radius_conf_dir'].'clients';
$paths['auth_servers'] = $paths['radius_conf_dir'].'auth_servers';

$paths['hostapd_skeleton'] = $base_plugin.'data/hostapd_skeleton';
$paths['hostapd'] = '/etc/hostapd/hostapd'; // + _IFACE.conf
$paths['interfaces'] = '/etc/network/interfaces';

//-------------------------------------------------------------------------------------------

?>