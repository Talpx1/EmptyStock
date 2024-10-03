<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TOwner of Model
 */
final readonly class BelongsTo implements ValidationRule {
    /**
     * @param  class-string<Model>  $self_model  class-string of the model to check
     * @param  TOwner  $owner  owner model
     * @param  string  $owner_id_field  field where the owner is is stored in the model to check.
     */
    public function __construct(
        private string $self_model,
        private Model $owner,
        private string $owner_id_field,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        $owner_id = $this->owner->id; //@phpstan-ignore-line
        $model = $this->self_model::findOrFail($value);

        $should_fail = $model->{$this->owner_id_field} !== $owner_id;

        if ($should_fail) {
            $fail(':attribute is not allowed to perform this action')->translate();
        }
    }
}
