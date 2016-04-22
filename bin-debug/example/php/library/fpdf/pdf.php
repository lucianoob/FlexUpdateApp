<?php

require('fpdf.php');

class PDF extends FPDF
{
	public function __construct() 
	{ 
        parent::__construct('L'); 
    }
	// Load data
	function LoadData($file)
	{
		// Read file lines
		$lines = file($file);
		$data = array();
		foreach($lines as $line)
			$data[] = explode(';',trim($line));
		return $data;
	}

	// Simple table
	function BasicTable($header, $data)
	{
		// Header
		foreach($header as $col)
			$this->Cell(0,7,$col,1);
		$this->Ln();
		// Data
		foreach($data as $row)
		{
			foreach($row as $col)
				$this->Cell(0,6,$col,1);
			$this->Ln();
		}
	}

	// Better table
	function ImprovedTable($header, $data)
	{
		// Column widths
		$w = array();
		// Header
		for($i=0;$i<count($header);$i++)
		{
			$w[$i] = strlen($header[$i])*16;	
			$this->Cell($w[$i],7,$header[$i],1,0,'C');
		}
		$this->Ln();
		// Data
		foreach($data as $row)
		{
			for($i=0; $i<count($row); $i++)
			{
				$this->Cell($row[$i],6,$row[0],1, 1);
			}
			//$this->Cell(strlen($row[0])*16,6,$row[1],1);
			//$this->Cell($w[2],6,$row[2],1,0,'R');
			$this->Ln();
		}
		// Closing line
		$this->Cell(array_sum($w),0,'','T');
	}

	// Colored table
	function FancyTable($header, $data)
	{
		// Colors, line width and bold font
		$this->SetFillColor(0,0,255);
		$this->SetTextColor(255);
		$this->SetDrawColor(0,0,128);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
		// Header
		$w = array();
		for($i=0;$i<count($header);$i++)
		{
			$w[$i] = strlen($header[$i])*16;
			$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
		}
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = false;
		foreach($data as $row)
		{
			for($i=0; $i<count($row); $i++)
			{
				$this->Cell($w[$i],6,$row[$i],1, 1);
			}
			//$this->Cell($w[0],6,$row[0],'LR',0,'C',$fill);
			//$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
			//$this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);
			$this->Ln();
			$fill = !$fill;
		}
		// Closing line
		$this->Cell(array_sum($w),0,'','T');
	}
}
?>