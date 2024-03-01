<?php

namespace App\Http\Controllers;

use App\Models\Cards;
use App\Http\Requests\Card\StoreRequest;
use App\Http\Requests\Card\UpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class CardsController extends Controller
{

    public function index()
    {
        $cards = Cards::all();
        return response()->json(['cards' => $cards], 200);
    }

    public function show($id)
    {
        try {
            $cards = Cards::findOrFail($id);

            return response()->json(['cards' => $cards], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Carta no encontrada'], 404);
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $cardsPath = $this->storeCard($request->file('image'));

            $cards = new Cards($validatedData);
            $cards->image = $cardsPath;
            $cards->save();

            return response()->json(['message' => 'Carta creada con éxito', 'cards' => $cards], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $cards = Cards::findOrFail($id);

            $validatedData = $request->validated();


            $cards->fill($validatedData);

            if ($request->hasFile('image')) {
                $this->deleteImage($cards->image);

                $cardPath = $this->storeCard($request->file('image'));
                $cards->image = $cardPath;
            }

            $cards->save();

            $cards->refresh();



            return response()->json(['message' => 'Carta actualizada con éxito', 'cards' => $cards], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'carta no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $cards = Cards::findOrFail($id);
            $this->deleteImage($cards->image);
            $cards->delete();

            return response()->json(['message' => 'Carta eliminada con éxito'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Carta no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function storeCard($file)
    {
        $cardName = time() . '_' . $file->getClientOriginalName();

        $path = $file->storeAs('', $cardName, 'cards');

        $url = Storage::disk('cards')->url($path);

        return $url;
    }

    private function deleteImage($cardsPath)
    {
        if ($cardsPath && Storage::disk('cards')->exists($cardsPath)) {
            Storage::disk('cards')->delete($cardsPath);
        }
    }
}
