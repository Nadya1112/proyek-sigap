<?php
namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;

class PetaSebaranController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::all();
        return view('public.sebaran', compact('kecamatans'));
    }
}