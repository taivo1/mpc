// This file is based on jquery ajax.
// You don't have to make use of jquery. You can use prototype, mootools or your
// own ajax call.
function complex_ajax_call(form_id,action,section,plugin,output_id)
{
    // This script will serialize the form indicated by an id and submit it
    // to the desired page.
    // Once the response has arrived it display the response inside the id
    // defined in output_id
    if(!ms_check_form_fields()||action!='save')
    {
        document.body.style.cursor = 'wait';
        clear_test_alerts();
        var json_field=json_encode(form_id);

        submit_data="section="+section+"&plugin="+plugin+"&action="+action+"&type=complex&"+"form_fields="+json_field;

        //$("#output2").html("<pre>"+submit_data+"</pre>");
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

function allow_edit(){
    readonly=false;
    
    $('#PIN').removeAttr('readonly');
    $('#PIN').removeClass('readonly');    

    $('#username').removeAttr('readonly');
    $('#username').removeClass('readonly');

    $('#password').removeAttr('readonly');
    $('#password').removeClass('readonly');

    $('#phone').removeAttr('readonly');
    $('#phone').removeClass('readonly');

    $('#init1').removeAttr('readonly');
    $('#init1').removeClass('readonly');

    $('#dial').removeAttr('readonly');
    $('#dial').removeClass('readonly');    
}
var value;
var readonly=true;
$(document).ready(function () {
    value=$('#dial').val();
    $('#dial').change( function() {
      if(readonly)
          {
              $('#dial').val(value);
          }
    });
});