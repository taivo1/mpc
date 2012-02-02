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
function refresh_gprs($country,$operator)
{
    global $section;
    global $plugin;
    global $operators_file_path;

	$data=list_operators($operators_file_path);
	$path='/etc/wvdial.conf';
	$known_operator=parse_wvdial($path);

	if ($country)
	{
		if ($operator)
		{
            response_additem('value',$data[$country][$operator]['username'],'username');
            response_additem('value',$data[$country][$operator]['password'],'password');
            response_additem('value','*99***1#','phone');
            response_additem('value',' AT+CGDCONT=1,"IP","'.$data[$country][$operator]['apn'].'"','init1');
            response_additem('value',$known_operator['pin'],'PIN');
            response_additem('value','atd','dial');
		}
		else
		{
            response_additem('html',add_operators($data,$country),'add_operators');
            response_additem('value','','username');
            response_additem('value','','password');
            response_additem('value','','phone');
            response_additem('value','','init1');
            response_additem('value','','PIN');
            response_additem('value','atd','dial');
		}
	}
	else
	{
		if(($known_operator['country'])&&($known_operator['operator']))
		{
            response_additem('value',$known_operator['country'],'country_list');
            response_additem('html',$known_operator['country'],'add_operators');
            response_additem('value',$known_operator['operator'],'country_operators');
		}
		else
		{
            response_additem('value','other','country_list');
            response_additem('value','other','country_operators');
		}
        response_additem('value',$known_operator['username'],'username');
        response_additem('value',$known_operator['password'],'password');
        response_additem('value',$known_operator['phone'],'phone');
        response_additem('value',$known_operator['init2'],'init1');
        response_additem('value',$known_operator['pin'],'PIN');
        response_additem('value','atd','dial');
	}
}
?>
