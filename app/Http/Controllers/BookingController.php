<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;

class BookingController extends Controller
{
    public function search_rooms(Request $request) {
        $data = $request->data;

        if(!$data['start'] || !$data['end'] || !$data['count']) {
            $result['code'] = 101;
            print_r(json_encode($result));
            return;
            //required fields are not filled
        }

        if((!is_numeric(strtotime($data['start'])))||(!is_numeric(strtotime($data['end'])))) {
            $result['code'] = 102;
            print_r(json_encode($result));
            return;
            //incorrect date
        }

        if(!is_numeric($data['count']) || ($data['count'] < 1) || ($data['count'] > 4)) {
            $result['code'] = 103;
            print_r(json_encode($result));
            return;
            //incorrect count of visitors
        }

        try{
            $result['rooms'] = Room::select('rooms.real_num', 'rooms.cost_per_night', 'rooms.max_visitors_count', 'room_types.name', 'room_types.description')
                ->leftJoin('bookings', function($join) {
                    $join->on('rooms.id', '=', 'bookings.room_id')
                        ->on('bookings.date_start', '>=', $data['start'])
                        ->on('bookings.date_start', '<=', $data['end'])
                        ->on('bookings.date_end', '>=', $data['start'])
                        ->on('bookings.date_end', '<=', $data['end'])
                })
                ->join('room_types', 'room_types.id', '=', 'rooms.room_type_id')
                ->where('bookings.id', '=', NULL)
                ->where('rooms.max_visitors_count', '>=', $data['count'])
                ->get();
        }catch (\Illuminate\Database\QueryException $exception){
            print_r(json_encode($exception->getMessage()));
            return;
        }
        $result['code'] = 120;
        print_r(json_encode($result));
        return;
    }
}
