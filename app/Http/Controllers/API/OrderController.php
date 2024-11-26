<?php

namespace App\Http\Controllers\API;

use App\Models\Meal;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Rules\CheckQuantity;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;

class OrderController extends Controller
{
    public function makeOrder(OrderRequest $request)
    {

        try {
            $customer = auth()->user();
            $res_customer = $customer->reservations()
                ->where('table_id', $request->table_id)
                ->where('id', $request->reservation_id)
                ->get();

            if (empty($res_customer)) {
                return response()->json(['status' => false, 'message' => 'This reservation is not found'], 404);
            }
            //user have already make and order before and want to add another meals
            $order = $customer->orders()->first();

            $request['user_id'] = $customer->id;
            //user first to make an order
            if (empty($order)) {
                $order = Order::create($request->all());
            }

            $totalAmount = 0;

            foreach ($request->meals as $meal) {
                $getMeal = Meal::find($meal['id']);
                $mealPrice = $getMeal->price;
                $discount = $getMeal->discount;
                $discountedPrice = $mealPrice - ($mealPrice * ($discount / 100));
                $amountToPay = round($discountedPrice * $meal['quantity'], 2);
                OrderDetail::create([
                    'order_id' => $order->id,
                    'meal_id' => $meal['id'],
                    'quantity' => $meal['quantity'],
                    'amount_to_pay' => $amountToPay,
                ]);
                $getMeal->update(['quantity_available' => $getMeal->quantity_available -= $meal['quantity']]);
                $totalAmount += $amountToPay;
            }
            $order->update(['total' => round($totalAmount + $order->total, 2)]);
            return response()->json(['status' => true, 'message' => 'Order placed successfully', 'order' => $order], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => 'An error occur while place the order: ' . $e->getMessage()], 500);
        }
    }

    public function checkout(Request $request)
    {
        try {
            $customer = auth()->user();
            $order = $customer->orders()
                ->where('paid', false)
                ->where('table_id', $request->table_id)
                ->where('reservation_id', $request->reservation_id)
                ->first();
            if (!$order) {
                return response()->json(['status' => false, 'message' => 'This Order is not found or already paid'], 404);
            }
            $Paid  = $order->update(['paid' => true]);
            if ($Paid) {
                return response()->json([
                    'status' => true,
                    'message' => "Order: {$order->id} paid successfully.",
                    'data' => new InvoiceResource($order)
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => 'An error occur while place the order: ' . $e->getMessage()], 500);
        }
    }
}
