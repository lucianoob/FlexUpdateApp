<?php
require_once 'ErpConexaoService.php';
require_once 'erpAuditoriaService.php';
require_once 'vo/ErpSetores.php';
/**
* Classe de administra��o dos setores do sistema.
*/
class ErpSetoresService 
{
	var $tablename = "erpsetores";
	var $ErpConexao;
	var $ErpQuery;
	/** 
	* Construtor: Construtor da classe setores. 
	*/
	public function ErpSetoresService() 
	{
		$this->ErpConexao = new ErpConexaoService();
	}
	/**
	* M�todo para listar todos os setores do sistema.
	* @returns Retorna todos os setores do sistema.
	*/
	public function listarErpSetores()
	{
		$this->ErpConexao->openConexao();
		$this->ErpQuery = "SELECT * FROM ".$this->tablename." ORDER BY SETnId DESC";
		$result = mysql_query($this->ErpQuery);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "ErpSetores"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->ErpConexao->closeConexao();
		return $rows;
	}
	/**
	* M�todo para salvar os setores.
	* @param $obj Cont�m os dados dos setores a serem salvos.
	* @returns Retorna a lista de setores atualizada.
	*/
	public function inserirErpSetores($obj)
	{
		$this->ErpConexao->openConexao();
		$this->ErpQuery = "INSERT INTO ".$this->tablename." 
		(SETsNome, SETsDescricao, SETdInclusao, SETdAlteracao)
		VALUES 
		('".$obj->SETsNome."', '".$obj->SETsDescricao."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->ErpQuery);
		$this->throwExceptionOnError();
		$this->ErpConexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarErpSetores();	
	}
	/**
	* M�todo para editar os setores.
	* @param $obj Cont�m os dados dos setores a serem atualizados.
	* @returns Retorna a lista de setores atualizada.
	*/
	public function editarErpSetores($obj)
	{
		$this->ErpConexao->openConexao();
		$this->ErpQuery = "UPDATE ".$this->tablename." SET
		SETsNome = '".$obj->SETsNome."', SETsDescricao = '".$obj->SETsDescricao."', SETdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE SETnId = '".$obj->SETnId."'";
		$result = mysql_query($this->ErpQuery);
		$this->throwExceptionOnError();
		$this->ErpConexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarErpSetores();	
	}
	/**
	* M�todo para excluir os setores.
	* @param $obj Cont�m o ID do setor a ser excluido.
	* @returns Retorna a lista de setores atualizada.
	*/
	public function excluirErpSetores($index)
	{
		$this->ErpConexao->openConexao();
		$this->ErpQuery = "DELETE FROM ".$this->tablename." 
		WHERE SETnId = '".$index."'";
		$result = mysql_query($this->ErpQuery);
		$this->throwExceptionOnError();
		$this->ErpConexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarErpSetores();	
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
