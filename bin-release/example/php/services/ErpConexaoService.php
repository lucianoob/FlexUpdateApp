<?php
/**
* Classe de conex�o com o banco de dados.
*/
class ErpConexaoService {

	var $username = "iautomat_iauto";
	var $password = "ia010458";
	//var $username = "jhsobrinho";
	//var $password = "jhs270910";
	var $server = "localhost";
	var $port = "3306";
	var $databasename = "ErpJHSobrinho";

	var $connection;
	
	/** 
	* Construtor: Construtor da classe conexao. 
	*/
	public function ErpConexaoService() 
	{
	  	
	}
	/**
	* M�todo de conex�o com o banco de dados.
	* returns Retorna o estado da conex�o com o servidor e com o banco de dados.
	*/
	public function openConexao()
	{
		$this->connection = mysql_connect($this->server, $this->username, $this->password);
		if (!$this->connection) 
		{
			die('N�o foi poss�vel realizar a conex�o com o servidor: ' . mysql_error());
		}	
		if (!mysql_select_db($this->databasename, $this->connection)) 
		{
			die ('N�o foi poss�vel conectar com o banco de dados: ' . mysql_error());
		}
		return true;
	}
	/**
	* M�todo que fecha a conex�o com o banco de dados.
	* returns Retorna o estado do fechamento da conex�o.
	*/
	public function closeConexao()
	{
		if(!mysql_close($this->connection))
		{
			die ('N�o foi poss�vel fechar a conex�o com o banco de dados: ' . mysql_error());
		}
		return true;
	}
}
?>