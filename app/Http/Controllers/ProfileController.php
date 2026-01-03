<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Customer;
use App\Models\Staff; 
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
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information and sync with Customer/Staff tables.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // 1. Fill the User model with validated data
        $user->fill($request->validated());

        // 2. Synchronize data to the respective Customer or Staff table
        if ($user->role === 'customer') {
            // Find customer by matric number (since that is the unique link)
            $customer = Customer::where('matric_no', $user->matric_number)->first();
            
            if ($customer) {
                $customer->update([
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_no' => $user->phoneNum,
                    'ic_no' => $user->icNum,
                    'address' => $user->address,
                    'city' => $user->city,
                    'postcode' => $user->postcode,
                    'state' => $user->state,
                    'college_address' => $user->collegeAddress,
                    'emergency_contact_name' => $user->eme_name,
                    'emergency_no' => $user->emephoneNum,
                    'emergency_relation' => $user->emerelation,
                    'bank_name' => $user->bankName,
                    'account_no' => $user->accountNum,
                ]);
            }

        } elseif ($user->role === 'staff') {
            // Find staff by their ORIGINAL email (in case they are changing it now)
            $originalEmail = $user->getOriginal('email');
            $staff = Staff::where('email', $originalEmail)->first();

            if ($staff) {
                $staff->update([
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_no' => $user->phoneNum,
                    'ic_no' => $user->icNum,
                    'address' => $user->address,
                    'city' => $user->city,
                    'postcode' => $user->postcode,
                    'state' => $user->state,
                    // Add position or other staff specific fields if they were editable
                ]);
            }
        }

        // 3. Handle Email Verification Reset
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 4. Save the User Model
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

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}