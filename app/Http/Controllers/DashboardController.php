<?php

namespace App\Http\Controllers;

use App\Services\DashboardAnalyticsService;
use App\Services\AiAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected DashboardAnalyticsService $analyticsService;
    protected AiAnalysisService $aiService;

    public function __construct(
        DashboardAnalyticsService $analyticsService,
        AiAnalysisService $aiService
    ) {
        $this->analyticsService = $analyticsService;
        $this->aiService = $aiService;
    }

    /**
     * Display the Executive Analytics & AI Dashboard.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['periodo', 'fecha_desde', 'fecha_hasta', 'oficina']);
        $analytics = $this->analyticsService->getAnalytics($filters);
        $aiReport = $this->aiService->generateExecutiveReport($analytics);

        return view('dashboard', compact('analytics', 'aiReport'));
    }

    /**
     * AJAX endpoint to update analytics data dynamically with filters.
     */
    public function filterData(Request $request): JsonResponse
    {
        $filters = $request->only(['periodo', 'fecha_desde', 'fecha_hasta', 'oficina']);
        $analytics = $this->analyticsService->getAnalytics($filters);
        $aiReport = $this->aiService->generateExecutiveReport($analytics);

        return response()->json([
            'success' => true,
            'analytics' => $analytics,
            'ai_report' => $aiReport,
        ]);
    }

    /**
     * AJAX endpoint to ask the AI assistant questions about analytics.
     */
    public function askAi(Request $request): JsonResponse
    {
        $request->validate([
            'question' => 'required|string|max:500',
        ]);

        $filters = $request->only(['periodo', 'fecha_desde', 'fecha_hasta', 'oficina']);
        $analytics = $this->analyticsService->getAnalytics($filters);
        $response = $this->aiService->answerQuestion($request->input('question'), $analytics);
        return response()->json($response);
    }

    /**
     * Export complete analytical dashboard to Excel.
     */
    public function export(Request $request)
    {
        $filters = $request->only(['periodo', 'fecha_desde', 'fecha_hasta', 'oficina']);
        $analytics = $this->analyticsService->getAnalytics($filters);
        $aiReport = $this->aiService->generateExecutiveReport($analytics);

        $periodName = $filters['periodo'] ?? 'todo';
        $fileName = 'reporte_ejecutivo_ia_' . $periodName . '_' . now()->format('Ymd_His') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\DashboardExport($analytics, $aiReport), $fileName);
    }
}
