<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpmateriais_pecas.php';
class erpmateriais_pecasService
{
	var $tablename = "erpmateriais_pecas";
	var $conexao;
	var $query;
	function erpmateriais_pecasService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpmateriais_pecas($tipo)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.*, t1.UNDsUnidade, t1.UNDsNome FROM ".$this->tablename." AS t0, erpunidades AS t1 
		WHERE t0.MATsTipo = '".$tipo."' AND t0.MATnUnidade = t1.UNDnId";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpmateriais_pecas"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpmateriais_pecas($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE MATnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpmateriais_pecas");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpnovomateriais_pecas($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(MATnId,MATsTipo,MATsCodigo,MATsNome,MATlDescricao,MATnUnidade,MATdInclusao,MATdAlteracao) 
		VALUES 
		('".$obj->MATnId."','".$obj->MATsTipo."','".$obj->MATsCodigo."','".$obj->MATsNome."','".$obj->MATlDescricao."','".$obj->MATnUnidade."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return true;
	}
	public function inserirerpmateriais_pecas($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(MATnId,MATsTipo,MATsCodigo,MATsNome,MATlDescricao,MATnUnidade,MATdInclusao,MATdAlteracao) 
		VALUES 
		('".$obj->MATnId."','".$obj->MATsTipo."','".$obj->MATsCodigo."','".$obj->MATsNome."','".$obj->MATlDescricao."','".$obj->MATnUnidade."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return true;
	}
	public function editarerpmateriais_pecas($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		MATnId = '".$obj->MATnId."',MATsTipo = '".$obj->MATsTipo."',MATsCodigo = '".$obj->MATsCodigo."',MATsNome = '".$obj->MATsNome."',MATlDescricao = '".$obj->MATlDescricao."',MATnUnidade = '".$obj->MATnUnidade."',MATdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE MATnId = '".$obj->MATnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return true;
	}
	public function excluirerpmateriais_pecas($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE MATnId = '".$index."'";
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