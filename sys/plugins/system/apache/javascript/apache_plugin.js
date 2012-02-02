//<![CDATA[

$(document).ready(function() {
    check_conditions();
});

var editing=false;

function check_conditions()
{
    /* IMPORTANT: upload_file functions must be on top */
    upload_btn('server_cert', !exists_server_cert);
    upload_btn('server_key', !exists_server_key);

    if (!$('#delete_btn').hasClass('disabled'))
    {
        $('#delete_btn').bind('click', function(e) {
            delete_cert_files();
        });
    }

    $('#key_password_panel').hide();

    if ( !http_ssl_config )
    {
        $('#http_ssl_config').hide();
    }
}

function cancel_edit(item)
{
    $('#edit_panel_'+item).remove();
    $('#edit_btn_'+item).show();
    editing=false;
}

function edit_dir(item)
{
    if ( !editing )
    {
        editing=true;
        $('#edit_btn_'+item).hide();

        var params = {};
        params['directory'] = item;
        nv_ajax_call(php_section, php_plugin, 'get_dir_info', '', params);
    }
    else
    {
        alert('You have to cancel the current operation.');
    }
}

function update_dir(item)
{
    $('#output').html('<fieldset><h2>Applying changes...</h2></fieldset>');

    var params = {};
    params['directory'] = item;
    nv_ajax_call(php_section, php_plugin, 'update_dir_config', 'edit_dir_form', params);
}

function set_global(item)
{
    var params = {};
    params['directory'] = item;
    nv_ajax_call(php_section, php_plugin, 'set_global', '', params);
}

function reset_row(item)
{
    $('#dir_'+item+' .global').remove();
    $('#dir_'+item+' .http').remove();
    $('#dir_'+item+' .https').remove();
}

function show_global_update(item, image)
{
    cancel_edit(item);
    reset_row(item);
    var row = '<td class="global"><img src="'+image+'" /></td>';
    $('#dir_'+item+' .directory').after(row);
}

function show_http_update(item, image_http, image_https)
{
    cancel_edit(item);
    reset_row(item);

    // The order is IMPORTANT!!
    var row2 = '<td class="https"><img src="'+image_https+'" /></td>';
    $('#dir_'+item+' .directory').after(row2);

    var row1 = '<td class="http"><img src="'+image_http+'" /></td>';
    $('#dir_'+item+' .directory').after(row1);
}

function restart_apache()
{
    $('#output').html('<fieldset><h2>Restarting Apache2 server...</h2></fieldset>');
    nv_ajax_call (php_section, php_plugin, 'restart_apache', '', new Array() );
}

//]]>
