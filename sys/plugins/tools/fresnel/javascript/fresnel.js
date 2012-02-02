/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function fresnel_calc()
{
    if(!ms_check_form_fields())
    {
        var x2,x5,y,val;
        input=$('#fresnel_distance').val();
        x2=(input/(4*2.4));
        x5=(input/(4*5));
        val=17.32*Math.sqrt(x2);
        val=Math.round(val*1000)/1000;
        $('#24b').html(val+' m');
        val=17.32*Math.sqrt(x5);
        val=Math.round(val*1000)/1000;
        $('#5b').html(val+' m');
    }
    else
    {
        $('#24b').html('--');
        $('#5b').html('--');
    }
}
