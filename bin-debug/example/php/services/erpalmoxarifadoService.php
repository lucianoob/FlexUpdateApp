<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpalmoxarifado.php';
class erpalmoxarifadoService
{
	var $tablename = "erpalmoxarifado";
	var $conexao;
	var $query;
	function erpalmoxarifadoService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpalmoxarifado()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename;
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpalmoxarifado"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpalmoxarifado($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE ALMnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpalmoxarifado");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpalmoxarifado($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(ALMsCodigo,ALMsTipo,ALMsNome,ALMlDescricao,ALMdInclusao,ALMdAlteracao) 
		VALUES 
		('".$obj->ALMsCodigo."','".$obj->ALMsTipo."','".$obj->ALMsNome."','".$obj->ALMlDescricao."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpalmoxarifado();
	}
	public function editarerpalmoxarifado($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		ALMsCodigo = '".$obj->ALMsCodigo."',ALMsTipo = '".$obj->ALMsTipo."',ALMsNome = '".$obj->ALMsNome."',ALMlDescricao = '".$obj->ALMlDescricao."',ALMdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE ALMnId = '".$obj->ALMnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpalmoxarifado();
	}
	public function excluirerpalmoxarifado($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE ALMnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpalmoxarifado();	
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