<?php
class ErpSalvarService 
{
	public function ErpSalvarService() 
	{
		
	}
	public function saveImage($folder, $content, $filename)
	{
		if (!is_dir("../photos/".$folder)) 
			mkdir("../photos/".$folder, 0777);
		$data = $content->data;
		$result = file_put_contents("../photos/".$folder."/".$filename, $data );
		return $result;
	}
	function mysql_escape_mimic($inp) 
	{ 
		if(is_array($inp)) 
			return array_map(__METHOD__, $inp); 

		if(!empty($inp) && is_string($inp)) 
		{ 
			return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp); 
		}	 

		return $inp; 
	}
}
?>