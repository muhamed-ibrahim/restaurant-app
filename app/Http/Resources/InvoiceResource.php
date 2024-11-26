<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $meals = $this->order_details->map(function ($detail) {
            return [
                'description' => $detail->meal->description,
                'unit-price' => $detail->meal->price,
                'discount' => $detail->meal->discount,
                'quantity' => $detail->quantity,
                'price' => $detail->amount_to_pay,
            ] ?? null;
        });
        return [
            'id' => $this->id,
            'table_id' => $this->table_id,
            'reservation_id' => $this->reservation_id,
            'customer_details' => [
                'customer_name' => $this->user->name,
                'customer_phone' => $this->user->phone,
            ],
            'order_details' => $meals,
            'total_price' => $this->total,
            'data' => $this->updated_at->format('Y-m-d'),
            'time' => $this->updated_at->format('H:i:s'),

        ];
    }
}
