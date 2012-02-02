//<![CDATA[

var editing=false;

function reset_client_form ()
{
    $('#client_form_panel').hide();
    document.forms['client_form'].reset();
    $('#client_servers option').remove().appendTo('#available_servers');
}

function cancel_new_client()
{
    reset_client_form ();
    editing=false;
}

function show_new_client_form()
{
    if ( !editing )
    {
        editing=true;
        clear_test_alerts('client_form');
        reset_client_form();
        $('#client_form_panel').show();
    }
    else
    {
        alert('You have to cancel the current operation.');
    }
}

function create_client()
{
    if(!ms_check_form_fields('client_form'))
    {
        $('#client_servers option').each(function(i) {
            $(this).attr("selected", "selected");
        });
        nv_ajax_call (php_section, php_plugin, 'create_client', 'client_form', {});
    }
}

function delete_client(clientname)
{
    if ( !editing )
    {
        var params = {};
        params['clientname'] = clientname;
        nv_ajax_call(php_section, php_plugin, 'delete_client', '', params);
        //reset_client_form();
    }
    else
    {
        alert('You have to cancel the current operation.');
    }
}

function update_client(clientname)
{
    if(!ms_check_form_fields('edit_clt_form'))
    {
        $('#client_servers_'+clientname+' option').each(function(i) {
            $(this).attr("selected", "selected");
        });

        var params = {};
        params['clientname'] = clientname;
        nv_ajax_call (php_section, php_plugin, 'update_client', 'edit_clt_form', params);
    }
}

function cancel_edit_clt(clientname)
{
    $('#edit_clt_'+clientname).remove();
    $('#edit_clt_'+clientname+'_btn').show();
    $('#delete_clt_'+clientname+'_btn').show();
    editing=false;
}

function edit_client(clientname)
{
    if ( !editing )
    {
        editing=true;
        $('#edit_clt_'+clientname+'_btn').hide();
        $('#delete_clt_'+clientname+'_btn').hide();

        var params = {};
        params['clientname'] = clientname;
        nv_ajax_call (php_section, php_plugin, 'get_client_info', '', params);
    }
    else
    {
        alert('You have to cancel the current operation.');
    }
}

function delete_clt_as(servername)
{
    for (client in client_vas)
    {
        idx = client_vas[client].indexOf(servername);
        client_vas[client].splice(idx, 1);
    }
}

function add_auth_server(suffix)
{
    if (suffix != undefined) suffix = '_'+suffix; else suffix = '';
    return !$('#available_servers'+suffix+
              ' option:selected').remove().appendTo('#client_servers'
              +suffix);
}

function remove_auth_server(suffix)
{
    if (suffix != undefined) suffix = '_'+suffix; else suffix = '';
    return !$('#client_servers'+suffix+
              ' option:selected').remove().appendTo('#available_servers'
              +suffix);
}

//]]>
