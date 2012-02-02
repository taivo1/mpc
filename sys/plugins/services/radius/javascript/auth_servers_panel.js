//<![CDATA[

var editing=false;

function reset_auth_server_form ()
{
    $('#auth_server_form_panel').hide();
    document.forms['auth_server_form'].reset();
}

function cancel_new_auth_server()
{
    reset_auth_server_form ();
    editing=false;
}

function show_new_auth_server_form()
{
    if ( !editing )
    {
        editing=true;
        clear_test_alerts('auth_server_form');
        reset_auth_server_form();
        $('#auth_server_form_panel').show();
    }
    else
    {
        alert('You have to cancel the current operation.');
    }
}

function create_auth_server()
{
    if(!ms_check_form_fields('auth_server_form'))
    {
        nv_ajax_call (php_section, php_plugin, 'create_auth_server', 'auth_server_form', {});
    }
}

function delete_auth_server(servername)
{
    if ( !editing )
    {
        var params = {};
        params['servername'] = servername;
        nv_ajax_call(php_section, php_plugin, 'delete_auth_server', '', params);
        reset_auth_server_form();
    }
    else
    {
        alert('You have to cancel the current operation.');
    }
}

function update_auth_server(servername)
{
    if(!ms_check_form_fields('edit_avs_form'))
    {
        var params = {};
        params['servername'] = servername;
        nv_ajax_call (php_section, php_plugin, 'update_auth_server', 'edit_avs_form', params);
    }
}

function cancel_edit_avs(servername)
{
    $('#edit_avs_'+servername).remove();
    $('#edit_avs_'+servername+'_btn').show();
    $('#delete_avs_'+servername+'_btn').show();
    editing=false;
}

function edit_auth_server(servername)
{
    if ( !editing )
    {
        editing=true;
        $('#edit_avs_'+servername+'_btn').hide();
        $('#delete_avs_'+servername+'_btn').hide();

        var params = {};
        params['servername'] = servername;
        nv_ajax_call (php_section, php_plugin, 'get_auth_server_info', '', params);
    }
    else
    {
        alert('You have to cancel the current operation.');
    }
}


//]]>
