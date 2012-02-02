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

$paths['hostapd'] = '/etc/hostapd/hostapd'; // + _IFACE.conf
$paths['hostapd_conf_dir'] = '/etc/manager_system/hostapd/';
$paths['security'] = $paths['hostapd_conf_dir'].'security';
;
$paths['radius_conf_dir'] = '/etc/manager_system/radius/';
$paths['users'] = $paths['radius_conf_dir'].'users';
$paths['auth_servers'] = $paths['radius_conf_dir'].'auth_servers';
$paths['acct_servers'] = $paths['radius_conf_dir'].'acct_servers';
$paths['clients'] = $paths['radius_conf_dir'].'clients';

$paths['fr_ath_skeleton'] = $base_plugin.'data/fr_ath_skeleton';
$paths['freeradius'] = '/etc/freeradius/';

$paths['fr_users'] = $paths['freeradius'].'users';
$paths['fr_clients'] = $paths['freeradius'].'clients.conf';
$paths['fr_eap'] = $paths['freeradius'].'eap.conf';
$paths['fr_acct_listen'] = $paths['freeradius'].'acct_listen.conf';

$paths['cacert'] = $paths['freeradius'].'certs/ca.pem';
$paths['server_cert'] = $paths['freeradius'].'certs/server.pem';
$paths['server_key'] = $paths['freeradius'].'certs/server.key';


//-------------------------------------------------------------------------------------------

?>