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
        $events = Event::where('status', 'published')
            ->get(['id', 'title', 'description'])
            ->map(fn($e) => [
                'type' => 'event',
                'id' => $e->id,
                'title' => $e->title,
                'excerpt' => Str::limit(strip_tags((string) $e->description), 200),
                'url' => route('public.event.detail', ['id' => $e->id]),
            ]);

        $graduates = Graduate::where('publish_status', 'published')
            ->where('consent_status', 'granted')
            ->with(['major', 'school', 'campus', 'academicYear'])
            ->get([
                'id',
                'name',
                'student_reference',
                'degree_level',
                'gpa',
                'school_id',
                'major_id',
                'campus_id',
                'academic_year_id',
                'profile_text',
                'achievements',
                'activities',
                'projects',
                'professional_interests',
                'quote',
            ])
            ->filter(fn($g) => filled($g->student_reference))
            ->map(fn($g) => [
                'type' => 'graduate',
                'id' => $g->id,
                'title' => $g->name,
                'excerpt' => Str::limit(strip_tags((string) ($g->profile_text ?: $g->quote)), 200),
                'metadata' => [
                    'degree_level' => $g->degree_level ?: null,
                    'gpa' => $g->gpa !== null ? number_format((float) $g->gpa, 2) : null,
                    'school' => $g->school?->name,
                    'major' => $g->major?->name,
                    'campus' => $g->campus?->name,
                    'academic_year' => $g->academicYear?->title,
                    'achievements' => $this->searchText($g->achievements),
                    'activities' => $this->searchText($g->activities),
                    'projects' => $this->searchText($g->projects),
                    'professional_interests' => $this->searchText($g->professional_interests),
                    'quote' => $g->quote,
                ],
                'url' => route('public.graduate.detail', ['student_reference' => $g->student_reference]),
            ]);

        $candidateLimit = max(50, (int) config('yearbook.semantic_search_candidate_limit', 500));

        return $events->concat($graduates)
            ->take($candidateLimit)
            ->values();
    }

    private function buildPrompt(string $query, Collection $candidates, int $limit): string
    {
        $catalog = $candidates
            ->map(function ($item, $idx) {
                if ($item['type'] === 'event') {
                    return "{$idx}. [event] {$item['title']} — {$item['excerpt']}";
                }

                $metadata = collect($item['metadata'] ?? [])
                    ->map(function ($value, $key) {
                        $value = is_array($value) ? implode(', ', $value) : $value;

                        return filled($value) ? "{$key}: {$value}" : null;
                    })
                    ->filter()
                    ->implode('; ');

                return "{$idx}. [graduate] {$item['title']} — {$item['excerpt']}"
                    . ($metadata !== '' ? " — {$metadata}" : '');
            })
            ->implode("\n");

        return 'You are the search assistant for a university yearbook. Visitor query: "' . $query . '"' . "\n\n"
            . 'Pick the best-matching items from the catalog below, ranked most to least relevant, at most ' . $limit . ". Use the structured graduate metadata when it is relevant to the query. In particular, use the actual GPA values when the visitor asks about academic performance, high GPA, low GPA, GPA ranges, top students, honors, or similar topics. Never invent a GPA, school, major, campus, academic year, or achievement that is not present in the catalog.\n\n"
            . 'Respond ONLY with JSON, no markdown fences:' . "\n"
            . '[{"index": <number>, "reason": "<one short sentence>"}]' . "\n\n"
            . 'If nothing matches, respond with [].' . "\n\n"
            . 'Catalog:' . "\n"
            . $catalog;
    }

    private function searchText(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value)) {
            return collect($value)
                ->flatten()
                ->filter(fn($item) => filled($item))
                ->map(fn($item) => trim((string) $item))
                ->filter()
                ->implode(', ');
        }

        return trim(strip_tags((string) $value)) ?: null;
    }

    private function parseMatches(string $raw): Collection
    {
        $decoded = json_decode(trim(preg_replace('/```json|```/i', '', $raw)), true);

        return is_array($decoded)
            ? collect($decoded)
                ->filter(fn($r) => is_array($r) && isset($r['index']))
                ->values()
            : collect();
    }

    private function hydrate(array $match, Collection $candidates): ?array
    {
        $c = $candidates->get((int) $match['index']);

        return $c ? [...$c, 'reason' => $match['reason'] ?? null] : null;
    }
}
