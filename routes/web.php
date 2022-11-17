<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorizationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/auth/login', function (Request $request) {
    $request->session()->put('state', $state = Str::random(40));

    $request->session()->put(
        'code_verifier',
        $code_verifier = Str::random(128)
    );

    $codeChallenge = strtr(rtrim(
        base64_encode(hash('sha256', $code_verifier, true)),
        '='
    ), '+/', '-_');

    $query = http_build_query([
        'client_id' => env('CLIENT_ID'),
        'redirect_uri' => env('REDIRECT_URL'),
        'response_type' => 'code',
        'scope' => '',
        'state' => $state,
        'code_challenge' => $codeChallenge,
        'code_challenge_method' => 'S256',
    ]);

    return redirect('http://my-open-id.test/open-id/authorize?' . $query);
});

Route::view('login', 'auth.login')->name('login');
Route::post('login', [AuthController::class, 'postLogin']);

Route::get('/open-id/authorize', [AuthorizationController::class, 'authorizeOpenID']);

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/logout', [AuthController::class, 'logout']);
});
