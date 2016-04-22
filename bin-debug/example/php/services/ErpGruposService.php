<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpgrupos.php';
class erpgruposService
{
	var $tablename = "erpgrupos";
	var $conexao;
	var $query;
	function erpgruposService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpgrupos()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename;
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpgrupos"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpgrupos($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE GRPnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpgrupos");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpgrupos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(GRPnId,GRPsNome,GRPlDescricao,GRPdAlteracao,GRPdInclusao) 
		VALUES 
		('".$obj->GRPnId."','".$obj->GRPsNome."','".$obj->GRPlDescricao."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpgrupos();
	}
	public function editarerpgrupos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		GRPnId = '".$obj->GRPnId."',GRPsNome = '".$obj->GRPsNome."',GRPlDescricao = '".$obj->GRPlDescricao."',GRPdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE GRPnId = '".$obj->GRPnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpgrupos();
	}
	public function excluirerpgrupos($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE GRPnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpgrupos();	
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