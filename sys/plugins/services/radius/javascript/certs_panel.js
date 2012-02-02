//<![CDATA[

function upload_btn(file, enabled)
{
  var html;
  if (enabled)
  {
    html = '<input id="upload_'+file+'_btn" name="upload_'+file+'_btn" '+
           ' type="button" value="Upload" />';
    $('#upload_'+file).html(html);
    upload_cert_file (file);
  }
  else
  {
    html = '<input id="upload_'+file+'_fake" name="upload_'+file+'_fake" '+
           ' type="button" value="Upload" class="disabled" disabled />';
    $('#upload_'+file+'_garbage').html($('#upload_'+file).html());
    $('#upload_'+file).html(html);
  }
}

function reset_cert_pass_form()
{
    document.forms['cert_pass_form'].reset();
    cert_clear_test_alerts('cert_pass_form');
    $('#key_password_panel').hide();
}

function cancel_cert_pass()
{
    reset_cert_pass_form();
    upload_btn('server_key', true);
}

function disable_delete_btn()
{
    $('#delete_btn').attr('disabled','true');
    $('#delete_btn').addClass('disabled');
    $('#delete_btn').unbind('click');
}

function cert_files_deleted()
{
    disable_delete_btn();
    $('#radius_config').hide();
    if ( !$('#key_password_panel').is(':hidden') ) cancel_cert_pass();
    upload_btn('cacert', true);
    upload_btn('server_cert', true);
    upload_btn('server_key', true);
}

function cert_file_uploaded (file, data)
{
    //$('#upload_'+file+'_output').html('');

    ret = data.split(",");
    if (ret[0] == "VALID_FILE" || ret[0] == "PASSWORD_REQUIRED")
    {
        upload_btn(file, false);

        if (ret[1] == 'NOT_ALL')
        {
            $('#delete_btn').removeAttr('disabled');
            $('#delete_btn').removeClass('disabled');
            $('#delete_btn').unbind('click');
            $('#delete_btn').bind('click', function(e) {
              delete_cert_files();
            });
        } else {
            if (ret[2] == 'VALID') $('#radius_config').show();
            else alert('Certificate and private key mismatch.');
        }

        if (ret[0] == "PASSWORD_REQUIRED")
        {
            $('#key_password_panel').show();
        }
        //alert('File was successfully uploaded.');
    } else {
        alert(data);
    }
}

function upload_cert_file (file)
{
    $('#upload_'+file+'_btn').upload({
            name: file,
            method: 'post',
            action: php_url_plugin + 'php/upload.php',
            params: {section: php_section, plugin: php_plugin},
            enctype: 'multipart/form-data',
            autoSubmit: false,
            onSelect: function() {
                $('#output').html('');
                this.submit();
            },
            onSubmit: function() {
                //$('#upload_'+file+'_output').html('Uploading file...');
            },
            onComplete: function(data) {
                cert_file_uploaded (file, data);
            }
        });
}

function delete_cert_files()
{
    var params = new Array();
    nv_ajax_call (php_section, php_plugin, 'delete_cert_files', '', params );
}

function save_cert_pass()
{
    cert_clear_test_alerts('cert_pass_form');
    $('#output').html('<fieldset><h2>Applying changes...</h2></fieldset>');
    nv_ajax_call (php_section, php_plugin, 'save_cert_pass', 'cert_pass_form', new Array() );
}
function cert_clear_test_alerts(id)
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

function cert_set_alert(id)
{
    $('#'+id).addClass('data_check_failed');
    $('#'+id+'_ms_cte').html('* This field must be 4 to 8191 characters long.');
    $('#'+id+'_ms_cte').addClass('ms_cte');
}

//]]>
