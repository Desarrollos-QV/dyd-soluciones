<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubaccountController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UnidadesController;
use App\Http\Controllers\AssignementsController;
use App\Http\Controllers\CollectionsController;

use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\EjecucionInstalacionController;
use App\Http\Controllers\EntregaServicioController;
use App\Http\Controllers\OtroServicioController;
use App\Http\Controllers\SolicitudInstalacionController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\ReporteIngresoEgresoController;
use App\Http\Controllers\ServicioAgendadoController;
use App\Http\Controllers\ProspectsController;
use App\Http\Controllers\SellersController;
use App\Http\Controllers\DevicesController;

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
Auth::routes();

/*|--------------------------------------------------------------------------
| User Views LOGGED and ADMIN
|--------------------------------------------------------------------------
*/
// Route::middleware(['auth', 'can:isAdmin'])->prefix('admin')->name('admin.')->group(function () {

Route::group(['middleware' => 'isAdmin'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    /**
     * Gestor de cobranza
     */
    Route::resource('collections', CollectionsController::class);
    Route::get('sendTestNotify/{type}', [CollectionsController::class, 'sendTestNoify']);

    /**
     * Prospectos
     */
    Route::resource('prospects', ProspectsController::class);

    /**
     * Vendedores
     */
    Route::resource('sellers', SellersController::class);

    /**
     * * Subaccounts Routes
     */
    Route::resource('subaccounts', SubaccountController::class)->except(['show']);

    /**
     * * Clientes Routes
     */
    Route::resource('clientes', ClienteController::class);

    /**
     * Dispositivos y materiales
     */
    Route::resource('devices', DevicesController::class);


    /**
     * * Unidades Routes
     */
    Route::resource('unidades', UnidadesController::class);

    /**
     * Asignaciones
     */
    Route::resource('assignements', AssignementsController::class);

    /**
     * Tecnicos
     */
    Route::resource('tecnicos', TecnicoController::class);
    
    /**
     * Solicitudes
     */
    Route::resource('solicitudes', SolicitudInstalacionController::class);
    Route::post('solicitudes/{id}/aceptar', [SolicitudInstalacionController::class, 'aceptar'])->name('solicitudes.aceptar');
    
    /**
     * Ejecuciones
     */
    Route::resource('ejecuciones', EjecucionInstalacionController::class);
    
    /**
     * Entregas
     */
    Route::resource('entregas', EntregaServicioController::class);
    
    /**
     * Otros Servicios
     */
    Route::resource('otros-servicios', OtroServicioController::class);
    
    /**
     * Inventarios
     */
    Route::resource('inventarios', InventarioController::class);
    
    /**
     * Ingresos/Gastos
     * Reportes
     */
    Route::resource('gastos', GastoController::class);
    Route::get('reportes', [ReporteIngresoEgresoController::class, 'index'])->name('reportes.index');
    Route::get('reportes/exportar-excel', [ReporteIngresoEgresoController::class, 'exportarExcel'])->name('reportes.exportarExcel');

    /**
     * Servicios Agendados
     * Reportes
     */
    Route::resource('servicios_agendados', ServicioAgendadoController::class);
    Route::post('delete-file', [ServicioAgendadoController::class, 'deleteFile'])->name('servicios_agendados.delete-file');
    Route::get('servicios_agendados/generarPDF/{id}', [ServicioAgendadoController::class, 'generarPDF'])->name('servicios_agendados.generarPDF');
});

/**
 * Servicios Agendados
 * Ligas Externas que no requieren autenticación
 */
Route::get('servicios_agendados/generarPDF/{id}', [ServicioAgendadoController::class, 'generarPDF'])->name('servicios_agendados.generarPDF');
Route::get('firmar/{id}', [ServicioAgendadoController::class, 'firmar'])->name('servicios_agendados.firmar');
Route::post('/firmar-conformidad', [ServicioAgendadoController::class, 'guardar'])->name('firmar.conformidad.guardar');

