<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpvendainsumos.php';
class erpvendainsumosService
{
	var $tablename = "erpvendainsumos";
	var $conexao;
	var $query;
	function erpvendainsumosService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpvendainsumos($index)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." WHERE VEUnVenda = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpvendainsumos"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listarerpvendainsumosproduto($index, $produto)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.*, t1.INSsTipo, t1.INSsNome FROM ".$this->tablename." AS t0, erpinsumos AS t1
		WHERE t0.VEUnVenda = '".$index."' AND t0.VEUnProduto = '".$produto."' AND t0.VEUnInsumos = t1.INSnId";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpvendainsumos($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE VEUnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpvendainsumos");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpvendainsumos($arraycol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($arraycol); $i++)
		{
			$array = $arraycol[$i];
			$this->query = "INSERT INTO ".$this->tablename."
			(VEUnId,VEUnVenda,VEUnProduto,VEUnInsumos,VEUfValor,VEUdInclusao,VEUdAlteracao) 
			VALUES 
			('".$obj->VEUnId."','".$obj->VEUnVenda."','".$obj->VEUnProduto."','".$obj->VEUnInsumos."','".$obj->VEUfValor."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function editarerpvendainsumos($arraycol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($arraycol); $i++)
		{
			$array = $arraycol[$i];
			if(!$obj->VEUnId)
			{
				$this->query = "INSERT INTO ".$this->tablename."
				(VEUnId,VEUnVenda,VEUnProduto,VEUnInsumos,VEUfValor,VEUdInclusao,VEUdAlteracao) 
				VALUES 
				('".$obj->VEUnId."','".$obj->VEUnVenda."','".$obj->VEUnProduto."','".$obj->VEUnInsumos."','".$obj->VEUfValor."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			} else
			{
				$this->query = "UPDATE ".$this->tablename." SET
				VEUnId = '".$obj->VEUnId."',VEUnVenda = '".$obj->VEUnVenda."',VEUnProduto = '".$obj->VEUnProduto."',VEUnInsumos = '".$obj->VEUnInsumos."',VEUfValor = '".$obj->VEUfValor."',VEUdAlteracao = '".date("Y-m-d H:i:s")."'
				WHERE VEUnId = '".$obj->VEUnId."'";
			}
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function excluirerpvendainsumos($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE VEUnVenda = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
	}
	public function excluirerpvendainsumo($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE VEUnId = '".$index."'";
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