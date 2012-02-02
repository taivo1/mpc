//<![CDATA[

var editing=false;
var num_slot = 0;

function convert_to_edit_form(username)
{
    $('#user_form_content').remove().appendTo('#user_'+username+'_panel');
    //$('#user_form_content').css('background-color', '#fff');
    $('#user_form_content').addClass('edit_usr_entry');
    $('#user_form_content').addClass('radius_border');
}

function convert_to_new_form()
{
    $('#user_form_content').remove().appendTo('#user_form_container');
    //$('#user_form_content').css('background-color', '#eee');
    $('#user_form_content').removeClass('edit_usr_entry');
    $('#user_form_content').removeClass('radius_border');
}

function reset_user_form ()
{
    $('#user_form_panel').hide();
    document.forms['user_form'].reset();
    $('#username').removeAttr('readonly');
    $('#username').removeClass('readonly');
    $('#timeout_ckb').removeAttr('checked');
    onchange_timeout_ckb();
    $('.ts_row').remove();
    $('#user_create_form_btn').unbind("click");
    $('#user_cancel_form_btn').unbind("click");
    clear_test_alerts('user_form');
    num_slot = 0;
}

function cancel_new_user()
{
    convert_to_new_form();
    reset_user_form ();
    editing=false;
}

function show_new_user_form()
{
    if ( !editing )
    {
        editing=true;
        convert_to_new_form();
        reset_user_form();
        $('#user_create_form_btn').val ('create');
        $('#user_create_form_btn').click(create_user);
        $('#user_cancel_form_btn').click(cancel_new_user);
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
        var params = new Array();
        params['nslots'] = num_slot.toString();
        nv_ajax_call (php_section, php_plugin, 'create_user', 'user_form', params);
    }
}

function delete_user(username)
{
    if ( !editing )
    {
        var params = new Array();
        params['username'] = username;
        nv_ajax_call(php_section, php_plugin, 'delete_user', '', params);
        //reset_user_form();
    }
    else
    {
        alert('You have to cancel the current operation.');
    }
}

function update_user()
{
    if(!ms_check_form_fields('user_form'))
    {
        var params = new Array();
        params['nslots'] = num_slot.toString();
        nv_ajax_call (php_section, php_plugin, 'update_user', 'user_form', params);
    }
}

function cancel_edit_usr(username)
{
    cancel_new_user();
    $('#edit_usr_'+username+'_btn').show();
    $('#delete_usr_'+username+'_btn').show();
    editing=false;
}

function edit_user(username)
{
    if ( !editing )
    {
        editing=true;
        $('#edit_usr_'+username+'_btn').hide();
        $('#delete_usr_'+username+'_btn').hide();
        convert_to_edit_form(username);
        reset_user_form();
        $('#user_create_form_btn').val ('Update');
        $('#user_create_form_btn').click(update_user);
        $('#user_cancel_form_btn').click(function(){
            cancel_edit_usr(username);
        });

        var params = new Array();
        params['username'] = username;
        nv_ajax_call (php_section, php_plugin, 'get_user_info', 'user_form', params);
    }
    else
    {
        alert('You have to cancel the current operation.');
    }
}

