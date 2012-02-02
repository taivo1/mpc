//<![CDATA[
$(document).ready(function(){
  check_conditions();
});
function check_conditions()
{
    clear_test_alerts();
    if ($('#iface_sel').val()=="static")
    {
        
        $('#address').show();
        $('#address_lab').show();
        $('#netmask').show();
        $('#DNS1').show();
        $('#DNS2').show();
        $('#broadcast').show();
        $('#netmask_lab').show();
        $('#DNS1_lab').show();
        $('#DNS2_lab').show();
        $('#broadcast_lab').show();
        $('#activate_dhclient_div').hide();
    }
    else
    {
        $('#mode').val('Managed');
        $('#address').hide();
        $('#netmask').hide();
        $('#gateway').hide();
        $('#DNS1').hide();
        $('#DNS2').hide();
        $('#broadcast').hide();
        $('#address_lab').hide();
        $('#netmask_lab').hide();
        $('#gateway_lab').hide();
        $('#DNS1_lab').hide();
        $('#DNS2_lab').hide();
        $('#broadcast_lab').hide();
        $('#activate_dhclient_div').show();
    }
    
    if ($('#tx_power').val()=="other")
    {
        $('#tx_pow').show();
    }
    else
    {
        $('#tx_pow').hide();
    }

    
    if ($('#rate').val()=="other")
    {
        $('#rate_value').show();
        if ($('#freq').val()==2)
        {

            $('#rate_advice_2').show();
            $('#rate_advice_5').hide();
        }
        else
        {
            $('#rate_advice_2').hide();
            $('#rate_advice_5').show();
        }
    }
    else
    {
        $('#rate_value').hide();
        $('#rate_advice_2').hide();
        $('#rate_advice_5').hide();
    }

    check_security_conditions();    
    switch ($('#freq').val())
    {
        case '2':
            $('#channel2').show();
            $('#channel5').hide();
            $('#mode-abg').children().remove().end().append('<option value="1">b</option><option selected value="2">g</option>') ;            
        break;
        case'5':
            $('#channel2').hide();
            $('#channel5').show();
            $('#mode-abg').children().remove().end().append('<option selected value="3">a</option>') ;
        break;
    }
    
    switch ($('#mode').val())
    {
        case 'managed':
            if ($('#iface_sel').val()=='static')
            {
                $('#address').show();                
                $('#address_lab').show();
                $('#mac_essid').show();
                $('#mac_essid2').show();
                $('#mac_essid_i').addClass('ms_mac');
                $('#gateway_lab').show();
                $('#gateway').show();
                // Values to check at save.
                $('#network_plugin_content input').addClass('ms_ip');
                $('#address').addClass('ms_mandatory');
                $('#netmask').addClass('ms_mandatory');
                $('#gateway').addClass('ms_mandatory');
            }
            else
            {
                $('#mac_essid').show();
                $('#mac_essid2').show();
                $('#mac_essid_i').addClass('ms_mac');

                $('#network_plugin_content input').removeClass('ms_mandatory ms_ip');
            }
            $('#security_lab').show();
            $('#security_dat').show();

            // Show security options
            $('#security_div').show();
            // But hide EAP
            $('#wpa_eap_ckb_div').hide();

            // Hide mac filter options.
            $('#mac_filter_div').hide();

        break;
        case 'ad-hoc':
            $('#address').show();
            $('#address_lab').show();
            $('#security_lab').hide();
            $('#security_dat').hide();
            $('#sec_pass').hide();
            $('#sec_pass1').hide();
            $('#sec_pass2').hide();
            $('#sec_pass3').hide();
            $('#mac_essid').hide();
            $('#mac_essid2').hide();
            $('#mac_essid_i').removeClass('ms_mac');
            $('#gateway_lab').show();
            $('#gateway').show();

            // Values to check at save.
            $('#network_plugin_content input').addClass('ms_ip');
            $('#address').addClass('ms_mandatory');
            $('#netmask').addClass('ms_mandatory');
            $('#gateway').removeClass('ms_mandatory');

            // Hide security options
            $('#security_div').hide();
            $('#protocol').val('none');
            $('#wpa_eap_ckb_div').show();

            // Show mac filter options.
            $('#mac_filter_div').show();
        break;
        case 'master':
            $('#address').show();
            $('#address_lab').show();            
            $('#security_lab').show();
            $('#security_dat').show();
            $('#mac_essid').hide();
            $('#mac_essid2').hide();
            $('#mac_essid_i').removeClass('ms_mac');
            $('#gateway').hide();
            $('#gateway_lab').hide();

            // Values to check at save.
            $('#network_plugin_content input').addClass('ms_ip');
            $('#address').addClass('ms_mandatory');
            $('#netmask').addClass('ms_mandatory');
            $('#gateway').removeClass('ms_mandatory ms_ip');

            // Show security options
            $('#security_div').show();
            $('#wpa_eap_ckb_div').show();

            // Show mac filter options.
            $('#mac_filter_div').show();
        break;
    }
    if($('#mac_filter_check_ath0').attr('checked'))
    {
        $('#mac_filter_hide_ath0').show();
        hide_mac();
    }
    else
    {
        $('#mac_filter_hide_ath0').hide();
        hide_mac();
    }
    if($('#mac_filter_check_ath1').attr('checked'))
    {
        $('#mac_filter_hide_ath1').show();
        hide_mac();
    }
    else
    {
        $('#mac_filter_hide_ath1').hide();
        hide_mac();
    }
}

