<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Profile;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * @see https://github.com/pinkary-project/pinkary.com/blob/main/app/Rules/Username.php
 */
final readonly class Username implements ValidationRule {
    /** @var string[] */
    private readonly array $reserved;

    /**
     * Create a new rule instance.
     *
     * @param  array<int, string>  $reserved
     */
    public function __construct(
        private ?Profile $profile = null,
        ?array $reserved = null
    ) {
        $this->reserved = $reserved ?? config('reserved_usernames');
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

        if (\Safe\preg_match('/[A-Za-z].*[A-Za-z]/', $value) === 0) {
            $fail('The :attribute must contain at least 2 letters.')->translate();

            return;
        }
        if (\Safe\preg_match('/^\w+$/', $value) === 0) {
            $fail('The :attribute may only contain letters, numbers, and underscores.')->translate();

            return;
        }

        if (in_array($value, $this->reserved, true)) {
            $fail('The :attribute is reserved.')->translate();

            return;
        }

        $query = Profile::whereRaw('lower(username) = ?', [mb_strtolower($value)]);

        if ($this->profile instanceof Profile) {
            $query->where('id', '!=', $this->profile->id);
        }

        if ($query->exists()) {
            $fail('The :attribute has already been taken.')->translate();
        }
    }
}
