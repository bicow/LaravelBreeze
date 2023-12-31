<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\clientes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/cadastrar', function () {
    return view('cadastrar');
})->middleware(['auth', 'verified'])->name('cadastrar');

Route::get('/listar', function () {
    $clientes = clientes::all();
    return view('listar', compact('clientes'));
})->middleware(['auth', 'verified'])->name('listar');

Route::post('/cadastrar', function (Request $informacoes) {
    clientes::create([
        'nome' => $informacoes->nome,
        'endereco' => $informacoes->endereco,
        'bairro' => $informacoes->bairro,
        'cep' => $informacoes->cep,
        'cidade' => $informacoes->cidade,
        'estado' => $informacoes->estado
    ]);

    $clientes = clientes::all();
    return redirect('/listar');
    // return view('listar', compact('clientes'));
});

Route::get('editar/{id}', function($id) {
    $cliente = clientes::findOrFail($id);
    return view('editar', compact('cliente'));
});
    
Route::put('/atualizar/{id}', function(Request $informacoes, $id) {
    $clientes = clientes::all();
    $clientes = clientes::findOrFail($id);
    $clientes->nome = $informacoes->nome;
    $clientes->endereco = $informacoes->endereco;
    $clientes->bairro = $informacoes->bairro;
    $clientes->cep = $informacoes->cep;
    $clientes->cidade = $informacoes->cidade;
    $clientes->estado = $informacoes->estado;
    $clientes->save();

    return redirect('listar');
});

Route::delete('excluir/{id}', function($id) {
    $cliente = clientes::findOrFail($id);
    $cliente->delete();
    
    return redirect("listar");
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
