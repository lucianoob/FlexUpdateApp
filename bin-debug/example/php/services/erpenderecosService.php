<?php
require_once 'ErpConexaoService.php';
require_once 'ErpCorreiosService.php';
require_once 'vo/erpenderecos.php';
class erpenderecosService
{
	var $tablename = "erpenderecos";
	var $conexao;
	var $query;
	function erpenderecosService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpenderecos()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename;
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpenderecos"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function inserirerpenderecos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(ENDnId,ENDsNome,ENDsCep,ENDsEndereco,ENDsNumero,ENDsComplemento,ENDsBairro,ENDsCidade,ENDsEstado,ENDsObservacoes,ENDdAlteracao,ENDdInclusao) 
		VALUES 
		('".$obj->ENDnId."','".$obj->ENDsNome."','".$obj->ENDsCep."','".$obj->ENDsEndereco."','".$obj->ENDsNumero."','".$obj->ENDsComplemento."','".$obj->ENDsBairro."','".$obj->ENDsCidade."','".$obj->ENDsEstado."','".$obj->ENDsObservacoes."','".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpenderecos();
	}
	public function editarerpenderecos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		ENDnId = '".$obj->ENDnId."',ENDsNome = '".$obj->ENDsNome."',ENDsCep = '".$obj->ENDsCep."',ENDsEndereco = '".$obj->ENDsEndereco."',ENDsNumero = '".$obj->ENDsNumero."',ENDsComplemento = '".$obj->ENDsComplemento."',ENDsBairro = '".$obj->ENDsBairro."',ENDsCidade = '".$obj->ENDsCidade."',ENDsEstado = '".$obj->ENDsEstado."',ENDsObservacoes = '".$obj->ENDsObservacoes."',ENDdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE ENDnId = '".$obj->ENDnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpenderecos();
	}
	public function excluirerpenderecos($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE ENDnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpenderecos();	
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