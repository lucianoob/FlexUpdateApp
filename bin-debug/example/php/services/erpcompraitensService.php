<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpcompraitens.php';
class erpcompraitensService
{
	var $tablename = "erpcompraitens";
	var $conexao;
	var $query;
	function erpcompraitensService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpcompraitens($index)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." WHERE COInCompra = '".$index."' ORDER BY COInItem";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpcompraitens"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpcompraitens($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE COMnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpcompraitens");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpcompraitens($arraycol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($arraycol); $i++)
		{
			$array = $arraycol[$i];
			$this->query = "INSERT INTO ".$this->tablename."
			(COInId,COInCompra,MATnMaterialPeca,MATsTipo,COInItem,COIsDescricao,COIfQuantidade,COIfValorUnitario,COIfValorTotal,COIsPrazoEntrega,COIbNaoConforme,COIdInclusao,COIdAlteracao) 
			VALUES 
			('".$obj->COInId."','".$obj->COInCompra."','".$obj->MATnMaterialPeca."','".$obj->MATsTipo."','".$obj->COInItem."','".$obj->COIsDescricao."','".$obj->COIfQuantidade."','".$obj->COIfValorUnitario."','".$obj->COIfValorTotal."','".$obj->COIsPrazoEntrega."','".$obj->COIbNaoConforme."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function editarerpcompraitens($arraycol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($arraycol); $i++)
		{
			$array = $arraycol[$i];
			if(!$obj->COInId)
			{
				$this->query = "INSERT INTO ".$this->tablename."
				(COInId,COInCompra,MATnMaterialPeca,MATsTipo,COInItem,COIsDescricao,COIfQuantidade,COIfValorUnitario,COIfValorTotal,COIsPrazoEntrega,COIbNaoConforme,COIdInclusao,COIdAlteracao) 
				VALUES 
				('".$obj->COInId."','".$obj->COInCompra."','".$obj->MATnMaterialPeca."','".$obj->MATsTipo."','".$obj->COInItem."','".$obj->COIsDescricao."','".$obj->COIfQuantidade."','".$obj->COIfValorUnitario."','".$obj->COIfValorTotal."','".$obj->COIsPrazoEntrega."','".$obj->COIbNaoConforme."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			} else 
			{
				$this->query = "UPDATE ".$this->tablename." SET
				COInId = '".$obj->COInId."',COInCompra = '".$obj->COInCompra."',MATnMaterialPeca = '".$obj->MATnMaterialPeca."',MATsTipo = '".$obj->MATsTipo."',COInItem = '".$obj->COInItem."',COIsDescricao = '".$obj->COIsDescricao."',COIfQuantidade = '".$obj->COIfQuantidade."',COIfValorUnitario = '".$obj->COIfValorUnitario."',COIfValorTotal = '".$obj->COIfValorTotal."',COIsPrazoEntrega = '".$obj->COIsPrazoEntrega."',COIbNaoConforme = '".$obj->COIbNaoConforme."',COIdAlteracao = '".date("Y-m-d H:i:s")."'
				WHERE COInId = '".$obj->COInId."'";
			}
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function excluirerpcompraitens($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE COInCompra = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
	}
	public function excluirerpcompraitem($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE COInId = '".$index."'";
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