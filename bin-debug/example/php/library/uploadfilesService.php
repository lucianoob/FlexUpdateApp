<?php
$MAXIMUM_FILESIZE = 1024 * 1024 * 20; // 20MB
$path = $_POST["path"];
$codigo = $_POST["id"];
function retira_acentos( $texto )
{
  $array1 = array(   "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç"
                     , "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç", "@", " ");
  $array2 = array(   "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c"
                     , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C", "-", "_" );
  return str_replace( $array1, $array2, $texto );
}
if ($_FILES['Filedata']['size'] <= $MAXIMUM_FILESIZE)
{
	if (!is_dir("../storage/".$path)) 
	{
		mkdir("../storage/".$path, 0777);
	}
	$_FILES['Filedata']['name'] = retira_acentos($_FILES['Filedata']['name']);
	move_uploaded_file($_FILES['Filedata']['tmp_name'], "../storage/".$path."/".$codigo."@".$_FILES['Filedata']['name']);
}
?>