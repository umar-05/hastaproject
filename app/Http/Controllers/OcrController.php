<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        try {
            $image = $request->file('image');
            $tempPath = $image->store('temp', 'public');
            $fullPath = storage_path('app/public/' . $tempPath);

            // Try multiple OCR passes with different settings
            $texts = [];
            
            // Pass 1: Default settings
            $ocr1 = new TesseractOCR($fullPath);
            $this->setTesseractPath($ocr1);
            $ocr1->psm(3);
            $texts[] = $ocr1->run();
            
            // Pass 2: Treat as single block
            $ocr2 = new TesseractOCR($fullPath);
            $this->setTesseractPath($ocr2);
            $ocr2->psm(6);
            $texts[] = $ocr2->run();
            
            // Pass 3: Sparse text
            $ocr3 = new TesseractOCR($fullPath);
            $this->setTesseractPath($ocr3);
            $ocr3->psm(11);
            $texts[] = $ocr3->run();

            // Combine all text from different passes
            $combinedText = implode("\n", $texts);
            
            \Log::info('OCR Combined Output:', ['text' => $combinedText]);

            // Extract matric card data
            $extractedData = $this->extractUTMMatricCard($combinedText, $fullPath);

            // Delete temporary file
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            return response()->json([
                'success' => true,
                'data' => $extractedData,
                'raw_text' => $combinedText
            ]);

        } catch (\Exception $e) {
            \Log::error('OCR Error:', ['message' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

private function extractUTMMatricCard($text, $imagePath = null)
{
    $data = [
        'matricNum' => null,
        'name' => null,
        'faculty' => null,
        'phone' => null
    ];

    $lines = explode("\n", $text);
    $cleanText = preg_replace('/\s+/', ' ', $text);

    // First, try to extract matric number from combined text
    $matricPatterns = [
        '/\b([A-Z]\d{2}[A-Z]{2}\d{4})\b/i',
        '/\b([A-Z]\d{2}[A-Z]{1,3}\d{3,5})\b/i',
        '/([A-Z]\d{2}[A-Z0-9]{2}\d{4})/i',
    ];

    foreach ($lines as $line) {
        $line = trim($line);
        foreach ($matricPatterns as $pattern) {
            if (preg_match($pattern, $line, $matches)) {
                $potential = strtoupper($matches[1]);
                if (preg_match('/[A-Z]/', $potential) && preg_match('/\d/', $potential) && strlen($potential) >= 8) {
                    $data['matricNum'] = $potential;
                    \Log::info('Found matric in text:', ['matric' => $potential]);
                    break 2;
                }
            }
        }
    }

    // If matric not found, do dedicated matric number extraction
    if (!$data['matricNum'] && $imagePath) {
        $data['matricNum'] = $this->extractMatricNumber($imagePath);
    }

    // Extract Faculty FIRST (before name, so we can filter it out)
    $facultyKeywords = [
        'COMPUTING', 'COMPUTER', 'ENGINEERING', 'BUSINESS', 'SCIENCE', 
        'EDUCATION', 'MANAGEMENT', 'ENVIRONMENT', 'CHEMICAL', 
        'MECHANICAL', 'ELECTRICAL', 'CIVIL', 'ARCHITECTURE', 'DESIGN',
        'BUILT', 'GEOINFORMATION'
    ];
    
    $detectedFaculty = null;
    foreach ($facultyKeywords as $keyword) {
        if (stripos($cleanText, $keyword) !== false) {
            $detectedFaculty = $keyword;
            $data['faculty'] = $keyword;
            \Log::info('Found faculty:', ['faculty' => $keyword]);
            break;
        }
    }

    // Extract Name - More strict filtering
    $excludeWords = [
        'RHB', 'KEMENTERIAN', 'PENDIDIKAN', 'TINGGI', 'TINGG', 'UTM', 
        'UNIVERSIT', 'UNIVERSTT', 'TEKNOLOG', 'MALAYSIA', 'MYSISWA', 
        'SECPH', 'ISLAMIC', 'MYDEBIT', 'VISA', 'JOHOR', 'BAHRU', 
        'UNDERGRADUATE', 'DEBIT', 'STUDENT', 'CARD', 'MATRIC',
        // Add all faculty keywords to exclude list
        'COMPUTING', 'COMPUTER', 'ENGINEERING', 'BUSINESS', 'SCIENCE', 
        'EDUCATION', 'MANAGEMENT', 'ENVIRONMENT', 'CHEMICAL', 
        'MECHANICAL', 'ELECTRICAL', 'CIVIL', 'ARCHITECTURE', 'DESIGN',
        'BUILT', 'GEOINFORMATION'
    ];
    
    $nameLines = [];
    $inNameSection = false;
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        if (strlen($line) < 3) continue;
        
        // Check if this line contains any excluded words
        $shouldSkip = false;
        foreach ($excludeWords as $word) {
            if (stripos($line, $word) !== false) {
                $shouldSkip = true;
                break;
            }
        }
        
        if ($shouldSkip) {
            if ($inNameSection) break; // Stop collecting name if we hit excluded words
            continue;
        }
        
        // Look for all caps lines that could be names
        if (preg_match('/^[A-Z][A-Z\s]{4,}$/u', $line)) {
            // Additional validation: names usually have multiple words
            $words = explode(' ', $line);
            $validWords = array_filter($words, function($word) {
                return strlen($word) >= 2; // Each word should be at least 2 chars
            });
            
            // If it's a single word and matches faculty keyword, skip it
            if (count($validWords) === 1 && in_array(strtoupper($line), $excludeWords)) {
                continue;
            }
            
            // Names typically have 2+ words (first name, last name, etc.)
            if (count($validWords) >= 2 || !$inNameSection) {
                $nameLines[] = $line;
                $inNameSection = true;
            }
        } elseif ($inNameSection && strlen($line) > 3) {
            if (preg_match('/^[A-Z\s]+$/i', $line)) {
                $upperLine = strtoupper($line);
                // Check if this continuation line is not an excluded word
                $isExcluded = false;
                foreach ($excludeWords as $word) {
                    if (stripos($upperLine, $word) !== false) {
                        $isExcluded = true;
                        break;
                    }
                }
                if (!$isExcluded) {
                    $nameLines[] = $upperLine;
                }
            } else {
                break;
            }
        }
        
        if (count($nameLines) >= 3) break;
    }
    
    if (!empty($nameLines)) {
        $fullName = implode(' ', $nameLines);
        
        // Final validation: name should not be just a single faculty word
        if (count($nameLines) > 1 || !in_array(strtoupper($fullName), $excludeWords)) {
            $data['name'] = $fullName;
            \Log::info('Found name:', ['name' => $fullName, 'lines' => count($nameLines)]);
        } else {
            \Log::warning('Rejected potential name (matched exclusion list):', ['text' => $fullName]);
        }
    }

    // Extract Phone
    if (preg_match('/\b(01\d{8,9})\b/', $cleanText, $matches)) {
        $data['phone'] = $matches[1];
    }

    return $data;
}

    private function extractMatricNumber($imagePath)
{
    if (!file_exists($imagePath)) {
        return null;
    }

    try {
        // Try multiple aggressive approaches for matric number
        $approaches = [
            ['psm' => 7, 'whitelist' => true],   // Single line with whitelist
            ['psm' => 8, 'whitelist' => true],   // Single word with whitelist
            ['psm' => 11, 'whitelist' => true],  // Sparse text with whitelist
            ['psm' => 6, 'whitelist' => false],  // Block of text
            ['psm' => 3, 'whitelist' => false],  // Auto
        ];

        foreach ($approaches as $approach) {
            $ocr = new TesseractOCR($imagePath);
            $this->setTesseractPath($ocr);
            $ocr->psm($approach['psm']);
            
            if ($approach['whitelist']) {
                $ocr->whitelist(range('A', 'Z'), range('0', '9'));
            }
            
            $text = $ocr->run();
            \Log::info("Matric scan attempt (PSM {$approach['psm']}):", ['text' => $text]);
            
            // Look for matric pattern in this specific scan
            if (preg_match('/([A-Z]\d{2}[A-Z]{2}\d{4})/i', $text, $matches)) {
                \Log::info('Found matric!', ['matric' => $matches[1]]);
                return strtoupper($matches[1]);
            }
            
            // Also try more flexible pattern
            if (preg_match('/([A-Z]\d{2}[A-Z]{1,3}\d{3,5})/i', $text, $matches)) {
                $potential = strtoupper($matches[1]);
                if (strlen($potential) >= 8 && strlen($potential) <= 11) {
                    \Log::info('Found matric (flexible)!', ['matric' => $potential]);
                    return $potential;
                }
            }
        }
        
        return null;
        
    } catch (\Exception $e) {
        \Log::error('Matric extraction error:', ['error' => $e->getMessage()]);
        return null;
    }
}

    private function setTesseractPath($ocr)
    {
        // 1. Check for Windows local development path
        $windowsPath = 'C:\\Program Files\\Tesseract-OCR\\tesseract.exe';
        if (file_exists($windowsPath)) {
            $ocr->executable($windowsPath);
            return;
        }

        // 2. For Railway (Linux), we simply call 'tesseract'. 
        // Nixpacks adds it to the system $PATH, so it works globally.
        $ocr->executable('tesseract');
    }
}
