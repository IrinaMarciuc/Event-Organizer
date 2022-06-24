<?php
namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Facades\Session as FacadesSession;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }  
      
    public function checkLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        $remember = $request->only('remember');
        if (Auth::attempt($credentials, $remember)) {
            return redirect()->intended('')
                        ->withSuccess('Signed in');
        }
  
        return redirect("login")->withError('Login details are not valid');
    }

    public function register()
    {
        return view('auth.registration');
    }
      
    public function registerUser(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
           
        $data = $request->all();
        $check = $this->create($data);
         
        return redirect("login")->withSuccess('Successfully created account');
    }

    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => FacadesHash::make($data['password'])
      ])->assignRole('user');
    }
    
    public function signOut() {
        FacadesSession::flush();
        Auth::logout();
  
        return Redirect('login');
    }
}