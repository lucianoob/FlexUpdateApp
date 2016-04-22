<?php
class ErpListarService 
{
	var $xml = '';
	public function ErpListarService() 
	{
		
	}
	public function listarArquivosXML($folder)
	{
		$this->xml .= "<root>";
		$this->xml .= "\n";
		$this->getXMLList("../storage/".$folder, true);
		$this->xml .= "</root>";
		$result = $this->xml; 
		return $result;
	}
	function getXMLList($dir, $recurse=false)
	{
		// array to hold return value
	    $retval = array();
	
	    // add trailing slash if missing
	    if(substr($dir, -1) != "/") $dir .= "/";
	
	    // open pointer to directory and read list of files
	    $d = @dir($dir) or mkdir($dir);	
	    //die("getFileList: Failed opening directory $dir for reading");
	    $dxml = explode("/", $dir);
	    $tabd = "";
	    $tabf = "";
	    $nl = "\n";
	    for($z=0; $z<count($dxml)-1; $z++)
	    {
	    	$tabd .= "\t";
	    }
	    $tabf .= $tabd."\t";
	    $this->xml .= $tabd.'<folder label="'.$dxml[count($dxml)-2].'">'.$nl;
	    while(false !== ($entry = $d->read())) {
	      // skip hidden files
	      if($entry[0] == ".") continue;
	      if(is_dir("$dir$entry")) {
	        $retval[] = array(
	          "name" => "$entry",
	          "type" => filetype("$dir$entry"),
	          "dir" => "$dir",
	          "size" => 0,
	          "lastmod" => filemtime("$dir$entry")
	        );
	        if($recurse && is_readable("$dir$entry/")) {
	          $retval = array_merge($retval, $this->getXMLList("$dir$entry/", true));
	        }
	      } else if(is_readable("$dir$entry")) {
	      	$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
	      	$mime = finfo_file($finfo, dirname(__FILE__)."/".$dir.$entry);
	        $retval[] = array(
	          "name" => "$entry",
	          "type" => "$mime",
	          "dir" => "$dir",
	          "size" => filesize("$dir$entry"),
	        "lastmod" => filemtime("$dir$entry")
	        );
	    	$this->xml .= $tabf.'<file label="'.$entry.'" type="'.$mime.'" size="'.filesize("$dir$entry").'" lastmod="'.date("d/m/Y H:i:s", filemtime("$dir$entry")).'"/>'.$nl;
	      }
	    }
	    $this->xml .= $tabd.'</folder>'.$nl;
	    $d->close();
	    
	    return $retval;
	}
	function getFileList($dir, $recurse=false)
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
	          "lastmod" => filemtime("$dir$entry")
	        );
	        if($recurse && is_readable("$dir$entry/")) {
	          $retval = array_merge($retval, $this->getFileList("$dir$entry/", true));
	        }
	      } else if(is_readable("$dir$entry")) {
	      	$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
	      	$mime = finfo_file($finfo, dirname(__FILE__)."/".$dir.$entry);
	        $retval[] = array(
	          "name" => "$entry",
	          "type" => "$mime",
	          "dir" => "$dir",
	          "size" => filesize("$dir$entry"),
	        "lastmod" => filemtime("$dir$entry")
	        );
	      }
	    }
	    $d->close();
	
	    return $retval;
 	 } 
}
?>