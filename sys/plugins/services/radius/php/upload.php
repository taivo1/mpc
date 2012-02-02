<?php
// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.

include_once '../php/paths.php';
include_once '../php/certs.php';

$uploaddir = '../data/';
$name = key($_FILES);
$uploadfile = $uploaddir . $name;

function isAllowedExtension($fileName)
/* ------------------------------------------------------------------------ */
{
  $allowedExtensions = array("pem", "key", "crt");
  return in_array(end(explode(".", $fileName)), $allowedExtensions);
}
/* ------------------------------------------------------------------------ */

function move_file($name, $uploadfile)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    exec("sudo mv ".$uploadfile." ".$paths[$name]);
    exec("sudo chown root:freerad ".$paths[$name]);
    exec("sudo chmod 640 ".$paths[$name]);
}
/* ------------------------------------------------------------------------ */

function check_certs_files ()
/* ------------------------------------------------------------------------ */
{
   global $paths;

   if ( exists_certificates ($paths) )
   {
        echo ",ALL";
        if ( are_cert_and_key_valid($paths) )
        {
            exec ('sudo /etc/init.d/freeradius restart');
            echo ",VALID";
        }
        else echo ",INVALID";
    }
    else
    {
        echo ",NOT_ALL";
    }
}
/* ------------------------------------------------------------------------ */

function is_cert_file_valid ($name, $uploadfile)
/* ------------------------------------------------------------------------ */
{
    exec ("sudo /etc/ssl/sh/mod_crt.sh $uploadfile", $ret);
    $is_valid = $ret[0] == 'VALID_FILE';
    if ($is_valid)
    {
        echo $ret[0];
        move_file ($name, $uploadfile);
    }
    else echo "Invalid certificate file. File not uploaded.";
    return $is_valid;
}
/* ------------------------------------------------------------------------ */

function check_uploaded_file ($name, $uploadfile)
/* ------------------------------------------------------------------------ */
{
    switch ($name)
    {
        case 'cacert':
            $is_valid = is_cert_file_valid($name, $uploadfile);
            break;

        case 'server_cert':
            $is_valid = is_cert_file_valid($name, $uploadfile);
            break;

        case 'server_key':
            exec ("sudo /etc/ssl/sh/mod_key.sh $uploadfile", $ret);
            $is_valid = $ret[0] == 'VALID_FILE' || $ret[0] == 'PASSWORD_REQUIRED';
            if ($is_valid)
            {
                echo $ret[0];
                if ($ret[0] == 'VALID_FILE')
                {
                    move_file ($name, $uploadfile);
                    update_servername();
                }
            }
            else echo "Invalid private key file. File not uploaded.";
            break;

        default:
            echo "File not expected!";
    }

    if ($is_valid)
    {
        check_certs_files ();
    }
    else
    {
        exec ('rm $uploadfile');
    }
}
/* ------------------------------------------------------------------------ */

if (isAllowedExtension($_FILES[$name]['name']))
{
    if (move_uploaded_file($_FILES[$name]['tmp_name'], $uploadfile))
    {
        check_uploaded_file($name, $uploadfile);
    }
    else
    {
        echo "Possible file upload attack!";
    }
}
else
{
    echo "Invalid file type. File not uploaded.";
}

?>