<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpservicos.php';
class erpservicosService
{
	var $tablename = "erpservicos";
	var $conexao;
	var $query;
	function erpservicosService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpservicos()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename;
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpservicos"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpservicos($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE SERnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpservicos");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpservicos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(SERnId,SERsTipo,SERsCodigo,SERsDescricao,SERsTempo,SERnUnidade,SERfValor,SERdInclusao,SERdAlteracao) 
		VALUES 
		('".$obj->SERnId."','".$obj->SERsTipo."','".$obj->SERsCodigo."','".$obj->SERsDescricao."','".$obj->SERsTempo."','".$obj->SERnUnidade."','".$obj->SERfValor."','".$obj->SERdInclusao."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpservicos();
	}
	public function editarerpservicos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		SERnId = '".$obj->SERnId."',SERsTipo = '".$obj->SERsTipo."',SERsCodigo = '".$obj->SERsCodigo."',SERsDescricao = '".$obj->SERsDescricao."',SERsTempo = '".$obj->SERsTempo."',SERnUnidade = '".$obj->SERnUnidade."',SERfValor = '".$obj->SERfValor."',SERdInclusao = '".$obj->SERdInclusao."',SERdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE SERnId = '".$obj->SERnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpservicos();
	}
	public function excluirerpservicos($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE SERnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpservicos();	
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