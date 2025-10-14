# 📝 Memo Generator - Setup Guide

## Overview
This is a standalone Word document generator for creating professional Memorandums with A4 page size.

## Installation Steps

### 1. Install PHPWord Library

You need to install the PHPWord library using Composer.

**Option A: Using Composer (Recommended)**
```bash
cd c:\xampp\htdocs\ceremony2
composer require phpoffice/phpword
```

**Option B: Manual Installation**
If you don't have Composer installed:
1. Download Composer from: https://getcomposer.org/download/
2. Install Composer on your system
3. Open Command Prompt and navigate to your project folder
4. Run: `composer require phpoffice/phpword`

### 2. Add Logo Files

Place your logo images in the `img` folder:

- **Left Logo (Kuwait Oil Company)**: Save as `img/logo-left.png`
- **Right Logo (NEWKUWAIT)**: Save as `img/logo-right.png`

**Logo Requirements:**
- Format: PNG (recommended) or JPG
- Recommended size: 150x150 pixels or similar square dimensions
- The generator will resize them to 80x80 points

### 3. Access the Generator

Open your browser and go to:
```
http://localhost/ceremony2/memo-letter.php
```

## How to Use

1. **Fill in the form fields:**
   - From: Your position/title
   - To: Recipient's position/title
   - Date: Auto-filled with today's date (you can change it)
   - Ref: Reference number (optional)
   - Contract Number: e.g., "CONTRACT NO. 25063239"
   - Contract Title: Full title of the contract
   - Subject Line (SUB:): Brief subject description
   - Body Text: Main content (separate paragraphs with double line breaks)
   - Signature Name: Your name for the signature section
   - Footer details: Excl, Cc, and website

2. **Click "Generate Memorandum"**

3. **Download the Word document**
   - File will be automatically downloaded as `.docx`
   - Filename format: `Memorandum_YYYYMMDD_HHMMSS.docx`

## Features

✅ A4 page size
✅ Professional formatting matching your template
✅ Logo placeholders (with support for actual images)
✅ Automatic paragraph formatting
✅ Proper spacing and alignment
✅ Signature section with line
✅ Footer with website
✅ Clean, responsive web form

## Customization

### Change Page Margins
Edit lines 47-50 in `memo-letter.php`:
```php
'marginLeft' => Converter::inchToTwip(1),
'marginRight' => Converter::inchToTwip(1),
'marginTop' => Converter::inchToTwip(0.8),
'marginBottom' => Converter::inchToTwip(0.8),
```

### Change Fonts
Look for `'name' => 'Times New Roman'` and replace with your preferred font.

### Change Font Sizes
Look for `'size' => 11` (or other numbers) and adjust as needed.

## Troubleshooting

### "PHPWord Library Required" Error
- You need to install PHPWord using Composer (see Installation Step 1)

### Logo Not Showing
- Make sure logo files are placed in `img/logo-left.png` and `img/logo-right.png`
- Check file permissions
- Verify file path in lines 61 and 71 of the code

### Download Not Working
- Check PHP file permissions
- Verify temp directory is writable
- Check browser download settings

## Next Steps

This generator currently supports **Memorandums**. 

To add **Letter Generator**, we'll create a similar form with letter-specific formatting. Let me know when you're ready!

## Support

For any issues or customization needs, refer to:
- PHPWord Documentation: https://phpword.readthedocs.io/
- This is a standalone project, independent from the main ceremony system

---
Created: October 15, 2025
