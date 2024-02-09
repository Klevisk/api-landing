<?php

namespace App\Http\Controllers;

use App\Models\Promotions;
use App\Http\Requests\Promotion\StoreRequest;
use App\Http\Requests\Promotion\UpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PromotionsController extends Controller
{
    public function index()
    {
        $promotions = Promotions::all();
        return response()->json(['promotions' => $promotions], 200);
    }

    public function show($id)
    {
        try {
            $promotions = Promotions::findOrFail($id);

            return response()->json(['promotions' => $promotions], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Promocion no encontrada'], 404);
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $validatedData = $request->validated();


            $promotions = new Promotions($validatedData);
            $promotions->save();

            return response()->json(['message' => 'Promocion creada con éxito', 'promotions' => $promotions], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $promotions = Promotions::findOrFail($id);
            $validatedData = $request->validated();


            $promotions->fill($validatedData);
            $promotions->save();

            return response()->json(['message' => 'Promocion actualizada con éxito', 'promotions' => $promotions], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Promocion no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $promotions = Promotions::findOrFail($id);

            $promotions->delete();

            return response()->json(['message' => 'Promocion eliminada con éxito'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Promocion no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
