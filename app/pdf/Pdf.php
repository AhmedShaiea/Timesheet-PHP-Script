<?php 

namespace Pdf;

class Pdf extends \Anouar\Fpdf\Fpdf {
    private $headerText;
	
    //Page header
    public function Header() {
        $this->SetMargins(5, 10);
		$this->SetAutoPageBreak(true, 10);
		$this->SetY(0);
        // Set font
        //$this->SetFont('helvetica', 'B', 20);
		$this->SetFont('Arial','B',12);
        // Title
        $this->Cell(0, 10, ($this->headerText ? $this->headerText : ''), 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->Ln();
    }

    // Page footer
    public function Footer() {
        // Position at 7 mm from bottom
        $this->SetY(-8);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 8, 'Page '.$this->page.'/'.$this->AliasNbPages, 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
	
	public function SetHeaderText($text) {
		$this->headerText = $text;
	}
}
