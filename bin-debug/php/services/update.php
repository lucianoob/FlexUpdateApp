<?php
class update
{
	
	function update()
	{
		
	}
	public function getConfig($dir)
	{
		$nome = "Setup-";
		
		$file = "../../$dir/ErpiAutomate-inf.xml";
	
		$config = simplexml_load_file($file);
		
		$nome .= $config->sistema->nome."_";
		
		$nome .= str_replace(".", "", $config->sistema->versao);
	
		return $nome;
	}
	public function getDirList()
  	{
	    // array to hold return value
	    $retval = array();
	    $dir = "../../";
	     
	    // add trailing slash if missing
	    if(substr($dir, -1) != "/") $dir .= "/";
	
	    // open pointer to directory and read list of files
	    $d = @dir($dir) or mkdir($dir);	 
	    //die("getFileList: Failed opening directory $dir for reading");
	    while(false !== ($entry = $d->read())) 
	    {
	      // skip hidden files
	      if($entry[0] == ".") continue;
	      
	      if(is_dir("$dir$entry")) 
	      {
	        $retval[] = array(
	          "label" => "$entry (".date("d/m/Y H:i:s", filemtime("$dir$entry")).")",
	          "name" => "$entry"
	        );
	      }
	    }
	    $d->close();
	
	    return $retval;
 	} 
 	public function getFileList($dir, $tipo, $inicial, $final, $recurse=true)
 	{
 		// array to hold return value
 		$retval = array();
 		$dt_inicial = DateTime::createFromFormat('d/m/Y', $inicial);
 		$dt_final = DateTime::createFromFormat('d/m/Y', $final);
 		$dt_file = new DateTime();
 	
 		// add trailing slash if missing
 		if(substr($dir, -1) != "/") $dir .= "/";
 	
 		// open pointer to directory and read list of files
 		$d = @dir($dir) or mkdir($dir);
 		//die("getFileList: Failed opening directory $dir for reading");
 		while(false !== ($entry = $d->read()))
 		{
 			// skip hidden files
 			if($entry[0] == ".") continue;
 			 
 			$dt_file->setTimestamp(filemtime("$dir$entry"));
 			if(is_dir("$dir$entry"))
 			{
 				// 		        $retval[] = array(
 				// 		          "name" => "$entry",
 				// 		          "type" => strtoupper(filetype("$dir$entry")),
 				// 		          "dir" => "$dir",
 				// 		          "size" => 0,
 				// 		          "lastmod" => date("d/m/Y H:i:s", filemtime("$dir$entry"))
 				// 		        );
 				if($recurse && is_readable("$dir$entry/"))
 				{
 					$retval = array_merge($retval, $this->getFileList("$dir$entry/", $tipo, $inicial, $final, true));
 				}
 			} else if(is_readable("$dir$entry"))
 			{
 				if($dt_file >= $dt_inicial && $dt_file <= $dt_final)
 				{
 					$mime = explode(".", $entry);
 					$type = array_pop($mime);
 					if($tipo[0] != "*")
 						if(!in_array($type, $tipo))
 						continue;
 					$retval[] = array(
 							"name" => "$entry",
 							"type" => strtoupper($type),
 							"dir" => "$dir",
 							"size" => $this->filesize_format(filesize("$dir$entry")),
 							"lastmod" =>  date ("d/m/Y H:i:s", filemtime("$dir$entry"))
 					);
 				}
 			}
 		}
 		$d->close();
 	
 		return $retval;
 	}
 	private function filesize_format($filesize)
 	{
 		if($filesize >= (1024*1024))
 			return round($filesize/(1024*1024), 2)." Mb";
 		else if($filesize >= (1024))
 			return round($filesize/(1024), 2)." Kb";
 		else 
 			return round($filesize, 2)." b";
 	}
 	public function createUpdate($name, $files, $filenames)
 	{
 		$path = "../../example/php/temp/$name.zip";
 		$zip = new ZipArchive;
 		if(file_exists($path))
 			unlink($path);
 		$open = $zip->open($path, ZIPARCHIVE::CREATE);
 		if ($open === TRUE)
 		{
 			$cont = 0;
 			for($i=0; $i<count($files); $i++)
 			{
	 			if($zip->addFile($files[$i], $filenames[$i]))
	 				$cont++;
 			}
	 		$zip->close();
	 		$retval = array(
	 				"name" => "$name",
	 				"size" => $this->filesize_format(filesize("$path")),
	 				"lastmod" =>  date ("d/m/Y H:i:s", filemtime("$path")),
	 				"return" => true
	 		);
 		} else
 		{
 			$retval = array(
 					"name" => "$name",
 					"size" => "",
 					"lastmod" =>  "",
 					"return" => false
 			);
 		}
 		return $retval;
 	}
}
//  $test = new update();
// echo("<pre>");
// print_r($test->getFileList('../../../erpjhsobrinho', array("swf"), '03/02/2013', '05/02/2013'));
// echo("</pre>");

// $test = new update();
// if($test->createUpdate(array("../../../erpjhsobrinho/ErpIAutomate-inf.xml", "../../../erpjhsobrinho/model/data/menu.xml"), array("ErpIAutomate-inf.xml", "model/data/menu.xml")))
// 	echo("ok");
// else
// 	echo("erro");

// $test = new update();
// echo("<pre>");
// print_r($test->getDirList());
// echo("</pre>");

// $test = new update();
// echo($test->getConfig("erpjhsobrinho"));

?>