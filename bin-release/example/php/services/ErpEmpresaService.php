<?php
require_once 'ErpConexaoService.php';
require_once 'vo/ErpEmpresa.php';
/**
* Classe de administra��o dos setores do sistema.
*/
class ErpEmpresaService 
{
	var $tablename = "erpempresa";
	var $ErpConexao;
	var $ErpQuery;
	/** 
	* Construtor: Construtor da classe setores. 
	*/
	public function ErpEmpresaService() 
	{
		$this->ErpConexao = new ErpConexaoService();
	}
	/**
	* M�todo para listar todos os setores do sistema.
	* @returns Retorna todos os setores do sistema.
	*/
	public function listarErpEmpresa()
	{
		$this->ErpConexao->openConexao();
		$this->ErpQuery = "SELECT * FROM ".$this->tablename." ORDER BY SISnId DESC";
		$result = mysql_query($this->ErpQuery);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "ErpEmpresa"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->ErpConexao->closeConexao();
		return $rows[$i-1];
	}
	/**
	* M�todo para salvar os setores.
	* @param $aSIS Cont�m os dados dos setores a serem salvos.
	* @returns Retorna a lista de setores atualizada.
	*/
	public function inserirErpEmpresa($obj)
	{
		$this->ErpConexao->openConexao();
		$this->ErpQuery = "INSERT INTO ".$this->tablename." 
		(SISsNomeFantasia, SISsRazaoSocial, SISsCNPJ, SISsIE, SISlEnderecos, SISlTelefones, SISlFaxs, SISlEmails, SISlSites, SISlSocios, SISlLogos,
		SISdAlteracao, SISdInclusao)
		VALUES (
		'".$obj->SISsNomeFantasia."', '".$obj->SISsRazaoSocial."', '".$obj->SISsCNPJ."', '".$obj->SISsIE."', '".$obj->SISlEnderecos."', 
		'".$obj->SISlTelefones."', '".$obj->SISlFaxs."', '".$obj->SISlEmails."', '".$obj->SISlSites."', '".$obj->SISlSocios."', '".$obj->SISlLogos."', 
		'".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->ErpQuery);
		$this->throwExceptionOnError();
		$this->ErpConexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarErpEmpresa();	
	}
	/**
	* M�todo para editar os setores.
	* @param $aSIS Cont�m os dados dos setores a serem atualizados.
	* @returns Retorna a lista de setores atualizada.
	*/
	public function editarErpEmpresa($obj)
	{
		$this->ErpConexao->openConexao();
		$this->ErpQuery = "UPDATE ".$this->tablename." SET
		SISsNomeFantasia = '".$obj->SISsNomeFantasia."', SISsRazaoSocial = '".$obj->SISsRazaoSocial."', SISsCNPJ = '".$obj->SISsCNPJ."',
		SISsIE = '".$obj->SISsIE."', SISlEnderecos = '".$obj->SISlEnderecos."', SISlTelefones = '".$obj->SISlTelefones."', 
		SISlFaxs = '".$obj->SISlFaxs."', SISlEmails = '".$obj->SISlEmails."', SISlSites = '".$obj->SISlSites."', 
		SISlSocios = '".$obj->SISlSocios."', SISlLogos = '".$obj->SISlLogos."',
		SISdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE SISnId = '".$obj->SISnId."'";
		$result = mysql_query($this->ErpQuery);
		$this->throwExceptionOnError();
		$this->ErpConexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarErpEmpresa();	
	}
	/**
	* M�todo para excluir os setores.
	* @param $aSIS Cont�m o ID do setor a ser excluido.
	* @returns Retorna a lista de setores atualizada.
	*/
	public function excluirErpEmpresa($index)
	{
		$this->ErpConexao->openConexao();
		$this->ErpQuery = "DELETE FROM ".$this->tablename." 
		WHERE SISnId = '".$index."'";
		$result = mysql_query($this->ErpQuery);
		$this->throwExceptionOnError();
		$this->ErpConexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarErpEmpresa();	
	}
	/**
	* M�todo para coletar os erros do MySQL.
	* @param $link Link do erro no MySQL.
	*/
	private function throwExceptionOnError($link = null) 
	{
		if($link == null) {
			$link = $this->ErpConexao->connection;
		}
		if(mysql_error($link)) {
			$msg = mysql_errno($link) . ": " . mysql_error($link);
			throw new Exception('MySQL Error - '. $msg. '\n Query: '.$this->ErpQuery);
		}		
	}
}

?>
