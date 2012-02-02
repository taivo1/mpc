// This file is based on jquery ajax.
// You don't have to make use of jquery. You can use prototype, mootools or your
// own ajax call.
function complex_ajax_call(form_id,output_id,section,plugin)
{
    // This script will serialize the form indicated by an id and submit it
    // to the desired page.
    // Once the response has arrived it display the response inside the id
    // defined in output_id
    //var fields = $("#"+form_id+" :input").serializeArray();
    if(!ms_check_form_fields())
    {
        document.body.style.cursor = 'wait';
        var json_field=json_encode(form_id);
        submit_data="section="+section+"&plugin="+plugin+"&type=complex&"+"form_fields="+json_field;
        //alert (submit_data);

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

function stop_zigbee_log(section,plugin)
{
	submit_data="section="+section+"&plugin="+plugin+"&type=zigbee_log_off";
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
function start_zigbee_log(section,plugin)
{
	submit_data="section="+section+"&plugin="+plugin+"&type=zigbee_log_on";
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
function load_zigbee_data(form_id,output_id,section,plugin)
{
    var json_field=json_encode(form_id);
    submit_data="section="+section+"&plugin="+plugin+"&type=load_zigbee_data&"+"form_fields="+json_field;
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
