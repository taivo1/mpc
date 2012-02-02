// This file is based on jquery ajax.
// You don't have to make use of jquery. You can use prototype, mootools or your
// own ajax call.
function save_data(form_id,section,plugin,inter)
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
        submit_data="section="+section+"&plugin="+plugin+"&action=save&interface="+inter+"&form_fields="+json_field;
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
function save()
{
    save_data('long_range_link',$('#section').val(),$('#plugin').val(),$('#interface_selector').val());
}
function check_opt()
{
    clear_test_alerts();
    if ($('#input_method').val()=='Auto')
    {        
        $('#distance_value').removeAttr("readonly");
        $('#distance_value').removeClass("readonly");
        $('#distance_value').addClass("ms_mandatory");
        $('#acktimeout').attr('readonly',true);
        $('#acktimeout').addClass("readonly");
        $('#acktimeout').removeClass('ms_mandatory');
        $('#acktimeout').val('');
        $('#slottime').attr('readonly',true);
        $('#slottime').addClass("readonly");
        $('#slottime').removeClass('ms_mandatory');
        $('#slottime').val('');
        $('#ctstimeout').attr('readonly',true);
        $('#ctstimeout').addClass("readonly");
        $('#ctstimeout').removeClass('ms_mandatory');
        $('#ctstimeout').val('');
    }
    else
    {
        $('#distance_value').val('');
        $('#distance_value').attr('readonly',true);
        $('#distance_value').addClass("readonly");
        $('#distance_value').removeClass("ms_mandatory");
        $('#acktimeout').removeAttr('readonly',true);
        $('#slottime').removeAttr('readonly',true);
        $('#ctstimeout').removeAttr('readonly',true);
        $('#acktimeout').removeClass('readonly');
        $('#slottime').removeClass('readonly');
        $('#ctstimeout').removeClass('readonly');
        $('#acktimeout').addClass("ms_mandatory");
        $('#slottime').addClass("ms_mandatory");
        $('#ctstimeout').addClass("ms_mandatory");
    }
}
