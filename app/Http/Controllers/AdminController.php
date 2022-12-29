<?php

namespace App\Http\Controllers;

use App\Models\AppointmentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserAppointment;
use App\Models\QouteDetail;
use App\Models\Qoute;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{


    public function dashboard()
    {
        $users = User::count();
        $admin_apointments = UserAppointment::where('role', 1)->where('status', 0)->count();
        // $user_apointments = UserAppointment::where('role', 0)->where('status', 0)->count();
        $complete_apointments = UserAppointment::where('status', 1)->count();
        return view('dashboard', compact('users','admin_apointments','complete_apointments'));
    }

    public function users()
    {
        $users =  User::where('remember_token', NULL)->get();
        return view('users', compact('users'));
    }

    public function add_users_show()
    {
        $url = url('add_users');
        $title = 'Add User';
        $text = 'Save';

        return view('add_user', ['url' => $url, 'title' => $title, 'text' => $text]);
    }

    //=========================== Add Users Api ======================================
    public function add_users(Request $request)
    {

        if ($request->file('profile_img') == null) {
            $image_name = "";
        } else {
            $path_title = $request->file('profile_img')->store('public/images');

            $image_name = basename($path_title);
        }
        $request->validate([
            
            'name' => 'required | min:5',
            'email' => 'required|email|unique:users',
            'phone' => 'required ',
            'password' => 'required|min:8',
        ]);

        $users = new User();
        $users->profile_img = "images/" . $image_name;
        $users->name = $request->name;
        $users->email = $request->email;
        $users->phone = $request->phone;
        $users->password = $request->password;
        $users->save();
        return redirect(route('users', compact('users')))->with('add_message', 'User Added successfully');
    }


    public function edit_user($id)
    {
        $record = User::find($id);
        $url = url('update_user') . "/" . $id;
        $title = 'Edit User';
        $text = 'Update';
        return view('add_user', ['record' => $record, 'url' => $url, 'title' => $title, 'text' => $text]);
    }

    public function update_user($id, Request $request)
    {


        $request->validate([

            'name' => 'required | min:5',
            'email' => 'required',
            'phone' => 'required ',
            'password' => 'required|min:8',
        ]);

        $users = User::findOrFail($id);
        if ($request->file('profile_img') == null) {
            if($users->profile_img){
                $image_name = $users->profile_img;
            } else {
                $image_name = "";
            }
           
        } else {

            $path_title = $request->file('profile_img')->store('public/images');

            $image_name = "images/" .  basename($path_title);
        }
        $users->profile_img = $image_name;
        $users->name = $request->name;
        $users->email = $request->email;
        $users->phone = $request->phone;
        $users->password = $request->password;
        $users->save();

        return redirect(route('users'))->with('update_message', 'User Update successfully');
    }

    public function delete_user(Request $request)
    {
        $user_id = $request->delete_user_id;
        $user_appointment = UserAppointment::where('user_id', $user_id);
        $user_appointment->delete();
        $qoute_id = Qoute::where('user_id',  $user_id)->first();
        if($qoute_id){
            $qoute_det = QouteDetail::where('qoute_id',  $qoute_id->id);
            $qoute_det->delete();
        }
        
        $qoute = Qoute::where('user_id', $user_id);
        if($qoute){
            $qoute->delete();
        }
    
        $users = User::findOrFail($user_id);
        $users->delete();
        return redirect(route('users'))->with('delete_message', 'User Deleted successfully');
    }

    //==============================User Apointments =====================================
    public function user_appointment()
    {
        $user_appointments =  DB::table('user_appointments')
            ->join('users', 'user_appointments.user_id', '=', 'users.id')
            ->select('user_appointments.*', 'users.name')->where('user_appointments.role', 1)->get();
        return view('user_appointment', compact('user_appointments'));
    }

    public function user_appointment_details($id)
    {
  
        $users_appointment = UserAppointment::find($id);

        $user_name =  DB::table('user_appointments')
            ->join('users', 'user_appointments.user_id', '=', 'users.id')
            ->join('qoutes', 'user_appointments.id', '=', 'qoutes.appointment_id')
            ->select('users.name', 'qoutes.start_date')->where('user_appointments.id', $id)->first();

            $before = AppointmentDetail::where('appointment_id', $users_appointment->id)->where('status', 0)->get();
            $after = AppointmentDetail::where('appointment_id', $users_appointment->id)->where('status', 1)->get();
            // dd($user_name);

        return view('user_appointment_details', ['users_appointment' => $users_appointment , 'user_name' => $user_name, 'before' => $before , 'after' => $after]);
    }

    public function user_appointment_show()
    {
        $url = url('add_appointment');
        $title = 'Add Appointment';
        $text = 'Save';
        $users = User::all();

        return view('add_appointment', ['users' => $users, 'url' => $url, 'title' => $title, 'text' => $text]);
    }


    public function add_appointment(Request $request)
    {
       
        date_default_timezone_set('Asia/Karachi');
        $time = date('h:i:s');
    


        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'address' => 'required',
            'user_id' => 'required',
        ]);

        $user_appointment = new UserAppointment();
        $user_appointment->title = $request->title;
        $user_appointment->description = $request->description;
        $user_appointment->address = $request->address;
        $user_appointment->role = 1;
        $user_appointment->user_id = $request->user_id;
        $user_appointment->save();

        $this->UserNofication($request->user_id, 'New Appointment  ', 'You Have '. $request->title . '  From Admin',$time);

        return redirect(route('user_appointment', compact('user_appointment')))->with('add_message', 'User Appointment Added successfully');
    }


    public function edit_appointment($id)
    {
        $record = UserAppointment::find($id);
        $url = url('update_appointment') . "/" . $id;
        $title = 'Edit Appointment';
        $text = 'Update';
        $users = User::all();
        return view('add_appointment', ['users' => $users, 'record' => $record, 'url' => $url, 'title' => $title, 'text' => $text]);
    }

    public function update_appointment($id, Request $request)
    {
        date_default_timezone_set('Asia/Karachi');
        $time = date('h:i:s');

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'address' => 'required',
            'user_id' => 'required',
        ]);

        $user_appointment = UserAppointment::findOrFail($id);

        $user_appointment->title = $request->title;
        $user_appointment->description = $request->description;
        $user_appointment->address = $request->address;
        $user_appointment->user_id = $request->user_id;
        $user_appointment->save();

        $this->UserNofication($request->user_id, 'Update Appointment ', 'You Have '. $request->title . '  From Admin',$time);


        return redirect(route('user_appointment'))->with('update_message', 'User Appontment Update successfully');
    }

    public function delete_appointment(Request $request)
    {
        $appointment_id = $request->delete_appointment_id;
      
        $qoute_id = Qoute::where('appointment_id',  $appointment_id)->first();
        if($qoute_id){
            $qoute_det = QouteDetail::where('qoute_id',  $qoute_id->id);
            $qoute_det->delete();
        }
    
        $qoute = Qoute::where('appointment_id',  $appointment_id);
        if($qoute){
            $qoute->delete();
        }
        $details = AppointmentDetail::where('appointment_id',  $appointment_id);
        $details->delete();
        $users = UserAppointment::findOrFail($appointment_id);
        $users->delete();
        return redirect(route('user_appointment'))->with('delete_message', 'User Appointment Deleted successfully');
    }

    //==========================User Appointment Added Itself ================================
    public function user_appointment_itself()
    {
        $user_appointments =  DB::table('user_appointments')
            ->join('users', 'user_appointments.user_id', '=', 'users.id')
            ->select('user_appointments.*', 'users.name')->where('user_appointments.role', 0)->where('user_appointments.status', 0)->get();
        return view('user_appointment_itself', compact('user_appointments'));
    }


    public function user_appointment_itself_show()
    {
        $url = url('add_appointment_itself');
        $title = 'Add Appointment';
        $text = 'Save';
        $users = User::all();

        return view('add_appointment_itself', ['users' => $users, 'url' => $url, 'title' => $title, 'text' => $text]);
    }


    public function add_appointment_itself(Request $request)
    {


        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'address' => 'required',
            'date' => 'required ',
            'time' => 'required',
            'user_id' => 'required',
        ]);

        $user_appointment = new UserAppointment();
        $user_appointment->title = $request->title;
        $user_appointment->description = $request->description;
        $user_appointment->address = $request->address;
        $user_appointment->date = $request->date;
        $user_appointment->time = $request->time;
        $user_appointment->role = 1;
        $user_appointment->user_id = $request->user_id;
        $user_appointment->save();

        return redirect(route('user_appointment_itself', compact('user_appointment')))->with('add_message', 'User Appointment Added successfully');
    }


    public function edit_appointment_itself($id)
    {
        $record = UserAppointment::find($id);
        $url = url('update_appointment_itself') . "/" . $id;
        $title = 'Edit Appointment';
        $text = 'Update';
        $users = User::all();
        return view('add_appointment_itself', ['users' => $users, 'record' => $record, 'url' => $url, 'title' => $title, 'text' => $text]);
    }

    public function update_appointment_itself($id, Request $request)
    {


        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'address' => 'required',
            'date' => 'required ',
            'time' => 'required',
            'user_id' => 'required',
        ]);

        $user_appointment = UserAppointment::findOrFail($id);

        $user_appointment->title = $request->title;
        $user_appointment->description = $request->description;
        $user_appointment->address = $request->address;
        $user_appointment->date = $request->date;
        $user_appointment->time = $request->time;
        $user_appointment->user_id = $request->user_id;
        $user_appointment->save();


        return redirect(route('user_appointment_itself'))->with('update_message', 'User Appontment Update successfully');
    }

    public function delete_appointment_itself(Request $request)
    {
        $appointment_id = $request->delete_appointment_id;
        $details = AppointmentDetail::where('appointment_id',  $appointment_id);
        $details->delete();

        $qoute_id = Qoute::where('appointment_id',  $appointment_id)->first();
        if($qoute_id){
            $qoute_det = QouteDetail::where('qoute_id',  $qoute_id->id);
            $qoute_det->delete();
        }
        $qoute = Qoute::where('appointment_id',  $appointment_id);
        if($qoute){
            $qoute->delete();
        }
     
        $users = UserAppointment::findOrFail($appointment_id);
        $users->delete();
        return redirect(route('user_appointment_itself'))->with('delete_message', 'User Appointment Deleted successfully');
    }

    //======================Complete Appointment Added Itself ================================
    public function complete_appointment()
    {
        $user_appointments =  DB::table('user_appointments')
            ->join('users', 'user_appointments.user_id', '=', 'users.id')
            ->select('user_appointments.*', 'users.name')->where('user_appointments.status', 4)->get();
        
        return view('complete_appointment', compact('user_appointments'));
    }

    public function delete_complete_appointment(Request $request)
    {
        $appointment_id = $request->delete_appointment_id;

        $qoute_id = Qoute::where('appointment_id',  $appointment_id)->first();
        if($qoute_id){
            $qoute_det = QouteDetail::where('qoute_id',  $qoute_id->id);
            $qoute_det->delete();
        }
    
        $qoute = Qoute::where('appointment_id',  $appointment_id);
        if($qoute){
            $qoute->delete();
        }

        $details = AppointmentDetail::where('appointment_id',  $appointment_id);
        $details->delete();
        $users = UserAppointment::find($appointment_id);
        $users->delete();
        return redirect(route('complete_appointment'))->with('delete_message', 'User Appointment Deleted successfully');
    }

        //===========================================Qoute==========================================
        public function qoute()
        {
            $qoute =  DB::table('qoutes')
                ->join('users', 'qoutes.user_id', '=', 'users.id')
                ->join('user_appointments', 'qoutes.appointment_id', '=', 'user_appointments.id')
                ->select('qoutes.*', 'users.name', 'user_appointments.id as appointment_id', 'user_appointments.title')->where('qoutes.status', 0)->orWhere('qoutes.status', 2)->get();
            
                
            return view('qoute', compact('qoute'));
        }

        public function qoute_details($id){

            $qoutes = Qoute::find($id);

            $qoutes_details = DB::table('qoutes')
            ->join('qoute_details', 'qoutes.id' , '=', 'qoute_details.qoute_id')
            ->select('qoutes.*', 'qoute_details.job_desc', 'qoute_details.material_cost', 'qoute_details.hours')->where('qoutes.id', $id)->get();
        
            $total_cost = QouteDetail::where('qoute_id', $id)->select('material_cost')->sum('qoute_details.material_cost');

            $hours = QouteDetail::where('qoute_id', $id)->select('hours')->sum('qoute_details.hours');
           

            return view('qoutes_details',compact('qoutes','qoutes_details', 'total_cost', 'hours'));
        }


        public function approved_qoute()
        {
            $qoute =  DB::table('qoutes')
                ->join('users', 'qoutes.user_id', '=', 'users.id')
                ->join('user_appointments', 'qoutes.appointment_id', '=', 'user_appointments.id')
                ->select('qoutes.*', 'users.name', 'user_appointments.id as appointment_id', 'user_appointments.title')->where('qoutes.status',1)->where('user_appointments.status', 3)->get();
            
                
            return view('approved_qoutes', compact('qoute'));
        }
      
        public function approved_qoute_details($id){

            $qoutes = Qoute::where('id', $id)->where('status', 1)->first();

            $qoutes_details = DB::table('qoutes')
            ->join('qoute_details', 'qoutes.id' , '=', 'qoute_details.qoute_id')
            ->select('qoutes.*', 'qoute_details.job_desc', 'qoute_details.material_cost', 'qoute_details.hours')->where('qoutes.id', $id)->where('qoutes.status', 1)->get();
        
            $total_cost = QouteDetail::where('qoute_id',$qoutes->id)->select('material_cost')->sum('qoute_details.material_cost');

            $hours = QouteDetail::where('qoute_id', $qoutes->id)->select('hours')->sum('qoute_details.hours');
           

            return view('approved_qoutes_details',compact('qoutes','qoutes_details', 'total_cost', 'hours'));

        }


        public function status(Request $request){

            date_default_timezone_set('Asia/Karachi');
            $time = date('h:i:s');

            $qoute = Qoute::find($request->id);
            $qoute->status = $request->val ;
            $qoute->save();
                
            if(($request->val) == 1 ){

                $user_appointment =  UserAppointment::where('id', $qoute->appointment_id)->first();
                if(($user_appointment->status) == 2){

                    $user_appointment->status = 3;
                    $user_appointment->save();

                    $this->UserNofication($user_appointment->user_id, 'Approved Notification ', 'Your Appointment '. $user_appointment->title . '  is Approved By Admin',$time);
                }
             
            } elseif(($request->val) == 2){

                $user_appointment =  UserAppointment::where('id', $qoute->appointment_id)->first();
                    $user_appointment->status = 2;
                    $user_appointment->save();
                $this->UserNofication($user_appointment->user_id, 'Rejected Notification ', 'Your Appointment '. $user_appointment->title . '  is Rejected By Admin',$time);

            }


        }





    public function UserNofication($id, $title, $description, $time)
    {
     
       $notification = new Notification();
       $notification->user_id = $id;
       $notification->title = $title;
       $notification->description = $description;
       $notification->time = $time;
       $notification->save();
       
       $user = User::find($id);
       $user_token = $user->device_token;
 
       $SERVER_API_KEY = 'AAAAs2QpWlg:APA91bHVquJ8gLdQiXYrLCMaErZ4zWVDNTR45NYznLiyNB1j-52MhLyLTMWW9efHn7DtjGN-85c9MJHh4h4NRFYSIoxBNf5vR_9rPrhJHD_qOleLgDpsA3hoLNOyyEA1aAwiNXgcRsIL';
 
       $data = [
          "registration_ids" => [$user_token],
          "data" => [
             "title" => $title,
             "body" => $description,
          ]
       ];
       $dataString = json_encode($data);
 
       $headers = [
          'Authorization: key=' . $SERVER_API_KEY,
          'Content-Type: application/json',
       ];
 
       $ch = curl_init();
 
       curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
 
       $response = curl_exec($ch);
 
       //dd($response);
    }
}
