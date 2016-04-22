<?php 
require_once 'ErpConexaoService.php';

class erpUpdateService
{
	var $ErpConexao;
	var $ErpQuery;
	function erpUpdateService()
	{
		$this->ErpConexao = new ErpConexaoService();
	}
	public function checkUpdate($url)
	{
		if($stream=fopen($url,"r"))
        {
            $file_contents = stream_get_contents($stream);
            return $file_contents;
		}
	}
	public function checkPasswordUpdate($password, $md5)
	{
        return md5($password) == $md5;
	}
	public function downloadUpdate($url, $dir, $file_name = NULL)
	{ 
        set_time_limit(0); 
		ini_set("memory_limit","64M");
	    if($file_name == NULL){ $file_name = basename($url);} 
	    $url_stuff = parse_url($url); 
	    $port = isset($url_stuff['port']) ? $url_stuff['port'] : 80; 
	
	    $fp = fsockopen($url_stuff['host'], $port); 
	    if(!$fp)
	    { 
	    	return false;
	    } 
	
	    $query  = 'GET ' . $url_stuff['path'] . " HTTP/1.0\n"; 
	    $query .= 'Host: ' . $url_stuff['host']; 
	    $query .= "\n\n"; 
	
	    fwrite($fp, $query); 
	    
	    $buffer = "";
	
	    while ($tmp = fread($fp, 8192))   
	    { 
	        $buffer .= $tmp; 
	    } 
	
	    preg_match('/Content-Length: ([0-9]+)/', $buffer, $parts); 
	    $file_binary = substr($buffer, - $parts[1]); 
	    if($file_name == NULL)
	    { 
	        $temp = explode(".",$url); 
	        $file_name = $temp[count($temp)-1]; 
	    } 
	    $file_open = fopen($dir . "/" . $file_name,'w'); 
	    if(!$file_open)
	    { 
	    	return false;
	    } 
	    fwrite($file_open,$file_binary); 
	    fclose($file_open); 
	    return $file_name; 
	}
	public function extractSimpleUpdate($zipFile = '' )
	{    
		$zip = zip_open('../temp/'.$zipFile);
		if (!is_dir('../temp/zip')) 
			mkdir('../temp/zip', 0777);
		if ($zip) {
		  while ($zip_entry = zip_read($zip)) {
		    $fp = fopen("../temp/zip/".zip_entry_name($zip_entry), "w");
		    if (zip_entry_open($zip, $zip_entry, "r")) {
		      $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
		      fwrite($fp,"$buf");
		      zip_entry_close($zip_entry);
		      fclose($fp);
		    }
		  }
		  zip_close($zip);
		}
	    return true;
 	}
	public function extractUpdate($file)
	{
	
	     $zip=zip_open('../temp/'.$file);
	     if(!$zip) 
	     {
	     	return("Unable to proccess file '{$file}'");
	     }
	
	     $e='';
	
		if (!is_dir('../temp/zip')) 
			mkdir('../temp/zip', 0777);
	     while($zip_entry=zip_read($zip)) 
	     {
	        $zdir=dirname(zip_entry_name($zip_entry));
	        $zname=zip_entry_name($zip_entry);
	
	        if(!zip_entry_open($zip,$zip_entry,"r")) {$e.="Unable to proccess file '{$zname}'";continue;}
	        if(!is_dir("../temp/zip/".$zdir)) 
	        	$this->mkdirr("../temp/zip/".$zdir,0777);
	
	        #print "{$zdir} | {$zname} \n";
	
	        $zip_fs=zip_entry_filesize($zip_entry);
	        if(empty($zip_fs)) continue;
	
	        $zz=zip_entry_read($zip_entry,$zip_fs);
	
	        $z=fopen("../temp/zip/".$zname,"w");
	        fwrite($z,$zz);
	        fclose($z);
	        zip_entry_close($zip_entry);
	
	     } 
	     zip_close($zip);
		 $this->rrmdir("../temp/".$file); 
	     return($e);
	 }
 	public function installUpdate($dirRoot)
	{    
		$this->rcopy("../temp/zip", "../../");
		$this->rrmdir("../temp/zip");
		return true;
 	}
 	public function sqlUpdate($file)
	{    
		$result = false;
		if(file_exists("../temp/zip/updateSQL.sql"))
		{
			if($file_content=file_get_contents("../temp/zip/updateSQL.sql"))
	        {
				$this->ErpConexao = new ErpConexaoService();
	        	$this->ErpConexao->openConexao();
	        	$sql = explode(";", $file_content);
	        	for($i=0; $i<count($sql)-1; $i++)
	        	{
	        		$this->ErpQuery = $sql[$i];
		        	$result = mysql_query($this->ErpQuery);
					$this->throwExceptionOnError();
					sleep(2);
	        	}
	        	$this->ErpConexao->closeConexao();
	        	$result = unlink("../temp/zip/updateSQL.sql");
			} else
				$result = false;
		} else
			$result = true;
		return $result;
 	}
 	public function rcopy($src, $dst) 
	{
	   //if (file_exists($dst)) rrmdir($dst);
	   if (is_dir($src)) 
	   {
		 if (!is_dir($dst)) 
			mkdir($dst, 0777);
	     $files = scandir($src);
	     foreach ($files as $file)
	     {
	     	if ($file != "." && $file != "..") 
	     		$this->rcopy("$src/$file", "$dst/$file");
	     } 
	   }
	   else if (file_exists($src)) 
	   	@copy($src, $dst);
	}
	public function rrmdir($dir) 
	{
	   if (is_dir($dir)) 
	   {
	     $files = scandir($dir);
	     foreach ($files as $file)
	     {
	     	if ($file != "." && $file != "..") 
	     		$this->rrmdir("$dir/$file");
	     }
	     rmdir($dir);
	   }
	   else if(file_exists($dir)) 
	   	 unlink($dir);
	}
	public function mkdirr($pn,$mode=null) 
	{
	
	   if(is_dir($pn)||empty($pn)) return true;
	   $pn=str_replace(array('/', ''),DIRECTORY_SEPARATOR,$pn);
	
	   if(is_file($pn)) {trigger_error('mkdirr() File exists', E_USER_WARNING);return false;}
	
	   $next_pathname=substr($pn,0,strrpos($pn,DIRECTORY_SEPARATOR));
	   if($this->mkdirr($next_pathname,$mode)) {if(!file_exists($pn)) {return mkdir($pn,$mode);} }
	   return false;
	}
  	public function downloadFOpenUpdate($url, $dir, $file_name = NULL)
	{ 
    	set_time_limit(0);
		ini_set("memory_limit","64M");
		$chunksize = 1*(1024*1024); // how many bytes per chunk 
	    $buffer = ''; 
	    $cnt =0; 
	    // $handle = fopen($filename, 'rb'); 
	    $handle = fopen($url, 'rb'); 
	    if ($handle === false) { 
	        return false; 
	    } 
	    while (!feof($handle)) { 
	        $buffer = fread($handle, $chunksize); 
	        echo $buffer; 
	        ob_flush(); 
	        flush(); 
	        if ($retbytes) { 
	            $cnt += strlen($buffer); 
	        } 
	    } 
	        $status = fclose($handle); 
	    if ($retbytes && $status) { 
	        return $cnt; // return num. bytes delivered like readfile() does. 
	    } 
	    return $status;
	}
	private function throwExceptionOnError($link = null) 
	{
		if($link == null) {
			$link = $this->ErpConexao->connection;
		}
		if(mysql_error($link)) {
			$msg = mysql_errno($link) . ": " . mysql_error($link);
			throw new Exception('MySQL Error - '. $msg);
		}		
	}
}
?>