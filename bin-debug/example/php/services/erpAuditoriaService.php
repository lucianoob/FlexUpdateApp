<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpauditoria.php';
class erpauditoriaService
{
	var $tablename = "erpauditoria";
	var $conexao;
	var $query;
	function erpauditoriaService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpauditoria()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename;
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpauditoria"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpauditoria($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE ADTnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpauditoria");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpauditoria($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(ADTnId,ADTsPage,ADTlQueryOld,ADTlQueryNew,ADTdInclusao) 
		VALUES 
		('".$obj->ADTnId."','".$obj->ADTsPage."','".$obj->ADTlQueryOld."','".$obj->ADTlQueryNew."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpauditoria();
	}
	public function editarerpauditoria($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		ADTnId = '".$obj->ADTnId."',ADTsPage = '".$obj->ADTsPage."',ADTlQueryOld = '".$obj->ADTlQueryOld."',ADTlQueryNew = '".$obj->ADTlQueryNew."'
		WHERE ADTnId = '".$obj->ADTnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpauditoria();
	}
	public function excluirerpauditoria($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE ADTnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpauditoria();	
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