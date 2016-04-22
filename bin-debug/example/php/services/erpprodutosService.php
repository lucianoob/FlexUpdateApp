<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpprodutos.php';
class erpprodutosService
{
	var $tablename = "erpprodutos";
	var $conexao;
	var $query;
	function erpprodutosService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpprodutos()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename;
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpprodutos"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpprodutos($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE PROnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpprodutos");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpprodutos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(PROnId,PROsCodigo,PROsNome,PROnMaterial,PROfQuantidade,PROnTempoProducao,PROaArquivos,PROdInclusao,PROdAlteracao) 
		VALUES 
		('".$obj->PROnId."','".$obj->PROsCodigo."','".$obj->PROsNome."','".$obj->PROnMaterial."','".$obj->PROfQuantidade."','".$obj->PROnTempoProducao."','".$obj->PROaArquivos."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpprodutos();
	}
	public function editarerpprodutos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		PROnId = '".$obj->PROnId."',PROsCodigo = '".$obj->PROsCodigo."',PROsNome = '".$obj->PROsNome."',PROnMaterial = '".$obj->PROnMaterial."',PROfQuantidade = '".$obj->PROfQuantidade."',PROnTempoProducao = '".$obj->PROnTempoProducao."',PROaArquivos = '".$obj->PROaArquivos."',PROdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE PROnId = '".$obj->PROnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpprodutos();
	}
	public function excluirerpprodutos($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE PROnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpprodutos();	
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