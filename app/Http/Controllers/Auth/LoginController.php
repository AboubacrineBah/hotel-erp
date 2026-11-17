<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirection par défaut (sera surchargée par authenticated()).
     */
    protected $redirectTo = '/erp/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Après authentification réussie : on envoie vers le bon espace
     * selon les permissions de l'utilisateur.
     */
    protected function authenticated(Request $request, $user)
    {
        // Compte suspendu
        if (! $user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Votre compte a été suspendu par l\'administrateur.']);
        }

        // Staff ERP
        if ($user->can('access_erp')) {
            return redirect()->intended(route('erp.dashboard'));
        }

        // Client VIP / public connecté
        return redirect()->intended(route('client.bookings'));
    }
}
