<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpvendas.php';
class erpvendasService
{
	var $tablename = "erpvendas";
	var $conexao;
	var $query;
	
	function erpvendasService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpvendas($tipo)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.*, t1.CLIsRazaoSocial FROM ".$this->tablename." AS t0, erpclientes AS t1 
		WHERE t0.VENnCliente = t1.CLInId AND t0.VENsTipo = '".$tipo."' ORDER BY t0.VENnId";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpvendas"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listarerpvendasperiodo($date)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.*, t1.CLIsRazaoSocial FROM ".$this->tablename." AS t0, erpclientes AS t1 
						WHERE t0.VENnCliente = t1.CLInId AND t0.VENdData >= '".$date."'
						AND t0.VENbOrcamento <> 'Y'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpvendas"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpvendas($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE VENnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpvendas");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpvendas($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(VENsTipo,VENdData,VENnCliente,VENsContato,VENsDepto,VENsTelefone,VENsFax,VENfTotal,VENfFrete,VENfImposto,VENfDesconto,VENfTotalVenda,VENsCondicoesPagamento,VENdPrevisaoEntrega,VENlObservacoes,VENbOrcamento,VENdInclusao,VENdAlteracao) 
		VALUES 
		('".$obj->VENsTipo."','".$obj->VENdData."','".$obj->VENnCliente."','".$obj->VENsContato."','".$obj->VENsDepto."','".$obj->VENsTelefone."','".$obj->VENsFax."','".$obj->VENfTotal."','".$obj->VENfFrete."','".$obj->VENfImposto."','".$obj->VENfDesconto."','".$obj->VENfTotalVenda."','".$obj->VENsCondicoesPagamento."','".$obj->VENdPrevisaoEntrega."','".$obj->VENlObservacoes."','".$obj->VENbOrcamento."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpvendas($obj->VENsTipo);
	}
	public function editarerpvendas($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		VENsTipo = '".$obj->VENsTipo."',VENdData = '".$obj->VENdData."',VENnCliente = '".$obj->VENnCliente."',VENsContato = '".$obj->VENsContato."',VENsDepto = '".$obj->VENsDepto."',VENsTelefone = '".$obj->VENsTelefone."',VENsFax = '".$obj->VENsFax."',VENfTotal = '".$obj->VENfTotal."',VENfFrete = '".$obj->VENfFrete."',VENfImposto = '".$obj->VENfImposto."',VENfDesconto = '".$obj->VENfDesconto."',VENfTotalVenda = '".$obj->VENfTotalVenda."',VENsCondicoesPagamento = '".$obj->VENsCondicoesPagamento."',VENdPrevisaoEntrega = '".$obj->VENdPrevisaoEntrega."',VENlObservacoes = '".$obj->VENlObservacoes."',VENbOrcamento = '".$obj->VENbOrcamento."',VENdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE VENnId = '".$obj->VENnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpvendas($obj->VENsTipo);
	}
	public function excluirerpvendas($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE VENnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return true;	
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