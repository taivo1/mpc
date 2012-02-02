//<![CDATA[

$(document).ready(function(){
  check_conditions();
});

var editing=false;

function check_conditions()
{
    $('#user_form_panel').hide();
}

function reset_user_form ()
{
    $('#user_form_panel').hide();
    document.forms['user_form'].reset();
}

function cancel_new()
{
    reset_user_form ();
    editing=false;
}

function show_new_user_form()
{
    if ( !editing )
    {
        editing=true;
        clear_test_alerts('user_form');
        reset_user_form();
        $('#user_form_panel').show();
    }
    else
    {
        alert('You have to cancel the current operation.');
    }
}

function create_user()
{    
    if(!ms_check_form_fields('user_form'))
    {
        nv_ajax_call (php_section, php_plugin, 'create_user', 'user_form', new Array());
    }
}

function delete_user(username)
{
    if ( !editing )
    {
        var params = new Array();
        params['username'] = username;
        nv_ajax_call(php_section, php_plugin, 'delete_user', '', params);
        reset_user_form();
    }
    else
    {
        alert('You have to cancel the current operation.');
    }
}

function update_user(username)
{
    if(!ms_check_form_fields('edit_user_form'))
    {
        var params = new Array();
        params['username'] = username;
        nv_ajax_call (php_section, php_plugin, 'update_user', 'edit_user_form', params);
    }
}

function cancel_edit(username)
{
    $('#edit_user_'+username).remove();
    $('#change_pass_'+username).show();
    $('#delete_'+username).show();
    editing=false;
}

function edit_user(username)
{
    if ( !editing )
    {
        editing=true;
        $('#change_pass_'+username).hide();
        $('#delete_'+username).hide();

        var row = '\n\
        <div id="edit_user_'+username+'">\n\
        <form id="edit_user_form" name="edit_user_form">\n\
        <table class="edit radius_border">\n\
        <tr>\n\
           <td class="right">Password</td>\n\
           <td>\n\
               <input type="password" class="ms_mandatory" \n\
               id="'+username+'_password" name="password" />\n\
           </td>\n\
           <td class="right">Confirm password</td>\n\
           <td>\n\
               <input type="password" class="ms_mandatory" \n\
               id="'+username+'_cnf_password" name="cnf_password" />\n\
           </td>\n\
           <td class="buttons">\n\
               <input type="button" onclick="update_user(\'' + username + '\')" value="ok">\n\
               <input type="button" onclick="cancel_edit(\'' + username + '\')" value="cancel">\n\
           </td>\n\
        </tr>\n\
        <tr>\n\
           <td></td>\n\
           <td>\n\
             <div id="'+username+'_password_ms_cte"></div>\n\
           </td>\n\
           <td></td>\n\
           <td>\n\
             <div id="'+username+'_cnf_password_ms_cte"></div>\n\
           </td>\n\
        </tr>\n\
        </table>\n\
        </form>\n\
        </div>\n\
        ';

        $('#user_'+username).after(row);
    }
    else
    {
        alert('You have to cancel the current operation.');
    }
}

//]]>
