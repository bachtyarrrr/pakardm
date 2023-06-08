<?php

namespace App\Http\Controllers\pengguna;

use App\Http\Controllers\pengguna\PenggunaController;
use App\Models\Diagnosa;
use Illuminate\Http\Request;
use App\Models\Gejala;
use App\Models\Penyakit;
use Illuminate\Support\Facades\DB;

class DiagnosaController extends PenggunaController
{
    public $title = "Diagnosa";

    public function index()
    {
        $title = $this->title;
        $bcrum = $this->bcrum('Diagnosa');
        $gejalas = Gejala::all();
        return view('pengguna.diagnosa.index', compact('title', 'bcrum', 'gejalas'));
    }

    public function analisa(Request $request)
    {
        if (empty($request->kondisi) || count($request->kondisi) < 2) {
            $this->notification('success', 'Sehat', 'Pilih Gejala Untuk Konsultasi');
            return redirect(route('pengguna.diagnosa.index'));
        }
        $arbobot = [0, 1, 0.75, 0.5, 0.25];
        $argejala = [];
        $arrCfKombine = [];
        $condition = [];
        for ($i = 0; $i < count($request->kondisi); $i++) {
            $arkondisi = explode("_", $request->kondisi[$i]);

            if ($arkondisi[1] == '1') {
                $condition[] = $arkondisi[0];
            }

            $kondisi[] = ['gejala_id' => $arkondisi[0]];
            $kepastian[$arkondisi[0]] = $arkondisi[1];
            if (strlen($request->kondisi[$i]) > 1) {
                $argejala += [$arkondisi[0] => $arkondisi[1]];
                $penyakits = Penyakit::with(['basis_pengetahuans' => function ($result) use ($kepastian) {
                    $result->with('gejala')->whereIn('gejala_id', array_keys($kepastian));
                }])->groupBy('id')->orderBy('id')->get();
            }
        }
        $bp = DB::table('basis_pengetahuan AS bp')
            ->whereIn('bp.penyakit_id', function ($query) use ($condition) {
                $query->select('bp_sub.penyakit_id')
                    ->from('basis_pengetahuan AS bp_sub')
                    ->whereIn('bp_sub.gejala_id', $condition);
            })->get();

        $bp = $bp->reduce(function ($carry, $item) {
            if (!array_key_exists($item->penyakit_id, $carry)) {
                $carry[$item->penyakit_id]['gejalas'] = [$item->gejala_id];
                return $carry;
            }

            $carry[$item->penyakit_id]['gejalas'][] = $item->gejala_id;
            return $carry;
        }, []);

        $result = array_key_first($bp);
        foreach ($bp as $penyakit => $value) {
            foreach ($condition as $i => $symptom) {
                if (in_array($symptom, $value['gejalas'])) {
                    $result = $penyakit;
                    unset($condition[$i]);
                }
            }
        }

        if (empty($penyakit)) {
            $penyakit = null;
        } else {
            $penyakit = Penyakit::where('id', $result)->first();
        }

        if (!empty($penyakit)) {
            Diagnosa::create([
                'nik' => session('biodata')['nik'],
                'nama_pemilik' => session('biodata')['nama_pemilik'],
                'no_hp' => session('biodata')['no_hp'],
                'alamat' => session('biodata')['alamat'],
                // 'nama_peliharaan' => session('biodata')['nama_peliharaan'],
                // 'jekel' => session('biodata')['jekel'],
                // 'umur' => (session('biodata')['umur']) > 0 ? (session('biodata')['umur']) : null,
                // 'berat' => (session('biodata')['berat']) > 0 ? (session('biodata')['berat']) : null,
                // 'suhu' => (session('biodata')['suhu']) > 0 ? (session('biodata')['suhu']) : null,
                'penyakit_id' => $result,
                'presentase' => '0'
            ]);
        }

        $title = $this->title;
        $bcrum = $this->bcrum('Hasil', route('pengguna.diagnosa.index'), $title);
        $gejalas = Gejala::all();
        return view('pengguna.diagnosa.analisa', compact('penyakit', 'kepastian', 'gejalas', 'title', 'bcrum'));
    }

    public function reset(Request $request)
    {
        $request->session('biodata')->flush();
        return redirect()->route('pengguna.biodata.index');
    }
}
