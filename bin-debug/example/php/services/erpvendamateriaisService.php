<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpvendamateriais.php';
class erpvendamateriaisService
{
	var $tablename = "erpvendamateriais";
	var $conexao;
	var $query;
	function erpvendamateriaisService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpvendamateriais($index)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.*, t1.MATsCodigo, t1.MATsNome FROM ".$this->tablename." AS t0, erpmateriais_pecas AS t1 
		WHERE t0.VEMnVenda = '".$index."' AND t0.VEMnMaterial = t1.MATnId";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpvendamateriais"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listarerpvendamateriaisproduto($index, $produto)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.*, t1.MATsCodigo, t1.MATsNome FROM ".$this->tablename." AS t0, erpmateriais_pecas AS t1 
		WHERE t0.VEMnVenda = '".$index."' AND t0.VEMnProduto = '".$produto."' AND t0.VEMnMaterial = t1.MATnId";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpvendamateriais"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpvendamateriais($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE VEMnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpvendamateriais");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpvendamateriais($arraycol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($arraycol); $i++)
		{
			$array = $arraycol[$i];
			$this->query = "INSERT INTO ".$this->tablename."
			(VEMnId,VEMnVenda,VEMnProduto,VEMnMaterial,VEMnLote,VEMfQuantidade,VEMfValor,VEMdInclusao,VEMdAlteracao) 
			VALUES 
			('".$obj->VEMnId."','".$obj->VEMnVenda."','".$obj->VEMnProduto."','".$obj->VEMnMaterial."','".$obj->VEMnLote."','".$obj->VEMfQuantidade."','".$obj->VEMfValor."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
			$qx = $this->query;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function editarerpvendamateriais($arraycol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($arraycol); $i++)
		{
			$array = $arraycol[$i];
			if(!$obj->VEMnId)
			{
				$this->query = "INSERT INTO ".$this->tablename."
				(VEMnId,VEMnVenda,VEMnProduto,VEMnMaterial,VEMnLote,VEMfQuantidade,VEMfValor,VEMdInclusao,VEMdAlteracao) 
				VALUES 
				('".$obj->VEMnId."','".$obj->VEMnVenda."','".$obj->VEMnProduto."','".$obj->VEMnMaterial."','".$obj->VEMnLote."','".$obj->VEMfQuantidade."','".$obj->VEMfValor."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			} else
			{
				$this->query = "UPDATE ".$this->tablename." SET
				VEMnId = '".$obj->VEMnId."',VEMnVenda = '".$obj->VEMnVenda."',VEMnProduto = '".$obj->VEMnProduto."',VEMnMaterial = '".$obj->VEMnMaterial."',VEMnLote = '".$obj->VEMnLote."',VEMfQuantidade = '".$obj->VEMfQuantidade."',VEMfValor = '".$obj->VEMfValor."',VEMdAlteracao = '".date("Y-m-d H:i:s")."'
				WHERE VEMnId = '".$obj->VEMnId."'";
			}
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function excluirerpvendamateriais($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE VEMnVenda = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
	}
	public function excluirerpvendamateriai($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE VEMnId = '".$index."'";
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