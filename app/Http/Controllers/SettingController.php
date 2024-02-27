<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;


class SettingController extends Controller
{

    public function getConfiguration()
    {
        $settings = Setting::all();

        return response()->json(['settings' => $settings], 200);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:true,false',
        ]);

        $settings = Setting::first();
        $settings->update(['status' => $request->status]);

        return response()->json(['message' => 'Configuración actualizada exitosamente'], 200);
    }

}
