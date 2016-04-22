<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpprodutoservicos.php';
class erpprodutoservicosService
{
	var $tablename = "erpprodutoservicos";
	var $conexao;
	var $query;
	function erpprodutoservicosService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpprodutoservicos($index)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.*, t1.SERsCodigo, t1.SERsTipo, t1.SERfValor FROM ".$this->tablename." AS t0, erpservicos AS t1 
		WHERE t0.PRSnProduto = '".$index."' AND t0.PRSnServico = t1.SERnId";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpprodutoservicos"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpprodutoservicos($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE PRSnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpprodutoservicos");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpprodutoservicos($arraycol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($arraycol); $i++)
		{
			$array = $arraycol[$i];
			$this->query = "INSERT INTO ".$this->tablename."
			(PRSnId,PRSnProduto,PRSnServico,PRSnTempo,PRSdInclusao,PRSdAlteracao) 
			VALUES 
			('".$obj->PRSnId."','".$obj->PRSnProduto."','".$obj->PRSnServico."','".$obj->PRSnTempo."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function editarerpprodutoservicos($arraycol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($arraycol); $i++)
		{
			$array = $arraycol[$i];
			if(!$obj->PRSnId)
			{
				$this->query = "INSERT INTO ".$this->tablename."
				(PRSnId,PRSnProduto,PRSnServico,PRSnTempo,PRSdInclusao,PRSdAlteracao) 
				VALUES 
				('".$obj->PRSnId."','".$obj->PRSnProduto."','".$obj->PRSnServico."','".$obj->PRSnTempo."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			} else
			{
				$this->query = "UPDATE ".$this->tablename." SET
				PRSnId = '".$obj->PRSnId."',PRSnProduto = '".$obj->PRSnProduto."',PRSnServico = '".$obj->PRSnServico."',PRSnTempo = '".$obj->PRSnTempo."',PRSdAlteracao = '".date("Y-m-d H:i:s")."'
				WHERE PRSnId = '".$obj->PRSnId."'";
			}
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function excluirerpprodutoservicos($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE PRSnProduto = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
	}
	public function excluirerpprodutoservico($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE PRSnId = '".$index."'";
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