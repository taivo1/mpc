//<![CDATA[

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
 *  Author: Octavio Benedi Sanchez
 */

/*
 * ms_mandatory- obligatorio. - This field is necessary to complete the action
 * ms_numerical - numerico - Only numerical values allowed
 * ms_text - texto - Only text values allowed
 * ms_alnum - alfanumerico - Only text and number values allowed
 * ms_float - float in decimal format like 10.2
 * ms_ip - IP - The value is not a valid IP
 * ms_host - Host - The value is not a valid Host
 * ms_url - URL values. parameters allowed.
 * ms_mac - MAC - The value is not a valid MAC
 * ms_subnet - subred - The value is not a valid subnet
 * ms_email - email field validator.
 */
var error = new Array();
    error['ms_mandatory']='This field is required';
    error['ms_numerical']='Only numerical characters allowed';
    error['ms_text']='Only text allowed';
    error['ms_alnum']='Only alphanumerical values allowed';
    error['ms_float']='Only float in decimal format allowed';
    error['ms_ip']='Only IP address allowed';
    error['ms_host']='Only IP address or hostname allowed';
    error['ms_url']='Only url strings allowed';
    error['ms_mac']='Only MAC values allowed';
    error['ms_subnet']='Only IP or subnet values allowed';
    error['ms_email']='Only email address allowed';
    error['ms_hex']='Only hexadecimal number allowed';
/*
 * The checker can be binding to a unique input just using it's id.
 * For a input with id test_me just this is needed:

$('#test_me').change(function(i){
    test_element(this)
})

*/

function ms_check_form_fields(id)
{
    // Iterar por los inputs de un id dado o si no hay un id definido por todos.
    // Busca los tags de clase y aplica las reglas.
    var error_detected=false;
    var selector='';
    if (id!=null)
    {
        selector='#'+id;
    }
    clear_test_alerts(id);
    $(selector+' input').each(function(i){
        // be carefull with the lazy evaluation if you change this.
        error_detected=(test_element(this)||error_detected);
    });
    return error_detected;
}

function clear_test_alerts(id)
{
    var selector='';
    if (id!=null)
    {
        selector='#'+id;
    }
    $(selector+' input').each(function(i){
        $(this).removeClass('data_check_failed');
        $('#'+$(this).attr('id')+'_ms_cte').html('');
    });
}

function test_element(el)
{
    var error_detected=false;
    if($(el).attr('name'))
    {
        var len=$(el).val().length;        
        if($(el).hasClass("ms_numerical")&&len)
        {
           if(!is_numerical(el))
            {
                error_detected=true;
                set_alert($(el).attr('id'),'ms_numerical');
            }
        }
        if($(el).hasClass("ms_text")&&len)
        {
           if(!is_text(el))
            {
                error_detected=true;
                set_alert($(el).attr('id'),'ms_text');
            }
        }
        if($(el).hasClass("ms_alnum")&&len)
        {
           if(!is_alphanumerical(el))
            {
                error_detected=true;
                set_alert($(el).attr('id'),'ms_alnum');
            }
        }
        if($(el).hasClass("ms_float")&&len)
        {
           if(!is_float(el))
            {
                error_detected=true;
                set_alert($(el).attr('id'),'ms_float');
            }
        }
        if($(el).hasClass("ms_ip")&&len)
        {
           if(!is_ip(el))
            {
                error_detected=true;
                set_alert($(el).attr('id'),'ms_ip');
            }
        }
        if($(el).hasClass("ms_host")&&len)
        {
           if(!is_host(el))
            {
                error_detected=true;
                set_alert($(el).attr('id'),'ms_host');
            }
        }
        if($(el).hasClass("ms_url")&&len)
        {
           if(!is_url(el))
            {
                error_detected=true;
                set_alert($(el).attr('id'),'ms_url');
            }
        }
        if($(el).hasClass("ms_mac")&&len)
        {
           if(!is_mac(el))
            {
                error_detected=true;
                set_alert($(el).attr('id'),'ms_mac');
            }
        }
        if($(el).hasClass("ms_subnet")&&len)
        {
           if(!is_subnet(el))
            {
                error_detected=true;
                set_alert($(el).attr('id'),'ms_subnet');
            }
        }
        if($(el).hasClass("ms_email")&&len)
        {
           if(!is_email(el))
            {
                error_detected=true;
                set_alert($(el).attr('id'),'ms_email');
            }
        }
        if($(el).hasClass("ms_hex")&&len)
        {
           if(!is_hex(el))
            {
                error_detected=true;
                set_alert($(el).attr('id'),'ms_hex');
            }
        }
        // Mandatory check should be the last allways.
        if($(el).hasClass("ms_mandatory"))
        {
            if(!is_mandatory(el))
            {
                error_detected=true;
                set_alert($(el).attr('id'),'ms_mandatory');
            }
        }
    }
    return error_detected;
}

function is_mandatory(el)
{
    return $(el).val().length;
}

function is_numerical(el)
{
    // if you need a negative number, create your own or use float.
    var pattern_number = /^\d+$/;
    return pattern_number.test($(el).val());
}

function is_text(el)
{
   var pattern_text = /^[a-zA-Z ]+$/;
   return pattern_text.test($(el).val());
}

function is_alphanumerical(el)
{
    var pattern_alpha = /^\w+$/;
    return pattern_alpha.test($(el).val());
}

function is_float(el)
{
    // Just on decimal format.
    var pattern_decimal = /^\-?\d+(\.\d+)?$/;
    return pattern_decimal.test($(el).val());
}

function is_ip(el)
{
    var pattern_decimal = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
    return pattern_decimal.test($(el).val());
}

function is_host(el)
{
    // IP or host value.
    var pattern_host = /^(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?$|(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
    return pattern_host.test($(el).val());
}

function is_url(el)
{
    // Url with optional parameters.
    var pattern_url = /^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/;
    return pattern_url.test($(el).val());
}
function is_mac(el)
{
    var pattern_mac=/^([0-9A-F]{2}[:]){5}[0-9A-F]{2}$/i;
    return pattern_mac.test($(el).val());
}

function is_subnet(el)
{
    // Check for subnet values like 192.168.1.0/24
    var pattern_subnet = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(?:\/(?:3[0-2]|[1-2]?[0-9]))?$/;
    return pattern_subnet.test($(el).val());
}

function is_email(el)
{
    // Two simple validators provided too if needed.(not RFC822 compilant)
    //var pattern_email = /^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|\d+)$/i;
    //var pattern_email = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    // RFC822 compliant email address matcher translated to javascript from the php function of Cal Henderson.
    // Source code of the php function is licensed under a Creative Commons Attribution-ShareAlike 2.5 License by Cal Henderson.
    // http://www.iamcal.com/publish/articles/php/parsing_email
    var pattern_email = /^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*/;
    return pattern_email.test($(el).val());
}

function is_hex(el)
{
   var pattern_text = /^[0-9A-E]+$/;
   return pattern_text.test($(el).val());
}

function set_alert(id,message)
{    
    $('#'+id).addClass('data_check_failed');
    // cte is check type error acronym.
    $('#'+id+'_ms_cte').html('* '+error[message]);
    $('#'+id+'_ms_cte').addClass('ms_cte');
    //alert(error[message]);
}

//]]>