//<![CDATA[

var editing=false;

function update_acct_server(servername)
{
    if(!ms_check_form_fields('edit_acvs_form'))
    {
        var params = {};
        params['servername'] = servername;
        nv_ajax_call (php_section, php_plugin, 'update_acct_server', 'edit_acvs_form', params);
    }
}

function cancel_edit_acvs(servername)
{
    $('#edit_acvs_'+servername).remove();
    $('#edit_acvs_'+servername+'_btn').show();
    editing=false;
}

function edit_acct_server(servername)
{
    if ( !editing )
    {
        editing=true;
        $('#edit_acvs_'+servername+'_btn').hide();

        var params = {};
        params['servername'] = servername;
        nv_ajax_call (php_section, php_plugin, 'get_acct_server_info', '', params);
    }
    else
    {
        alert('You have to cancel the current operation.');
    }
}

//]]>
