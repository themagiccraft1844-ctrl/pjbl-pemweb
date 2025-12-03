<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;

class PegawaiController extends Controller
{
    public function index(){
        return view('pegawai.index',[
            'pegawai' => Pegawai::all()
        ]);
    }
    public function store(Request $request){
        $rules = [
            'nama'=>'required',
            'jabatan'=>'required',
            'jenis_kelamin'=>'required',
            'tanggal_lahir'=>'required',
        ];
        $request->validate($rules);
        $filename=null;
        if ($request->hasFile('foto')){
            $filename = time() . '.'. $request->foto->extension();
            $request->foto->move(public_path('foto'),$filename);
        }
        Pegawai::create([ 
            'nama'=>$request->nama,
            'jabatan'=>$request->jabatan,
            'jenis_kelamin'=>$request->jenis_kelamin,
            'tanggal_lahir'=>$request->tanggal_lahir,
            'foto'=>$filename
        ]);
         return back()->with('success','Data berhasil ditambahkan');
    }
}
