<?php

namespace App\Http\Controllers;

use App\Exceptions\AiServiceTimeoutException;
use App\Services\SemanticSearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(): View
    {
        return view('search.index', ['query' => null, 'results' => collect(), 'error' => null]);
    }

    public function search(Request $request, SemanticSearchService $search): View
    {
        $data = $request->validate(['query' => 'required|string|max:255']);
        $results = collect();
        $error = null;

        try {
            $results = $search->search($data['query']);
        } catch (AiServiceTimeoutException) {
            $error = 'The search assistant took too long to respond. Please try again.';
        } catch (\Throwable $e) {
            report($e);
            $error = 'Search is temporarily unavailable. Please try again shortly.';
        }

        return view('search.index', ['query' => $data['query'], 'results' => $results, 'error' => $error]);
    }
}
