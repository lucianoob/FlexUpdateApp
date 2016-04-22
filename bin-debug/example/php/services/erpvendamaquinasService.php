<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpvendamaquinas.php';
class erpvendamaquinasService
{
	var $tablename = "erpvendamaquinas";
	var $conexao;
	var $query;
	function erpvendamaquinasService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpvendamaquinas($index)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." WHERE VEInVenda = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpvendamaquinas"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpvendamaquinas($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE VEInId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpvendamaquinas");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpvendamaquinas($objcol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($objcol); $i++)
		{
			$obj = $objcol[$i];
			$this->query = "INSERT INTO ".$this->tablename."
			(VEInVenda,VEInItem,VEIsDescricao,VEIfValor,VEIsPrazoEntrega,VEIdInclusao,VEIdAlteracao) 
			VALUES 
			('".$obj->VEInVenda."','".$obj->VEInItem."','".$obj->VEIsDescricao."','".$obj->VEIfValor."','".$obj->VEIsPrazoEntrega."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function editarerpvendamaquinas($objcol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($objcol); $i++)
		{
			$obj = $objcol[$i];
			if(!$obj->VEInId)
			{
				$this->query = "INSERT INTO ".$this->tablename."
				(VEInVenda,VEInItem,VEIsDescricao,VEIfValor,VEIsPrazoEntrega,VEIdInclusao,VEIdAlteracao) 
				VALUES 
				('".$obj->VEInVenda."','".$obj->VEInItem."','".$obj->VEIsDescricao."','".$obj->VEIfValor."','".$obj->VEIsPrazoEntrega."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			} else
			{
				$this->query = "UPDATE ".$this->tablename." SET
				VEInVenda = '".$obj->VEInVenda."',VEInItem = '".$obj->VEInItem."',VEIsDescricao = '".$obj->VEIsDescricao."',VEIfValor = '".$obj->VEIfValor."',VEIsPrazoEntrega = '".$obj->VEIsPrazoEntrega."',VEIdAlteracao = '".date("Y-m-d H:i:s")."'
				WHERE VEInId = '".$obj->VEInId."'";
			}
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function excluirerpvendamaquinas($index)
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
	public function excluirerpvendamaquina($index)
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