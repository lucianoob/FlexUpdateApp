<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpentradas.php';
class erpentradasService
{
	var $tablename = "erpentradas";
	var $conexao;
	var $query;
	function erpentradasService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpentradas()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename;
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpentradas"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listarerpentradasmaterialpeca($index)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t1.*, t2.FORsNome 
		FROM erpentradas AS t1, erpfornecedores AS t2 
		WHERE t1.ENTnFornecedor=t2.FORnId AND t1.ENTnMaterialPeca='$index'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpentradas"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listarerpentradasmaterialpeca_estoque($index)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t1.*, t2.FORsNome 
		FROM erpentradas AS t1, erpfornecedores AS t2 
		WHERE t1.ENTnFornecedor=t2.FORnId AND t1.ENTnMaterialPeca='$index' AND (ENTfQuantidade-ENTfRetirada) > 0";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpentradas"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpentradas($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE ENTnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpentradas");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpentradas($arraycol)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($arraycol); $i++)
		{
			$array = $arraycol[$i];
			$this->query = "INSERT INTO ".$this->tablename."
			(ENTnId,ENTnCompra,ENTnFornecedor,ENTnMaterialPeca,ENTsTipo,ENTfQuantidade,ENTfValor,ENTdData,ENTfRetirada,ENTdAlteracao,ENTdInclusao) 
			VALUES 
			('".$obj->ENTnId."','".$obj->ENTnCompra."','".$obj->ENTnFornecedor."','".$obj->ENTnMaterialPeca."','".$obj->ENTsTipo."','".$obj->ENTfQuantidade."','".$obj->ENTfValor."','".$obj->ENTdData."','".$obj->ENTfRetirada."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		return $return;
	}
	public function editarerpentradas($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		ENTnId = '".$obj->ENTnId."',ENTnCompra = '".$obj->ENTnCompra."',ENTnFornecedor = '".$obj->ENTnFornecedor."',ENTnMaterialPeca = '".$obj->ENTnMaterialPeca."',ENTsTipo = '".$obj->ENTsTipo."',ENTfQuantidade = '".$obj->ENTfQuantidade."',ENTfValor = '".$obj->ENTfValor."',ENTdData = '".$obj->ENTdData."',ENTfRetirada = '".$obj->ENTfRetirada."',ENTdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE ENTnId = '".$obj->ENTnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpentradas();
	}
	public function movimentarerpentradas($index, $quantidade, $tipo)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename."
		WHERE ENTnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpentradas");
		if($tipo == "Retirada")
			$movimento = $row->ENTfRetirada+$quantidade;
		else if($tipo == "Retorno")
			$movimento = $row->ENTfRetirada-$quantidade;
		$this->query = "UPDATE ".$this->tablename." SET
		ENTfRetirada = '".$movimento."', ENTdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE ENTnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return true;
	}
	public function excluirerpentradas($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE ENTnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpentradas();	
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