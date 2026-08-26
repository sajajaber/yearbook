<?php

namespace App\Rules;

use App\Models\Graduate;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ConsentGrantedForPublish implements ValidationRule
{
    protected ?string $consentStatus;

    public function __construct(?string $consentStatus)
    {
        $this->consentStatus = $consentStatus;
    }

    /**
     * Ensures a graduate cannot be marked 'published' unless
     * consent has been granted. Delegates the actual business
     * rule to Graduate::consentAllowsPublishing() so this check
     * stays in sync with Graduate::canBePublished() used elsewhere
     * (e.g. GraduateController::publish()).
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === 'published' && !Graduate::consentAllowsPublishing($this->consentStatus)) {
            $fail('A graduate cannot be published without granted consent.');
        }
    }
}
