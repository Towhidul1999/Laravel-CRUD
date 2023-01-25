<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Exception;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            'message'=> 'JSON Response',
            'data'=> $users
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // return $request->all();
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ],401);
        }

        try{
            $user = User::create([
                'name'=> $request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User Saved Success',
                'data' => $user
            ],200);

        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong'
            ],400);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try{
            User::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Single Data Response',
                'data'=> User::find($id)
            ]);

        }catch(Exception $e){
            return response()->json([
                'success' => true,
                'message' => 'Wrong Operation'
            ]);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ],401);
        }

        try{

            $user = User::findorFail($id);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            

            return response()->json([
                'success' => true,
                'message' => 'User Update Success',
                'data' => $user
            ],200);

        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Something Wrong'
            ],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try{
            User::findOrFail($id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Remove Success'
            ]);

        }catch(Exception $e){
            return response()->json([
                'success' => true,
                'message' => 'Wrong Operation'
            ]);
        }
    }
}
