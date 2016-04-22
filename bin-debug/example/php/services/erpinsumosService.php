<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpinsumos.php';
class erpinsumosService
{
	var $tablename = "erpinsumos";
	var $conexao;
	var $query;
	function erpinsumosService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpinsumos()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename;
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpinsumos"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpinsumos($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE INSnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpinsumos");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpinsumos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(INSnId,INSsTipo,INSsNome,INSfValor,INSnHoras,INSnMinutos,INSlDescricao,INSdInclusao,INSdAlteracao) 
		VALUES 
		('".$obj->INSnId."','".$obj->INSsTipo."','".$obj->INSsNome."','".$obj->INSfValor."','".$obj->INSnHoras."','".$obj->INSnMinutos."','".$obj->INSlDescricao."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpinsumos();
	}
	public function editarerpinsumos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		INSnId = '".$obj->INSnId."',INSsTipo = '".$obj->INSsTipo."',INSsNome = '".$obj->INSsNome."',INSfValor = '".$obj->INSfValor."',INSnHoras = '".$obj->INSnHoras."',INSnMinutos = '".$obj->INSnMinutos."',INSlDescricao = '".$obj->INSlDescricao."',INSdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE INSnId = '".$obj->INSnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpinsumos();
	}
	public function excluirerpinsumos($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE INSnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpinsumos();	
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