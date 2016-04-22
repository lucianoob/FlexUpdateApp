<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpganhos.php';
class erpganhosService
{
	var $tablename = "erpganhos";
	var $conexao;
	var $query;
	function erpganhosService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpganhos()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename;
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpganhos"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function inserirerpganhos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(GANnId,GANsNome,GANlDescricao,GANdInclusao,GANdAlteracao) 
		VALUES 
		('".$obj->GANnId."','".$obj->GANsNome."','".$obj->GANlDescricao."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpganhos();
	}
	public function editarerpganhos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		GANnId = '".$obj->GANnId."',GANsNome = '".$obj->GANsNome."',GANlDescricao = '".$obj->GANlDescricao."',GANdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE GANnId = '".$obj->GANnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpganhos();
	}
	public function excluirerpganhos($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE GANnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpganhos();	
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