<?php

namespace App\Services;

use App\Contracts\AiProviderInterface;
use App\Models\Event;
use App\Models\Graduate;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SemanticSearchService
{
    public function __construct(private AiProviderInterface $aiProvider) {}

    public function search(string $query, int $limit = 10): Collection
    {
        $candidates = $this->publishedCandidates();
        if ($candidates->isEmpty()) {
            return collect();
        }

        $raw = $this->aiProvider->generate($this->buildPrompt($query, $candidates, $limit));

        return $this->parseMatches($raw)
            ->map(fn($m) => $this->hydrate($m, $candidates))
            ->filter()
            ->values();
    }

    private function publishedCandidates(): Collection
    {
        $events = Event::where('status', 'published')->get(['id', 'title', 'description'])
            ->map(fn($e) => [
                'type' => 'event',
                'id' => $e->id,
                'title' => $e->title,
                'excerpt' => Str::limit(strip_tags((string) $e->description), 200),
                'url' => route('public.event.detail', ['id' => $e->id]),
            ]);

        $graduates = Graduate::where('publish_status', 'published')->where('consent_status', 'granted')
            ->get(['id', 'name', 'public_slug', 'student_reference', 'profile_text', 'quote'])
            ->filter(fn($g) => filled($g->public_slug))
            ->map(fn($g) => [
                'type' => 'graduate',
                'id' => $g->id,
                'title' => $g->name,
                'excerpt' => Str::limit(strip_tags((string) ($g->profile_text ?: $g->quote)), 200),
                'url' => route('public.graduate.detail', ['public_slug' => $g->public_slug]),
            ]);

        $candidateLimit = max(50, (int) config('yearbook.semantic_search_candidate_limit', 500));

        return $events->concat($graduates)
            ->sortByDesc(fn($item) => $item['type'] === 'graduate' ? 1 : 0)
            ->take($candidateLimit)
            ->values();
    }

    private function buildPrompt(string $query, Collection $candidates, int $limit): string
    {
        $catalog = $candidates->map(fn($i, $idx) => "{$idx}. [{$i['type']}] {$i['title']} — {$i['excerpt']}")->implode("\n");

        return <<<PROMPT
        You are the search assistant for a university yearbook. Visitor query: "{$query}"

        Pick the best-matching items from the catalog below, ranked most to least relevant,
        at most {$limit}. Respond ONLY with JSON, no markdown fences:
        [{"index": <number>, "reason": "<one short sentence>"}]
        If nothing matches, respond with [].

        Catalog:
        {$catalog}
        PROMPT;
    }

    private function parseMatches(string $raw): Collection
    {
        $decoded = json_decode(trim(preg_replace('/```json|```/i', '', $raw)), true);
        return is_array($decoded)
            ? collect($decoded)->filter(fn($r) => is_array($r) && isset($r['index']))->values()
            : collect();
    }

    private function hydrate(array $match, Collection $candidates): ?array
    {
        $c = $candidates->get((int) $match['index']);
        return $c ? [...$c, 'reason' => $match['reason'] ?? null] : null;
    }
}
