<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Token;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Client;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('clientAuth')->except('dashboard');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    
    public function userDetails(Request $request)
    {
           
           //check if token is valid
           $token_string = $request->input('api_token');
           $token = Token::where(['client_id'=>$request->get('client')->id,'token'=>$token_string])->first();
           if(!$token)
           {
               return response()->json(['error' => 'Invalid Token'], HttpResponse::HTTP_UNAUTHORIZED);
           }
           $user = User::find($token->user_id);
           
           if(!$user)
           {
                return response()->json(['error' => 'Invalid Token'], HttpResponse::HTTP_UNAUTHORIZED);
           }
       
    
       
    
       return response()->json(compact('user'));
    }
    
    public function signup(Request $request)
    {
         
        
        $token = new Token;
          //validate request
          $v = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users,email',
                'password'=>'required'
            ]);
          if($v->fails())
          {
              $errors = $v->errors();
              return response()->json(['error' => $errors->all()], 400);
          }
            
          $credentials = Input::only('name','email', 'password');
          $credentials['password'] = bcrypt($credentials['password']);
          $token_string = str_random(60);
          
          
          try {
                $user = User::create($credentials);
              
        
                $token->client_id = $request->get('client')->id;
                $token->user_id = $user->id;
                $token->token = $token_string;
                
                $token->save();
          } catch (Exception $e) {
              
              return response()->json(['error' => 'User already exists.'], HttpResponse::HTTP_CONFLICT);
          }
          
        
          
        
          return response()->json(compact('token_string'));
    }
    public function signin(Request $request)
    {
        
           $credentials = Input::only('email');
           $password = Input::only('password');
           
           $user = User::where($credentials)->first();
          
           if ( ! $user) {
               return response()->json(['error' => 'Username and password doesn\'t match.'], HttpResponse::HTTP_UNAUTHORIZED);
           }
           else{
               if(!Hash::check($password['password'], $user->password))
               {
                   return response()->json(['error' => 'Username and password doesn\'t match.'], HttpResponse::HTTP_UNAUTHORIZED);
               }
               else{
                   $token = new Token;
                   $token_string = str_random(60);
                   $token->client_id = $request->get('client')->id;
                   $token->user_id = $user->id;
                   $token->token = $token_string;
                    
                   $token->save();
                   return response()->json(compact('token_string'));
               }
           }
           
    }
    public function dashboard(Request $request)
    {
        $tokens = DB::table('tokens')
                    ->join('clients', 'tokens.client_id', '=', 'clients.id')
                     ->select(DB::raw('count(token) as count'),'tokens.client_id','tokens.user_id','clients.client_id')
                     ->groupBy('tokens.client_id')
                     ->groupBy('tokens.user_id')
                     ->get();
        //$tokens = Token::groupBy('client_id','user_id')->get();
        //var_dump($tokens); die();
        return view('dashboard')->with('tokens',$tokens);
    }
}
