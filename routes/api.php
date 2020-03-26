<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use App\Item;
use App\Character;
use App\Http\Controllers\CombinationController;

function makeRespone($toSend) {
  return response()
  ->json($toSend)
  ->header("Access-Control-Allow-Origin", "*");
}

Route::get('items', function() {
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    return response()
    ->json(Item::all())
    ->header("Access-Control-Allow-Origin", "*");
});

Route::get('items/{id}', function($id) {
    return response()
    ->json(Item::find($id))
    ->header("Access-Control-Allow-Origin", "*");
});

Route::post('items', function(Request $request) {
    return response()
    ->json(Item::create($request->all()))
    ->header("Access-Control-Allow-Origin", "*");
});

Route::put('items/{id}', function(Request $request, $id) {
    $item = Item::findOrFail($id);
    $item->update($request->all());

    return response()
    ->json($item)
    ->header("Access-Control-Allow-Origin", "*");
});

Route::delete('items/{id}', function($id) {
    Item::find($id)->delete();

    return response(204)
    ->header("Access-Control-Allow-Origin", "*");
});

Route::get('combination/{maxCorruption}/{character}', function($maxCorruption, $character) {
    return response()
    ->json(CombinationController::calculateForCharacter($maxCorruption, $character))
    ->header("Access-Control-Allow-Origin", "*");
});

Route::get('combination/{maxCorruption}', function($maxCorruption) {
    return response()
    ->json(CombinationController::calculate($maxCorruption))
    ->header("Access-Control-Allow-Origin", "*");
});

Route::get('characters', function() {
    // If the Content-Type and Accept headers are set to 'application/json',
    // this will return a JSON structure. This will be cleaned up later.
    return response()
    ->json(Character::all())
    ->header("Access-Control-Allow-Origin", "*");
});

Route::get('characters/{id}', function($id) {
    return response()
    ->json(Character::find($id))
    ->header("Access-Control-Allow-Origin", "*");
});

Route::post('characters', function(Request $request) {
    return response()
    ->json(Character::create($request->all()))
    ->header("Access-Control-Allow-Origin", "*");
});

Route::put('characters/{id}', function(Request $request, $id) {
    $item = Character::findOrFail($id);
    $item->update($request->all());

    return response()
    ->json($item)
    ->header("Access-Control-Allow-Origin", "*");
});

Route::delete('characters/{id}', function($id) {
    Character::find($id)->delete();

    return response(204)
    ->header("Access-Control-Allow-Origin", "*");
});
