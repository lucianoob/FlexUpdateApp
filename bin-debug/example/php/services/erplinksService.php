<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erplinks.php';
class erplinksService
{
	var $tablename = "erplinks";
	var $conexao;
	var $query;
	function erplinksService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerplinks()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename;
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erplinks"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function inserirerplinks($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(LNKnId,LNKsNome,LNKsURL,LNKsDescricao,LNKdInclusao,LNKdAlteracao) 
		VALUES 
		('".$obj->LNKnId."','".$obj->LNKsNome."','".$obj->LNKsURL."','".$obj->LNKsDescricao."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerplinks();
	}
	public function editarerplinks($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		LNKnId = '".$obj->LNKnId."',LNKsNome = '".$obj->LNKsNome."',LNKsURL = '".$obj->LNKsURL."',LNKsDescricao = '".$obj->LNKsDescricao."',LNKdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE LNKnId = '".$obj->LNKnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerplinks();
	}
	public function excluirerplinks($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE LNKnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerplinks();	
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