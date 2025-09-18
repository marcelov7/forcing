<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ForcingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Rotas públicas
Route::get('/', function () {
    return redirect()->route('forcing.index');
});

// Rotas de autenticação
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rota para refresh de CSRF token (para problemas em mobile)
Route::get('/csrf-token-refresh', function () {
    return response()->json(['token' => csrf_token()]);
})->name('csrf-token-refresh');

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    
    // Dashboard - redireciona para a lista de forcings
    Route::get('/dashboard', function () {
        return redirect()->route('forcing.index');
    })->name('dashboard');
    
    // Rotas de forcing (todos os usuários autenticados)
    Route::get('/forcing/terms', [ForcingController::class, 'showTerms'])->name('forcing.terms');
    Route::post('/forcing/refresh-table', [ForcingController::class, 'refreshTable'])->name('forcing.refresh-table');
    Route::resource('forcing', ForcingController::class);
    // Rotas para modais (desktop)
    Route::post('/forcing/{forcing}/liberar', [ForcingController::class, 'liberar'])->name('forcing.liberar');
    Route::post('/forcing/{forcing}/registrar-execucao', [ForcingController::class, 'registrarExecucao'])->name('forcing.registrar-execucao');
    Route::post('/forcing/{forcing}/solicitar-retirada', [ForcingController::class, 'solicitarRetirada'])->name('forcing.solicitar-retirada');
    Route::get('/forcing/{forcing}/retirar', [ForcingController::class, 'retirar'])->name('forcing.retirar-get');
    Route::post('/forcing/{forcing}/retirar', [ForcingController::class, 'retirar'])->name('forcing.retirar');
    
    // Rotas para páginas mobile (iOS/Android)
    Route::get('/forcing/{forcing}/liberar-page', [ForcingController::class, 'showLiberarPage'])->name('forcing.liberar-page');
    Route::get('/forcing/{forcing}/executar-page', [ForcingController::class, 'showExecutarPage'])->name('forcing.executar-page');
    Route::get('/forcing/{forcing}/solicitar-retirada-page', [ForcingController::class, 'showSolicitarRetiradaPage'])->name('forcing.solicitar-retirada-page');
    Route::get('/forcing/{forcing}/retirar-page', [ForcingController::class, 'showRetirarPage'])->name('forcing.retirar-page');
    Route::get('/forcing/from-email/{forcing}', [ForcingController::class, 'fromEmail'])->name('forcing.from-email');
    
    // Rotas de usuários (apenas admin)
    Route::resource('users', UserController::class)->middleware('check.profile:admin');
    
    // Rotas de alterações de lógica
    Route::resource('logic-changes', \App\Http\Controllers\LogicChangeController::class);
    Route::delete('/logic-changes/{logicChange}/remove-file', [\App\Http\Controllers\LogicChangeController::class, 'removeFile'])->name('logic-changes.remove-file');
    
    // Rotas de aprovação
    Route::patch('/logic-changes/{logicChange}/approve-manager', [\App\Http\Controllers\LogicChangeController::class, 'approveAsManager'])->name('logic-changes.approve-manager');
    Route::patch('/logic-changes/{logicChange}/approve-coordinator', [\App\Http\Controllers\LogicChangeController::class, 'approveAsCoordinator'])->name('logic-changes.approve-coordinator');
    Route::patch('/logic-changes/{logicChange}/approve-specialist', [\App\Http\Controllers\LogicChangeController::class, 'approveAsSpecialist'])->name('logic-changes.approve-specialist');
    
    // Páginas de aprovação para mobile
    Route::get('/logic-changes/{logicChange}/approve-manager-page', [\App\Http\Controllers\LogicChangeController::class, 'approveAsManagerPage'])->name('logic-changes.approve-manager-page');
    Route::get('/logic-changes/{logicChange}/approve-coordinator-page', [\App\Http\Controllers\LogicChangeController::class, 'approveAsCoordinatorPage'])->name('logic-changes.approve-coordinator-page');
    Route::get('/logic-changes/{logicChange}/approve-specialist-page', [\App\Http\Controllers\LogicChangeController::class, 'approveAsSpecialistPage'])->name('logic-changes.approve-specialist-page');
    
    // Rota para marcar como implementado
    Route::patch('/logic-changes/{logicChange}/mark-implemented', [\App\Http\Controllers\LogicChangeController::class, 'markAsImplemented'])->name('logic-changes.mark-implemented');
    Route::get('/logic-changes/{logicChange}/mark-implemented-page', [\App\Http\Controllers\LogicChangeController::class, 'markAsImplementedPage'])->name('logic-changes.mark-implemented-page');
    
    // Perfil do usuário atual
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

// Rotas de Super Admin
Route::middleware(['auth', 'super.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('units', \App\Http\Controllers\Admin\UnitController::class);
    Route::get('units/{unit}/users', [\App\Http\Controllers\Admin\UnitController::class, 'users'])->name('units.users');
    Route::get('units/{unit}/forcings', [\App\Http\Controllers\Admin\UnitController::class, 'forcings'])->name('units.forcings');
    
    // Estatísticas de emails
    Route::get('email-stats', [\App\Http\Controllers\EmailStatsController::class, 'index'])->name('email-stats');
    Route::get('email-stats/api', [\App\Http\Controllers\EmailStatsController::class, 'api'])->name('email-stats.api');
    Route::post('email-stats/reset', [\App\Http\Controllers\EmailStatsController::class, 'reset'])->name('email-stats.reset');
});
