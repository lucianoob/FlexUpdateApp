<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpalmoxarifadoitens.php';
class erpalmoxarifadoitensService
{
	var $tablename = "erpalmoxarifadoitens";
	var $conexao;
	var $query;
	function erpalmoxarifadoitensService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpalmoxarifadoitens($index)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." WHERE ALInAlmoxarifado = '".$index."' ORDER BY ALInId";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpalmoxarifadoitens"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpalmoxarifadoitens($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE ALInId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpalmoxarifadoitens");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpalmoxarifadoitens($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(ALInAlmoxarifado,ALIsTipo,ALIsReferencia,ALIdData,ALInQuantidade,ALIdInclusao,ALIdAlteracao) 
		VALUES 
		('".$obj->ALInAlmoxarifado."','".$obj->ALIsTipo."','".$obj->ALIsReferencia."','".$obj->ALIdData."','".$obj->ALInQuantidade."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpalmoxarifadoitens($obj->ALInAlmoxarifado);
	}
	public function editarerpalmoxarifadoitens($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		ALInAlmoxarifado = '".$obj->ALInAlmoxarifado."',ALIsTipo = '".$obj->ALIsTipo."',ALIsReferencia = '".$obj->ALIsReferencia."',ALIdData = '".$obj->ALIdData."',ALInQuantidade = '".$obj->ALInQuantidade."',ALIdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE ALInId = '".$obj->ALInId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpalmoxarifadoitens($obj->ALInAlmoxarifado);
	}
	public function excluirerpalmoxarifadoitens($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE ALInId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return true;	
	}
	public function excluirtodoserpalmoxarifadoitens($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE ALInAlmoxarifado = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return true;	
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