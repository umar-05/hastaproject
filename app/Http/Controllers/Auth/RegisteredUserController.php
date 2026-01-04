<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Process uploaded matric card and extract information using OCR
     */
    public function processMatricCard(Request $request)
    {
        $request->validate([
            'matric_card' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Store image temporarily
            $image = $request->file('matric_card');
            $tempPath = $image->store('temp', 'public');
            $fullPath = storage_path('app/public/' . $tempPath);

            // Initialize OCR
            $ocr = new TesseractOCR($fullPath);

            $tesseractPath = 'C:\\Program Files\\Tesseract-OCR\\tesseract.exe';
            if (!file_exists($tesseractPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tesseract OCR is not installed correctly.',
                ], 500);
            }

            $ocr->executable($tesseractPath);
            $ocr->lang('eng');

            // Run OCR
            $text = $ocr->run();

            // Parse OCR text
            $data = $this->parseMatricCardData($text);

            // Delete temp image
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $data['name'],
                    'matric_number' => $data['matric_number'],
                    'faculty' => $data['faculty'],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'OCR processing failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Extract name, matric number, and faculty from OCR text
     */
    private function parseMatricCardData(string $text): array
    {
        $data = [
            'name' => '',
            'matric_number' => '',
            'faculty' => '',
        ];

        $lines = array_filter(array_map('trim', explode("\n", $text)));

        foreach ($lines as $line) {
            if (preg_match('/[A-Z]?\d{8,10}/', $line, $match) && empty($data['matric_number'])) {
                $data['matric_number'] = $match[0];
            }

            if (preg_match('/(?:name|nama)\s*[:\-]?\s*(.+)/i', $line, $match)) {
                $data['name'] = trim($match[1]);
            }

            if (preg_match('/(?:faculty|fakulti|school)\s*[:\-]?\s*(.+)/i', $line, $match)) {
                $data['faculty'] = trim($match[1]);
            }
        }

        // Fallback: longest non-numeric line as name
        if ($data['name'] === '') {
            foreach ($lines as $line) {
                if (!preg_match('/\d/', $line) && strlen($line) > strlen($data['name'])) {
                    $data['name'] = $line;
                }
            }
        }

        return $data;
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'matric_number' => ['required', 'string', 'max:20', 'unique:customer,matricNum'],
            'faculty' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:customer,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Customer::create([
            'matricNum' => $request->matric_number,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'faculty' => $request->faculty,
        ]);

        event(new Registered($user));
        
        // Removed auto-login to prevent redirect loop
        // Auth::login($user);

        return redirect(route('login'))->with('success', 'You have signed up!');
    }

    
}
