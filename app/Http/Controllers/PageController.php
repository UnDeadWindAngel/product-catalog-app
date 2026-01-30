<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PageController extends Controller
{
    public function home()
    {
        return Inertia::render('Home');
    }

    public function productShow($id)
    {
        return Inertia::render('Product/Show', [
            'id' => $id
        ]);
    }

    public function login()
    {
        return Inertia::render('Auth/Login');
    }

    public function adminProducts()
    {
        return Inertia::render('Admin/Products/Index');
    }

    public function adminProductCreate()
    {
        return Inertia::render('Admin/Products/Create');
    }

    public function adminProductEdit($id)
    {
        return Inertia::render('Admin/Products/Edit', [
            'id' => $id
        ]);
    }
}
