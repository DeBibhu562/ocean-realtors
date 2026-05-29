<?php

namespace App\Http\Controllers;

use App\Repositories\PropertyRepository;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __construct(
        protected PropertyRepository $propertyRepository
    ) {}

    public function welcome(): Response
    {
        return Inertia::render('Welcome', [
            'featuredProperties' => $this->propertyRepository
                ->featuredForHomepage(null, 6)
                ->values()
                ->all(),
        ]);
    }
}
