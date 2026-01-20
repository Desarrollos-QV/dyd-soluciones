<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubaccountController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UnidadesController;
use App\Http\Controllers\AssignementsController;
use App\Http\Controllers\CollectionsController;
use App\Http\Controllers\SettingsController;

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
use App\Http\Controllers\HistorialCajaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SimControlController;


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
     * Prospectos
     */
    Route::resource('prospects', ProspectsController::class);
    Route::get('prospects/status/{status}', [ProspectsController::class, 'ChangeStatus'])->name('prospects.status');
    Route::get('prospects/assign/{id}/{seller}', [ProspectsController::class, 'AssignSeller'])->name('prospects.assign');
    Route::post('prospects/deleteSelected', [ProspectsController::class, 'deleteSelected'])->name('prospects.bulkDelete');
    /**
     * Vendedores
     */
    Route::resource('sellers', SellersController::class);
    Route::post('sellers/deleteSelected', [SellersController::class, 'deleteSelected'])->name('sellers.bulkDelete');

    /**
     * * Subaccounts Routes
     */
    Route::resource('subaccounts', SubaccountController::class)->except(['show']);

    /**
     * * Clientes Routes
     */
    Route::resource('clientes', ClienteController::class);
    Route::get('clientes/downloadDocs/{cliente}', [ClienteController::class, 'downloadDocs'])->name('clientes.downloadDocs');
    Route::post('clientes/deleteSelected', [ClienteController::class, 'deleteSelected'])->name('clientes.bulkDelete');
    Route::get('clientes/{cliente}/unidades', [ClienteController::class, 'getUnidades'])->name('clientes.unidades');

    /**
     * Dispositivos y materiales
     */
    Route::resource('devices', DevicesController::class);
    Route::post('devices/deleteSelected', [DevicesController::class, 'deleteSelected'])->name('devices.bulkDelete');


    /**
     * * Unidades Routes
     */
    Route::resource('unidades', UnidadesController::class);
    Route::post('unidades/uploads', [UnidadesController::class, 'uploads'])->name('unidades.uploads');
    Route::get('unidades/fetchUploads/{unidad}', [UnidadesController::class, 'fetchUploads'])->name('unidades.fetchUploads');
    Route::get('unidades/deleteUploads/{unidad}', [UnidadesController::class, 'deleteUploads'])->name('unidades.deleteUploads');

    Route::get('unidades/assign/{id}/{client}', [UnidadesController::class, 'AssignClient'])->name('unidades.assign');
    Route::get('unidades/assigndisp/{id}/{disp}', [UnidadesController::class, 'AssignDisp'])->name('unidades.assignDisp');
    Route::get('unidades/assignDevice/{id}/{device}', [UnidadesController::class, 'assignDevice'])->name('unidades.assignDevice');
    Route::get('unidades/assignSIM/{id}/{sim}', [UnidadesController::class, 'assignSIM'])->name('unidades.assignSIM');
    Route::post('unidades/deleteSelected', [UnidadesController::class, 'deleteSelected'])->name('unidades.bulkDelete');

    /**
     * Asignaciones
     */
    Route::get('assignements/inprogress', [AssignementsController::class, 'AssignsInProgress'])->name('assignements.inprogress');
    Route::get('assignements/performed', [AssignementsController::class, 'AssignsPerformed'])->name('assignements.performed');
    Route::get('assignements/assign/{id}/{tecnico}', [AssignementsController::class, 'AssignTecn'])->name('assignements.assign');
    Route::resource('assignements', AssignementsController::class);
    Route::post('assignements/deleteSelected', [AssignementsController::class, 'deleteSelected'])->name('assignements.bulkDelete');

    /**
     * Tecnicos
     */
    Route::resource('tecnicos', TecnicoController::class);
    Route::post('tecnicos/deleteSelected', [TecnicoController::class, 'deleteSelected'])->name('tecnicos.bulkDelete');

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
     * Control de SIM
     */
    Route::resource('simcontrol', SimControlController::class);
    Route::post('simcontrol/deleteSelected', [SimControlController::class, 'deleteSelected'])->name('simcontrol.bulkDelete');

    /**
     * Ingresos/Gastos
     * Reportes
     */
    Route::resource('gastos', GastoController::class);
    Route::post('gastos/deleteSelected', [GastoController::class, 'deleteSelected'])->name('gastos.bulkDelete');
    Route::get('reportes', [ReporteIngresoEgresoController::class, 'index'])->name('reportes.index');
    Route::get('reportes/exportar-excel', [ReporteIngresoEgresoController::class, 'exportarExcel'])->name('reportes.exportarExcel');

    Route::get('reportes/units', [ReporteIngresoEgresoController::class, 'ReportUnits'])->name('reportes.units');
    Route::get('reportes/exportar-clients', [ReporteIngresoEgresoController::class, 'exportarExcelClients'])->name('reportes.exportarExcelClients');


    /**
     * Gestor de cobranza
     */
    Route::get('collections/inprogress', [CollectionsController::class, 'inprogress'])->name('collections.inprogress');
    Route::get('collections/completed', [CollectionsController::class, 'completed'])->name('collections.completed');
    Route::post('collections/deleteSelected', [CollectionsController::class, 'deleteSelected'])->name('collections.bulkDelete');
    Route::get('collections/getCollecionAll', [CollectionsController::class, 'getCollecionAll'])->name('collections.getCollecionAll');
    Route::resource('collections', CollectionsController::class);
    Route::get('collections/{collection}', [CollectionsController::class, 'show'])->name('collections.show');
    Route::post('collections/{collection}/pay', [CollectionsController::class, 'pagar'])->name('collections.pay');
    Route::post('collections/{collection}/notify', [CollectionsController::class, 'notify'])->name('collections.notify');

    /**
     * Configuraciones
     */
    Route::get('sendTestNotify/{type}', [SettingsController::class, 'sendTestNoify'])->name('sendTestNoify');
    Route::resource('settings', SettingsController::class);

    /**
     * Servicios Agendados
     * Reportes
     */
    Route::get('servicios_agendados/inprogress', [ServicioAgendadoController::class, 'AssignsInProgress'])->name('servicios_agendados.inprogress');
    Route::get('servicios_agendados/performed', [ServicioAgendadoController::class, 'AssignsPerformed'])->name('servicios_agendados.performed');
    Route::resource('servicios_agendados', ServicioAgendadoController::class);
    Route::post('delete-file', [ServicioAgendadoController::class, 'deleteFile'])->name('servicios_agendados.delete-file');
    Route::get('servicios_agendados/generarPDF/{id}', [ServicioAgendadoController::class, 'generarPDF'])->name('servicios_agendados.generarPDF');


    /**
     * Gestion de Historial de Caja
     */
    Route::get('historial-caja/exportar-excel', [HistorialCajaController::class, 'exportarExcel'])->name('historial-caja.exportarExcel');
    Route::resource('historial-caja', HistorialCajaController::class)->names('historial-caja');
    Route::get('historial-caja/getElement/{id}', [HistorialCajaController::class, 'getElement'])->name('historial-caja.getElement');

});

/**
 * Notificaciones
 */
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');

/**
 * Servicios Agendados
 * Ligas Externas que no requieren autenticación
 */

Route::get('servicios_agendados/generarPDF/{id}', [ServicioAgendadoController::class, 'generarPDF'])->name('servicios_agendados.generarPDF');
Route::get('servicios_agendados/seePhotoRecord/{id}', [ServicioAgendadoController::class, 'seePhotoRecord'])->name('servicios_agendados.seePhotoRecord');
Route::get('firmar/{id}', [ServicioAgendadoController::class, 'firmar'])->name('servicios_agendados.firmar');
Route::post('/firmar-conformidad', [ServicioAgendadoController::class, 'guardar'])->name('firmar.conformidad.guardar');
