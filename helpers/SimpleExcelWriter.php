<?php
/**
 * Simple Excel Writer
 * Create Excel files in XLSX format without external dependencies
 */

class SimpleExcelWriter {
    
    private $sheets = [];
    private $currentSheet = 0;
    private $sheetName = 'Sheet1';
    
    public function __construct() {
        $this->sheets[$this->currentSheet] = [
            'name' => $this->sheetName,
            'rows' => []
        ];
    }
    
    public function setSheetName($name) {
        $this->sheets[$this->currentSheet]['name'] = $name;
    }
    
    public function addNewSheet($name) {
        $this->currentSheet++;
        $this->sheetName = $name;
        $this->sheets[$this->currentSheet] = [
            'name' => $name,
            'rows' => []
        ];
    }
    
    public function addRow($data) {
        $this->sheets[$this->currentSheet]['rows'][] = $data;
    }
    
    public function generate() {
        // Create ZIP archive
        $zip = new ZipArchive();
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx');
        
        if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return false;
        }
        
        // Add [Content_Types].xml
        $zip->addFromString('[Content_Types].xml', $this->getContentTypes());
        
        // Add _rels/.rels
        $zip->addFromString('_rels/.rels', $this->getRels());
        
        // Add xl/workbook.xml
        $zip->addFromString('xl/workbook.xml', $this->getWorkbook());
        
        // Add xl/_rels/workbook.xml.rels
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->getWorkbookRels());
        
        // Add sheets
        foreach ($this->sheets as $index => $sheet) {
            $sheetIndex = $index + 1;
            $zip->addFromString('xl/worksheets/sheet' . $sheetIndex . '.xml', $this->getSheetXml($sheet['rows']));
        }
        
        $zip->close();
        
        // Output file content
        $content = file_get_contents($tempFile);
        unlink($tempFile);
        
        return $content;
    }
    
    private function getContentTypes() {
        $types = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
<Default Extension="xml" ContentType="application/xml"/>
<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
';
        
        foreach ($this->sheets as $index => $sheet) {
            $sheetIndex = $index + 1;
            $types .= '<Override PartName="/xl/worksheets/sheet' . $sheetIndex . '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
';
        }
        
        $types .= '</Types>';
        return $types;
    }
    
    private function getRels() {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>';
    }
    
    private function getWorkbook() {
        $sheets = '';
        foreach ($this->sheets as $index => $sheet) {
            $sheetIndex = $index + 1;
            $sheets .= '<sheet name="' . $this->escapeXml($sheet['name']) . '" sheetId="' . $sheetIndex . '" r:id="rId' . $sheetIndex . '"/>';
        }
        
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
<sheets>
' . $sheets . '
</sheets>
</workbook>';
    }
    
    private function getWorkbookRels() {
        $rels = '';
        foreach ($this->sheets as $index => $sheet) {
            $sheetIndex = $index + 1;
            $rels .= '<Relationship Id="rId' . $sheetIndex . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' . $sheetIndex . '.xml"/>
';
        }
        
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
' . $rels . '
</Relationships>';
    }
    
    private function getSheetXml($rows) {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
<sheetData>
';
        
        foreach ($rows as $rowIndex => $row) {
            $xml .= '<row r="' . ($rowIndex + 1) . '">';
            foreach ($row as $colIndex => $cell) {
                $colLetter = $this->getColumnLetter($colIndex);
                $cellRef = $colLetter . ($rowIndex + 1);
                $xml .= '<c r="' . $cellRef . '"><v>' . $this->escapeXml($cell) . '</v></c>';
            }
            $xml .= '</row>
';
        }
        
        $xml .= '</sheetData>
</worksheet>';
        
        return $xml;
    }
    
    private function getColumnLetter($index) {
        $letters = '';
        $i = $index;
        while ($i >= 0) {
            $letters = chr(65 + ($i % 26)) . $letters;
            $i = intval($i / 26) - 1;
        }
        return $letters;
    }
    
    private function escapeXml($string) {
        return htmlspecialchars($string, ENT_XML1, 'UTF-8');
    }
}
