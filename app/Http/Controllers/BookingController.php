<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;

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

        if((!is_numeric(strtotime($data['start'])))||(!is_numeric(strtotime($data['end'])))||($data['start'] >= $data['end'])) {
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

        $result['rooms'] = Room::select('rooms.id', 'rooms.real_num', 'rooms.cost_per_night', 'rooms.max_visitors_count', 'room_types.name', 'room_types.description')
            ->leftJoin('bookings', function ($join) use ($data) {
                $join->on('rooms.id', '=', 'bookings.room_id')
                    ->where('bookings.date_start', '>=', $data['start'])
                    ->where('bookings.date_start', '<=', $data['end'])
                    ->where('bookings.date_end', '>=', $data['start'])
                    ->where('bookings.date_end', '<=', $data['end']);
            })
            ->join('room_types', 'room_types.id', '=', 'rooms.room_type_id')
            ->where('bookings.id', '=', NULL)
            ->where('rooms.max_visitors_count', '=', $data['count'])
            ->get();

        $result['types'] = Room::select('room_types.name')
            ->leftJoin('bookings', function ($join) use ($data) {
                $join->on('rooms.id', '=', 'bookings.room_id')
                    ->where('bookings.date_start', '>=', $data['start'])
                    ->where('bookings.date_start', '<=', $data['end'])
                    ->where('bookings.date_end', '>=', $data['start'])
                    ->where('bookings.date_end', '<=', $data['end']);
            })
            ->join('room_types', 'room_types.id', '=', 'rooms.room_type_id')
            ->where('bookings.id', '=', NULL)
            ->where('rooms.max_visitors_count', '=', $data['count'])
            ->distinct()
            ->get();
        
        $result['code'] = 120;
        print_r(json_encode($result));
        return;
    }

    public function make_booking(Request $request) {
        $data = $request->data;
        
        if(session('role') != 'visitor') {
            $result['code'] = 101;
            print_r(json_encode($result));
            return;
        }

        $check = Booking::select('bookings.id')
            ->where('room_id', '=', $data['room_id'])
            ->where('bookings.date_start', '>=', $data['start'])
            ->where('bookings.date_start', '<=', $data['end'])
            ->where('bookings.date_end', '>=', $data['start'])
            ->where('bookings.date_end', '<=', $data['end'])
            ->get();

        if(count($check)) {
            $result['code'] = 102;
            print_r(json_encode($result));
            return;
        }

        Booking::create([
            'user_id' => session('user_id'),
            'room_id' => $data['room_id'],
            'date_start' => $data['start'],
            'date_end' => $data['end'],
            'status' => 1
        ]);

        $result['code'] = 120;
        print_r(json_encode($result));
    }

    public function delete_booking(Request $request) {
        $data = $request->data;

        $bookingToDelete = Booking::select('bookings.status', 'bookings.user_id')
            ->where('id', '=', $data['id'])
            ->first();

        if(!$bookingToDelete) {
            $result['code'] = 101;
            print_r(json_encode($result));
        }
        
        if(session('role') == 'visitor') {
            if(($bookingToDelete['status'] != 1) || (session('user_id') != $bookingToDelete['user_id']) || ($data['action'] == 'edit')) {
                $result['code'] = 102;
                print_r(json_encode($result));
            }
        }elseif((session('role') == 'operator') || (session('role') == 'admin')) {
            //
        }else {
            $result['code'] = 103;
            print_r(json_encode($result));
        }

        if($data['action'] == 'delete') {
            Booking::where('id', '=', $data['id'])->delete();
        }elseif($data['action'] == 'edit') {
            Booking::where('id', $data['id'])->update([
                'status' => $data['status']
            ]);
        }

        $result['code'] = 120;
        print_r(json_encode($result));
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

    public function load_table(Request $request) {
        $data = $request->data;
        
        $offset = max(0, (($data['page'] - 1) * 3));

        if(($data['table'] == '1') || ($data['table'] == '2') || ($data['table'] == '3')) {
            $result['table'] = Booking::select('bookings.id as b_id', 'bookings.date_start', 'bookings.date_end', 'bookings.status',
            'rooms.real_num', 'rooms.cost_per_night', 'rooms.max_visitors_count', 'room_types.name',
            'users.id as u_id', 'users.name as u_name', 'users.mail')
                ->join('rooms', 'rooms.id', '=', 'bookings.room_id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->join('users', 'users.id', '=', 'bookings.user_id')
                ->where('bookings.status', '=', $data['table'])
                ->limit(3)
                ->offset($offset)
                ->get();

            $result['count'] = ceil(Booking::where('status', '=', $data['table'])->count()/3);
        }elseif($data['table'] == 4) {
            $result['table'] = User::select('users.id', 'users.name', 'users.mail', 'roles.name as role')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->limit(3)
                ->offset($offset)
                ->get();

                $result['count'] = ceil(User::count()/3);
        }
        print_r(json_encode($result));
        return;
    }
}
