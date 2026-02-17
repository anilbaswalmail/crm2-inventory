<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Site;

class SitesController extends Controller
{
    public function index(): void
    {
        $siteModel = new Site();

        $this->render('sites/index', [
            'pageTitle' => 'Sites Inventory',
            'categories' => $siteModel->distinctValues('category'),
            'countries' => $siteModel->distinctValues('country'),
        ]);
    }
}
