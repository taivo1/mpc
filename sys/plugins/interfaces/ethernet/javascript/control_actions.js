//<![CDATA[

$(document).ready(function(){
  check_me();
});
function check_me()
{
    clear_test_alerts();
    // Hide configuration values if static.
    if ($('#iface_sel').val()=="static")
    {
        $('#address').show();        
        $('#netmask').show();        
        $('#gateway').show();

        $('#address_ms_cte').show();
        $('#netmask_ms_cte').show();
        $('#gateway_ms_cte').show();

        $('#address').addClass('ms_mandatory ms_ip');
        $('#netmask').addClass('ms_mandatory ms_ip');
        $('#gateway').addClass('ms_mandatory ms_ip');

        $('#DNS1').show();
        $('#DNS2').show();
        $('#broadcast').show();
        
        $('#DNS1').addClass('ms_ip');
        $('#DNS2').addClass('ms_ip');
        $('#broadcast').addClass('ms_ip');


        $('#address_lab').show();
        $('#netmask_lab').show();
        $('#gateway_lab').show();
        $('#DNS1_lab').show();
        $('#DNS2_lab').show();
        $('#broadcast_lab').show();
    }
    else
    {
        $('#address').hide();
        $('#netmask').hide();
        $('#gateway').hide();

        $('#address_ms_cte').hide();
        $('#netmask_ms_cte').hide();
        $('#gateway_ms_cte').hide();

        $('#address').removeClass('ms_mandatory ms_ip');
        $('#netmask').removeClass('ms_mandatory ms_ip');
        $('#gateway').removeClass('ms_mandatory ms_ip');

        $('#DNS1').hide();
        $('#DNS2').hide();
        $('#broadcast').hide();

        $('#DNS1').removeClass('ms_ip');
        $('#DNS2').removeClass('ms_ip');
        $('#broadcast').removeClass('ms_ip');


        $('#address_lab').hide();
        $('#netmask_lab').hide();
        $('#gateway_lab').hide();
        $('#DNS1_lab').hide();
        $('#DNS2_lab').hide();
        $('#broadcast_lab').hide();
    }
}
//]]>