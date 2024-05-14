<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use ImageKit\ImageKit;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function registerUser(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'email' => 'required|email|unique:customers',
                    'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
                    'address' => 'nullable|string',
                    'phone' => 'nullable|numeric',
                    'biography' => 'nullable|string',
                    'password' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'],
                    'confirm_password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if ($request->password != $request->confirm_password) {
                return response()->json([
                    'status' => false,
                    'message' => 'Лозинките не се совпаѓаат!',
                ], 401);
            }

            $customer = new Customer();
            $customer->firstname = $request->firstname;
            $customer->lastname = $request->lastname;
            $customer->email = $request->email;
            $customer->profile_picture = $this->uploadImage($request->profile_picture) ?? 'https://ik.imagekit.io/lztd93pns/Igralishte/Avatars/user_LxHjwweEL.png?updatedAt=1710037822026';
            $customer->address = $request->address ?? null;
            $customer->phone = $request->phone ?? null;
            $customer->biography = $request->biography ?? null;
            $customer->password = Hash::make($request->password);
            $customer->save();

            return response()->json([
                'status' => true,
                'message' => 'Успешна регистрација',
                'token' => $customer->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Се случи неочекувна грешка. Обиди се повторно!'
            ], 500);
        }
    }

    public function loginUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            if (!Auth::guard('customer')->attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Внесовте невалидно корисничко име или лозинка',
                ], 401);
            }

            $customer = Customer::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'Најавувањето е успешно',
                'token' => $customer->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => 'Се случи неочекувна грешка при најава. Обиди се повторно!'
            ], 500);
        }
    }

    public function logoutUser(Request $request)
    {
        try {
            $user = \auth('sanctum')->user();

            foreach ($user->tokens as $token) {
                $token->delete();
            }

            return response()->json([
                'status' => true,
                'message' => 'Се одјавивте!'
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Се случи неочекувана грешка при одјава. Обиди се повторно.'
            ], 500);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => 'Се случи неочекувана грешка при одјава. Обиди се повторно.'
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['status' => true, 'message' => __($status)])
            : response()->json(['status' => false, 'message' => __($status)], 500);
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirectUrl('http://127.0.0.1:8000/api/auth/google/callback')->redirect();
    }

    public function handleFacebookCallback(Request $request)
    {
        try {
            $user = Socialite::driver('facebook')->stateless()->user();

            $customer = Customer::where('email', $user->email)->first();

            if ($customer) {
                $token = $customer->createToken('API Token')->plainTextToken;
            } else {
                $nameParts = explode(' ', $user->name, 2);

                $newCustomer = new Customer();
                $newCustomer->firstname = $nameParts[0] ?? 'User';
                $newCustomer->lastname = $nameParts[1] ?? 'User';
                $newCustomer->email = $user->email;
                $newCustomer->password = Hash::make($this->generateRandomPassword());
                $newCustomer->profile_picture = $user->avatar;
                $newCustomer->save();

                $token = $newCustomer->createToken('API Token')->plainTextToken;
            }

            return response()->json([
                'status' => true,
                'message' => 'Најавувањето е успешно',
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Настана неочекувана грешка при најава. Ве молиме обидете се повторно.',
            ], 500);
        }
    }

    public function handleGoogleCallback(Request $request)
    {
        $user = Socialite::driver('google')->stateless()->user();

        $customer = Customer::where('email', $user->email)->first();

        if ($customer) {
            $token = $customer->createToken('API Token')->plainTextToken;
        } else {
            $newCustomer = new Customer();
            $newCustomer->firstname = $user->user['given_name'] ?? 'User';
            $newCustomer->lastname = $user->user['family_name'] ?? 'User';
            $newCustomer->email = $user->email;
            $newCustomer->password = Hash::make($this->generateRandomPassword());
            $newCustomer->profile_picture = $user->avatar;

            $newCustomer->save();

            $token = $newCustomer->createToken('API Token')->plainTextToken;
        }

        return response()->json([
            'status' => true,
            'message' => 'Најавувањето е успешно',
            'token' => $token
        ], 200);
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

    private function generateRandomPassword()
    {
        $length = 32;
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
        $password = '';

        $password .= substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 1);
        $password .= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 1);
        $password .= substr(str_shuffle('0123456789'), 0, 1);
        $password .= substr(str_shuffle('!@#$%^&*()-_=+'), 0, 1);

        $password .= substr(str_shuffle($chars), 0, $length - 4);

        $password = str_shuffle($password);

        return $password;
    }
}
