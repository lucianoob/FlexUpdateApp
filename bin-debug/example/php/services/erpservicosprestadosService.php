<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpservicosprestados.php';
class erpservicosprestadosService
{
	var $tablename = "erpservicosprestados";
	var $conexao;
	var $query;
	function erpservicosprestadosService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpservicosprestados()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.*, t1.UNDnId, t1.UNDsUnidade, t1.UNDsNome FROM ".$this->tablename." AS t0, erpunidades AS t1 
		WHERE t0.SEPnUnidade = t1.UNDnId";
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
	public function selecionarerpservicosprestados($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE SEPnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpservicosprestados");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpservicosprestados($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(SEPnId,SEPsTipo,SEPsCodigo,SEPsDescricao,SEPsTempo,SEPnUnidade,SEPfValor,SEPdInclusao,SEPdAlteracao) 
		VALUES 
		('".$obj->SEPnId."','".$obj->SEPsTipo."','".$obj->SEPsCodigo."','".$obj->SEPsDescricao."','".$obj->SEPsTempo."','".$obj->SEPnUnidade."','".$obj->SEPfValor."','".$obj->SEPdInclusao."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpservicosprestados();
	}
	public function editarerpservicosprestados($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		SEPnId = '".$obj->SEPnId."',SEPsTipo = '".$obj->SEPsTipo."',SEPsCodigo = '".$obj->SEPsCodigo."',SEPsDescricao = '".$obj->SEPsDescricao."',SEPsTempo = '".$obj->SEPsTempo."',SEPnUnidade = '".$obj->SEPnUnidade."',SEPfValor = '".$obj->SEPfValor."',SEPdInclusao = '".$obj->SEPdInclusao."',SEPdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE SEPnId = '".$obj->SEPnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpservicosprestados();
	}
	public function excluirerpservicosprestados($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE SEPnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpservicosprestados();	
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