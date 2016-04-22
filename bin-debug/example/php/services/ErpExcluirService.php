<?php
class ErpExcluirService 
{
	public function ErpExcluirService() 
	{
		
	}
	public function excluirImage($folder, $filename)
	{
		$result = "";
		if(file_exists("../photos/".$folder."/".$filename))
			$result = unlink("../photos/".$folder."/".$filename);
		return $result;
	}
	public function excluirArquivo($folder, $filename)
	{
		$result = "";
		if(file_exists("../storage/".$folder."/".$filename))
			$result = unlink("../storage/".$folder."/".$filename);
		return $result;
	}
}
?>