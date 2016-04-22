<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpinstrumentos.php';
class erpinstrumentosService
{
	var $tablename = "erpinstrumentos";
	var $conexao;
	var $query;
	function erpinstrumentosService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpinstrumentos()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename;
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpinstrumentos"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function inserirerpinstrumentos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(INSnId,INSsCodigo,INSsNome,INSsDescricao,INSsCapacidade,INSsGraduacao,INSdInclusao,INSdAlteracao) 
		VALUES 
		('".$obj->INSnId."','".$obj->INSsCodigo."','".$obj->INSsNome."','".$obj->INSsDescricao."','".$obj->INSsCapacidade."','".$obj->INSsGraduacao."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpinstrumentos();
	}
	public function editarerpinstrumentos($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		INSnId = '".$obj->INSnId."',INSsCodigo = '".$obj->INSsCodigo."',INSsNome = '".$obj->INSsNome."',INSsDescricao = '".$obj->INSsDescricao."',INSsCapacidade = '".$obj->INSsCapacidade."',INSsGraduacao = '".$obj->INSsGraduacao."',INSdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE INSnId = '".$obj->INSnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpinstrumentos();
	}
	public function excluirerpinstrumentos($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE INSnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpinstrumentos();	
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