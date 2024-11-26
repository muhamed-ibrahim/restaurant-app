<?php

namespace App\Http\Requests;

use App\Rules\CheckQuantity;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'table_id' => 'required|exists:tables,id',
            'reservation_id' => 'required|exists:reservations,id',
            'meals' => 'required|array',
            'meals.*.id' => 'required|exists:meals,id',
            'meals.*.quantity' => ['required', 'numeric', 'min:1', new CheckQuantity()],
        ];
    }
}
