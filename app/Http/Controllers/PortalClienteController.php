<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortalClienteController extends Controller
{
    /**
     * Render the Client Mobile App (Android / iOS / Web).
     */
    public function index()
    {
        return view('portal.app');
    }
}
