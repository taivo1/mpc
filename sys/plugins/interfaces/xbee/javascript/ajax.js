function complex_ajax_call(form_id,output_id,section,plugin,action)
{
    if((!ms_check_form_fields())&&(action=='save'))
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
    else if(action=='run_commands')
    {
        var json_field=json_encode(form_id);
        submit_data="section="+section+"&plugin="+plugin+"&action="+action+"&form_fields="+json_field;
        $.ajax({
                   type: "POST",
                   url: "index.php",
                   data: submit_data,
                   success: function(datos){
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

function add_one_more_own_at()
{
    var i=$('#own_at').children().size();
    $('#own_at').append('<div><input type="text" name="own_at_'+i+'" class="own_at" /></div>');
}
function reset_own_at()
{
    $('#own_at').html('');
    var i=0;
    $('#own_at').append('<div><input type="text" name="own_at_'+i+'" class="own_at" /></div>');
}