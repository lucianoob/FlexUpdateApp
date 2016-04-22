<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erporcamentos.php';
class erporcamentosService
{
	var $tablename = "erporcamentos";
	var $conexao;
	var $query;
	function erporcamentosService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerporcamentos()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.*, t1.FORsNome FROM ".$this->tablename." AS t0, erpfornecedores AS t1 
						WHERE t0.ORCnFornecedor = t1.FORnId ORDER BY ORCnId";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erporcamentos"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function inserirerporcamentos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(ORCnId,ORCdData,ORCnFornecedor,ORCsContato,ORCsDepto,ORCsTelefone,ORCsFax,ORCfTotal,ORCsCondicoesPagamento,ORCdPrevisaoEntrega,ORClObservacoes,ORClArquivos,ORCdInclusao,ORCdAlteracao) 
		VALUES 
		('".$obj->ORCnId."','".$obj->ORCdData."','".$obj->ORCnFornecedor."','".$obj->ORCsContato."','".$obj->ORCsDepto."','".$obj->ORCsTelefone."','".$obj->ORCsFax."','".$obj->ORCfTotal."','".$obj->ORCsCondicoesPagamento."','".$obj->ORCdPrevisaoEntrega."','".$obj->ORClObservacoes."','".$obj->ORClArquivos."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerporcamentos();
	}
	public function editarerporcamentos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		ORCnId = '".$obj->ORCnId."',ORCdData = '".$obj->ORCdData."',ORCnFornecedor = '".$obj->ORCnFornecedor."',ORCsContato = '".$obj->ORCsContato."',ORCsDepto = '".$obj->ORCsDepto."',ORCsTelefone = '".$obj->ORCsTelefone."',ORCsFax = '".$obj->ORCsFax."',ORCfTotal = '".$obj->ORCfTotal."',ORCsCondicoesPagamento = '".$obj->ORCsCondicoesPagamento."',ORCdPrevisaoEntrega = '".$obj->ORCdPrevisaoEntrega."',ORClObservacoes = '".$obj->ORClObservacoes."',ORClArquivos = '".$obj->ORClArquivos."',ORCdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE ORCnId = '".$obj->ORCnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerporcamentos();
	}
	public function excluirerporcamentos($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE ORCnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerporcamentos();	
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