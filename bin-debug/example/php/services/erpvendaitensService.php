<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpvendaitens.php';
class erpvendaitensService
{
	var $tablename = "erpvendaitens";
	var $conexao;
	var $query;
	function erpvendaitensService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpvendaitens($index)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." WHERE VEInVenda = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpvendaitens"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpvendaitens($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE VEInId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpvendaitens");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpvendaitens($arraycol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($arraycol); $i++)
		{
			$array = $arraycol[$i];
			$this->query = "INSERT INTO ".$this->tablename."
			(VEInVenda,VEInItem,VENnProduto,VEIsDescricao,VEIfQuantidade,VEIfValorUnitario,VEIfCusto,VEIfLucro,VEIfValorTotal,VEIsPrazoEntrega,VEIdInclusao,VEIdAlteracao) 
			VALUES 
			('".$obj->VEInVenda."','".$obj->VEInItem."','".$obj->VENnProduto."','".$obj->VEIsDescricao."','".$obj->VEIfQuantidade."','".$obj->VEIfValorUnitario."','".$obj->VEIfCusto."','".$obj->VEIfLucro."','".$obj->VEIfValorTotal."','".$obj->VEIsPrazoEntrega."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function editarerpvendaitens($arraycol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($arraycol); $i++)
		{
			$array = $arraycol[$i];
			if(!$obj->VEInId)
			{
				$this->query = "INSERT INTO ".$this->tablename."
				(VEInVenda,VEInItem,VENnProduto,VEIsDescricao,VEIfQuantidade,VEIfValorUnitario,VEIfCusto,VEIfLucro,VEIfValorTotal,VEIsPrazoEntrega,VEIdInclusao,VEIdAlteracao) 
				VALUES 
				('".$obj->VEInVenda."','".$obj->VEInItem."','".$obj->VENnProduto."','".$obj->VEIsDescricao."','".$obj->VEIfQuantidade."','".$obj->VEIfValorUnitario."','".$obj->VEIfCusto."','".$obj->VEIfLucro."','".$obj->VEIfValorTotal."','".$obj->VEIsPrazoEntrega."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			} else
			{
				$this->query = "UPDATE ".$this->tablename." SET
				VEInVenda = '".$obj->VEInVenda."',VEInItem = '".$obj->VEInItem."',VENnProduto = '".$obj->VENnProduto."',VEIsDescricao = '".$obj->VEIsDescricao."',VEIfQuantidade = '".$obj->VEIfQuantidade."',VEIfValorUnitario = '".$obj->VEIfValorUnitario."',VEIfCusto = '".$obj->VEIfCusto."',VEIfLucro = '".$obj->VEIfLucro."',VEIfValorTotal = '".$obj->VEIfValorTotal."',VEIsPrazoEntrega = '".$obj->VEIsPrazoEntrega."',VEIdAlteracao = '".date("Y-m-d H:i:s")."'
				WHERE VEInId = '".$obj->VEInId."'";
			}
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function excluirerpvendaitens($index)
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
	public function excluirerpvendaitem($index)
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