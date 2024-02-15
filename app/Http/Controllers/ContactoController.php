<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactoController extends Controller
{
    public function enviarMensaje(Request $request)
    {

        $request->validate([
            'nombre' => 'required',
            'email' => 'required|email',
            'mensaje' => 'required',
        ]);


        $nombre = $request->input('nombre');
        $email = $request->input('email');
        $mensaje = $request->input('mensaje');


        Mail::raw("Nombre: $nombre\nEmail: $email\nMensaje: $mensaje", function ($message) {
            $message->to('klevisk26@gmail.com')->subject('Nuevo mensaje de contacto');
        });


        return response()->json(['message' => '¡Tu mensaje ha sido enviado correctamente, pronto le sera contactado!']);
    }
}
