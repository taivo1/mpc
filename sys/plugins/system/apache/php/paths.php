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

$paths['policy_skeleton'] = $base_plugin.'data/policy_skeleton';

$paths['apache2'] = '/etc/apache2/';
$paths['ap2_default'] = $paths['apache2'].'sites-available/default';
$paths['ap2_ssl'] = $paths['apache2'].'sites-available/default-ssl';
$paths['ap2_ssl_link'] = $paths['apache2'].'sites-enabled/default-ssl';
$paths['ap2_policies'] = $paths['apache2'].'policies/www_';
$paths['ap2_root'] = '/var/www/';
$paths['ap2_httpd'] = $paths['apache2'].'httpd.conf';

$paths['server_cert'] = $paths['apache2'].'certs/server.pem';
$paths['server_key'] = $paths['apache2'].'certs/server.key';
$paths['server_pass'] = $paths['apache2'].'certs/passphrase';

//-------------------------------------------------------------------------------------------

?>