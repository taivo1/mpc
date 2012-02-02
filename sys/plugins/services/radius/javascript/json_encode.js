function json_encode(form_id)
{
    var fields = new Object();
    $("#"+form_id+" :input").each(function(){
        if($(this).attr("type")=="checkbox")
        {
            if(this.checked)
            {
                fields[$(this).attr("name")]=$(this).val();
            }
        }
        else if($(this).attr("type")=="radio")
        {
            if(this.checked)
            {
                fields[$(this).attr("name")]=$(this).val();
            }
        }
        else
        {
            if($(this).val()!='')
            {
                fields[$(this).attr("name")]=$(this).val();
            }
        }
    });
    return $.toJSON(fields);
}