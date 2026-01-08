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
     * Process uploaded matric card using OCR
     */
    public function processMatricCard(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // name matched to JS FormData
        ]);

        try {
            $image = $request->file('image');
            $tempPath = $image->store('temp', 'public');
            $fullPath = storage_path('app/public/' . $tempPath);

            $ocr = new TesseractOCR($fullPath);
            $tesseractPath = 'C:\\Program Files\\Tesseract-OCR\\tesseract.exe';
            
            if (!file_exists($tesseractPath)) {
                return response()->json(['success' => false, 'message' => 'OCR Engine not found.'], 500);
            }

            $ocr->executable($tesseractPath);
            $ocr->lang('eng');
            $text = $ocr->run();

            $data = $this->parseMatricCardData($text);

            if (file_exists($fullPath)) { unlink($fullPath); }

            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $data['name'],
                    'matricNum' => $data['matric_number'],
                    'faculty' => $data['faculty'],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Helper to parse OCR text
     */
    private function parseMatricCardData(string $text): array
    {
        $data = ['name' => '', 'matric_number' => '', 'faculty' => ''];
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
        return $data;
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Determine Validation Rules based on Role
        $isStudent = $request->role === 'student';

        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'matricNum' => ['required', 'string', 'max:20', 'unique:customer,matricNum'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:customer,email'],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            // Faculty is only strictly required for students
            'faculty'   => [$isStudent ? 'required' : 'nullable', 'string', 'max:255'],
        ]);

        // 2. Create the Customer
        $user = Customer::create([
            'matricNum'    => $request->matricNum,
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            // If staff, we save "UTM Staff" as the faculty
            'faculty'      => $isStudent ? $request->faculty : 'UTM Staff',
            'accStatus'    => 'active',
            'rewardPoints' => 0,
        ]);

        event(new Registered($user));

        return redirect()->route('login')->with('success', 'Registration successful! Please login to continue.');
    }
}