<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erprecebimentos.php';
class erprecebimentosService
{
	var $tablename = "erprecebimentos";
	var $conexao;
	var $query;
	function erprecebimentosService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerprecebimentos()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename;
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erprecebimentos"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function fluxoerprecebimentos($data, $tipo)
	{
		$this->conexao->openConexao();
		$seleDate = explode("-", $data);
		if($tipo == "semana")
		{
			$this->query = "SELECT CONCAT(LPAD(WEEK(RECdEmissao), 2, '0'), ':00') as semana, SUM(RECfValor) as cont_rec FROM ".$this->tablename." WHERE DATE(RECdEmissao) = '$data' GROUP BY WEEK(RECdEmissao) ORDER BY semana";
		} else if($tipo == "mes")
		{
			$this->query = "SELECT DAYOFMONTH(RECdEmissao) as mes, SUM(RECfValor) as cont_rec FROM ".$this->tablename." WHERE MONTH(RECdEmissao) = '".($seleDate[1]+1)."' AND YEAR(RECdEmissao) = '".$seleDate[0]."' GROUP BY DATE(RECdEmissao) ORDER BY mes";
		} else if($tipo == "ano")
		{
			$this->query = "SELECT MONTH(RECdEmissao) as ano, SUM(RECfValor) as cont_rec FROM ".$this->tablename." WHERE YEAR(RECdEmissao) = '".$seleDate[0]."' GROUP BY MONTH(RECdEmissao) ORDER BY ano";
		}
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
	public function vencererprecebimentos($date)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM erprecebimentos WHERE RECdVencimento <= '".$date."' AND RECbRecebido <> 'Y' ORDER BY RECdVencimento ASC";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erprecebimentos"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function inserirerprecebimentos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(RECnId,RECsNome,RECsPedido,RECnTipo,RECfValor,RECsTipoPagamento,RECdEmissao,RECdVencimento,RECbRecebido,RECsObservacoes,RECdInclusao,RECdAlteracao) 
		VALUES 
		('".$obj->RECnId."','".$obj->RECsNome."','".$obj->RECsPedido."','".$obj->RECnTipo."','".$obj->RECfValor."','".$obj->RECsTipoPagamento."','".$obj->RECdEmissao."','".$obj->RECdVencimento."','".$obj->RECbRecebido."','".$obj->RECsObservacoes."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerprecebimentos();
	}
	public function editarerprecebimentos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		RECnId = '".$obj->RECnId."',RECsNome = '".$obj->RECsNome."',RECsPedido = '".$obj->RECsPedido."',RECnTipo = '".$obj->RECnTipo."',RECfValor = '".$obj->RECfValor."',RECsTipoPagamento = '".$obj->RECsTipoPagamento."',RECdEmissao = '".$obj->RECdEmissao."',RECdVencimento = '".$obj->RECdVencimento."',RECbRecebido = '".$obj->RECbRecebido."',RECsObservacoes = '".$obj->RECsObservacoes."',RECdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE RECnId = '".$obj->RECnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerprecebimentos();
	}
	public function excluirerprecebimentos($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE RECnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerprecebimentos();	
	}
	public function recebererprecebimentos($array)
	{
		$this->conexao->openConexao();
		$results = true;
		for($i=0; $i<count($array); $i++)
		{
			$this->query = "UPDATE ".$this->tablename." SET
			RECbRecebido = 'Y', RECdAlteracao = '".date("Y-m-d H:i:s")."'
			WHERE RECnId = '".$array[$i]."'";
			$result = mysql_query($this->query);
			$results &= $result;
		}
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($results === false)
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