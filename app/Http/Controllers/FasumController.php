<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fasum;

class FasumController extends Controller
{
    public function index(Request $r)
    {
        $q = $r->query('q');
        $query = Fasum::query();
        if ($q) $query->where('nama_perumahan','like','%'.$q.'%')
                      ->orWhere('alamat','like','%'.$q.'%');

        $fasums = $query->paginate(12);
        return view('public.informasi', compact('fasums','q'));
    }
}
