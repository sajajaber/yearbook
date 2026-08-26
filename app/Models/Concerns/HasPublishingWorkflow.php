<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait HasPublishingWorkflow
{
    protected string $statusColumn = 'status';

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where($this->statusColumn, 'draft');
    }

    public function scopeReviewed(Builder $query): Builder
    {
        return $query->where($this->statusColumn, 'reviewed');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where($this->statusColumn, 'approved');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where($this->statusColumn, 'published');
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->where($this->statusColumn, 'archived');
    }

    public function submitForReview(): bool
    {
        return $this->transitionTo('reviewed');
    }

    public function approve(): bool
    {
        return $this->transitionTo('approved');
    }

    public function reject(): bool
    {
        return $this->transitionTo('draft');
    }

    public function publish(): bool
    {
        if (! $this->guardPublish()) {
            return false;
        }

        return $this->transitionTo('published');
    }

    public function archive(): bool
    {
        return $this->transitionTo('archived');
    }

    protected function transitionTo(string $status): bool
    {
        /** @var \Illuminate\Database\Eloquent\Model $this */
        return $this->update([$this->statusColumn => $status]);
    }

    protected function guardPublish(): bool
    {
        return true;
    }

    public function isPublished(): bool
    {
        return $this->{$this->statusColumn} === 'published';
    }
}