function add_time_slot(dweek, hour_start, min_start, hour_end, min_end)
{
   /* Default values */
   if (dweek == undefined) dweek = '';
   if (hour_start == undefined) hour_start = '';
   if (min_start == undefined) min_start = '';
   if (hour_end == undefined) hour_end = '';
   if (min_end == undefined) min_end = '';

   var html = '\
  <div id="ts_' + num_slot + '" class="ts_row">\
      <select name="dweek_'+ num_slot + '" id="dweek_' + num_slot + '">\
   ';

    if (dweek == 'Al')
    {
       html += '<option value="Al" selected>All</option>\n';
    }
    else
    {
       html += '<option value="Al">All</option>\n';
    }

    if (dweek == 'Wk')
    {
       html += '<option value="Wk" selected>Monday-Friday</option>\n';
    }
    else
    {
       html += '<option value="Wk">Monday-Friday</option>\n';
    }

    html += '<option disabled="disabled">-----------------</option>\n';

    if (dweek == 'Mo')
    {
       html += '<option value="Mo" selected>Monday</option>\n';
    }
    else
    {
       html += '<option value="Mo">Monday</option>\n';
    }

    if (dweek == 'Tu')
    {
       html += '<option value="Tu" selected>Tuesday</option>\n';
    }
    else
    {
       html += '<option value="Tu">Tuesday</option>\n';
    }

    if (dweek == 'We')
    {
       html += '<option value="We" selected>Wednesday</option>\n';
    }
    else
    {
       html += '<option value="We">Wednesday</option>\n';
    }

    if (dweek == 'Th')
    {
       html += '<option value="Th" selected>Thursday</option>\n';
    }
    else
    {
       html += '<option value="Th">Thursday</option>\n';
    }

    if (dweek == 'Fr')
    {
       html += '<option value="Fr" selected>Friday</option>\n';
    }
    else
    {
       html += '<option value="Fr">Friday</option>\n';
    }

    if (dweek == 'Sa')
    {
       html += '<option value="Sa" selected>Saturday</option>\n';
    }
    else
    {
       html += '<option value="Sa">Saturday</option>\n';
    }

    if (dweek == 'Su')
    {
       html += '<option value="Su" selected>Sunday</option>\n';
    }
    else
    {
       html += '<option value="Su">Sunday</option>\n';
    }

    html += '\
      </select>\
      <table><tr>\
      <td>From</td>\
      <td><input type="text" name="ts_hour_start_'+ num_slot  +'" id="ts_hour_start_'+ num_slot +
           '" size="2" maxlength="2" value="' + hour_start + '"\
           class="ms_mandatory ms_numerical" /></td><td>:</td>\
      <td><input type="text" name="ts_min_start_'+ num_slot  +'" id="ts_min_start_'+ num_slot  +
           '" size="2" maxlength="2" value="' + min_start + '"\
           class="ms_mandatory ms_numerical" /></td>\
      <td>To</td>\
      <td><input type="text" name="ts_hour_end_'+ num_slot  +'" id="ts_hour_end_'+ num_slot  +
           '" size="2" maxlength="2" value="' + hour_end + '"\
           class="ms_mandatory ms_numerical" /></td><td>:</td>\
      <td><input type="text" name="ts_min_end_'+ num_slot  +'" id="ts_min_end_'+ num_slot  +
           '" size="2" maxlength="2" value="' + min_end + '"\
           class="ms_mandatory ms_numerical" /></td>\
      <td><span class="ref" onclick="remove_time_slot(' + num_slot + ')">Remove</span></td>\
      </tr>\
      <tr>\
      </table>\
    </div>';
      /*+'
      <td></td>\
      <td><div id="ts_hour_start_'+num_slot+'_ms_cte"></div></td>\
      <td></td>\
      <td><div id="ts_min_start_'+num_slot+'_ms_cte"></div></td>\
      <td></td>\
      <td><div id="ts_hour_end_'+num_slot+'_ms_cte"></div></td>\
      <td></td>\
      <td><div id="ts_min_end_'+num_slot+'_ms_cte"></div></td>\
      </tr>\
      </table>\*/
      
    num_slot = num_slot + 1;
    $('#add_ts_btn').before (html);
}

function remove_time_slot(num)
{
  $('#ts_'+num).remove ();
}

function onchange_timeout_ckb()
{
    if ($('#timeout_ckb').attr('checked')) {
        $('#session_timeout').removeAttr('readonly');
        $('#session_timeout').removeClass('readonly');
        $('#session_timeout').addClass('ms_mandatory');
    } else {
        $('#session_timeout').attr('readonly', 'true');
        $('#session_timeout').addClass('readonly');
        $('#session_timeout').removeClass('ms_mandatory');
    }
}

//]]>
