<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpclientes.php';
class erpclientesService
{
	var $tablename = "erpclientes";
	var $conexao;
	var $query;
	function erpclientesService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpclientes()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename;
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpclientes"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpclientes($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE CLInId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpclientes");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpclientes($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(CLInId,CLIsNomeFantasia,CLIsRazaoSocial,CLIsCNPJ,CLIsCPF,CLIsIE,CLIsIM,CLIsCep,CLIsEndereco,CLIsNumero,CLIsComplemento,CLIsBairro,CLIsCidade,CLIsEstado,CLIlTelefones,CLIlFaxs,CLIlEmails,CLIlSites,CLIdDataAbertura,CLIsRamoAtividade,CLIsPrincipalProdutoProcesso,CLIlSocios,CLIaArquivos,CLIdValidade,CLIsObservacoes,CLIdAlteracao,CLIdInclusao) 
		VALUES 
		('".$obj->CLInId."','".$obj->CLIsNomeFantasia."','".$obj->CLIsRazaoSocial."','".$obj->CLIsCNPJ."','".$obj->CLIsCPF."','".$obj->CLIsIE."','".$obj->CLIsIM."','".$obj->CLIsCep."','".$obj->CLIsEndereco."','".$obj->CLIsNumero."','".$obj->CLIsComplemento."','".$obj->CLIsBairro."','".$obj->CLIsCidade."','".$obj->CLIsEstado."','".$obj->CLIlTelefones."','".$obj->CLIlFaxs."','".$obj->CLIlEmails."','".$obj->CLIlSites."','".$obj->CLIdDataAbertura."','".$obj->CLIsRamoAtividade."','".$obj->CLIsPrincipalProdutoProcesso."','".$obj->CLIlSocios."','".$obj->CLIaArquivos."','".$obj->CLIdValidade."','".$obj->CLIsObservacoes."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpclientes();
	}
	public function editarerpclientes($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		CLInId = '".$obj->CLInId."',CLIsNomeFantasia = '".$obj->CLIsNomeFantasia."',CLIsRazaoSocial = '".$obj->CLIsRazaoSocial."',CLIsCNPJ = '".$obj->CLIsCNPJ."',CLIsCPF = '".$obj->CLIsCPF."',CLIsIE = '".$obj->CLIsIE."',CLIsIM = '".$obj->CLIsIM."',CLIsCep = '".$obj->CLIsCep."',CLIsEndereco = '".$obj->CLIsEndereco."',CLIsNumero = '".$obj->CLIsNumero."',CLIsComplemento = '".$obj->CLIsComplemento."',CLIsBairro = '".$obj->CLIsBairro."',CLIsCidade = '".$obj->CLIsCidade."',CLIsEstado = '".$obj->CLIsEstado."',CLIlTelefones = '".$obj->CLIlTelefones."',CLIlFaxs = '".$obj->CLIlFaxs."',CLIlEmails = '".$obj->CLIlEmails."',CLIlSites = '".$obj->CLIlSites."',CLIdDataAbertura = '".$obj->CLIdDataAbertura."',CLIsRamoAtividade = '".$obj->CLIsRamoAtividade."',CLIsPrincipalProdutoProcesso = '".$obj->CLIsPrincipalProdutoProcesso."',CLIlSocios = '".$obj->CLIlSocios."',CLIaArquivos = '".$obj->CLIaArquivos."',CLIdValidade = '".$obj->CLIdValidade."',CLIsObservacoes = '".$obj->CLIsObservacoes."',CLIdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE CLInId = '".$obj->CLInId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpclientes();
	}
	public function excluirerpclientes($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE CLInId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpclientes();	
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