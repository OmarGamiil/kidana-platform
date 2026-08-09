<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Services\OfficeDashboardService;
use App\Support\CurrentOfficeContextResolver;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly CurrentOfficeContextResolver $contextResolver,
        private readonly OfficeDashboardService $dashboard,
    ) {}

    public function index(Request $request): Response
    {
        $context = $this->contextResolver->resolve($request->user());

        return Inertia::render('office/Dashboard', $this->dashboard->build($context)->toArray());
    }
}
