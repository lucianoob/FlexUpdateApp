<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpunidades.php';
class erpunidadesService
{
	var $tablename = "erpunidades";
	var $conexao;
	var $query;
	function erpunidadesService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpunidades()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." ORDER BY UNDfFator";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpunidades"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listarerpunidadesproduto()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." WHERE UNDbProduto='Y' ORDER BY UNDsTipo";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpunidades"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listarerpunidadestipo($type)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." WHERE UNDsTipo='".$type."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpunidades"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listarerptipos()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT UNDsTipo FROM ".$this->tablename." GROUP BY UNDsTipo";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpunidades($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE UNDnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpunidades");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpunidades($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(UNDnId,UNDsTipo,UNDsNome,UNDsUnidade,UNDnSI,UNDfFator,UNDsOperacao,UNDbProduto,UNDdInclusao,UNDdAlteracao) 
		VALUES 
		('".$obj->UNDnId."','".$obj->UNDsTipo."','".$obj->UNDsNome."','".$obj->UNDsUnidade."','".$obj->UNDnSI."','".$obj->UNDfFator."','".$obj->UNDsOperacao."','".$obj->UNDbProduto."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpunidades();
	}
	public function editarerpunidades($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		UNDnId = '".$obj->UNDnId."',UNDsTipo = '".$obj->UNDsTipo."',UNDsNome = '".$obj->UNDsNome."',UNDsUnidade = '".$obj->UNDsUnidade."',UNDnSI = '".$obj->UNDnSI."',UNDfFator = '".$obj->UNDfFator."',UNDsOperacao = '".$obj->UNDsOperacao."',UNDbProduto = '".$obj->UNDbProduto."',UNDdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE UNDnId = '".$obj->UNDnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpunidades();
	}
	public function excluirerpunidades($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE UNDnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpunidades();	
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