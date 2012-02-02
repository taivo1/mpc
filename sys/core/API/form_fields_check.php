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

/* ------------------------------------------------------------------------ */

$check_functions = Array (
    "ms_mandatory" => "is_filled",
    "ms_numerical" => "is_numerical",
    "ms_text" => "is_text",
    "ms_alnum" => "is_alnum",
    "ms_float" => "is_a_float",
    "ms_ip" => "is_ip",
    "ms_host" => "is_host",
    "ms_mac" => "is_mac",
    "ms_subnet" => "is_subnet",
    "ms_url" => "is_url",
    "ms_email" => "is_email",
    "ms_hex" => "is_hex",
    "ms_path" => "is_path"
);

/* ------------------------------------------------------------------------ */

function are_form_fields_valid ($post_data, $fields_check_types,
                                $fileds_ms_ctes=Array())
/* ------------------------------------------------------------------------ */
{
  global $check_functions;

  $are_valid = true;

  foreach ($fields_check_types as $name => $types)
  {
      foreach ($types as $type)
      {
          $is_valid = $check_functions[$type] ($post_data[$name]);
          if ( !$is_valid )
          {
              if ( isset($fileds_ms_ctes[$name]) )
              {
                  response_additem ("script", "set_alert('$fileds_ms_ctes[$name]', '$type')");
              }
              else
              {
                  response_additem ("script", "set_alert('$name', '$type')");
              }
          }
          $are_valid = $are_valid && $is_valid;
      }
  }

  return $are_valid;
}
/* ------------------------------------------------------------------------ */

function is_filled ($field)
/* ------------------------------------------------------------------------ */
{
    return !empty($field);
}
/* ------------------------------------------------------------------------ */

function is_numerical ($data)
/* ------------------------------------------------------------------------ */
{
    return strlen($data)==0 || ctype_digit($data);
}
/* ------------------------------------------------------------------------ */

function is_text ($data)
/* ------------------------------------------------------------------------ */
{
    return strlen($data)==0 || ctype_alpha($data);
}
/* ------------------------------------------------------------------------ */

function is_alnum ($data)
/* ------------------------------------------------------------------------ */
{
    return strlen($data)==0 || ctype_alnum($data);
}
/* ------------------------------------------------------------------------ */

function is_ip ($ip)
/* ------------------------------------------------------------------------ */
{
    return strlen($ip)==0 || filter_var($ip, FILTER_VALIDATE_IP);
}

function is_a_float($data)
{
    $pattern_decimal = '/^\-?\d+(\.\d+)?$/';
    return strlen($ip)==0 ||preg_match($pattern_decimal, $data);
}

function is_host ($host)
/* ------------------------------------------------------------------------ */
{
    $pattern_host = '/^(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?$|(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/';
    return strlen($host)==0 || preg_match($pattern_host, $host);
}
/* ------------------------------------------------------------------------ */

function is_mac ($mac)
/* ------------------------------------------------------------------------ */
{
    // To allow 00-19-66-89-23-bf macs just uncomment next line and comment the if.
    //if (preg_match('/^([0-9a-fA-F]{2}[:-]){5}[0-9a-fA-F]{2}$/i ',$mac))
    // This will allow macs like 00:19:66:89:23:ba
    return strlen($mac)==0 || (preg_match('/^([0-9A-F]{2}[:]){5}[0-9A-F]{2}$/i ',$mac));
}
/* ------------------------------------------------------------------------ */

function is_subnet ($address)
/* ------------------------------------------------------------------------ */
{
    $addr_regexp = '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}'.
    '(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(?:\/(?:3[0-2]|[1-2]?[0-9]))?$/';

    return strlen($address)==0 || preg_match($addr_regexp, $address);
}
/* ------------------------------------------------------------------------ */

function is_url ($url)
/* ------------------------------------------------------------------------ */
{
    return strlen($url)==0 || filter_var($url, FILTER_VALIDATE_URL);
}
/* ------------------------------------------------------------------------ */
function is_email($email)
{
    // e-mail address validation
	$e = "/^[-+\\.0-9=a-z_]+@([-0-9a-z]+\\.)+([0-9a-z]){2,4}$/i";
	// from address
	if(preg_match($e, $email)||strlen($email)==0)
	{
		return true;
	}
    else
    {
        return false;
    }
}

function is_hex($data)
{
   $pattern_text = '/^[0-9A-E]+$/';
   return strlen($url)==0 || preg_match($data, $pattern_text);
}

function is_path($data)
{
   $pattern_text = '/^[0-9A-Za-z\.\_\-\/]+$/';
   return strlen($url)==0 || preg_match($data, $pattern_text);
}

?>