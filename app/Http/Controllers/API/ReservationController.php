<?php

namespace App\Http\Controllers\API;

use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReserveTableRequest;
use App\Http\Requests\AvailabeTableRequest;

class ReservationController extends Controller
{
    //check table is Availabe to reserve or not
    public function checkAvailabe(Request $request)
    {
        try {
            $tableId = $request->table_id;
            $dataFrom = $request->from_time;
            $dataTo = $request->to_time;
            $isAvailable = Table::where('id', $tableId)
                ->whereDoesntHave('reservations', function ($query) use ($dataFrom, $dataTo) {
                    $query->where(function ($query) use ($dataFrom, $dataTo) {
                        $query->whereBetween('from_time', [$dataFrom, $dataTo])
                            ->orWhereBetween('to_time', [$dataFrom, $dataTo])
                            ->orWhere(function ($query) use ($dataFrom, $dataTo) {
                                $query->where('from_time', '<', $dataFrom)
                                    ->where('to_time', '>', $dataTo);
                            });
                    });
                })
                ->exists();

            return  $isAvailable;
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }
    //get the Availabe tables
    public function AvailabeTables(AvailabeTableRequest $request)
    {
        try {
            $availabe_table = Table::where('capacity', '>=', $request->guest_number)
                ->whereNotIn('tables.id', function ($query) use ($request) {
                    $query->select('table_id')
                        ->from('reservations')
                        ->where(function ($query) use ($request) {
                            $query->whereBetween('from_time', [$request->from_time, $request->to_time])
                                ->orWhereBetween('to_time', [$request->from_time, $request->to_time])
                                ->orWhere(function ($query) use ($request) {
                                    $query->where('from_time', '<', $request->from_time)
                                        ->where('to_time', '>', $request->to_time);
                                });
                        });
                })
                ->get();
            if (empty($availabe_table)) {
                return response()->json(['status' => false, 'message' => 'There are no Availabe Table at that time'], 404);
            }
            return response()->json(['status' => true, 'message' => 'Availabe Tables Retrivied successfully', 'data' => $availabe_table], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }

    //reserve the table
    public function ReserveTable(ReserveTableRequest $request)
    {
        try {
            $customer = auth()->user();
            $isAvailable = $this->checkAvailabe($request);
            if ($isAvailable) {
                $request['user_id'] = $customer->id;
                $reservation = Reservation::create($request->all());
                if ($reservation) {
                    return response()->json(['status' => true, 'message' => 'Reservation Table Added successfully', 'data' => $reservation], 201);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'This Table Not Avaliable at that time'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }
}
