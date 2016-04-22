<?php
require_once 'ErpConexaoService.php';
class erpSistemaService
{
	var $conexao;
	var $query;
	var $query1;
	function erpSistemaService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listar_arquivos_sistema()
	{
		$content = "name;type;dir;size;lastmod\n";
		$files = $this->getFileList('../../', true);
		for($i=0; $i<count($files); $i++)
		{
			$file = $files[$i];
			$content .= $file['name'].';'.$file['type'].';'.$file['dir'].';'.$file['size'].';'.$file['lastmod']."\n";
		}
		$zip = new ZipArchive;
		if ($zip->open('../temp/filesystem.zip', ZIPARCHIVE::CREATE) === TRUE) 
		{
			$zip->addFromString("filesystem.csv", $content);
			$zip->close();
			return true;
		} else
		{
			return false;
		} 
	}
	private function getFileList($dir, $recurse=false)
  	{
	    // array to hold return value
	    $retval = array();
	
	    // add trailing slash if missing
	    if(substr($dir, -1) != "/") $dir .= "/";
	
	    // open pointer to directory and read list of files
	    $d = @dir($dir) or mkdir($dir);	 
	    //die("getFileList: Failed opening directory $dir for reading");
	    while(false !== ($entry = $d->read())) {
	      // skip hidden files
	      if($entry[0] == ".") continue;
	      if(is_dir("$dir$entry")) {
	        $retval[] = array(
	          "name" => "$entry",
	          "type" => filetype("$dir$entry"),
	          "dir" => "$dir",
	          "size" => 0,
	          "lastmod" => date ("d/m/Y H:i:s", filemtime("$dir$entry"))
	        );
	        if($recurse && is_readable("$dir$entry/")) {
	          $retval = array_merge($retval, $this->getFileList("$dir$entry/", true));
	        }
	      } else if(is_readable("$dir$entry")) {
	      	$mime = mime_content_type(dirname(__FILE__)."/".$dir.$entry);
	        $retval[] = array(
	          "name" => "$entry",
	          "type" => "$mime",
	          "dir" => "$dir",
	          "size" => $this->filesize_format(filesize("$dir$entry")),
	          "lastmod" =>  date ("d/m/Y H:i:s", filemtime("$dir$entry"))
	        );
	      }
	    }
	    $d->close();
	
	    return $retval;
 	} 
 	private function filesize_format($filesize)
 	{
 		if($filesize >= (1024*1024))
 			return round($filesize/(1024*1024), 2)."Mb";
 		else if($filesize >= (1024))
 			return round($filesize/(1024), 2)."Kb";
 		else 
 			return round($filesize, 2)."b";
 	}
	public function listar_banco_dados()
	{
		$this->conexao->openConexao();
		$this->query = "SHOW TABLES";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$zip = new ZipArchive;
		if ($zip->open('../temp/databases.zip', ZIPARCHIVE::CREATE) === TRUE) 
		{
			while($table = mysql_fetch_object($result))
			{
				$content = "";
				$this->query1 = "SHOW FULL COLUMNS FROM ".$table->Tables_in_erpjhsobrinho;
				$result1 = mysql_query($this->query1);
				$this->throwExceptionOnError();
				$content .= "Field;";
				$content .= "Type;";
				$content .= "Collation;";
				$content .= "Null;";
				$content .= "Key;";
				$content .= "Default;";
				$content .= "Extra;";
				$content .= "Comment\n";
				while($field = mysql_fetch_object($result1))
				{
					$content .= $field->Field.";";
					$content .= $field->Type.";";
					$content .= $field->Collation.";";
					$content .= $field->Null.";";
					$content .= $field->Key.";";
					$content .= $field->Default.";";
					$content .= $field->Extra.";";
					$content .= utf8_decode($field->Comment);
					$content .= "\n";
				}
			    $zip->addFromString("$table->Tables_in_erpjhsobrinho.csv", $content);
			}
			$this->conexao->closeConexao();
			$zip->close();
			return true;
		} else 
		{
			return false;
		}
	}
	private function throwExceptionOnError($link = null)
	{
		if($link == null) {
			$link = $this->conexao->connection;
		}
		if(mysql_error($link)) {
			$msg = mysql_errno($link) . ": " . mysql_error($link);
			throw new Exception('MySQL Error - '. $msg. '\n Query: '.$this->query);
		}
	}
}
?>