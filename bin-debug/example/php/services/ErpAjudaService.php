<?phprequire_once 'ErpConexaoService.php';require_once 'vo/erpajuda.php';class erpajudaService{	var $tablename = "erpajuda";	var $conexao;	var $query;	function erpajudaService()	{		$this->conexao = new ErpConexaoService();	}	public function listarerpajuda()	{		$this->conexao->openConexao();		$this->query = "SELECT * FROM ".$this->tablename." ORDER BY HLPsSetor,HLPnCapitulo";		$result = mysql_query($this->query);		$this->throwExceptionOnError();		$rows = array();		$i = 0;		while($row = mysql_fetch_object($result, "erpajuda"))		{			$rows[$i] = $row;			$i++;		}		$this->conexao->closeConexao();		return $rows;	}	public function buscarerpajuda($search)	{		$this->conexao->openConexao();		$this->query = "SELECT * FROM ".$this->tablename."						WHERE HLPsSetor LIKE '%".$search."%' OR 							  HLPsTitulo LIKE '%".$search."%' OR 							  HLPsDescricao LIKE '%".$search."%' OR 							  HLPsTags LIKE '%".$search."%' 							  ORDER BY HLPsSetor,HLPnCapitulo";		$result = mysql_query($this->query);		$this->throwExceptionOnError();		$rows = array();		$i = 0;		while($row = mysql_fetch_object($result, "erpajuda"))		{			$rows[$i] = $row;			$i++;		}		$this->conexao->closeConexao();		return $rows;	}	public function listarcomentarioserpajuda($tabela)	{		$this->conexao->openConexao();		$this->query = "SHOW FULL COLUMNS FROM ".$tabela;		$result = mysql_query($this->query);		$this->throwExceptionOnError();		$rows = array();		$i = 0;		while($row = mysql_fetch_object($result))		{			$rows[$i] = $row;			$i++;		}		$this->conexao->closeConexao();		return $rows;	}	public function selecionarerpajuda($index)	{		$this->conexao->openConexao();		$rows = array();		$this->query = "SELECT * FROM ".$this->tablename." 		WHERE HLPnId = '".$index."'";		$result = mysql_query($this->query);		$row = mysql_fetch_object($result, "erpajuda");		$rows[0] = $row;		$this->throwExceptionOnError();		$this->conexao->closeConexao();		if($result === false)			return false;		else			return $rows;	}	public function inserirerpajuda($obj)	{		$this->conexao->openConexao();		$this->query = "INSERT INTO ".$this->tablename."		(HLPnId,HLPsSetor,HLPnCapitulo,HLPsTitulo,HLPsDescricao,HLPsTags,HLPbExibir,HLPdAlteracao,HLPdInclusao) 		VALUES 		('".$obj->HLPnId."','".$obj->HLPsSetor."','".$obj->HLPbDica."','".$obj->HLPbFormulario."','".$obj->HLPbTutorial."','".$obj->HLPnCapitulo."','".$obj->HLPsTitulo."','".$obj->HLPsDescricao."','".$obj->HLPsResumo."','".$obj->HLPsToolTips."','".$obj->HLPsTags."','".$obj->HLPbExibir."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";		$result = mysql_query($this->query);		$this->throwExceptionOnError();		$this->conexao->closeConexao();		if($result === false)			return false;		else			return $this->listarerpajuda();	}	public function editarerpajuda($obj)	{		$this->conexao->openConexao();		$this->query = "UPDATE ".$this->tablename." SET		HLPnId = '".$obj->HLPnId."',HLPsSetor = '".$obj->HLPsSetor."',HLPnCapitulo = '".$obj->HLPnCapitulo."',HLPsTitulo = '".$obj->HLPsTitulo."',HLPsDescricao = '".$obj->HLPsDescricao."',HLPsTags = '".$obj->HLPsTags."',HLPbExibir = '".$obj->HLPbExibir."',HLPdAlteracao = '".date("Y-m-d H:i:s")."'		WHERE HLPnId = '".$obj->HLPnId."'";		$result = mysql_query($this->query);		$this->throwExceptionOnError();		$this->conexao->closeConexao();		if($result === false)			return false;		else			return $this->listarerpajuda();	}	public function excluirerpajuda($index)	{		$this->conexao->openConexao();		$this->query = "DELETE FROM ".$this->tablename." 		WHERE HLPnId = '".$index."'";		$result = mysql_query($this->query);		$this->throwExceptionOnError();		$this->conexao->closeConexao();		if($result === false)			return false;		else			return $this->listarerpajuda();		}	private function throwExceptionOnError($link = null)	{		if($link == null) {			$link = $this->conexao->connection;		}		if(mysql_error($link)) {			$msg = mysql_errno($link) . ": " . mysql_error($link);			throw new Exception('MySQL Error - '. $msg. '\n Query: '.$this->query);		}	}}?>