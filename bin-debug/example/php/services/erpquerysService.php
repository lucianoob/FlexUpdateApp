<?php
require_once 'ErpConexaoService.php';
require_once 'vo/erpquerys.php';
class erpquerysService
{
	var $tablename = "erpquerys";
	var $conexao;
	var $query;
	
	function erpquerysService()
	{
		$this->conexao = new ErpConexaoService();
	}
	public function listar_campos($tablename)
	{
		$this->conexao->openConexao();
		$this->query = "SHOW FULL COLUMNS FROM ".$tablename." 
							WHERE Field NOT LIKE '%Alteracao%' AND Field NOT LIKE '%Inclusao%' ";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function procurar($tablename, $query)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$tablename."	WHERE ".$query;
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result))
		{
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function listarerpquerys()
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM ".$this->tablename;
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($row = mysql_fetch_object($result))
		{
			$row->QUEsPesquisa = str_replace("#","'",$row->QUEsPesquisa);
			$row->QUEsQuery = str_replace("#","'",$row->QUEsQuery);
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	public function selecionarerpquerys($index)
	{
		$this->conexao->openConexao();
		$rows = array();
		$this->query = "SELECT * FROM ".$this->tablename." 
		WHERE QUEnId = '".$index."'";
		$result = mysql_query($this->query);
		$row = mysql_fetch_object($result, "erpquerys");
		$rows[0] = $row;
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $rows;
	}
	public function inserirerpquerys($obj)
	{
		$this->conexao->openConexao();
		$this->query = "INSERT INTO ".$this->tablename."
		(QUEnId,QUEsNome,QUEsPesquisa,QUEsQuery,QUEdInclusao,QUEdAlteracao) 
		VALUES 
		('".$obj->QUEnId."','".$obj->QUEsNome."','".str_replace("'","#",$obj->QUEsPesquisa)."','".str_replace("'","#",$obj->QUEsQuery)."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpquerys();
	}
	public function editarerpquerys($obj)
	{
		$this->conexao->openConexao();
		$this->query = "UPDATE ".$this->tablename." SET
		QUEnId = '".$obj->QUEnId."',QUEsNome = '".$obj->QUEsNome."',QUEsPesquisa = '".str_replace("'","#",$obj->QUEsPesquisa)."',QUEsQuery = '".str_replace("'","#",$obj->QUEsQuery)."',QUEdAlteracao = '".date("Y-m-d H:i:s")."'
		WHERE QUEnId = '".$obj->QUEnId."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpquerys();
	}
	public function excluirerpquerys($index)
	{
		$this->conexao->openConexao();
		$this->query = "DELETE FROM ".$this->tablename." 
		WHERE QUEnId = '".$index."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$this->conexao->closeConexao();
		if($result === false)
			return false;
		else
			return $this->listarerpquerys();	
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