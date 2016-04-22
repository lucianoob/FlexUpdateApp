<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erppagamentos.php';
class erppagamentosService
{
	var $tablename = "erppagamentos";
	var $conexao;
	var $query;
	function erppagamentosService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerppagamentos()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.*, t1.FORsNome FROM erppagamentos as t0, erpfornecedores as t1 WHERE t0.PAGnFornecedor = t1.FORnId ORDER BY t0.PAGnId DESC";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erppagamentos"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function fluxoerppagamentos($data, $tipo)
	{
		$this->conexao->openConexao();
		$seleDate = explode("-", $data);
		if($tipo == "semana")
		{
			$this->query = "SELECT CONCAT(LPAD(WEEK(PAGdEmissao), 2, '0'), ':00') as semana, SUM(PAGfValor) as cont_pag FROM ".$this->tablename." WHERE DATE(PAGdEmissao) = '$data' GROUP BY WEEK(PAGdEmissao) ORDER BY semana";
		} else if($tipo == "mes")
		{
			$this->query = "SELECT DAYOFMONTH(PAGdEmissao) as mes, SUM(PAGfValor) as cont_pag FROM ".$this->tablename." WHERE MONTH(PAGdEmissao) = '".($seleDate[1]+1)."' AND YEAR(PAGdEmissao) = '".$seleDate[0]."' GROUP BY DATE(PAGdEmissao) ORDER BY mes";
		} else if($tipo == "ano")
		{
			$this->query = "SELECT MONTH(PAGdEmissao) as ano, SUM(PAGfValor) as cont_pag FROM ".$this->tablename." WHERE YEAR(PAGdEmissao) = '".$seleDate[0]."' GROUP BY MONTH(PAGdEmissao) ORDER BY ano";
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
	public function vencererppagamentos($date)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.*, t1.FORsNome FROM erppagamentos as t0, 
		erpfornecedores as t1 WHERE t0.PAGnFornecedor = t1.FORnId
		AND t0.PAGdVencimento <= '".$date."' AND t0.PAGbBaixado <> 'Y' ORDER BY t0.PAGdVencimento ASC";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erppagamentos"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function inserirerppagamentos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(PAGnId,PAGnFornecedor,PAGsDescricao,PAGsDuplicata,PAGsDesd,PAGfValor,PAGfJuros,PAGdEmissao,PAGdVencimento,PAGsBanco,PAGnContaBancaria,PAGnTipo,PAGsNumeroCheque,PAGbBaixado,PAGdInclusao,PAGdAlteracao) 
		VALUES 
		('".$obj->PAGnId."','".$obj->PAGnFornecedor."','".$obj->PAGsDescricao."','".$obj->PAGsDuplicata."','".$obj->PAGsDesd."','".$obj->PAGfValor."','".$obj->PAGfJuros."','".$obj->PAGdEmissao."','".$obj->PAGdVencimento."','".$obj->PAGsBanco."','".$obj->PAGnContaBancaria."','".$obj->PAGnTipo."','".$obj->PAGsNumeroCheque."','".$obj->PAGbBaixado."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerppagamentos();
	}
	public function inserirerppagamentoscompra($arraycol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($arraycol); $i++)
		{
			$array = $arraycol[$i];
			$this->query = "INSERT INTO ".$this->tablename."
			(PAGnId,PAGnFornecedor,PAGsDescricao,PAGsDuplicata,PAGsDesd,PAGfValor,PAGfJuros,PAGdEmissao,PAGdVencimento,PAGsBanco,PAGnContaBancaria,PAGnTipo,PAGsNumeroCheque,PAGbBaixado,PAGbCompra,PAGdInclusao,PAGdAlteracao) 
			VALUES 
			('".$obj->PAGnId."','".$obj->PAGnFornecedor."','".$obj->PAGsDescricao."','".$obj->PAGsDuplicata."','".$obj->PAGsDesd."','".$obj->PAGfValor."','".$obj->PAGfJuros."','".$obj->PAGdEmissao."','".$obj->PAGdVencimento."','".$obj->PAGsBanco."','".$obj->PAGnContaBancaria."','".$obj->PAGnTipo."','".$obj->PAGsNumeroCheque."','".$obj->PAGbBaixado."','".$obj->PAGbCompra."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function editarerppagamentos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		PAGnId = '".$obj->PAGnId."',PAGnFornecedor = '".$obj->PAGnFornecedor."',PAGsDescricao = '".$obj->PAGsDescricao."',PAGsDuplicata = '".$obj->PAGsDuplicata."',PAGsDesd = '".$obj->PAGsDesd."',PAGfValor = '".$obj->PAGfValor."',PAGfJuros = '".$obj->PAGfJuros."',PAGdEmissao = '".$obj->PAGdEmissao."',PAGdVencimento = '".$obj->PAGdVencimento."',PAGsBanco = '".$obj->PAGsBanco."',PAGnContaBancaria = '".$obj->PAGnContaBancaria."',PAGnTipo = '".$obj->PAGnTipo."',PAGsNumeroCheque = '".$obj->PAGsNumeroCheque."',PAGbBaixado = '".$obj->PAGbBaixado."',PAGdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE PAGnId = '".$obj->PAGnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerppagamentos();
	}
	public function excluirerppagamentos($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE PAGnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerppagamentos();	
	}
	public function excluirerppagamentoscompra($duplicata)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE PAGsDuplicata LIKE '".$duplicata."' AND PAGbCompra = 'Y'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return true;	
	}
	public function baixarerppagamentos($array)
	{
		$this->conexao->openConexao();
		$results = true;
		for($i=0; $i<count($array); $i++)
		{
			$this->query = "UPDATE ".$this->tablename." SET
			PAGbBaixado = 'Y',PAGdAlteracao = '".date("Y-m-d H:i:s")."'
			WHERE PAGnId = '".$array[$i]."'";
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