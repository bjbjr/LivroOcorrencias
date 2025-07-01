<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    // Por padrão, o Breeze não define uma rota '/', então vamos redirecionar para o login
    // ou para o dashboard se já estiver logado.
    if (auth()->check()) {
        if (auth()->user()->type === 'admin') {
            return redirect()->route('admin.dashboard'); // Uma futura rota de dashboard admin
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Rotas Administrativas ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Administrativo
    Route::get('/dashboard', function () {
        // Aqui você pode retornar uma view específica para o dashboard administrativo
        return view('admin.dashboard'); // Vamos criar essa view em breve
    })->name('dashboard');

    // Rotas para Gerenciar Clientes
    Route::resource('clients', \App\Http\Controllers\Admin\ClientController::class);

    // Rotas para Gerenciar Postos de Serviço
    Route::resource('service-points', \App\Http\Controllers\Admin\ServicePointController::class);
    // Note o slug 'service-points' para as URLs, mas o controller é ServicePointController

    // Rotas para Gerenciar Usuários do Aplicativo
    Route::resource('app-users', \App\Http\Controllers\Admin\AppUserController::class)->parameters(['app-users' => 'appUser']);
    Route::get('api/clients/{client}/service-points', [\App\Http\Controllers\Admin\AppUserController::class, 'getServicePointsForClient'])->name('api.clients.service-points');

    // Rotas para Gerenciar Contratos
    Route::resource('contracts', \App\Http\Controllers\Admin\ContractController::class);

    // Rotas para Visualizar Ocorrências (Admin)
    Route::get('occurrences', [\App\Http\Controllers\Admin\OccurrenceController::class, 'index'])->name('occurrences.index');
    Route::get('occurrences/{occurrence}', [\App\Http\Controllers\Admin\OccurrenceController::class, 'show'])->name('occurrences.show');

    // Futuramente: Rotas completas para gerenciar ocorrências (se necessário)
    // Route::resource('occurrences', \App\Http\Controllers\Admin\OccurrenceController::class);
});


require __DIR__.'/auth.php';
