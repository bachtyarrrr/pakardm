<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BasisPengetahuan;
use Illuminate\Http\Request;

class DecisionTreeController extends Controller
{
    public $title = 'Decision Tree';

    public function index()
    {
        $title = $this->title;
        $bps = BasisPengetahuan::with(['gejala', 'penyakit'])
            ->where('cf', '=', '1')
            ->get();

        return view('admin.decision-tree.index', compact('bps', 'title'));
    }
}
