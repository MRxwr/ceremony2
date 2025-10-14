<?php
// Memo & Letter Generator
// This is a standalone project for generating Word documents

// Check if PHPWord is installed
$phpWordPath = __DIR__ . '/vendor/autoload.php';
if (!file_exists($phpWordPath)) {
    die('<h2>PHPWord Library Required</h2>
        <p>Please install PHPWord library using Composer:</p>
        <pre>composer require phpoffice/phpword</pre>
        <p>Or download it manually and place in vendor folder.</p>');
}

require_once $phpWordPath;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\Font;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_memo'])) {
    generateMemo();
    exit;
}

function generateMemo() {
    // Get form data
    $from = $_POST['from'] ?? '';
    $to = $_POST['to'] ?? '';
    $date = $_POST['date'] ?? date('d-M-Y');
    $ref = $_POST['ref'] ?? '';
    $contractNo = $_POST['contract_no'] ?? '';
    $contractTitle = $_POST['contract_title'] ?? '';
    $subjectLine = $_POST['subject_line'] ?? '';
    $bodyText = $_POST['body_text'] ?? '';
    $signatureName = $_POST['signature_name'] ?? '';
    $exclText = $_POST['excl_text'] ?? 'Excl: As stated above';
    $ccText = $_POST['cc_text'] ?? 'Cc: File';
    $footerWebsite = $_POST['footer_website'] ?? 'www.kockw.com';
    
    // Create new PHPWord document
    $phpWord = new PhpWord();
    
    // Set A4 page size
    $sectionStyle = array(
        'marginLeft' => Converter::inchToTwip(1),
        'marginRight' => Converter::inchToTwip(1),
        'marginTop' => Converter::inchToTwip(0.8),
        'marginBottom' => Converter::inchToTwip(0.8),
    );
    $section = $phpWord->addSection($sectionStyle);
    
    // Header with logos
    $header = $section->addHeader();
    $headerTable = $header->addTable(array('width' => 100 * 50, 'unit' => 'pct'));
    $headerTable->addRow();
    
    // Left logo (Kuwait Oil Company)
    $leftCell = $headerTable->addCell(3000);
    $leftLogoPath = __DIR__ . '/img/logo-left.png';
    if (file_exists($leftLogoPath)) {
        $leftCell->addImage($leftLogoPath, array('width' => 80, 'height' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
    } else {
        $leftCell->addText('[Left Logo]', array('size' => 10, 'color' => '999999'), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
    }
    
    // Middle cell (empty)
    $headerTable->addCell(3000);
    
    // Right logo (NEWKUWAIT)
    $rightCell = $headerTable->addCell(3000);
    $rightLogoPath = __DIR__ . '/img/logo-right.png';
    if (file_exists($rightLogoPath)) {
        $rightCell->addImage($rightLogoPath, array('width' => 80, 'height' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
    } else {
        $rightCell->addText('[Right Logo]', array('size' => 10, 'color' => '999999'), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
    }
    
    // Add spacing
    $section->addTextBreak(1);
    
    // Title: Memorandum
    $section->addText(
        'Memorandum',
        array('size' => 18, 'bold' => true, 'name' => 'Times New Roman'),
        array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 240)
    );
    
    // From/To/Date/Ref table
    $metaTable = $section->addTable(array('width' => 100 * 50, 'unit' => 'pct'));
    
    // From and Date row
    $metaTable->addRow();
    $metaTable->addCell(4500)->addText(
        'From:    ' . $from,
        array('size' => 11, 'name' => 'Times New Roman'),
        array('spaceAfter' => 100)
    );
    $metaTable->addCell(4500)->addText(
        'Date:    ' . $date,
        array('size' => 11, 'name' => 'Times New Roman'),
        array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT, 'spaceAfter' => 100)
    );
    
    // To and Ref row
    $metaTable->addRow();
    $metaTable->addCell(4500)->addText(
        'To:    ' . $to,
        array('size' => 11, 'name' => 'Times New Roman'),
        array('spaceAfter' => 100)
    );
    $metaTable->addCell(4500)->addText(
        'Ref:    ' . $ref,
        array('size' => 11, 'name' => 'Times New Roman'),
        array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT, 'spaceAfter' => 100)
    );
    
    // Horizontal line
    $section->addLine(array(
        'weight' => 1,
        'width' => 450,
        'height' => 0,
        'color' => '000000'
    ));
    
    $section->addTextBreak(1);
    
    // Contract Number and Title (Bold, Centered)
    if (!empty($contractNo)) {
        $section->addText(
            strtoupper($contractNo),
            array('size' => 11, 'bold' => true, 'name' => 'Times New Roman'),
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 80)
        );
    }
    
    if (!empty($contractTitle)) {
        $section->addText(
            strtoupper($contractTitle),
            array('size' => 11, 'bold' => true, 'name' => 'Times New Roman'),
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 120)
        );
    }
    
    // Subject line (SUB:)
    if (!empty($subjectLine)) {
        $section->addText(
            'SUB: ' . strtoupper($subjectLine),
            array('size' => 11, 'bold' => false, 'underline' => 'single', 'name' => 'Times New Roman'),
            array('spaceAfter' => 200)
        );
    }
    
    // Body text (paragraphs)
    if (!empty($bodyText)) {
        $paragraphs = explode("\n\n", $bodyText);
        foreach ($paragraphs as $paragraph) {
            if (trim($paragraph)) {
                $section->addText(
                    trim($paragraph),
                    array('size' => 11, 'name' => 'Times New Roman'),
                    array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 200, 'lineHeight' => 1.15)
                );
            }
        }
    }
    
    $section->addTextBreak(2);
    
    // Signature section
    if (!empty($signatureName)) {
        // Signature line
        $signatureTable = $section->addTable();
        $signatureTable->addRow();
        $signatureCell = $signatureTable->addCell(3000);
        
        // Handwriting icon placeholder
        $signatureCell->addText('✍', array('size' => 20), array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT));
        
        // Line above name
        $section->addText(
            '____________________________',
            array('size' => 11, 'name' => 'Times New Roman'),
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT)
        );
        
        // Name
        $section->addText(
            strtoupper($signatureName),
            array('size' => 11, 'bold' => true, 'name' => 'Times New Roman'),
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT, 'spaceAfter' => 200)
        );
    }
    
    $section->addTextBreak(1);
    
    // Excl and Cc
    $section->addText($exclText, array('size' => 10, 'name' => 'Times New Roman'));
    $section->addText($ccText, array('size' => 10, 'name' => 'Times New Roman'));
    
    // Footer with website
    $footer = $section->addFooter();
    $footer->addText(
        $footerWebsite,
        array('size' => 9, 'color' => '0000FF', 'name' => 'Times New Roman'),
        array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER)
    );
    
    // Save document
    $filename = 'Memorandum_' . date('Ymd_His') . '.docx';
    $tempFile = sys_get_temp_dir() . '/' . $filename;
    
    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save($tempFile);
    
    // Download file
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($tempFile));
    
    readfile($tempFile);
    unlink($tempFile);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memo & Letter Generator</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .form-container {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #2c3e50;
            margin: 30px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        
        .btn-container {
            margin-top: 30px;
            text-align: center;
        }
        
        .btn-generate {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 50px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-generate:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .btn-generate:active {
            transform: translateY(0);
        }
        
        .info-box {
            background: #f0f7ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 4px;
        }
        
        .info-box p {
            color: #555;
            font-size: 13px;
            line-height: 1.6;
        }
        
        .info-box strong {
            color: #2c3e50;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 Memorandum Generator</h1>
            <p>Create professional memorandum documents in Word format</p>
        </div>
        
        <div class="form-container">
            <div class="info-box">
                <p><strong>📌 Logo Placeholders:</strong> Place your logo images as:</p>
                <p>• Left Logo (Kuwait Oil Company): <code>img/logo-left.png</code></p>
                <p>• Right Logo (NEWKUWAIT): <code>img/logo-right.png</code></p>
            </div>
            
            <form method="POST" action="">
                <div class="section-title">Basic Information</div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="from">From:</label>
                        <input type="text" id="from" name="from" placeholder="Ag. Team Leader Maint. Support & Reliability (N&WK)" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="text" id="date" name="date" placeholder="<?php echo date('d-M-Y'); ?>" value="<?php echo date('d-M-Y'); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="to">To:</label>
                        <input type="text" id="to" name="to" placeholder="Team Leader CMK Employee Services" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ref">Ref:</label>
                        <input type="text" id="ref" name="ref" placeholder="Reference number (optional)">
                    </div>
                </div>
                
                <div class="section-title">Subject Details</div>
                
                <div class="form-group">
                    <label for="contract_no">Contract Number:</label>
                    <input type="text" id="contract_no" name="contract_no" placeholder="CONTRACT NO. 25063239">
                </div>
                
                <div class="form-group">
                    <label for="contract_title">Contract Title:</label>
                    <input type="text" id="contract_title" name="contract_title" placeholder="CIVIL, AIR CONDITIONING AND ASSOCIATED MAINTENANCE SERVICES IN NORTH KUWAIT AREAS">
                </div>
                
                <div class="form-group">
                    <label for="subject_line">Subject Line (SUB:):</label>
                    <input type="text" id="subject_line" name="subject_line" placeholder="TRANSFER OF KUWAITI EMPLOYEES FROM THE CONTRACT NO.24060820">
                </div>
                
                <div class="section-title">Content</div>
                
                <div class="form-group">
                    <label for="body_text">Body Text:</label>
                    <textarea id="body_text" name="body_text" placeholder="Enter the main content of the memorandum. Separate paragraphs with double line breaks.

Example:
Reference to the above-mentioned subject, kindly note that the Contract No. 24060820 will expire on 31st Oct-2025...

In this regard, the Kuwaiti employees as mentioned in Annexure-1 shall be transferred...

Moreover, kindly be informed that..." required></textarea>
                </div>
                
                <div class="section-title">Signature & Footer</div>
                
                <div class="form-group">
                    <label for="signature_name">Signature Name:</label>
                    <input type="text" id="signature_name" name="signature_name" placeholder="MISHARI AL-ENIZI" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="excl_text">Excl Text:</label>
                        <input type="text" id="excl_text" name="excl_text" value="Excl: As stated above">
                    </div>
                    
                    <div class="form-group">
                        <label for="cc_text">CC Text:</label>
                        <input type="text" id="cc_text" name="cc_text" value="Cc: File">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="footer_website">Footer Website:</label>
                    <input type="text" id="footer_website" name="footer_website" value="www.kockw.com">
                </div>
                
                <div class="btn-container">
                    <button type="submit" name="generate_memo" class="btn-generate">
                        📄 Generate Memorandum
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
