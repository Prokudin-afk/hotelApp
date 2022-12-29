<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;

class BookingController extends Controller
{
    public function search_rooms(Request $request) {
        $data = $request->data;

        /*if(!$data['start'] || !$data['end'] || !$data['count']) {
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
        }*/

        $result['rooms'] = Room::select('rooms.real_num', 'rooms.cost_per_night', 'rooms.max_visitors_count', 'room_types.name', 'room_types.description')
            /*->leftJoin('bookings', function($join) {
                $join->on('rooms.id', '=', 'bookings.room_id');
                $join->on('bookings.date_start', '>=', Room::raw("'".$data['start']."'"));
                $join->on('bookings.date_start', '<=', Room::raw("'".$data['end']."'"));
                $join->on('bookings.date_end', '>=', Room::raw("'".$data['start']."'"));
                $join->on('bookings.date_end', '<=', Room::raw("'".$data['end']."'"));
            })*/
            ->leftJoin('bookings', function ($join) {
                $join->on('rooms.id', '=', 'bookings.room_id')
                    ->where('bookings.date_start', '>=', strtotime($data['start']));
                    //->where('bookings.date_start', '>=', date('Y-m-d', strtotime($data['start'])));
            })
            ->join('room_types', 'room_types.id', '=', 'rooms.room_type_id')
            ->where('bookings.id', '=', NULL)
            ->where('rooms.max_visitors_count', '>=', $data['count'])
            ->get();
        
        $result['code'] = 120;
        print_r(json_encode($result));
        return;
    }

    public function show_orders() {
        if(session('role') != 'visitor') {
            $result['code'] = 101;
            print_r(json_encode($result));
            return;
        }

        $result['orders'] = Booking::select('bookings.id', 'bookings.date_start', 'bookings.date_end', 'bookings.status', 
        'rooms.real_num', 'rooms.cost_per_night', 'rooms.max_visitors_count', 'room_types.name')
            ->join('rooms', 'rooms.id', '=', 'bookings.room_id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('bookings.user_id', '=', session('user_id'))
            ->get();

        print_r(json_encode($result));
        return;
    }
}
