// This file is based on jquery ajax.
// You don't have to make use of jquery. You can use prototype, mootools or your
// own ajax call.
function complex_ajax_call(form_id,output_id,section,plugin,action)
{
    // This script will serialize the form indicated by an id and submit it
    // to the desired page.
    // Once the response has arrived it display the response inside the id
    // defined in output_id
    //var fields = $("#"+form_id+" :input").serializeArray();    
      
    if((!$('#dhcp_server_'+$('#interface_selector').val()).attr('checked'))||(!ms_check_form_fields()))
    {

        document.body.style.cursor = 'wait';
        var json_field=json_encode(form_id);
        var inter=$('#interface_selector').val();
        submit_data="section="+section+"&plugin="+plugin+"&type="+action+"&interface="+inter+"&form_fields="+json_field;
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

function make_readonly_fields()
{
    clear_test_alerts();
    if($('#dhcp_server_'+$('#interface_selector').val()).attr('checked'))
    {
        $('input:text').removeClass('readonly');
        $('input:text').removeAttr('readonly');
    }
    else
    {
        $('input:text').addClass('readonly');
        $('input:text').attr('readonly','true');
    }
}
