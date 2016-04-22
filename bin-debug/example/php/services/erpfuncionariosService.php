<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpfuncionarios.php';
class erpfuncionariosService
{
	var $tablename = "erpfuncionarios";
	var $conexao;
	var $query;
	function erpfuncionariosService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpfuncionarios()
	{
		
		$this->conexao->openConexao();
		$this->query = "SELECT t0.FUNnId,t0.FUNsNome,t0.FUNnUsuario,t1.USUsNome,t0.FUNsFuncao,
		t0.FUNsCarteiraProfissional,t0.FUNnCPF,t0.FUNsRG,t0.FUNdDataNascimento,t0.FUNdAdmissao,t0.FUNsFoto,
		t0.FUNdAlteracao,t0.FUNdInclusao		
		FROM ".$this->tablename." AS t0
		INNER JOIN erpusuarios AS t1 ON t0.FUNnUsuario = t1.USUnId";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpfuncionarios"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listarerpfuncionariosproducao()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.FUNnId,t0.FUNsNome,t0.FUNnUsuario,t1.USUsNome,t0.FUNsFuncao,
		t0.FUNsCarteiraProfissional,t0.FUNnCPF,t0.FUNsRG,t0.FUNdDataNascimento,t0.FUNdAdmissao,t0.FUNsFoto,
		t0.FUNdAlteracao,t0.FUNdInclusao		
		FROM ".$this->tablename." AS t0
		INNER JOIN erpusuarios AS t1 ON t0.FUNnUsuario = t1.USUnId
		WHERE t1.SETsSetor = '86'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpfuncionarios"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function inserirerpfuncionarios($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(FUNnId,FUNsNome,FUNnUsuario,FUNsFuncao,FUNsCarteiraProfissional,FUNnCPF,FUNsRG,FUNdDataNascimento,FUNdAdmissao,FUNsFoto,FUNdAlteracao,FUNdInclusao) 
		VALUES 
		('".$obj->FUNnId."','".$obj->FUNsNome."','".$obj->FUNnUsuario."','".$obj->FUNsFuncao."','".$obj->FUNsCarteiraProfissional."','".$obj->FUNnCPF."','".$obj->FUNsRG."','".$obj->FUNdDataNascimento."','".$obj->FUNdAdmissao."','".$obj->FUNsFoto."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpfuncionarios();
	}
	public function editarerpfuncionarios($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		FUNnId = '".$obj->FUNnId."',FUNsNome = '".$obj->FUNsNome."',FUNnUsuario = '".$obj->FUNnUsuario."',FUNsFuncao = '".$obj->FUNsFuncao."',FUNsCarteiraProfissional = '".$obj->FUNsCarteiraProfissional."',FUNnCPF = '".$obj->FUNnCPF."',FUNsRG = '".$obj->FUNsRG."',FUNdDataNascimento = '".$obj->FUNdDataNascimento."',FUNdAdmissao = '".$obj->FUNdAdmissao."',FUNsFoto = '".$obj->FUNsFoto."',FUNdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE FUNnId = '".$obj->FUNnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpfuncionarios();
	} 
	public function excluirerpfuncionarios($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE FUNnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpfuncionarios();	
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