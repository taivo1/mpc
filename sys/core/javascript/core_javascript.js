//<![CDATA[
var core_left_image=0;
var core_section_navbar_slider;
var core_section_navbar_overflow_value;
var core_section_navbar_overflow;

window.onload=function() {
  core_section_navbar_slider=document.getElementById('section_navbar_slider');
  core_section_navbar_overflow_value=get_computed_width('section_navbar_menu');
  core_section_navbar_overflow=overflow_width(core_section_navbar_overflow_value);  
}

function overflow_width(limit)
{
    var offset=0;
    var i=0;
    while (document.getElementById('section_navbar_slider_image_'+i)!=null)
    {
        offset+=getwidthimage('section_navbar_slider_image_'+i)+5;
        i++;
    }
    // This value is not arbitrary, is the widht of section_navbar_menu on main css
    //  if that value is changed on main css please update that value here too.
    if(offset>limit)
    {
         return true;
    }
    return false;
}
function jump_next_image()
{
    if(core_section_navbar_overflow)
    {
        var check_id=core_left_image+1;
        if (document.getElementById('section_navbar_slider_image_'+check_id)==null)
        {
            //alert('no mas'+getoffsetofimage(core_left_image));
            core_section_navbar_slider.style.left='-'+getoffsetofimage(core_left_image)+'px';
        }
        else
        {
            core_left_image++;
            //alert(getoffsetofimage(core_left_image));
            core_section_navbar_slider.style.left='-'+getoffsetofimage(core_left_image)+'px';
        }
        get_css_id()
    }
}
function jump_previous_image()
{
    if(core_section_navbar_overflow)
    {
        var offset=0;
        if (core_left_image>0)
        {
            core_left_image--;
            offset=getoffsetofimage(core_left_image);
            core_section_navbar_slider.style.left='-'+getoffsetofimage(core_left_image)+'px';
        }

        //alert(offset);
    }
}
function getoffsetofimage(possition){
    var offset=0;
    for (var i=0;i<possition;i++)
    {
        offset+=getwidthimage('section_navbar_slider_image_'+i)+5;
    }    
    return offset;
}
function getwidthimage(id)
{
    var image = document.getElementById(id);
    return image.offsetWidth;
}
function get_computed_width(id)
{
    var value=650; // Default width for scroll
    if(!document.getElementById) return;
    {
        var element=document.getElementById(id);        
        if(element!=null)
        {
            if(document.defaultView)
            {
                value = document.defaultView.getComputedStyle(element, "").getPropertyValue('Width').split('px');
                return  value[0];
            }
            else if(element.currentStyle)
            {
                value = element.currentStyle['Width'].split('px');
                return  value[0];
            }
        }
    }
    return value;
}
//]]>