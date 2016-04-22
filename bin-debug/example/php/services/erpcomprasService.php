<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpcompras.php';
class erpcomprasService
{
	var $tablename = "erpcompras";
	var $conexao;
	var $query;
	function erpcomprasService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpcompras()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.*, t1.FORsNome FROM ".$this->tablename." AS t0, erpfornecedores AS t1 
						WHERE t0.COMnFornecedor = t1.FORnId ORDER BY COMnId";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpcompras"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listarerpcomprasorcamento($index)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." 
						WHERE COMnOrcamento = '$index'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpcompras"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listarerpcomprasperiodo($date)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.*, t1.FORsNome FROM ".$this->tablename." AS t0, erpfornecedores AS t1 
						WHERE t0.COMnFornecedor = t1.FORnId AND t0.COMdData >= '".$date."'
						AND t0.COMdEntrega = '0000-00-00'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpcompras"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpcompras($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE COMnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpcompras");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpcompras($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(COMnId,COMnOrcamento,COMdData,COMnFornecedor,COMsContato,COMsDepto,COMsTelefone,COMsFax,COMfTotal,COMsCondicoesPagamento,COMdPrevisaoEntrega,COMlObservacoes,COMdEntrega,COMsNF,COMnAtraso,COMnPontuacaoAtraso,COMnLotesNC,COMnPontuacaoLotesNC,COMsRecebedor,COMlArquivos,COMdInclusao,COMdAlteracao) 
		VALUES 
		('".$obj->COMnId."','".$obj->COMnOrcamento."','".$obj->COMdData."','".$obj->COMnFornecedor."','".$obj->COMsContato."','".$obj->COMsDepto."','".$obj->COMsTelefone."','".$obj->COMsFax."','".$obj->COMfTotal."','".$obj->COMsCondicoesPagamento."','".$obj->COMdPrevisaoEntrega."','".$obj->COMlObservacoes."','".$obj->COMdEntrega."','".$obj->COMsNF."','".$obj->COMnAtraso."','".$obj->COMnPontuacaoAtraso."','".$obj->COMnLotesNC."','".$obj->COMnPontuacaoLotesNC."','".$obj->COMsRecebedor."','".$obj->COMlArquivos."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpcompras();
	}
	public function editarerpcompras($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		COMnId = '".$obj->COMnId."',COMnOrcamento = '".$obj->COMnOrcamento."',COMdData = '".$obj->COMdData."',COMnFornecedor = '".$obj->COMnFornecedor."',COMsContato = '".$obj->COMsContato."',COMsDepto = '".$obj->COMsDepto."',COMsTelefone = '".$obj->COMsTelefone."',COMsFax = '".$obj->COMsFax."',COMfTotal = '".$obj->COMfTotal."',COMsCondicoesPagamento = '".$obj->COMsCondicoesPagamento."',COMdPrevisaoEntrega = '".$obj->COMdPrevisaoEntrega."',COMlObservacoes = '".$obj->COMlObservacoes."',COMdEntrega = '".$obj->COMdEntrega."',COMsNF = '".$obj->COMsNF."',COMnAtraso = '".$obj->COMnAtraso."',COMnPontuacaoAtraso = '".$obj->COMnPontuacaoAtraso."',COMnLotesNC = '".$obj->COMnLotesNC."',COMnPontuacaoLotesNC = '".$obj->COMnPontuacaoLotesNC."',COMsRecebedor = '".$obj->COMsRecebedor."',COMlArquivos = '".$obj->COMlArquivos."',COMdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE COMnId = '".$obj->COMnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpcompras();
	}
	public function excluirerpcompras($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE COMnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpcompras();	
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