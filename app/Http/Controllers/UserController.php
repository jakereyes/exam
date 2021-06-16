<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth; 
use App\Models\Product;

class UserController extends Controller
{
    public function login(){ 

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['Access_token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json($success, 201); 
        } 
        else{ 
            return response()->json(['message'=>'Invalid credentials'], 401); 
        } 
    }

    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'email' => 'unique:users,email', 
            'password' => 'required', 
        ]);

        if ($validator->fails()) { 
            return response()->json(['message'=>'Email already taken'], 400);            
        }

        $input = $request->all(); 
                $input['password'] = bcrypt($input['password']); 
                $user = User::create($input); 
        return response()->json(['message'=>"User successfully registered"], 201); 
    }

    public function order(Request $request) 
    { 
        $user = Auth::user(); 
        $id = $request->id;
        $quantity = $request->quantity;
        $products = Product::select("*")->where('id', $id)->first();
        if($quantity > $products->quantity)
        {
            return response()->json(
                [
                    'message' => 'Failed to order this product due to unavailability of the stock',
                    'Product' => $products->name,
                    'Available' => $products->quantity
                ], 400);
        } 
        else
        {
            $sum = $products->quantity - $quantity;
            Product::where('id', $id)->update(['quantity' => $sum]);
            return response()->json(
                [
                    'success' => 'You have successfully ordered this product',
                    'Product' => $products->name,
                    'Available' => $sum
                ], 201);
        }
         
    } 
}
