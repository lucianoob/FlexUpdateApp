<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpfornecedores.php';
class erpfornecedoresService
{
	var $tablename = "erpfornecedores";
	var $conexao;
	var $query;
	function erpfornecedoresService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerptodos()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." ORDER BY FORsNome";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpfornecedores"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listarerpfornecedores()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." WHERE FORbPrestador <> 'Y' ORDER BY FORsNome";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpfornecedores"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listarerpprestadores()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." WHERE FORbPrestador = 'Y' ORDER BY FORsNome";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpfornecedores"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpfornecedores($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE FORnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpfornecedores");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpfornecedores($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(FORnId,FORsNome,FORlDescricao,FORsNomeFantasia,FORsRazaoSocial,FORsCNPJ,FORsCPF,FORsCep,FORsEndereco,FORsNumero,FORsComplemento,FORsBairro,FORsCidade,FORsEstado,FORlTelefones,FORlFaxs,FORlEmails,FORlSites,FORaArquivos,FORdValidade,FORbPrestador,FORdInclusao,FORdAlteracao) 
		VALUES 
		('".$obj->FORnId."','".$obj->FORsNome."','".$obj->FORlDescricao."','".$obj->FORsNomeFantasia."','".$obj->FORsRazaoSocial."','".$obj->FORsCNPJ."','".$obj->FORsCPF."','".$obj->FORsCep."','".$obj->FORsEndereco."','".$obj->FORsNumero."','".$obj->FORsComplemento."','".$obj->FORsBairro."','".$obj->FORsCidade."','".$obj->FORsEstado."','".$obj->FORlTelefones."','".$obj->FORlFaxs."','".$obj->FORlEmails."','".$obj->FORlSites."','".$obj->FORaArquivos."','".$obj->FORdValidade."','".$obj->FORbPrestador."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpfornecedores();
	}
	public function editarerpfornecedores($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		FORnId = '".$obj->FORnId."',FORsNome = '".$obj->FORsNome."',FORlDescricao = '".$obj->FORlDescricao."',FORsNomeFantasia = '".$obj->FORsNomeFantasia."',FORsRazaoSocial = '".$obj->FORsRazaoSocial."',FORsCNPJ = '".$obj->FORsCNPJ."',FORsCPF = '".$obj->FORsCPF."',FORsCep = '".$obj->FORsCep."',FORsEndereco = '".$obj->FORsEndereco."',FORsNumero = '".$obj->FORsNumero."',FORsComplemento = '".$obj->FORsComplemento."',FORsBairro = '".$obj->FORsBairro."',FORsCidade = '".$obj->FORsCidade."',FORsEstado = '".$obj->FORsEstado."',FORlTelefones = '".$obj->FORlTelefones."',FORlFaxs = '".$obj->FORlFaxs."',FORlEmails = '".$obj->FORlEmails."',FORlSites = '".$obj->FORlSites."',FORaArquivos = '".$obj->FORaArquivos."',FORdValidade = '".$obj->FORdValidade."',FORbPrestador = '".$obj->FORbPrestador."',FORdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE FORnId = '".$obj->FORnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpfornecedores();
	}
	public function excluirerpfornecedores($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE FORnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpfornecedores();	
	}
	public function inserirerpprestadores($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(FORnId,FORsNome,FORlDescricao,FORsNomeFantasia,FORsRazaoSocial,FORsCNPJ,FORsCPF,FORsCep,FORsEndereco,FORsNumero,FORsComplemento,FORsBairro,FORsCidade,FORsEstado,FORlTelefones,FORlFaxs,FORlEmails,FORlSites,FORaArquivos,FORdValidade,FORbPrestador,FORdInclusao,FORdAlteracao) 
		VALUES 
		('".$obj->FORnId."','".$obj->FORsNome."','".$obj->FORlDescricao."','".$obj->FORsNomeFantasia."','".$obj->FORsRazaoSocial."','".$obj->FORsCNPJ."','".$obj->FORsCPF."','".$obj->FORsCep."','".$obj->FORsEndereco."','".$obj->FORsNumero."','".$obj->FORsComplemento."','".$obj->FORsBairro."','".$obj->FORsCidade."','".$obj->FORsEstado."','".$obj->FORlTelefones."','".$obj->FORlFaxs."','".$obj->FORlEmails."','".$obj->FORlSites."','".$obj->FORaArquivos."','".$obj->FORdValidade."','".$obj->FORbPrestador."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpprestadores();
	}
	public function editarerpprestadores($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		FORnId = '".$obj->FORnId."',FORsNome = '".$obj->FORsNome."',FORlDescricao = '".$obj->FORlDescricao."',FORsNomeFantasia = '".$obj->FORsNomeFantasia."',FORsRazaoSocial = '".$obj->FORsRazaoSocial."',FORsCNPJ = '".$obj->FORsCNPJ."',FORsCPF = '".$obj->FORsCPF."',FORsCep = '".$obj->FORsCep."',FORsEndereco = '".$obj->FORsEndereco."',FORsNumero = '".$obj->FORsNumero."',FORsComplemento = '".$obj->FORsComplemento."',FORsBairro = '".$obj->FORsBairro."',FORsCidade = '".$obj->FORsCidade."',FORsEstado = '".$obj->FORsEstado."',FORlTelefones = '".$obj->FORlTelefones."',FORlFaxs = '".$obj->FORlFaxs."',FORlEmails = '".$obj->FORlEmails."',FORlSites = '".$obj->FORlSites."',FORaArquivos = '".$obj->FORaArquivos."',FORdValidade = '".$obj->FORdValidade."',FORbPrestador = '".$obj->FORbPrestador."',FORdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE FORnId = '".$obj->FORnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpprestadores();
	}
	public function excluirerpprestadores($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE FORnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpprestadores();	
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