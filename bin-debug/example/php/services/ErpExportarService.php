<?php
class ErpExportarService 
{
	/** 
	* Construtor: Construtor da classe grupos. 
	*/
	public function ErpExportarService() 
	{
		
	}
	public function toCSV($eDataProvider, $eHeader)
	{
		$file_content = str_replace(",",";", utf8_decode($eHeader))."\n";
		$arrayDataProvider = explode(";", $eDataProvider);
		for($i=0; $i < count($arrayDataProvider); $i++)
		{
			if($i)
			{
				$file_content .= "\n";
			}
			$file_content .= str_replace("|",";", utf8_decode($arrayDataProvider[$i]));
		}
		return $file_content;
	} 
	public function toTXT($eDataProvider, $eHeader)
	{
		$file_content = str_replace(",","\t", utf8_decode($eHeader))."\r\n";
		$arrayDataProvider = explode(";", $eDataProvider);
		for($i=0; $i < count($arrayDataProvider); $i++)
		{
			if($i)
			{
				$file_content .= "\r\n";
			}
			$file_content .= str_replace("|","\t", utf8_decode($arrayDataProvider[$i]));
		}
		return $file_content;
		
	}
	public function toHTML($eDataProvider, $eHeader)
	{
		$hHeader = explode(",",$eHeader);
		$file_content = "<HTML>\n";
		$file_content = "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>\n";
		$file_content .= "<TABLE BORDER='1' BORDERCOLOR='BLUE' cellspacing='0' cellpadding='2'>\n";
		$file_content .= "\t<THEAD>\n";
		$file_content .= "\t\t<TR BGCOLOR='BLUE'>\n";
		for($i=0;$i < count($hHeader);$i++)
		{
			$file_content .= "\t\t\t<TH NOWRAP='1'><FONT COLOR='WHITE'>".$hHeader[$i]."</FONT></TH>\n";
		}
		$file_content .= "\t\t</TR>\n";
		$file_content .= "\t</THEAD>\n";
		$file_content .= "\t<TFOOT>\n";
		$file_content .= "\t</TFOOT>\n";
		$arrayDataProvider = explode(";", $eDataProvider);
		$file_content .= "\t<TBODY>\n";
		for($i=0; $i < count($arrayDataProvider); $i++)
		{
			if($i)
			{
				$file_content .= "\r\n";
			}
			$hDp = explode("|", $arrayDataProvider[$i]);
			$file_content .= "\t\t<TR>\n";
			for($j=0;$j < count($hDp);$j++)
			{
				$file_content .= "\t\t\t<TD NOWRAP='1'>".$hDp[$j]."</TD>\n";
			}
			$file_content .= "\t\t</TR>\n";
		}
		$file_content .= "\t</TBODY>\n";
		$file_content .= "</TABLE>\n";
		$file_content .= "</HTML>\n";
		return $file_content;		
	} 
	public function toXML($eDataProvider, $eHeader)
	{
		$hHeader = explode(",", utf8_decode($eHeader));
		$file_content = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?> \n";
		$file_content .= "<data>\n";
		$arrayDataProvider = explode(";", $eDataProvider);
		for($i=0; $i < count($arrayDataProvider); $i++)
		{
			if($i)
			{
				$file_content .= "\n";
			}
			$file_content .= "\t<item>\n";
			$hDp = explode("|", utf8_decode($arrayDataProvider[$i]));
			for($j=0;$j < count($hDp);$j++)
			{
				$file_content .= "\t\t<".str_replace(" ", "", $hHeader[$i]).">".$hDp[$j]."</".str_replace(" ", "", $hHeader[$j]).">\n";
			}
			$file_content .= "\t</item>";
		}
		$file_content .= "\n</data>";
		return $file_content;		
	} 
	public function toXLS($eDataProvider, $eHeader)
	{
		//http://code.google.com/p/php-excel/wiki/QuickUsageGuide
		//http://phpexcel.codeplex.com/wikipage?title=Features&referringTitle=Examples (EXTRA)
		//Incluir a classe excelwriter
		require '../library/php-excel.class.php';

		$hHeader = explode(",", $eHeader);
		$data[0] = $hHeader;
		$arrayDataProvider = explode(";", $eDataProvider);
		for($i=0;$i < count($arrayDataProvider);$i++)
		{
			$data[$i+1] = explode("|", $arrayDataProvider[$i]);
		}

		// generate file (constructor parameters are optional)
		$xls = new Excel_XML('UTF-8', false, 'PLANILHA');
		$xls->addArray($data);
		return $xls->generateXML('my-test');;		
	}
	public function toPDF($eDataProvider, $eHeader)
	{
		// http://www.fpdf.org/
		// http://www.revistaphp.com.br/artigo.php?id=145
		require('../library/fpdf/pdf.php');
				
		$pdf = new PDF();
		// Column headings
		$header = explode(",", utf8_decode($eHeader));
		// Data loading
		$arrayDataProvider = explode(";", $eDataProvider);
		for($i=0;$i < count($arrayDataProvider);$i++)
		{
			$data[$i] = explode("|", utf8_decode($arrayDataProvider[$i]));
		}
		$pdf->SetFont('Arial','',12);
		//$pdf->AddPage();
		//$pdf->BasicTable($header,$data);
		//$pdf->AddPage();
		//$pdf->ImprovedTable($header,$data);
		$pdf->AddPage();
		$pdf->FancyTable($header,$data);
		$pdf->Close();
		$pdf->Output('../temp/test.pdf', 'F');
		return "pdf";
	}
}
?>