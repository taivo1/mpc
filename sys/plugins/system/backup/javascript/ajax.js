//<![CDATA[
function complex_ajax_call(action,args,output_id,section,plugin)
{
    document.body.style.cursor = 'wait';
    submit_data="section="+section+"&plugin="+plugin+"&action="+action+"&args="+args;
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

function execute(action,arg,section,plugin)
{
    complex_ajax_call(action,arg,'output',section,plugin);
}
$(document).ready(function() {
    $('#upload_file').upload({
        name: 'file',
        method: 'post',
        action: $('#url_plugin').val()+'php/upload.php',
        enctype: 'multipart/form-data',
        autoSubmit: true,
        onSelect: function() {
            $('#output').html('');
        },
        onSubmit: function() {
            $('#output').html('Uploading file...');
        },
        onComplete: function(data) {
            window.location.href=window.location.href
            
        }
    });
});
//]]>