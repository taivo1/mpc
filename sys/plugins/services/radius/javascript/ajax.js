
function nv_ajax_call (section, plugin, action, form_id, params)
{
    document.body.style.cursor = 'wait';
    var submit_data  = "type=nv";
    submit_data += "&section="+section+"&plugin="+plugin;

    if (form_id != "")
    {
        json_field=json_encode(form_id);
        submit_data += "&form_id="+form_id+"&form_fields="+json_field;
    }

    if (action != '')
    {
        submit_data += "&action=" + action;
    }

    for (key in params)
    {
        submit_data += "&"+key+"="+params[key];
    }

    //alert (submit_data);
    $.ajax({
               type: "POST",
               url: "index.php",
               data: submit_data,
               success: execute_action
            });
}

function execute_action (response)
{
    document.body.style.cursor = 'default';
    // A JSON array is expected
    var ret = eval('(' + response + ')');

    $.each(ret.item, function(i,item){
      if (item['type']=="script")
      {
          eval(item['value']);
      }
      else if (item['type']=="html")
      {
          $('#'+item['id']).html(item['value']);
      }
      else if (item['type']=="after")
      {
          $('#'+item['id']).after(item['value']);
      }
      else if (item['type']=="value")
      {
          $('#'+item['id']).val(item['value']);
      }
      else if (item['type']=="append")
      {
          $('#'+item['id']).append(item['value']);
      }
      else if (item['type']=="remove")
      {
          $('#'+item['id']).remove();
      }
    });
}