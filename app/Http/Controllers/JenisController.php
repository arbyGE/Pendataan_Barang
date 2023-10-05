<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\jenis;
use Carbon\Carbon;
use App\Exports\JenisExport;
use Maatwebsite\Excel\Facedes\Excel;

class JenisController extends Controller
{
    public function index(Request $request)
    {
        $Search = $request->Search;
        $jenis = jenis::where('nama_jenis','LIKE','%'.$Search.'%')->get();
        return view('admin/jenis/data-jenis',compact('jenis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenis = jenis::all();
        return view('/admin/jenis/jenis-add',compact('jenis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $jenis = new jenis;
        $jenis->nama_jenis = $request->nama_jenis;
        $jenis->save();
        return redirect('/admin/jenis/data-jenis');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(request $request,$id)
    {
        $jenis = jenis::findOrFail($id);
        return view('/admin/jenis/jenis-edit',compact('jenis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jenis = jenis::findOrFail($id);
        $jenis->nama_jenis = $request->nama_jenis;
        $jenis->save();
        return redirect('/admin/jenis/data-jenis');
    }

    public function delete($id)  {
        $jenis = jenis::findOrFail($id);
        return view('/admin/jenis/jenis-delete',compact('jenis'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deletejenis = jenis::findOrFail($id);
        $deletejenis->delete();
        return redirect('/admin/jenis/data-jenis');
    }
    public function export()
    {
     return (new JenisExport)->download('jenis-'.Carbon::now()->timestamp.'.xlsx');
    }
}

