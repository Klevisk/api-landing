<?php

namespace App\Http\Controllers;

use App\Models\Social;
use App\Http\Requests\Social\StoreRequest;
use App\Http\Requests\Social\UpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SocialController extends Controller
{
    public function index()
    {
        $socials = Social::all();
        return response()->json(['socials' => $socials], 200);
    }

    public function show($id)
    {
        try {
            $socials = Social::findOrFail($id);

            return response()->json(['socials' => $socials], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Red social no encontrada'], 404);
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $validatedData = $request->validated();


            $socials = new Social($validatedData);
            $socials->save();

            return response()->json(['message' => 'Red social creada con éxito', 'socials' => $socials], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $socials = Social::findOrFail($id);
            $validatedData = $request->validated();


            $socials->fill($validatedData);
            $socials->save();

            return response()->json(['message' => 'Red social actualizada con éxito', 'socials' => $socials], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Red social no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $socials = Social::findOrFail($id);

            $socials->delete();

            return response()->json(['message' => 'Red social eliminada con éxito'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Red social no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
