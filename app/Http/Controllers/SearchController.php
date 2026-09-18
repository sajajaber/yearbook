<?php

namespace App\Http\Controllers;

use App\Exceptions\AiServiceTimeoutException;
use App\Models\Event;
use App\Models\Graduate;
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
            $error = 'The search assistant took too long to respond. Showing keyword matches instead.';
            $results = $this->keywordFallback($data['query']);
        } catch (\Throwable $e) {
            report($e);
            $error = 'Semantic search is temporarily unavailable. Showing keyword matches instead.';
            $results = $this->keywordFallback($data['query']);
        }

        if ($results->isEmpty()) {
            $results = $this->keywordFallback($data['query']);
        }

        return view('search.index', ['query' => $data['query'], 'results' => $results, 'error' => $error]);
    }

    private function keywordFallback(string $query)
    {
        $events = Event::where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('location', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'title', 'description']);

        $graduates = Graduate::where('publish_status', 'published')
            ->where('consent_status', 'granted')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('profile_text', 'like', "%{$query}%")
                    ->orWhere('quote', 'like', "%{$query}%");
            })
            ->whereNotNull('student_reference')
            ->limit(10)
            ->get(['id', 'name', 'student_reference', 'profile_text', 'quote']);

        return $events->map(fn ($event) => [
            'type' => 'event',
            'id' => $event->id,
            'title' => $event->title,
            'excerpt' => \Illuminate\Support\Str::limit(strip_tags((string) $event->description), 200),
            'url' => route('public.event.detail', ['id' => $event->id]),
            'reason' => 'Keyword match',
        ])->concat(
            $graduates->map(fn ($graduate) => [
                'type' => 'graduate',
                'id' => $graduate->id,
                'title' => $graduate->name,
                'excerpt' => \Illuminate\Support\Str::limit(strip_tags((string) ($graduate->profile_text ?: $graduate->quote)), 200),
                'url' => route('public.graduate.detail', ['student_reference' => $graduate->student_reference]),
                'reason' => 'Keyword match',
            ])
        )->values();
    }
}
