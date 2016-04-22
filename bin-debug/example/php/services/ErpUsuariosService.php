<?php

require_once 'ErpConexaoService.php';
require_once 'erpAuditoriaService.php';
require_once 'vo/ErpUsuarios.php';

class ErpUsuariosService {

	var $tablename = "erpusuarios";
	var $conexao;
	var $query;

	public function ErpUsuariosService() 
	{
		$this->conexao = new ErpConexaoService();
	}

	public function loginErpusuarios($sLogin, $sSenha)
	{
		$this->conexao->openConexao();
		$row = new ErpUsuarios();
		$this->query = "SELECT * FROM ".$this->tablename." WHERE USUsLogin = '".$sLogin."' AND USUsSenha = '".md5($sSenha)."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		
		$row = mysql_fetch_object($result, "ErpUsuarios");
		$this->conexao->closeConexao();
		if($row === false)
			return false;
		else
			return $row;	
	}
	public function loginProducaoErpusuarios($id, $sSenha)
	{
		$this->conexao->openConexao();
		$row = new ErpUsuarios();
		$this->query = "SELECT * FROM ".$this->tablename." WHERE USUnId = '".$id."' AND USUsSenha = '".md5($sSenha)."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		
		$row = mysql_fetch_object($result, "ErpUsuarios");
		$this->conexao->closeConexao();
		if($row === false)
			return false;
		else
			return $row;	
	}
	public function selecionarErpUsuarios($login)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." WHERE USUsLogin = '$login'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$row = mysql_fetch_object($result, "ErpUsuarios");
		return $row;
	}
	/**
	* M�todo para listar todos os usu�rios do sistema.
	* @returns Retorna todos os usu�rios do sistema.
	*/
	public function listarErpUsuarios()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.USUnId, t0.USUsNome, 
						 t0.USUsLogin, t0.USUsSenha, 
						 t0.SETsSetor, t2.SETsNome, 
						 t0.USUsMenu, t0.GRPsGrupo, 
						 t1.GRPsNome, t0.USUsEmail, 
						 t0.USUsFullScreen, t0.USUdInclusao, 
						 t0.USUdAlteracao 
						 FROM (".$this->tablename." AS t0 
						 LEFT JOIN erpgrupos AS t1 ON t0.GRPsGrupo = t1.GRPnId) 
						 INNER JOIN erpsetores AS t2 ON t0.SETsSetor = t2.SETnId 
						 WHERE t0.USUnId != '1'
						 ORDER BY t0.USUnId";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "ErpUsuarios"))
		{
			$rows[$i] = $row;
			$i++;
		}
		return $rows;
	}
	/**
	* M�todo para salvar usu�rios.
	* @param $aUsuario Cont�m os dados do usu�rio a serem salvos.
	* @returns Retorna a lista de usu�rios atualizada.
	*/
	public function inserirErpUsuarios($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename." 
		(USUsNome, USUsLogin, USUsSenha, SETsSetor, USUsMenu, GRPsGrupo, USUsEmail, USUsFullScreen, USUdInclusao, USUdAlteracao)
		VALUES 
		('".$obj->USUsNome."', '".$obj->USUsLogin."', '".md5($obj->USUsSenha)."', '".$obj->SETsSetor."', '".$obj->USUsMenu."', '".$obj->GRPsGrupo."', 
		'".$obj->USUsEmail."', '".$obj->USUsFullScreen."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarErpUsuarios();	
	}
	/**
	* M�todo para editar os usuarios.
	* @param $aUSU Cont�m os dados dos usuarios a serem atualizados.
	* @returns Retorna a lista de usuarios atualizada.
	*/
	public function editarErpUsuarios($obj)
	{
		$this->conexao->openConexao();
		if($obj->USUsSenha != "")
		{
			$this->query = "UPDATE ".$this->tablename." SET
			USUsNome = '".$obj->USUsNome."', USUsLogin = '".$obj->USUsLogin."', USUsSenha = '".md5($obj->USUsSenha)."', SETsSetor = '".$obj->SETsSetor."', 
			USUsMenu = '".$obj->USUsMenu."', GRPsGrupo = '".$obj->GRPsGrupo."', USUsEmail = '".$obj->USUsEmail."', USUsFullScreen = '".$obj->USUsFullScreen."',  
			USUdAlteracao = '".date("Y-m-d H:i:s")."' 
			WHERE USUnId = '".$obj->USUnId."'";	
		} else 
		{
			$this->query = "UPDATE ".$this->tablename." SET
			USUsNome = '".$obj->USUsNome."', USUsLogin = '".$obj->USUsLogin."', SETsSetor = '".$obj->SETsSetor."', 
			USUsMenu = '".$obj->USUsMenu."', GRPsGrupo = '".$obj->GRPsGrupo."', USUsEmail = '".$obj->USUsEmail."', USUsFullScreen = '".$obj->USUsFullScreen."',  
			USUdAlteracao = '".date("Y-m-d H:i:s")."' 
			WHERE USUnId = '".$obj->USUnId."'";	
		}
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarErpUsuarios();	
	}
	/**
	* M�todo para excluir os usuarios.
	* @param $index Cont�m o ID do usuarios a ser excluido.
	* @returns Retorna a lista de usuarios atualizada.
	*/
	public function excluirErpUsuarios($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE USUnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarErpUsuarios();	
	}
	/**
	* M�todo para coletar os erros do MySQL.
	* @param $link Link do erro no MySQL.
	*/
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
