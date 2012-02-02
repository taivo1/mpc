<?php
/*
 * Copyright (C) 2008 Libelium Comunicaciones Distribuidas S.L.
 *
 * This file is part of Meshlium Manager System.
 * Meshlium Manager System will be released as free software; until then you cannot redistribute it
 * without express permission by libelium. 
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * 
 *
 * Version 0.1.0 
 *  Author: Octavio Benedi Sanchez
 */
function choose_dir ($dir, $id, $hide=false,$script='',$show='1')
{
// Este php muestrara los directorios que hay dentro de un directorio en un campo select. Nada más ni nada menos.
// No deja elegir el directorio . o el .. para evitar problemas de seguridad en su uso,  pero basta cambiar el if
// para que deje. Devuelve la cadena HTML.
unset ($list);
unset ($files);
unset ($file);
if (file_exists($dir))
{
	$files = scandir($dir);
	$list="<select id=".$id;
	$list.= ' onclick="'.$script.'" '; //PARA EL ONCHANGE
	$list.=" size='".$show."'>";
	//$list.="<option value=".GENERAL_CHOOSE.">".GENERAL_CHOOSE."</option>";
	foreach ($files as $file)
	{
		if ($hide)
		{
			if (is_dir($dir."/".$file) && $file[0]!=".")		
			{
				$list.="<option value=".$file.">".$file."</option>";		
			}
		}
		else
		{
			if (is_dir($dir."/".$file) && $file!="." && $file!="..")
			{
				$list.="<option value=".$file.">".$file."</option>";
			}
		}
	}
	$list.="</select>";
}
else
{
	$list="<select id=".$id.">";
	$list.="</select>";	
}

return $list;
}
function choose_file ($dir, $id, $hide=false,$script='',$show='1')
{
// Este php muestrara los archivos que hay dentro de un directorio en un campo select. Nada más ni nada menos.
// No deja elegir el directorio . o el .. para evitar problemas de seguridad en su uso,  pero basta cambiar el if
// para que deje. Devuelve la cadena HTML.
unset ($list);
unset ($files);
unset ($file);
if (file_exists($dir))
{
	$files = scandir($dir);
	$list="<select id=".$id;
	$list.= ' onchange="'.$script.'" '; //PARA EL ONCHANGE
	$list.=" size='".$show."'>";
	foreach ($files as $file)
	{
		if ($hide)
		{
			if (!(is_dir($dir."/".$file)) && $file[0]!=".")
			{
				$list.="<option value=".$file.">".$file."</option>";		
			}
		}
		else
		{
			if (!(is_dir($dir."/".$file)) && $file!="." && $file!="..")
			{
				$list.="<option value=".$file.">".$file."</option>";		
			}
		}
		
	}
	$list.="</select>";
}
else
{
	$list="<select id=".$id.">";
	$list.="</select>";
}
return $list;	
}
?>