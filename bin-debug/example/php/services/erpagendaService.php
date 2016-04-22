<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpagenda.php';
class erpagendaService
{
	var $tablename = "erpagenda";
	var $conexao;
	var $query;
	function erpagendaService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpagenda()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." ORDER BY AGEsNome";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpagenda"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpagenda($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE AGEnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpagenda");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpagenda($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(AGEnId,AGEsTipo,AGEsNome,AGElTelefones,AGElEmails,AGElEnderecos,AGEdAlteracao,AGEdInclusao) 
		VALUES 
		('".$obj->AGEnId."','".$obj->AGEsTipo."','".$obj->AGEsNome."','".$obj->AGElTelefones."','".$obj->AGElEmails."','".$obj->AGElEnderecos."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpagenda();
	}
	public function editarerpagenda($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		AGEnId = '".$obj->AGEnId."',AGEsTipo = '".$obj->AGEsTipo."',AGEsNome = '".$obj->AGEsNome."',AGElTelefones = '".$obj->AGElTelefones."',AGElEmails = '".$obj->AGElEmails."',AGElEnderecos = '".$obj->AGElEnderecos."',AGEdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE AGEnId = '".$obj->AGEnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpagenda();
	}
	public function excluirerpagenda($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE AGEnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpagenda();	
	}
	public function excluirselecionadoserpagenda($array)
	{
		$this->conexao->openConexao();
		$return = true;
		for($i=0; $i<count($array); $i++)
		{
			$this->query = "DELETE FROM ".$this->tablename."
			WHERE AGEnId = '".$array[$i]."'";
			$result = mysql_query($this->query);
			$this->throwExceptionOnError();
			$return &= $result;
		}
		$this->conexao->closeConexao();
		if($return)
			return $this->listarerpagenda();
		else
			return $return;
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