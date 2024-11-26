<?php

namespace App\Http\Controllers\API;

use App\Models\Meal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    //get meals
    public function listAll()
    {
        try {
            $items = Meal::where('quantity_available', '>=', 0)->get();
            if (empty($items)) {
                return response()->json(['message' => 'There is no items'], 200);
            }
            return response()->json(['status' => true,  'message' => 'All Meals Retrivied successfully', 'data' => $items], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }
}
