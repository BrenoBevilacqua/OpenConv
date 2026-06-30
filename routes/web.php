<?php

use App\Http\Controllers\ConvenioController;
use App\Http\Controllers\AcompanhamentoController;
use App\Http\Controllers\AcoesController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\TermoController;
use App\Http\Controllers\MedicaoController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

// Redireciona '/' para a tela de login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas de autenticação
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:4,3')->name('convenio.authenticate');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');


// Rotas protegidas por autenticação
Route::middleware('auth', 'logged_out')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/index', [ConvenioController::class, 'index'])->name('convenio.index');
    Route::get('/convenios/info', [Controller::class, 'historico'])->name('convenios.info');
    Route::get('/convenios/{id}/exportar-pdf', [ConvenioController::class, 'exportarPdf'])->name('convenios.exportar.pdf');

    // Acesso somente para admin e admin master
    Route::middleware('admin')->group(function () {
        // rotas de convênios (acessar, criar, editar, excluir)
        Route::get('/create', [ConvenioController::class, 'create'])->name('convenio.create');
        Route::post('/', [ConvenioController::class, 'store'])->name('convenio.store');
        Route::get('/convenios/{id}/edit', [ConvenioController::class, 'edit'])->name('convenio.edit');
        Route::put('/convenios/{id}', [ConvenioController::class, 'update'])->name('convenio.update');
        Route::delete('/convenios/{id}', [ConvenioController::class, 'destroy'])->name('convenio.destroy');

        // rotas de ações
        Route::post('/convenios/{convenio}/acoes', [AcoesController::class, 'storeAcao'])->name('convenios.acoes.store');
        Route::delete('/convenios/{convenio}/acoes/{acao?}', [AcoesController::class, 'destroy'])->name('convenios.acoes.destroy');

        // rotas de medicoes
        Route::get('/convenios/{convenio}/medicoes', [MedicaoController::class, 'getMedicoes'])->name('convenios.medicoes.index');
        Route::post('/convenios/{convenio}/medicoes', [MedicaoController::class, 'storeMedicao'])->name('convenios.medicoes.store');
        Route::delete('/convenios/{convenio}/medicoes/{medicao?}', [MedicaoController::class, 'destroy'])->name('convenios.medicoes.destroy');

        // rotas de termos
        Route::get('/convenios/{convenio}/termos', [TermoController::class, 'getTermos'])->name('convenios.termos.index');
        Route::post('/convenios/{convenio}/termos', [TermoController::class, 'storeTermo'])->name('convenios.termos.store');
        Route::delete('/convenios/{convenio}/termos/{termo?}', [TermoController::class, 'destroy'])->name('convenios.termos.destroy');

        // rota de acompanhamentos
        Route::post('/convenios/{convenio}/acompanhamentos', [AcompanhamentoController::class, 'storeAcompanhamento'])->name('convenios.acompanhamentos.store');
        Route::get('/convenios/{convenio}/acompanhamento', [AcompanhamentoController::class, 'getAcompanhamento'])->name('convenios.acompanhamento.get');

        // rotas contratos
        Route::post('/convenios/{convenio}/contratos', [ContratoController::class, 'storeContrato'])->name('convenios.contratos.store');
        Route::get('/convenios/{convenio}/contratos', [ContratoController::class, 'getContratos'])->name('convenios.contratos.index');
        Route::delete('/convenios/{convenio}/contratos/{contrato}', [ContratoController::class, 'destroyContrato'])->name('convenios.contratos.destroy');
    });

    // Acesso exclusivo para admin master
    Route::middleware('admin_master')->group(function () {
        Route::get('/admin/requisicoes', [AuthController::class, 'requisicoesPendentes'])->name('admin.requisicoes');
        Route::post('/admin/aprovar/{id}', [AuthController::class, 'approveAdmin'])->name('admin.aprovar');
        Route::get('/admin/dadosAuxiliares', [DropdownController::class, 'dadosAuxiliares'])->name('admin.dadosAuxiliares');
        Route::post('/admin/dadosAuxiliares', [DropdownController::class, 'dadosStore'])->name('admin.dadosStore');
        Route::delete('/admin/dadosAuxiliares/{id}', [DropdownController::class, 'dadosDestroy'])->name('admin.dadosDestroy');
    });
});
