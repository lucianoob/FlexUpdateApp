<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erptarefas.php';
class erptarefasService
{
	var $tablename = "erptarefas";
	var $conexao;
	var $query;
	function erptarefasService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerptarefas($index)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.*, t1.USUsNome FROM ".$this->tablename." AS t0, erpusuarios as t1
		WHERE t0.TRFnDono = t1.USUnId AND t0.TRFnDono = '".$index."'
				AND t0.TRFdRealizacao = '0000-00-00 00:00:00' 
				ORDER BY t0.TRFdVencimento";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erptarefas"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listarrealizadaserptarefas($index)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.*, t1.USUsNome FROM ".$this->tablename." AS t0, erpusuarios as t1
		WHERE t0.TRFnDono = t1.USUnId AND t0.TRFnDono = '".$index."' 
		ORDER BY t0.TRFdVencimento";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erptarefas"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listaralertaerptarefas($index)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT t0.*, t1.USUsNome FROM ".$this->tablename." AS t0, erpusuarios as t1
		WHERE t0.TRFnDono = t1.USUnId AND t0.TRFnDono = '".$index."'
				AND DATE_SUB(t0.TRFdVencimento, INTERVAL t0.TRFnAlerta MINUTE) <= NOW() 
				AND t0.TRFdRealizacao = '0000-00-00 00:00:00' 
				ORDER BY t0.TRFdVencimento";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erptarefas"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerptarefas($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE TRFnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erptarefas");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerptarefas($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(TRFnId,TRFsTipo,TRFsTitulo,TRFsDescricao,TRFnDono,TRFsParticipantes,TRFdCriacao,TRFdVencimento,TRFdAdiamento,TRFdRealizacao,TRFnAlerta,TRFbPopUp,TRFnAdiada,TRFnRealizador,TRFdAlteracao) 
		VALUES 
		('".$obj->TRFnId."','".$obj->TRFsTipo."','".$obj->TRFsTitulo."','".$obj->TRFsDescricao."','".$obj->TRFnDono."','".$obj->TRFsParticipantes."','".$obj->TRFdCriacao."','".$obj->TRFdVencimento."','".$obj->TRFdAdiamento."','".$obj->TRFdRealizacao."','".$obj->TRFnAlerta."','".$obj->TRFbPopUp."','".$obj->TRFnAdiada."','".$obj->TRFnRealizador."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerptarefas($obj->TRFnDono);
	}
	public function editarerptarefas($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		TRFnId = '".$obj->TRFnId."',TRFsTipo = '".$obj->TRFsTipo."',TRFsTitulo = '".$obj->TRFsTitulo."',TRFsDescricao = '".$obj->TRFsDescricao."',TRFnDono = '".$obj->TRFnDono."',TRFsParticipantes = '".$obj->TRFsParticipantes."',TRFdCriacao = '".$obj->TRFdCriacao."',TRFdVencimento = '".$obj->TRFdVencimento."',TRFdAdiamento = '".$obj->TRFdAdiamento."',TRFdRealizacao = '".$obj->TRFdRealizacao."',TRFnAlerta = '".$obj->TRFnAlerta."',TRFbPopUp = '".$obj->TRFbPopUp."',TRFnAdiada = '".$obj->TRFnAdiada."',TRFnRealizador = '".$obj->TRFnRealizador."',TRFdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE TRFnId = '".$obj->TRFnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerptarefas($obj->TRFnDono);
	}
	public function excluirerptarefas($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE TRFnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerptarefas($obj->TRFnDono);	
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