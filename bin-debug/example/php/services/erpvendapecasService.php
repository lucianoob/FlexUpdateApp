<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpvendapecas.php';
class erpvendapecasService
{
	var $tablename = "erpvendapecas";
	var $conexao;
	var $query;
	function erpvendapecasService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpvendapecas($index)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." WHERE VEInVenda = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpvendapecas"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpvendapecas($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE VEInId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpvendapecas");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpvendapecas($objcol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($objcol); $i++)
		{
			$obj = $objcol[$i];
			$this->query = "INSERT INTO ".$this->tablename."
			(VEInVenda,VEInItem,VEInPeca,VEInLote,VEIsDescricao,VEInQuantidade,VEIfCusto,VEIfLucro,VEIfValor,VEIsPrazoEntrega,VEIdInclusao,VEIdAlteracao) 
			VALUES 
			('".$obj->VEInVenda."','".$obj->VEInItem."','".$obj->VEInPeca."','".$obj->VEInLote."','".$obj->VEIsDescricao."','".$obj->VEInQuantidade."','".$obj->VEIfCusto."','".$obj->VEIfLucro."','".$obj->VEIfValor."','".$obj->VEIsPrazoEntrega."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function editarerpvendapecas($objcol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($objcol); $i++)
		{
			$obj = $objcol[$i];
			if(!$obj->VEInId)
			{
				$this->query = "INSERT INTO ".$this->tablename."
				(VEInVenda,VEInItem,VEInPeca,VEInLote,VEIsDescricao,VEInQuantidade,VEIfCusto,VEIfLucro,VEIfValor,VEIsPrazoEntrega,VEIdInclusao,VEIdAlteracao) 
				VALUES 
				('".$obj->VEInVenda."','".$obj->VEInItem."','".$obj->VEInPeca."','".$obj->VEInLote."','".$obj->VEIsDescricao."','".$obj->VEInQuantidade."','".$obj->VEIfCusto."','".$obj->VEIfLucro."','".$obj->VEIfValor."','".$obj->VEIsPrazoEntrega."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			} else
			{
				$this->query = "UPDATE ".$this->tablename." SET
				VEInVenda = '".$obj->VEInVenda."',VEInItem = '".$obj->VEInItem."',VEInPeca = '".$obj->VEInPeca."',VEInLote = '".$obj->VEInLote."',VEIsDescricao = '".$obj->VEIsDescricao."',VEInQuantidade = '".$obj->VEInQuantidade."',VEIfCusto = '".$obj->VEIfCusto."',VEIfLucro = '".$obj->VEIfLucro."',VEIfValor = '".$obj->VEIfValor."',VEIsPrazoEntrega = '".$obj->VEIsPrazoEntrega."',VEIdAlteracao = '".date("Y-m-d H:i:s")."'
				WHERE VEInId = '".$obj->VEInId."'";
			}
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function excluirerpvendapecas($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE VEInVenda = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
	}
	public function excluirerpvendapeca($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE VEInId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
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