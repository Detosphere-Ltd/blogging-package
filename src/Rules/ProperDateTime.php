<?php

namespace DetosphereLtd\BlogPackage\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ProperDateTime implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // permit UNIX timestamps or regular string
        return Carbon::canBeCreatedFromFormat($value, 'U') || Carbon::canBeCreatedFromFormat($value, 'Y-m-d H:i:s');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute should be a UNIX timestamp or in the format Y-m-d H:i:s';
    }
}
