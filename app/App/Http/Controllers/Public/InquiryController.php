<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\PropertyInquiry;
use Domain\Properties\Models\Property;
use Domain\Properties\States\PropertyStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function store(Request $request, Property $property): RedirectResponse
    {
        abort_if($property->status !== PropertyStatus::Available, 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new PropertyInquiry($property, $data));
        }

        return back()->with('success', 'Your message has been sent!');
    }
}
