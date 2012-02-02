/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    $("img.click").click(function()
    {
        if($(this).hasClass('litem'))
        {
            // Cambiar el src de todos.
            $('img.litem').each(
            function(){
                $(this).removeClass('active');
                $(this).attr({
                    src: $(this).attr('src').replace(/_hv.png/g,'.png')
                })
            });
            // Cambia el src de este para activarlo.
            $(this).addClass('active');
            $(this).attr({
                src: $(this).attr('src').replace(/.png/g,'_hv.png')
            })
        }
        else if($(this).hasClass('arrow'))
        {
            // Cambiar el src de todos.
            $('img.arrow').each(
            function(){
                $(this).removeClass('active');
                $(this).attr({
                    src: $(this).attr('src').replace(/_hv.png/g,'.png')
                })
            });
            // Cambia el src de este para activarlo.
            $(this).addClass('active');
            $(this).attr({
                src: $(this).attr('src').replace(/.png/g,'_hv.png')
            })
        }
        else if($(this).hasClass('ritem'))
        {
            // Cambiar el src de todos.
            $('img.ritem').each(
            function(){
                $(this).removeClass('active');
                $(this).attr({
                    src: $(this).attr('src').replace(/_hv.png/g,'.png')
                })
            });
            // Cambia el src de este para activarlo.
            $(this).addClass('active');
            $(this).attr({
                src: $(this).attr('src').replace(/.png/g,'_hv.png')
            })
        }
    });
 });

 function clear_select_rule()
 {
    // Cambiar el src de todos.
    $('img.active').each(
    function(){
        $(this).removeClass('active');
        $(this).attr({
            src: $(this).attr('src').replace(/_hv.png/g,'.png')
        })
    });
 }
 
 function save_rule(section,plugin)
 {
    if($("img.click.litem.active").size()&&$("img.click.arrow.active").size()&&$("img.click.ritem.active").size())
    {
        var str="";
        str=$("img.click.litem.active").attr("alt")+"|";
        str+=$("img.click.arrow.active").attr("alt")+"|";
        str+=$("img.click.ritem.active").attr("alt");
        //alert(str);
        complex_ajax_call(str,"rules_container",section,plugin,'save_rule');
        clear_select_rule();
    }
    else
    {
        alert('Complete all the steps before save and apply.')
    }
 }
 function delete_rule(section,plugin,rule_number)
 {
     complex_ajax_call(rule_number,"rules_container",section,plugin,'delete_rule');
 }