function hide_mac()
{
    $('#mac_filter_add_ath0').hide();
    $('#mac_filter_add_ath1').hide();
    $('#mac_filter_add_ath0').removeClass('ms_ip');
    $('#mac_filter_add_ath1').removeClass('ms_ip');
    $('#add_mac_filter_ok').hide();
}
function show_mac()
{
    $('#mac_filter_add_ath0').show();
    $('#mac_filter_add_ath1').show();
    $('#mac_filter_add_ath0').addClass('ms_ip');
    $('#mac_filter_add_ath1').addClass('ms_ip');
    $('#add_mac_filter_ok').show();
}

function add_mac()
{
    if(!test_element($('#mac_filter_add_'+$('#interface_selector').val())))
    {
        hide_mac();
        complex_ajax_call('mac_filter_ath0','output','interfaces','wifi','add_mac','ath0');
    }
}

/* ------------------------------------------------------------------------ */

function check_security_conditions()
{
    onchange_protocol ();
    onchange_key_size ();
    onchange_connection();
    onchange_ckb_set ('wpa_eap');
    onchange_ckb_set ('wpa_psk');
}

function onchange_protocol()
{
    switch ($('#protocol').val())
    {
        case "none":
            $('#wep').hide();
            $('#wpa').hide();

            $('#wep input').removeClass('ms_mandatory');
            $('#wpa input').removeClass('ms_mandatory');
            break;

        case "wep":
            $('#wpa').hide();
            $('#wep').show();

            $('#wep input').addClass('ms_mandatory');
            $('#wpa input').removeClass('ms_mandatory');
            break;

        case "wpa":
            $('#wep').hide();
            $('#wpa').show();

            $('#wep input').removeClass('ms_mandatory');
            break;
    }
    security_input_check();
}

function onchange_key_size()
{
    switch ($('#key_size').val())
    {
        case "40":
            $('#wep_pass_msg').html('*5 characters');
            $('#wep_pass').attr("maxlength", "5");
            if ($('#wep_pass').attr("value").length > 5)
            {
                $('#wep_pass').attr("value", "");
            }
            break;

        case "104":
            $('#wep_pass_msg').html('*13 characters');
            $('#wep_pass').attr("maxlength", "13");
            if ($('#wep_pass').attr("value").length > 13)
            {
                $('#wep_pass').attr("value", "");
            }
            break;
    }
}

function onchange_ckb_set(setid)
{
    if($('#'+setid+'_ckb').attr('checked'))
    {
        $('#'+setid).show();
    }
    else
    {
        $('#'+setid).hide();
    }
    security_input_check();
}

function onchange_connection()
{
    switch ($('#radius_connection').val())
    {
        case "local":
            $('#radius_remote_connection').hide();
            $('#radius_local_connection').show();
            break;

        case "remote":
            $('#radius_local_connection').hide();
            $('#radius_remote_connection').show();
            break;
    }
    security_input_check();
}

function security_input_check()
{
    clear_test_alerts('security_div');
    switch ($('#protocol').val())
    {
        case "none":
            $('#wep input').removeClass('ms_mandatory');
            $('#wpa input').removeClass('ms_mandatory');
            $('#radius_addr').removeClass('ms_ip');
            $('#radius_port').removeClass('ms_numerical');
            break;
        case "wep":
            $('#wep input').addClass('ms_mandatory');
            $('#wpa input').removeClass('ms_mandatory');
            $('#radius_addr').removeClass('ms_ip');
            $('#radius_port').removeClass('ms_numerical');
            break;
        case "wpa":
            $('#wep input').removeClass('ms_mandatory');           

            if($('#wpa_eap_ckb').attr('checked'))
            {
                switch ($('#radius_connection').val())
                {
                    case "local":
                        $('#wpa input').removeClass('ms_mandatory');
                        $('#radius_addr').removeClass('ms_ip');
                        $('#radius_port').removeClass('ms_numerical');
                        break;
                    case "remote":
                        $('#radius_remote_connection input').addClass('ms_mandatory');
                        $('#radius_addr').addClass('ms_ip');
                        $('#radius_port').addClass('ms_numerical');
                        break;
                }
            }
            else
            {
                $('#radius_remote_connection input').removeClass('ms_mandatory');
                $('#radius_addr').removeClass('ms_ip');
                $('#radius_port').removeClass('ms_numerical');
            }

            if($('#wpa_psk_ckb').attr('checked'))
            {
                $('#psk_pass').addClass('ms_mandatory');
                $('#cnf_psk_pass').addClass('ms_mandatory');
            }
            else
            {
                $('#psk_pass').removeClass('ms_mandatory');
                $('#cnf_psk_pass').removeClass('ms_mandatory');
            }

            break;
    }
    
    
}

//]]>