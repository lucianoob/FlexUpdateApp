<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erporcamentoitens.php';
class erporcamentoitensService
{
	var $tablename = "erporcamentoitens";
	var $conexao;
	var $query;
	function erporcamentoitensService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerporcamentoitens($index)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." WHERE ORInOrcamento = '".$index."' ORDER BY ORInItem";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erporcamentoitens"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function inserirerporcamentoitens($arraycol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($arraycol); $i++)
		{
			$array = $arraycol[$i];
			$this->query = "INSERT INTO ".$this->tablename."
			(ORInId,ORInOrcamento,MATnMaterialPeca,MATsTipo,ORInItem,ORIsDescricao,ORIfQuantidade,ORIfValorUnitario,ORIfValorTotal,ORIsPrazoEntrega,ORIdInclusao,ORIdAlteracao) 
			VALUES 
			('".$obj->ORInId."','".$obj->ORInOrcamento."','".$obj->MATnMaterialPeca."','".$obj->MATsTipo."','".$obj->ORInItem."','".$obj->ORIsDescricao."','".$obj->ORIfQuantidade."','".$obj->ORIfValorUnitario."','".$obj->ORIfValorTotal."','".$obj->ORIsPrazoEntrega."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function editarerporcamentoitens($arraycol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($arraycol); $i++)
		{
			$array = $arraycol[$i];
			if(!$obj->ORInId)
			{
				$this->query = "INSERT INTO ".$this->tablename."
				(ORInId,ORInOrcamento,MATnMaterialPeca,MATsTipo,ORInItem,ORIsDescricao,ORIfQuantidade,ORIfValorUnitario,ORIfValorTotal,ORIsPrazoEntrega,ORIdInclusao,ORIdAlteracao) 
				VALUES 
				('".$obj->ORInId."','".$obj->ORInOrcamento."','".$obj->MATnMaterialPeca."','".$obj->MATsTipo."','".$obj->ORInItem."','".$obj->ORIsDescricao."','".$obj->ORIfQuantidade."','".$obj->ORIfValorUnitario."','".$obj->ORIfValorTotal."','".$obj->ORIsPrazoEntrega."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			} else
			{
				$this->query = "UPDATE ".$this->tablename." SET
				ORInId = '".$obj->ORInId."',ORInOrcamento = '".$obj->ORInOrcamento."',MATnMaterialPeca = '".$obj->MATnMaterialPeca."',MATsTipo = '".$obj->MATsTipo."',ORInItem = '".$obj->ORInItem."',ORIsDescricao = '".$obj->ORIsDescricao."',ORIfQuantidade = '".$obj->ORIfQuantidade."',ORIfValorUnitario = '".$obj->ORIfValorUnitario."',ORIfValorTotal = '".$obj->ORIfValorTotal."',ORIsPrazoEntrega = '".$obj->ORIsPrazoEntrega."',ORIdAlteracao = '".date("Y-m-d H:i:s")."'
				WHERE ORInId = '".$obj->ORInId."'";
			}
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function excluirerporcamentoitens($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE ORInOrcamento = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
	}
	public function excluirerporcamentoitem($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE ORInId = '".$index."'";
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