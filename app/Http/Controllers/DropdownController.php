<?php

namespace App\Http\Controllers;

use App\Models\Dropdown;
use Illuminate\Http\Request;

class DropdownController extends Controller
{
    public function dadosAuxiliares()
    {
        $lists = ['fontes', 'concedentes', 'parlamentares', 'naturezas'];
        $data = [];

        foreach ($lists as $list) {
            $data[$list] = \App\Models\Dropdown::where('list', $list)->get();
        }

            return view('admin.dadosAuxiliares', compact('data', 'lists'));
        }

    public function dadosStore(Request $request)
{
    $item = Dropdown::create([
        'list' => $request->list,
        'name' => $request->name,
    ]);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'item' => $item
        ]);
    }

    return redirect()->back();
}

public function dadosDestroy(Request $request, $id)
{
    Dropdown::findOrFail($id)->delete();

    if ($request->ajax()) {
        return response()->json(['success' => true]);
    }

    return redirect()->back();
}


}
