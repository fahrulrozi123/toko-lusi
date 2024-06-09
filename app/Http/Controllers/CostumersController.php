<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Rules\MatchOldPassword;
use App\Models\Customer;
use App\Models\Province;
use App\Models\District;
use App\Models\Citie;

class CostumersController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/costumer/home';

    public function __construct()
    {
      $this->middleware('guest')->except('logout');
    }

    public function guard()
    {
     return Auth::guard('costumer');
    }

    public function showLoginForm(){
        if (Auth::guard('costumer')->check()) {
            session()->flash('success', 'Anda berhasil login. Silahkan berbelanja sepuasnya :).');
            return redirect('/costumer/home');
        }
        else {
            session()->flash('error', 'Email atau password salah.');
            return view('costumerAuth.login');
        }
    }

    private function loginFailed(){
        return redirect()
            ->back()
            ->withInput()
            ->with('error','Login failed, please try again!');
    }

    public function editProfileAndPassword()
    {
        $customer = Auth::guard('costumer')->user();
        $provinces = Province::all();
        $cities = Citie::all();
        $districts = District::all();
        return view('costumer.profile', compact('customer', 'provinces', 'cities', 'districts'));
    }
    
    public function updateProfile(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'min:11'],
            'address' => ['required', 'string'],
            'citie_id' => ['required'],
            'district_id' => ['required'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Panjang nama tidak boleh melebihi :max karakter.',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.min' => 'Panjang nomor telepon minimal :min karakter.',
            'address.required' => 'Alamat wajib diisi.',
            'citie_id.required' => 'Kota wajib dipilih.',
            'district_id.required' => 'Distrik wajib dipilih.',
        ]);

        $customer = Auth::guard('costumer')->user();

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone_number = $request->phone_number;
        $customer->address = $request->address;
        $customer->citie_id = $request->citie_id;
        $customer->district_id = $request->district_id;
        $customer->save();

        return redirect()->route('costumer.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'new_password.min' => 'Panjang kata sandi baru minimal :min karakter.',
            'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $customer = Auth::guard('costumer')->user();
        $customer->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('costumer.profile')->with('success', 'Kata Sandi berhasil diperbarui.');
    }

}
