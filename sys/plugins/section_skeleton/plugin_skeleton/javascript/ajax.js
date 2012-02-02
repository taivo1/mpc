// This file is based on jquery ajax.
// You don't have to make use of jquery. You can use prototype, mootools or your
// own ajax call. But remember to check for collision problems on core.
function simple_ajax_call(form_id,output_id,section,plugin)
{
    // This script will serialize the form indicated by an id and submit it
    // to the desired page.
    // Once the response has arrived it display the response inside the id
    // defined in output_id
    document.body.style.cursor = 'wait';
    submit_data="section="+section+"&plugin="+plugin+"&type=simple"
    if (form_id!='')
    {
        submit_data+='&'+$('#'+form_id).serialize();
    }
    //alert (submit_data);
    $.ajax({
               type: "POST",
               url: "index.php",
               data: submit_data,
               success: function(datos){
                        document.body.style.cursor = 'default';
                        $('#'+output_id).html(datos);
               }
            });
}
function complex_ajax_call(form_id,output_id,section,plugin)
{
    // This script will serialize the form indicated by an id and submit it
    // to the desired page.
    // Once the response has arrived it display the response inside the id
    // defined in output_id
    document.body.style.cursor = 'wait';
    submit_data="section="+section+"&plugin="+plugin+"&type=complex&"+$('#'+form_id).serialize();
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

/*
 * What is needed to make an ajax call to the server.
 *
 * 1º send data througt POST
 * 2º specify the section and plugin information
 *
 *
 */