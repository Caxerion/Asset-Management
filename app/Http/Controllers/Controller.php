<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Check if user is admin
     */
    protected function isAdmin()
    {
        return auth()->user()->role === 'admin';
    }

    /**
     * Check if user is PIC
     */
    protected function isPIC()
    {
        return auth()->user()->role === 'pic';
    }

    /**
     * Check if user is staff
     */
    protected function isStaff()
    {
        return auth()->user()->role === 'staff';
    }
}