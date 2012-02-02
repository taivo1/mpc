function complex_ajax_call(form_id,output_id,section,plugin,action)
{
    // Check input data

    if(action=='encrypt')
    {
        // Cypher user partition.
        $('#encrypt_user_partition_key').addClass('ms_mandatory');
        $('#encrypt_user_partition_key2').addClass('ms_mandatory');
        $('#mount_encrypted_user_partition_key').removeClass('ms_mandatory');
        $('#extra_storage_mountpoint').removeClass('ms_mandatory');
    }
    else if(action=='mount')
    {
        // Mount a previusly ciphered partition.
        $('#mount_encrypted_user_partition_key').addClass('ms_mandatory');
        $('#encrypt_user_partition_key').removeClass('ms_mandatory');
        $('#encrypt_user_partition_key2').removeClass('ms_mandatory');
        $('#extra_storage_mountpoint').removeClass('ms_mandatory');
    }
    else
    {
        // Format extra storage.
        $('#mount_encrypted_user_partition_key').removeClass('ms_mandatory');
        $('#encrypt_user_partition_key').removeClass('ms_mandatory');
        $('#encrypt_user_partition_key2').removeClass('ms_mandatory');
        $('#extra_storage_mountpoint').addClass('ms_mandatory');
    }

    if(!ms_check_form_fields())
    {
        document.body.style.cursor = 'wait';
        var json_field=json_encode(form_id);
        submit_data="section="+section+"&plugin="+plugin+"&action="+action+"&form_fields="+json_field;
        $.ajax({
                   type: "POST",
                   url: "index.php",
                   data: submit_data,
                   success: function(datos){
                          document.body.style.cursor = 'default';
                           // A JSON array is expected
                          var ret = eval('(' + datos + ')');
                          $.each(ret.item, function(i,item){
                              if (item['type']=="script")
                              {
                                  eval(item['value']);
                              }
                              else if (item['type']=="return")
                              {
                                  $('#'+output_id).html(item['value']);
                              }
                              else if (item['type']=="html")
                              {
                                  $('#'+item['id']).html(item['value']);
                              }
                              else if (item['type']=="value")
                              {
                                  $('#'+item['id']).val(item['value']);
                              }
                              else if (item['type']=="append")
                              {
                                  $('#'+item['id']).append(item['value']);
                              }
                          });
                   }
                });
    }
}