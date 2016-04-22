<?php
require_once 'ErpConexaoService.php';
/**
* Classe de conex�o com o banco de dados.
*/
class ErpCorreiosService 
{

	var $conexao;
	var $query;
	
	/** 
	* Construtor: Construtor da classe conexao. 
	*/
	public function ErpCorreiosService() 
	{
		$this->conexao = new ErpConexaoService();
	}
	public function cepCalculaFrete($cep_org,$cep_dst,$peso,$mao,$valor,$aviso) 
	{
		// http://forum.imasters.uol.com.br/lofiversion/index.php/t290867.html
		/*
		*********************************************
		VARIAVEIS
		*********************************************
		
		$CEP_ORG      - CEP DE ORIGEM
		$CEP_DST       - CEP DE DESTINO
		$PESO            - PESO DA ENCOMENDA
		$MAO              - ENTREGA EM MAOS ( DEFINIR (S)- SIM OU (N)-NAO
		$VALOR           - VALOR DECLARADO DO CONTEUDO (FORMATO COM VIRGULA: 10,00)
		*/
		
		$url="http://www.correios.com.br/encomendas/precos/calculo.cfm?resposta=paginaCorreios&servico=40010&cepOrigem={$cep_org}&cepDestino={$cep_dst}&peso={$peso}&MaoPropria={$mao}&valorDeclarado={$valor}&avisoRecebimento={$aviso}";
		$html = implode("", file($url));
		$html = explode('<td  align="center" colspan="2">', $html);
		$html = $html[5];
		$html = explode("<b>",$html);
		$html = $html[1];
		$html = explode("</b>",$html);
		return str_replace(',', '.', substr($html[0], 3));
	}
	public function cepCalculaFreteCurl($cep_org,$cep_dst,$peso,$mao,$valor,$aviso) 
	{
		// http://forum.imasters.uol.com.br/lofiversion/index.php/t290867.html
		$url="http://www.correios.com.br/encomendas/precos/calculo.cfm?resposta=paginaCorreios&servico=40010&cepOrigem={$cep_org}&cepDestino={$cep_dst}&peso={$peso}&MaoPropria={$mao}&valorDeclarado={$valor}&avisoRecebimento={$aviso}";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		$html = curl_exec ($ch);
		curl_close ($ch);
		
		$html = explode('<td  align="center" colspan="2">', $html);
		$html = $html[5];
		$html = explode("<b>",$html);
		$html = $html[1];
		$html = explode("</b>",$html);
		return str_replace(',', '.', substr($html[0], 3));
	}
	public function cepBuscaPorCep($cep)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM erpceptbllogradouros WHERE CEP = '".$cep."'";
		$result = mysql_query($this->query);
		$this->throwExceptionOnError();
		$rows = array();
		$i = 0;
		while($rl = mysql_fetch_array($result))
		{
			$this->query = "SELECT * FROM erpceptblbairros WHERE Codigo = '".$rl["CodigoBairro"]."'";
			$result = mysql_query($this->query);
			$rb = mysql_fetch_array($result);

			$this->query = "SELECT * FROM erpceptblcidades WHERE Codigo = '".$rl["CodigoCidade"]."'";
			$result = mysql_query($this->query);
			$rc = mysql_fetch_array($result);
			
			$row["Logradouro"] = html_entity_decode($rl["Descricao"]);
			$row["Bairro"] = html_entity_decode($rb["Descricao"]);
			$row["Cidade"] = html_entity_decode($rc["Descricao"]);
			$row["UF"] = $rc["UF"];
			$rows[$i] = $row;
			$i++;
		}
		$this->conexao->closeConexao();
		return $rows;
	}
	function cepBuscaPorLogradouro($logradouro)
	{
		$this->conexao->openConexao();
		$this->query = "SELECT * FROM erpceptbllogradouros WHERE 
		DescricaoNaoAbreviada Like '%".$logradouro."%' OR 
		Descricao Like '%".$logradouro."%' OR
		DescricaoSemAcento Like '%".$logradouro."%' ORDER BY Descricao LIMIT 100";
		$result = mysql_query($this->query);
		$rows = array();
		$cont = 0;
		while($rl = mysql_fetch_array($result))
		{
			$this->query = "SELECT * FROM erpceptblbairros WHERE Codigo = '".$rl["CodigoBairro"]."'";
			$resultb = mysql_query($this->query);
			$rb = mysql_fetch_array($resultb);

			$this->query = "SELECT * FROM erpceptblcidades WHERE Codigo = '".$rl["CodigoCidade"]."'";
			$resultc = mysql_query($this->query);
			$rc = mysql_fetch_array($resultc);
			
			$row["CEP"] = $rl["CEP"];
			$row["Logradouro"] = html_entity_decode($rl["Descricao"]);
			$row["Bairro"] = html_entity_decode($rb["Descricao"]);
			$row["Cidade"] = html_entity_decode($rc["Descricao"]);
			$row["UF"] = $rc["UF"];
			$rows[$cont] = $row;
			$cont++;
		}
		$this->conexao->closeConexao();
		return $rows;
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