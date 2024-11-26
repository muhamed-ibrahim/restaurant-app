<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Meal;  // Ensure you are using the correct model

class CheckQuantity implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $mealId = request()->input("meals." . explode('.', $attribute)[1] . ".id");
        $quantity = $value;

        $meal = Meal::find($mealId);

        if (!$meal) {
            return false;
        }

        return $quantity <= $meal->quantity_available;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The quantity for the selected meal exceeds the available quantity.';
    }
}




