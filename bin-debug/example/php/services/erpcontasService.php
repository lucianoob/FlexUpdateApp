<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpcontas.php';
class erpcontasService
{
	var $tablename = "erpcontas";
	var $conexao;
	var $query;
	function erpcontasService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpcontas()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename;
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpcontas"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function inserirerpcontas($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(CTAnId,CTAsNome,CTAsBanco,CTAsNumeroBanco,CTAsAgencia,CTAsTipo,CTAsNumero,CTAsTitular,CTAbCheque,CTAlObservacoes,CTAdInclusao,CTAdAlteracao) 
		VALUES 
		('".$obj->CTAnId."','".$obj->CTAsNome."','".$obj->CTAsBanco."','".$obj->CTAsNumeroBanco."','".$obj->CTAsAgencia."','".$obj->CTAsTipo."','".$obj->CTAsNumero."','".$obj->CTAsTitular."','".$obj->CTAbCheque."','".$obj->CTAlObservacoes."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpcontas();
	}
	public function editarerpcontas($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		CTAnId = '".$obj->CTAnId."',CTAsNome = '".$obj->CTAsNome."',CTAsBanco = '".$obj->CTAsBanco."',CTAsNumeroBanco = '".$obj->CTAsNumeroBanco."',CTAsAgencia = '".$obj->CTAsAgencia."',CTAsTipo = '".$obj->CTAsTipo."',CTAsNumero = '".$obj->CTAsNumero."',CTAsTitular = '".$obj->CTAsTitular."',CTAbCheque = '".$obj->CTAbCheque."',CTAlObservacoes = '".$obj->CTAlObservacoes."',CTAdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE CTAnId = '".$obj->CTAnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpcontas();
	}
	public function excluirerpcontas($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE CTAnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpcontas();	
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