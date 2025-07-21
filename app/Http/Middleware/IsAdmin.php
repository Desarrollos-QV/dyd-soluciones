<?php

namespace App\Http\Middleware;
use Illuminate\Contracts\Auth\Guard;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{

    /**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

    /**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if ($this->auth->guest()) { // Si es invitado y/o no esta logeado Requerimos acceso
			return redirect()->guest('login')->with(['login_required' => "Inicia sesión..."]);
		} else if ($this->auth->user()->role == 'admin' || $this->auth->user()->role == 'subadmin' || $this->auth->user()->role == 'tecnico') { // Si el Rol es admin y/o Subadmin damos acceso
            if ($request->route()->getName() != 'dashboard' && // Si la ruta es diferente del Dashboard
                $request->route()->getName() != 'home' && // Si la ruta es diferente del Dashboard
                !$this->auth->user()->hasPermission($request->route()->getName()) && // Si el permiso no esta autorizado
                $request->isMethod('get')) { // Validamos si tiene permiso para ver la seccion inicial
                return response()->view('admin.unauthorized'); // Denegamos el acceso
            }else { // Brindamos acceso
                return $next($request);
            }
        }


        // if (auth()->check() && auth()->user()->isAdmin()) {
        //     return $next($request);
        // }
     
        // abort(403, 'No autorizado.');
    }
}
