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

function update_servername()
/* ------------------------------------------------------------------------ */
{
    global $paths;

    exec ("sudo openssl x509 -noout -in ".$paths['server_cert']." -subject", $ssl);
    preg_match('/CN=(.*)/', $ssl[0], $matches);

    $sed = 'sudo sed \'/ServerName/c\\ServerName "'.$matches[1].'"\' '.
           $paths['ap2_httpd']." > ../data/temp_httpd";

    exec ($sed);
    exec ("sudo mv ../data/temp_httpd ".$paths['ap2_httpd']);
    exec ("sudo chown root:root ".$paths['ap2_httpd']);
}
/* ------------------------------------------------------------------------ */

function move_file($name, $uploadfile)
/* ------------------------------------------------------------------------ */
{
    global $paths;

    exec("sudo mv ".$uploadfile." ".$paths[$name]);
    exec("sudo chown root:root ".$paths[$name]);
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
            exec ('sudo a2ensite default-ssl');
            exec ('sudo /etc/init.d/apache2 force-reload');
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

function check_uploaded_file ($name, $uploadfile)
/* ------------------------------------------------------------------------ */
{
    switch ($name)
    {
        case 'server_cert':
            exec ("sudo /etc/ssl/sh/mod_crt.sh $uploadfile", $ret);
            $is_valid = $ret[0] == 'VALID_FILE';
            if ($is_valid)
            {
                echo $ret[0];
                move_file ($name, $uploadfile);
            }
            else echo "Invalid certificate file. File not uploaded.";
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