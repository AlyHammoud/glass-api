<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request){
        
        $cred = $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'password' => 'required|min:5',
        ]);

        if(!Auth::attempt($cred)){
            return response([
                'error' => "The provided credentials are not correct"
            ], 422);
        }

        /** @var User $user */
        $user = Auth::user();

        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function update(Request $request, User $user){

        $cred = $request->validate([
            'id' => 'required|numeric',
            'email' => 'required|email|min:4',
            'password' => 'required|confirmed|min:8'
        ]);

        $cred['password'] = bcrypt($request['password']);

        $user_update = $user->update($cred);

        return response([
            'user' =>  $user_update,
            'a' => $user
        ]);
    }

    public function logout(){
        /** @var User $user */

        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response([
            'success' => true
        ]);
    }

    public function sendEmail(Request $request, Contact $contact){
        $data = $request->validate([
            'email' => 'required|email',
            'phone_number' => 'numeric|nullable',
            'full_name' => 'required|string|max:20',
            'message' => 'required'
        ]);

        $contact->create($data);
        
        Mail::send('contacts_email',[
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'messages' => $data['message'],
        ], function ($message) use ($data) {
            $message->from($data['email']);
            $message->to('abc@example.com');
        });


        return response([
            'sent' => true
        ]);
    }
}
