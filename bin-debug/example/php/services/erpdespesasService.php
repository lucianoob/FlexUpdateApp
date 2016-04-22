<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpdespesas.php';
class erpdespesasService
{
	var $tablename = "erpdespesas";
	var $conexao;
	var $query;
	function erpdespesasService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listarerpdespesas()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename." ORDER BY DESsNome";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result, "erpdespesas"))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function inserirerpdespesas($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(DESnId,DESsNome,DESlDescricao,DESdInclusao,DESdAlteracao) 
		VALUES 
		('".$obj->DESnId."','".$obj->DESsNome."','".$obj->DESlDescricao."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpdespesas();
	}
	public function editarerpdespesas($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		DESnId = '".$obj->DESnId."',DESsNome = '".$obj->DESsNome."',DESlDescricao = '".$obj->DESlDescricao."',DESdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE DESnId = '".$obj->DESnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpdespesas();
	}
	public function excluirerpdespesas($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE DESnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpdespesas();	
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