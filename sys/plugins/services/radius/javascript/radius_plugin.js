//<![CDATA[

$(document).ready(function(){
  check_conditions();
});

function check_conditions()
{
    /* IMPORTANT: upload_file functions must be on top */
    upload_btn('cacert', !exists_cacert);
    upload_btn('server_cert', !exists_server_cert);
    upload_btn('server_key', !exists_server_key);

    if (!$('#delete_btn').hasClass('disabled'))
    {
        $('#delete_btn').bind('click', function(e) {
            delete_cert_files();
        });
    }

    $('#user_form_panel').hide();
    $('#auth_server_form_panel').hide();
    $('#acct_server_form_panel').hide();
    $('#client_form_panel').hide();

    $('#key_password_panel').hide();

    if ( !radius_config )
    {
        $('#radius_config').hide();
    }

    // I use a function to turn client into a constant value
    for (client in client_vas) create_client_tip(client);
    for (user in user_logtime) create_user_tip(user);
}

function create_client_tip(client)
{
    $('#'+client+'_show_vas').simpletip({
        fixed: false,
        position: 'bottom',
        onBeforeShow: function() {
            txt = '';
            for (idx in client_vas[client])
            {
                txt = txt + client_vas[client][idx] + '<br>';
            }
            this.update(txt);
        }
    });
}

function create_user_tip(user)
{
    $('#'+user+'_show_logtime').simpletip({
        fixed: false,
        position: 'bottom',
        onBeforeShow: function() {
            txt = '';
            for (idx in user_logtime[user])
            {
                txt = txt + user_logtime[user][idx] + '<br>';
            }
            this.update(txt);
        }
    });
}

function restart_radius()
{
    $('#output').html('<fieldset><h2>Restarting RADIUS server...</h2></fieldset>');
    nv_ajax_call (php_section, php_plugin, 'restart_radius', '', new Array() );
}

//]]>
