<?php
// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.

$uploaddir = '../data/';
$uploadfile = $uploaddir . basename($_FILES['file']['name']);

function isAllowedExtension($fileName) {
  $allowedExtensions = array("tgz", "tar.gz");
  return in_array(end(explode(".", $fileName)), $allowedExtensions);
}

if(isAllowedExtension($_FILES['file']['name'])) {
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile))
    {
        echo "<div><b>File</b> is valid, and was successfully uploaded.</div>";
        exec("cd $uploaddir; tar zxvf ".basename($_FILES['file']['name'])." ;rm -f ".basename($_FILES['file']['name']));
        //echo "<pre>".print_r($ret,true)."</pre>";
    }
    else
    {
        echo "<div>Possible file upload attack!</div>";
    }
}
else
{
    echo "<div>Invalid file type. File not uploaded.</div>";
}
?>