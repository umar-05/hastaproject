<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
public function edit(Request $request): View
{
    $user = Auth::guard('customer')->user();

    // Define the mapping for dropdowns
    $stateData = [
        'JOHOR' => ['Johor Bahru', 'Batu Pahat', 'Kluang', 'Muar', 'Skudai', 'Pasir Gudang'],
        'SELANGOR' => ['Shah Alam', 'Petaling Jaya', 'Subang Jaya', 'Klang', 'Cyberjaya', 'Sepang'],
        'KUALA LUMPUR' => ['Kuala Lumpur', 'Kepong', 'Cheras', 'Setiawangsa'],
        'PENANG' => ['Georgetown', 'Butterworth', 'Bayan Lepas', 'Bukit Mertajam'],
        'PERAK' => ['Ipoh', 'Taiping', 'Manjung', 'Teluk Intan'],
        'MELAKA' => ['Melaka City', 'Alor Gajah', 'Jasin'],
        'NEGERI SEMBILAN' => ['Seremban', 'Port Dickson', 'Nilai'],
        'PAHANG' => ['Kuantan', 'Temerloh', 'Bentong'],
        'KEDAH' => ['Alor Setar', 'Sungei Petani', 'Kulim'],
        'KELANTAN' => ['Kota Bharu', 'Pasir Mas', 'Tumpat'],
        'TERENGGANU' => ['Kuala Terengganu', 'Dungun', 'Kemaman'],
        'PERLIS' => ['Kangar', 'Arau'],
        'SABAH' => ['Kota Kinabalu', 'Sandakan', 'Tawau'],
        'SARAWAK' => ['Kuching', 'Miri', 'Sibu', 'Bintulu'],
    ];

    return view('profile.edit', [
        'user' => $user,
        'stateData' => $stateData,
    ]);
}

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user('customer');
        
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user('customer');

        Auth::guard('customer')->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
 * Store the user's uploaded documents.
 */
public function storeDocuments(Request $request): RedirectResponse
{
    // 1. Validate the files
    $request->validate([
        'ic_passport' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // Max 5MB
        'license'     => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        'matric_card' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
    ]);

    $user = $request->user('customer');

    // 2. Handle File Uploads (Save to 'public' disk)
    if ($request->hasFile('ic_passport')) {
        $user->doc_ic_passport = $request->file('ic_passport')->store('documents', 'public');
    }

    if ($request->hasFile('license')) {
        $user->doc_license = $request->file('license')->store('documents', 'public');
    }

    if ($request->hasFile('matric_card')) {
        $user->doc_matric = $request->file('matric_card')->store('documents', 'public');
    }

    // 3. Save to Database
    $user->save();

    return Redirect::route('profile.edit')->with('status', 'documents-uploaded');
}
}