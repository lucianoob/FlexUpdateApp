<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpvendaservicos.php';
class erpvendaservicosService
{
	var $tablename = "erpvendaservicos";
	var $conexao;
	var $query;
	function erpvendaservicosService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpvendaservicos($index)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." WHERE VESnVenda = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpvendaservicos"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listarerpvendaservicosproduto($index, $produto)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t1.*, t0.SERsCodigo, t0.SERsTipo, t2.PRSnTempo 
		FROM (".$this->tablename." AS t1 
			 LEFT JOIN erpservicos AS t0 ON t0.SERnId = t1.VESnServico) 
			 INNER JOIN erpprodutoservicos AS t2 ON t0.SERnId = t2.PRSnServico  
		WHERE t1.VESnVenda = '".$index."' AND t1.VESnProduto = '".$produto."' AND t2.PRSnProduto = '".$produto."'";
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
	public function selecionarerpvendaservicos($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE VESnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpvendaservicos");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpvendaservicos($arraycol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($arraycol); $i++)
		{
			$array = $arraycol[$i];
			$this->query = "INSERT INTO ".$this->tablename."
			(VESnId,VESnVenda,VESnProduto,VESnServico,VESfValor,VESdInclusao,VESdAlteracao) 
			VALUES 
			('".$obj->VESnId."','".$obj->VESnVenda."','".$obj->VESnProduto."','".$obj->VESnServico."','".$obj->VESfValor."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function editarerpvendaservicos($arraycol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($arraycol); $i++)
		{
			$array = $arraycol[$i];
			if(!$obj->VESnId)
			{
				$this->query = "INSERT INTO ".$this->tablename."
				(VESnId,VESnVenda,VESnProduto,VESnServico,VESfValor,VESdInclusao,VESdAlteracao) 
				VALUES 
				('".$obj->VESnId."','".$obj->VESnVenda."','".$obj->VESnProduto."','".$obj->VESnServico."','".$obj->VESfValor."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			} else
			{
				$this->query = "UPDATE ".$this->tablename." SET
				VESnId = '".$obj->VESnId."',VESnVenda = '".$obj->VESnVenda."',VESnProduto = '".$obj->VESnProduto."',VESnServico = '".$obj->VESnServico."',VESfValor = '".$obj->VESfValor."',VESdAlteracao = '".date("Y-m-d H:i:s")."'
				WHERE VESnId = '".$obj->VESnId."'";
			}
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function excluirerpvendaservicos($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE VESnVenda = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
	}
	public function excluirerpvendaservico($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE VESnId = '".$index."'";
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