<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\RestaurantForm;

class FormController extends Controller
{
    public function index()
    {
        $forms = RestaurantForm::get();
        return Inertia::render('Admin/Form/Index', ['forms' => $forms]);
    }
}
