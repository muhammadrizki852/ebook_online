<?php

namespace App\Http\Controllers\Concerns;

trait XlsxExportable
{
    private function xlsxColumnName(int $column): string
    {
        $name = '';

        while ($column > 0) {
            $column--;
            $name = chr(65 + ($column % 26)) . $name;
            $column = intdiv($column, 26);
        }

        return $name;
    }

    private function xlsxContentTypes(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>'
            . '<Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '</Types>';
    }

    private function xlsxRootRelationships(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>'
            . '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>'
            . '</Relationships>';
    }

    private function xlsxWorkbook(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="Report" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';
    }

    private function xlsxWorkbookRelationships(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
    }

    private function xlsxStyles(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="3"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="16"/><name val="Calibri"/></font><font><b/><sz val="11"/><name val="Calibri"/></font></fonts>'
            . '<fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FFD9EAD3"/><bgColor indexed="64"/></patternFill></fill></fills>'
            . '<borders count="2"><border><left/><right/><top/><bottom/><diagonal/></border><border><left style="thin"><color rgb="FF999999"/></left><right style="thin"><color rgb="FF999999"/></right><top style="thin"><color rgb="FF999999"/></top><bottom style="thin"><color rgb="FF999999"/></bottom><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="6"><xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0"/><xf numFmtId="0" fontId="1" fillId="0" borderId="1" xfId="0" applyAlignment="1"><alignment horizontal="center"/></xf><xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyAlignment="1"><alignment horizontal="center"/></xf><xf numFmtId="0" fontId="2" fillId="0" borderId="1" xfId="0"/><xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0"/><xf numFmtId="0" fontId="2" fillId="2" borderId="1" xfId="0"/></cellXfs>'
            . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            . '</styleSheet>';
    }

    private function xlsxAppProperties(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">'
            . '<Application>Laravel</Application></Properties>';
    }

    private function xlsxCoreProperties(): string
    {
        $timestamp = now()->toIso8601String();

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
            . '<dc:title>Report</dc:title><dc:creator>Ebook Store System</dc:creator><cp:lastModifiedBy>Ebook Store System</cp:lastModifiedBy>'
            . '<dcterms:created xsi:type="dcterms:W3CDTF">' . $timestamp . '</dcterms:created><dcterms:modified xsi:type="dcterms:W3CDTF">' . $timestamp . '</dcterms:modified>'
            . '</cp:coreProperties>';
    }

    private function createXlsxArchive(array $files): string
    {
        $archive = '';
        $centralDirectory = '';

        foreach ($files as $name => $contents) {
            $offset = strlen($archive);
            $crc = crc32($contents);
            $size = strlen($contents);
            $nameLength = strlen($name);

            $archive .= pack('VvvvvvVVVvv', 0x04034b50, 20, 0, 0, 0, 0, $crc, $size, $size, $nameLength, 0)
                . $name
                . $contents;

            $centralDirectory .= pack('VvvvvvvVVVvvvvvVV', 0x02014b50, 20, 20, 0, 0, 0, 0, $crc, $size, $size, $nameLength, 0, 0, 0, 0, 0, $offset)
                . $name;
        }

        $centralDirectoryOffset = strlen($archive);
        $archive .= $centralDirectory;

        return $archive . pack('VvvvvVVv', 0x06054b50, 0, 0, count($files), count($files), strlen($centralDirectory), $centralDirectoryOffset, 0);
    }

    private function xlsxCell(int $column, int $row, mixed $value, string $type = 'string', int $style = 0): string
    {
        $reference = $this->xlsxColumnName($column) . $row;
        $styleAttribute = $style ? ' s="' . $style . '"' : '';

        if ($type === 'number' && is_numeric($value)) {
            return '<c r="' . $reference . '"' . $styleAttribute . '><v>' . $value . '</v></c>';
        }

        return '<c r="' . $reference . '" t="inlineStr"' . $styleAttribute . '><is><t>' . $this->xmlEscape((string) $value) . '</t></is></c>';
    }

    private function xlsxWorksheet(array $rows, array $options = []): string
    {
        $sheetRows = '';
        $columnWidths = $options['columns'] ?? [6, 20, 24, 22, 28, 20, 20, 20, 20];
        $rowHeights = $options['rowHeights'] ?? [];

        foreach ($rows as $rowIndex => $row) {
            $cells = '';
            $rowNumber = $rowIndex + 1;
            $heightAttribute = isset($rowHeights[$rowNumber])
                ? ' ht="' . (float) $rowHeights[$rowNumber] . '" customHeight="1"'
                : '';

            foreach ($row as $columnIndex => $cell) {
                $cells .= $this->xlsxCell($columnIndex + 1, $rowNumber, $cell['value'] ?? '', $cell['type'] ?? 'string', $cell['style'] ?? 0);
            }

            $sheetRows .= '<row r="' . $rowNumber . '"' . $heightAttribute . '>' . $cells . '</row>';
        }

        $columns = '';
        foreach ($columnWidths as $index => $width) {
            $columnNumber = $index + 1;
            $columns .= '<col min="' . $columnNumber . '" max="' . $columnNumber . '" width="' . (float) $width . '" customWidth="1"/>';
        }

        $mergeCells = '';
        if (! empty($options['merges'])) {
            $mergeReferences = '';

            foreach ($options['merges'] as $reference) {
                $mergeReferences .= '<mergeCell ref="' . $this->xmlEscape($reference) . '"/>';
            }

            $mergeCells = '<mergeCells count="' . count($options['merges']) . '">' . $mergeReferences . '</mergeCells>';
        }

        $autoFilter = ! empty($options['autoFilter'])
            ? '<autoFilter ref="' . $this->xmlEscape($options['autoFilter']) . '"/>'
            : '';

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheetViews><sheetView workbookViewId="0">' . $this->xlsxPane($options['freezePane'] ?? null) . '</sheetView></sheetViews>'
            . '<sheetFormatPr defaultRowHeight="15"/>'
            . '<cols>' . $columns . '</cols>'
            . '<sheetData>' . $sheetRows . '</sheetData>'
            . $autoFilter
            . $mergeCells
            . '</worksheet>';
    }

    private function xlsxPane(?string $topLeftCell): string
    {
        if (! $topLeftCell || ! preg_match('/^([A-Z]+)([1-9][0-9]*)$/', $topLeftCell, $matches)) {
            return '';
        }

        $column = 0;
        foreach (str_split($matches[1]) as $letter) {
            $column = ($column * 26) + (ord($letter) - 64);
        }

        $xSplit = $column - 1;
        $ySplit = ((int) $matches[2]) - 1;
        $attributes = [];

        if ($xSplit > 0) {
            $attributes[] = 'xSplit="' . $xSplit . '"';
        }

        if ($ySplit > 0) {
            $attributes[] = 'ySplit="' . $ySplit . '"';
        }

        if (empty($attributes)) {
            return '';
        }

        $attributes[] = 'topLeftCell="' . $this->xmlEscape($topLeftCell) . '"';
        $attributes[] = 'activePane="' . ($xSplit > 0 && $ySplit > 0 ? 'bottomRight' : ($xSplit > 0 ? 'topRight' : 'bottomLeft')) . '"';
        $attributes[] = 'state="frozen"';

        return '<pane ' . implode(' ', $attributes) . '/>';
    }

    private function xmlEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
