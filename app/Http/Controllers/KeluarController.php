<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\keluar;
use App\Models\barang;
use Carbon\Carbon;
use App\Exports\KeluarExport;
use Maatwebsite\Excel\Facedes\Excel;
use Barryvdh\DomPDF\Facade\Pdf;


class KeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $Search = $request->Search;
        $barang = barang::all();
        $keluar = keluar::with('Brng')
            ->whereHas('Brng',function($query) use($Search) {
                $query->where('nama_barang','LIKE','%'.$Search.'%');
            })
            ->orWhere('tanggal','LIKE','%'.$Search.'%')
            ->orWhere('satuan','LIKE','%'.$Search.'%') 
                    ->paginate(10);
        return view('/admin/keluar/data-keluar',compact('keluar','barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $keluar = keluar::with('Brng')->get();
        return view('/admin/keluar/keluar-add',compact('keluar'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $keluar = new keluar;
        $keluar->id_barang = $request->id_barang;
        $keluar->tanggal = $request->tanggal;
        $keluar->jumlah = $request->jumlah;
        $keluar->satuan = $request->satuan;
        $keluar->keterangan = $request->keterangan;
        $keluar->save();
        return redirect('/admin/keluar/data-keluar');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    function pdf($id)  {
        $keluar = keluar::with('Brng')->findOrFail($id);
        // $pdf = Pdf::loadView('/admin/keluar/cetak-pdf-keluar',['keluar' => $keluar]);
        // return $pdf->download('invoice-barang-keluar'.Carbon::now()->timestamp.'.pdf');
        return view('/admin/keluar/cetak-pdf-keluar',['keluar' => $keluar]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $keluar = keluar::with('Brng')->findOrFail($id);
        $barang = barang::where('id','!=',$keluar->id_barang)->get(['id','nama_barang']);
        return view('/admin/keluar/keluar-edit',compact('keluar','barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $keluar = keluar::findOrFail($id);
        $keluar->id_barang = $request->id_barang;
        $keluar->tanggal = $request->tanggal;
        $keluar->jumlah = $request->jumlah;
        $keluar->satuan = $request->satuan;
        $keluar->keterangan = $request->keterangan;
        $keluar->save();
        return redirect('/admin/keluar/data-keluar');
    }

    public function delete($id)  {
        $keluar = keluar::with('Brng')->findOrFail($id);
        return view('/admin/keluar/keluar-delete',compact('keluar'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deletekeluar = keluar::findOrFail($id);
        $deletekeluar->delete();
        return redirect('/admin/keluar/data-keluar');
    }
    public function export()
    {
     return (new KeluarExport)->download('Data-Keluar-'.Carbon::now()->timestamp.'.xlsx');
    }
}
