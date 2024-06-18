<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Member;

use App\Enums\Role;
use App\Models\Organization;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property Organization $organization
 */
class MemberUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string|ValidationRule|\Illuminate\Contracts\Validation\Rule>>
     */
    public function rules(): array
    {
        return [
            'role' => [
                'string',
                Rule::enum(Role::class)
                    ->except([Role::Owner, Role::Placeholder]),
            ],
            'billable_rate' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'billable_rate_update_time_entries' => [
                'string',
                'in:true,false',
            ],
        ];
    }

    public function getBillableRate(): ?int
    {
        $input = $this->input('billable_rate');

        return $input !== null && $input !== 0 ? (int) $this->input('billable_rate') : null;
    }

    public function getBillableRateUpdateTimeEntries(): bool
    {
        return $this->has('billable_rate_update_time_entries') &&
            $this->input('billable_rate_update_time_entries') === 'true';
    }

    public function getRole(): Role
    {
        return Role::from($this->input('role'));
    }
}
