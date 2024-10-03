<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * @see https://github.com/pinkary-project/pinkary.com/blob/main/app/Rules/UnauthorizedEmailProviders.php
 */
final readonly class UnauthorizedEmailProviders implements ValidationRule {
    /** @var string[] */
    private readonly array $unauthorized_email_providers;

    /**
     * Create a new rule instance.
     *
     * @param  array<int, string>  $unauthorized_email_providers
     */
    public function __construct(
        /** @see https://github.com/disposable-email-domains/disposable-email-domains/blob/master/disposable_email_blocklist.conf */
        ?array $unauthorized_email_providers = null
    ) {
        $this->unauthorized_email_providers = $unauthorized_email_providers ?? config('unauthorized_email_providers');
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if (! is_string($value)) {
            return;
        }

        if (mb_strpos($value, '@') !== false) {
            [$email_account, $email_provider] = explode('@', $value);

            if (in_array($email_provider, $this->unauthorized_email_providers, true)) {
                $fail('The :attribute belongs to an unauthorized email provider.')->translate();

                return;
            }
        } else {
            $fail('The :attribute doesn\'t have an @.')->translate();

            return;
        }
    }
}
