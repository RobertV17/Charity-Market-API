<?php

namespace Modules\Item\Requests\Rules;

use Illuminate\Contracts\Validation\Rule;

class CorrectPrice implements Rule
{
    private const MIN_ITEM_PRICE = 1.00;
    private const MAX_ITEM_PRICE = 10000.00;

    private const COUNT_OF_DIGITS_AFTER_DOT = 2;

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
        if (is_numeric($value) && $this->isIncludedInPriceRange((float)$value)) {
            return $this->isCorrectFormat($value);
        }

        return  false;
    }

    /**
     * @param  float  $price
     *
     * @return bool
     */
    public function isIncludedInPriceRange(float $price): bool
    {
        return ($price >= self::MIN_ITEM_PRICE && $price <= self::MAX_ITEM_PRICE);
    }

    /**
     * @param  string  $price
     *
     * @return bool
     */
    public function isCorrectFormat(string $price): bool
    {
        $numberParts = explode('.', $price);
        $decimalPart = $numberParts[1] ?? null;

        if (is_null($decimalPart) || strlen($decimalPart) !== self::COUNT_OF_DIGITS_AFTER_DOT) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The price must not exceed 10 000 rub and have the following format: 250.00';
    }
}
