<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use ImageKit\ImageKit;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {

        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with(['success' => "Профилот е ажуриран!"]);
    }

    /**
     * Update the user's profile picture.
     */
    public function updateProfilePicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => ['nullable', 'image', 'max:4096'],
        ], [
            'image.required' => 'Прикачи слика!',
            'image.image' => 'Прикачи слика!',
            'image.max' => 'Максимална големина на слика: 4MB',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        if (!$request->image) {
            return redirect()->route('profile.edit');
        }

        $image = $this->uploadImage($request->image);

        $request->user()->profile_picture = $image ?? 'https://ik.imagekit.io/lztd93pns/Igralishte/Avatars/user_LxHjwweEL.png?updatedAt=1710037822026';
        $request->user()->save();

        return redirect()->route('profile.edit');
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

    private function uploadImage($image)
    {
        $imageKit = new ImageKit(
            'public_HqvXchqCR3L08wnPnLXHdgNDhk4=',
            'private_5xIEDMhXzw+5XKstdR4q/WOqiSQ=',
            'https://ik.imagekit.io/lztd93pns',
        );

        if ($image) {
            $fileType = mime_content_type($image->path());

            $fileData = [
                'file' => 'data:' . $fileType . ';base64,' . base64_encode(file_get_contents($image->path())),
                'fileName' => $image->getClientOriginalName(),
                'folder' => 'Igralishte/Avatars',
            ];

            $uploadedFile = $imageKit->uploadFile($fileData);

            if ($uploadedFile->result->url) {
                return $uploadedFile->result->url;
            }
        }

        return null;
    }
}
