//<![CDATA[
var just_loaded=true;
var data_changed=false;
var last_interface=' ';

$(document).ready(function(){
    $('#interface_selector').change( function() {
        load_interface();
    });
    if(just_loaded)
    {
        $('#interface_selector').val(' ');
        just_loaded=false
    }
});
function changes_made()
{
    if (data_changed==true)
    {
        if(confirm("Data has been changed without saving, Continue loading interface options?"))
        {
            data_changed=false;
            return true;
        }
        $('#interface_selector').val(last_interface);
        return false;
    }
    return true;

}

function load_changes_check()
{
    $("input").change( function() {
        data_changed=true;
    });

    $("select:not(#interface_selector)").change( function() {
        data_changed=true;
    });
}

function load_interface()
{
    if(changes_made())
    {
        var section=$('#section').val();
        var plugin=$('#plugin').val();
        var inter=$('#interface_selector').val();
        submit_data="section="+section+"&plugin="+plugin+"&type=load_interface&interface="+inter+"&action=load_interface";
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
                              else if (item['type']=="option")
                              {
                                  $('#'+item['id']).append('<option value='+item['value']+'>'+item['value']+'</option>',false);
                              }
                          });
                          last_interface=inter;
                          load_changes_check();
                               $('#input_method').change( function() {
                              check_opt();
                          });
                          check_opt();
                   }
                });
    }

}

//]]>