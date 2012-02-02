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
function is_ip($ip)
{
    return filter_var($ip,FILTER_VALIDATE_IP);
}
function is_url($url)
{
    // I don't like filter_var behaviour for url's so i use old school regexp
    // Obviusly this can be improved, but right now for me is enough.
    if (preg_match('(^((https?|ftp)://)?([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?$)',$url))
    {
        return true;
    }
    else
    {
        return false;
    }
}
function is_mac($mac)
{
    // To allow 00-19-66-89-23-bf macs just uncomment next line and comment the if.
    //if (preg_match('/^([0-9a-fA-F]{2}[:-]){5}[0-9a-fA-F]{2}$/i ',$mac))
    // This will allow macs like 00:19:66:89:23:ba
    if (preg_match('/^([0-9A-F]{2}[:]){5}[0-9A-F]{2}$/i ',$mac))
    {
        return true;
    }
    else
    {
        return false;
    }
}
function is_email($email)
{
    // e-mail address validation
	$e = "/^[-+\\.0-9=a-z_]+@([-0-9a-z]+\\.)+([0-9a-z]){2,4}$/i";
	// from address
	if(preg_match($e, $email))
	{
		return true;
	}
    else
    {
        return false;
    }
}
function is_valid_email_address($email){
    // RFC822 compliant email address matcher
    // This function is from Cal Henderson.
    // source code of this function is licensed under a Creative Commons Attribution-ShareAlike 2.5 License by Cal Henderson.
    // http://www.iamcal.com/publish/articles/php/parsing_email

		$qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';

		$dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';

		$atom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c'.
			'\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';

		$quoted_pair = '\\x5c[\\x00-\\x7f]';

		$domain_literal = "\\x5b($dtext|$quoted_pair)*\\x5d";

		$quoted_string = "\\x22($qtext|$quoted_pair)*\\x22";

		$domain_ref = $atom;

		$sub_domain = "($domain_ref|$domain_literal)";

		$word = "($atom|$quoted_string)";

		$domain = "$sub_domain(\\x2e$sub_domain)*";

		$local_part = "$word(\\x2e$word)*";

		$addr_spec = "$local_part\\x40$domain";

		return preg_match("!^$addr_spec$!", $email) ? 1 : 0;
	}

function is_alphanumeric($string)
{
    if (ctype_alnum($string))
    {
        return true;
    }
    else
    {
        return false;
    }
}
function satinize_filename($filename)
{
    return preg_replace('/[^0-9a-z\.\_\-]/i','',$filename);
}
?>