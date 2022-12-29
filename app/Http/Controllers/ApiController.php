<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAppointment;
use App\Models\AppointmentDetail;
use App\Models\Notification;
use App\Models\QouteDetail;
use App\Models\Qoute;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    //=============================== User Login Api==========================
    public function login(Request $request)
    {

        $rules = [
            'email' => 'required|string|email|max:255',
            'password' => 'required',
            'device_token' => 'required'
        ];

        $validator = FacadesValidator::make($request->all(), $rules);

        if ($validator->fails()) {
            $err = $validator->errors()->getMessages();
            $msg = array_values($err)[0][0];
            $res['status'] = false;
            $res['message'] = $msg;

            return response()->json($res);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if ($request->password == $user->password) {

                $token  = User::find($user->id);
                $token->device_token = $request->device_token;
                $token->save();


                $res['status'] = true;
                $res['message'] = "Password Matched! You have Login successfully!";
                $res['data'] =     $user;
                return response()->json($res);
            } else {

                $res['status'] = false;
                $res['message'] = "Password mismatch";
                return response()->json($res);
            }
        } else {

            $res['status'] = false;
            $res['message'] = "User does not exist";
            return response()->json($res);
        }
    }

    //==========================Pending Appointments Against User Api ==================================
    public function admin_appointments(Request $request)
    {

        $rules = [
            'user_id' => 'required',
        ];

        $validator = FacadesValidator::make($request->all(), $rules);

        if ($validator->fails()) {
            $err = $validator->errors()->getMessages();
            $msg = array_values($err)[0][0];
            $res['status'] = false;
            $res['message'] = $msg;

            return response()->json($res);
        }

        $admin_appointments = UserAppointment::where('user_id', $request->user_id)->where('status', 0)->get();

        if (count($admin_appointments) == 0) {

            $res['status'] = false;
            $res['message'] = "Appointments Can't Found Against User!";
            return response()->json($res, 404);
        } else {

            $res['status'] = true;
            $res['message'] = "Appointment Against User List!";
            $res['data'] = $admin_appointments;
            return response()->json($res);
        }
    }



    //======================Upload Image Api ========================================
    public function upload_img(Request $request)
    {

        if ($request->file('img') == null) {
            $image_name = "";

            $res['status'] = false;
            $res['message'] = "image can not upload successfully";
            return $res;
        } else {

            $path_title = $request->file('img')->store('public/images');

            $image_name = "images/" . basename($path_title);

            $res['status'] = true;
            $res['message'] = "image upload successfully";
            $res['data'] = $image_name;
            return $res;
        }
    }

    public function scheduled(Request $request)
    {
       

        $rules = [
            'user_id' => 'required',
            'appointment_id' => 'required',
            'date' => 'required',
            'time' => 'required',
         
        ];

        $validator = FacadesValidator::make($request->all(), $rules);

        if ($validator->fails()) {
            $err = $validator->errors()->getMessages();
            $msg = array_values($err)[0][0];
            $res['status'] = false;
            $res['message'] = $msg;

            return response()->json($res);
        }

        $scheduled = UserAppointment::where('id', $request->appointment_id)->where('user_id', $request->user_id)->first();
        $scheduled->date = $request->date;
        $scheduled->time = $request->time;
        $scheduled->status = 1;
        $scheduled->save();

        $res['status'] = true;
        $res['message'] = "You have Scheduled Appointment Sucessfully!!";
        $res['data'] = $scheduled;

        return response()->json($res);
    }

    public function scheduled_list(Request $request)
    {
 
       $list = UserAppointment::where('user_id', $request->user_id)->where('status',1)->get();
 
       if (count($list) == 0) {
 
          $res['status'] = false;
          $res['message'] = "Scheduled List Can't Found Against User!!";
          return response()->json($res);
          
       } else {
 
          $res['status'] = true;
          $res['message'] = "Scheduled List Against User!!";
          $res['data'] = $list;
          return response()->json($res);
       }
    }

    //=================================job status change Api ==========================

    public function job_status(Request $request)
    {

        $result = json_decode($request->getContent(), true);

        $rules = [
            'user_id' => 'required',
            'appointment_id' => 'required',
            'comment' => 'required',
            'appointment_pics_before' => 'required',
            'appointment_pics_after' => 'required'
        ];

        $validator = FacadesValidator::make($request->all(), $rules);

        if ($validator->fails()) {
            $err = $validator->errors()->getMessages();
            $msg = array_values($err)[0][0];
            $res['status'] = false;
            $res['message'] = $msg;

            return response()->json($res);
        }

        $appointments = UserAppointment::where('id', $result['appointment_id'])->where('user_id', $result['user_id'])->first();
        // dd($appointments);
        if (is_null($appointments)) {

            $res['status'] = false;
            $res['message'] = "Appointment Can't Found!!";
            return $res;
        } else {
            // $appointments = UserAppointment::where('id',$result['appointment_id'])->where('user_id', $result['user_id'])->first();
            $appointments->comment = $result['comment'];
            $appointments->save();
        }

        $appointment_pics_before = $result['appointment_pics_before'];

        foreach ($appointment_pics_before as $list) {

            $appointments_details = new AppointmentDetail();
            $appointments_details->appointment_id = $result['appointment_id'];
            $appointments_details->images = $list['img'];
            $appointments_details->status = 0;
            $appointments_details->save();
        }

        $appointment_pics_after = $result['appointment_pics_after']; 

        foreach ($appointment_pics_after as $list) {

            $appointments_details = new AppointmentDetail();
            $appointments_details->appointment_id = $result['appointment_id'];
            $appointments_details->images = $list['img'];
            $appointments_details->status = 1;
            $appointments_details->save();
        }

        $appointments = UserAppointment::where('id', $result['appointment_id'])->where('user_id', $result['user_id'])->first();
        $appointments->status = 4;
        $appointments->save();

        
        $res['status'] = true;
        $res['message'] = "You have Complete this Appointment Sucessfully!!";
        return response()->json($res);


      
    }

    public function user_appointment_itself(Request $request)
    {

        $rules = [
            'user_id' => 'required',
            'title' => 'required',
            'desc' => 'required',
            'address' => 'required',
            'date' => 'required',
            'time' => 'required'
        ];

        $validator = FacadesValidator::make($request->all(), $rules);

        if ($validator->fails()) {
            $err = $validator->errors()->getMessages();
            $msg = array_values($err)[0][0];
            $res['status'] = false;
            $res['message'] = $msg;

            return response()->json($res);
        }

        $appointments_itself = new UserAppointment();
        $appointments_itself->title = $request->title;
        $appointments_itself->description = $request->desc;
        $appointments_itself->address = $request->address;
        $appointments_itself->date = $request->date;
        $appointments_itself->time = $request->time;
        $appointments_itself->role = 0;
        $appointments_itself->user_id = $request->user_id;
        $appointments_itself->save();

        $res['status'] = true;
        $res['message'] = "You have Added Appointment Sucessfully!!";
        $res['data'] = $appointments_itself;

        return response()->json($res);
    }

     //==========================Complete Appointments Against User Api ===============================
     public function complete_appointments(Request $request)
     {
 
         $rules = [
             'user_id' => 'required',
         ];
 
         $validator = FacadesValidator::make($request->all(), $rules);
 
         if ($validator->fails()) {
             $err = $validator->errors()->getMessages();
             $msg = array_values($err)[0][0];
             $res['status'] = false;
             $res['message'] = $msg;
 
             return response()->json($res);
         }
 
         $admin_appointments = UserAppointment::where('user_id', $request->user_id)->where('status', 4)->get();
         $data = [];

         foreach($admin_appointments as $value){
            $detail = AppointmentDetail::select('images')->where('appointment_id', $value->id)->where('status',0)->get();
            $value->images_before = $detail;
            $details = AppointmentDetail::select('images')->where('appointment_id', $value->id)->where('status',1)->get();
            $value->images_after = $details;
            array_push($data, $value);

         }
 
         if (count($admin_appointments) == 0) {
 
             $res['status'] = false;
             $res['message'] = "Appointments Can't Found Against User!";
             return response()->json($res, 404);
         } else {
 
             $res['status'] = true;
             $res['message'] = "Complete Appointment Against User List!";
             $res['data'] = $data;
             return response()->json($res);
         }
     }

     //===================================Notification Against User ==========================
     public function notification(Request $request)
   {

      $notification = Notification::where('user_id', $request->user_id)->get();

      if (count($notification) == 0) {

         $res['status'] = false;
         $res['message'] = "Notification List Can't Found Against User!!";
         return response()->json($res);
         
      } else {

         $res['status'] = true;
         $res['message'] = "Notification List Against User!!";
         $res['data'] = $notification;
         return response()->json($res);
      }
   }

   public function qoute(Request $request){

    $result = json_decode($request->getContent(), true);

    $rules = [
        'user_id' => 'required',
        'appointment_id' => 'required',
        'address' => 'required',
        'start_date' => 'required',
        'qoute_data' => 'required',

    ];

    $validator = FacadesValidator::make($request->all(), $rules);

    if ($validator->fails()) {
        $err = $validator->errors()->getMessages();
        $msg = array_values($err)[0][0];
        $res['status'] = false;
        $res['message'] = $msg;

        return response()->json($res);
    }

    $user_appointment = UserAppointment::find($request->appointment_id);
    $user_appointment->status = 2;
    $user_appointment->save();


    $qoute = new Qoute();
    $qoute->user_id = $result['user_id'];
    $qoute->appointment_id = $result['appointment_id'];
    $qoute->address = $result['address'];
    $qoute->start_date = $result['start_date'];
    $qoute->save();

    $qoute_data = $result['qoute_data']; 

    foreach ($qoute_data as $list) {

        $qoute_details = new QouteDetail();
        $qoute_details->qoute_id = $qoute->id;
        $qoute_details->job_desc = $list['job_desc'];
        $qoute_details->material_cost =$list['material_cost'];
        $qoute_details->hours =$list['hours'];
        $qoute_details->save();
    }

    $res['status'] = true;
    $res['message'] = "Your Qoute data Inserted Successfully!!";
    return response()->json($res);

   }

   public function edit_qoute(Request $request){



    $result = json_decode($request->getContent(), true);

    $rules = [
        'user_id' => 'required',
        'appointment_id' => 'required',
        'address' => 'required',
        'start_date' => 'required',
        'qoute_data' => 'required',

    ];

    $validator = FacadesValidator::make($request->all(), $rules);

    if ($validator->fails()) {
        $err = $validator->errors()->getMessages();
        $msg = array_values($err)[0][0];
        $res['status'] = false;
        $res['message'] = $msg;

        return response()->json($res);
    }

    $user_id = $result['user_id'];
    $appointment_id =  $result['appointment_id'];

    $qoute = Qoute::where('user_id', $user_id)->where('appointment_id', $appointment_id)->first();
    $qoute->address = $result['address'];
    $qoute->start_date = $result['start_date'];
    $qoute->status = 0;
    $qoute->save();

    $qoute_details_delete = QouteDetail::where('qoute_id',$qoute->id);
    $qoute_details_delete->delete();

    $qoute_data = $result['qoute_data']; 

    foreach ($qoute_data as $list) {

        $qoute_details = new QouteDetail();
        $qoute_details->qoute_id = $qoute->id;
        $qoute_details->job_desc = $list['job_desc'];
        $qoute_details->material_cost =$list['material_cost'];
        $qoute_details->hours =$list['hours'];
        $qoute_details->save();
    }

    $res['status'] = true;
    $res['message'] = "Your Qoute data Updated Successfully!!";
    return response()->json($res);

   }


      //===================================qoute_list Against User ==========================
      public function qoute_list(Request $request)
      {
   

        //  $qoutes = Qoute::where('user_id', $request->user_id)->get();

        //  $data = [];

        //  foreach($qoutes as $qoute){

        //     $qoutes_details = QouteDetail::where('qoute_id', $qoute->id)->get();
        //     $qoute->qoute_details = $qoutes_details;
        //     array_push($data, $qoute);


        //  }

   
        //  if (count($data) == 0) {
   
        //     $res['status'] = false;
        //     $res['message'] = "Qoute List Can't Found Against User!!";
        //     return response()->json($res);
            
        //  } else {
   
        //     $res['status'] = true;
        //     $res['message'] = "Qoute List Against User!!";
        //     $res['data'] = $data;
        //     return response()->json($res);
        //  }

        $user_appointments = UserAppointment::where('user_id', $request->user_id)->where('status',2)->get();

        $data = [];

        foreach($user_appointments as $list){

            $qoutes = Qoute::where('appointment_id', $list->id)->first();
            $qoutes_details = QouteDetail::where('qoute_id', $qoutes->id)->get();

            $list->qoute = $qoutes;
            $list->qoute_details = $qoutes_details;
            array_push($data, $list);
   
        }

       if (count($data) == 0) {
 
          $res['status'] = false;
          $res['message'] = "Qouted List Can't Found Against User!!";
          return response()->json($res);
          
       } else {
 
          $res['status'] = true;
          $res['message'] = "Qouted List Against User!!";
          $res['data'] = $data;
          return response()->json($res);
       }

      }

      

    //===================================approved_list Against User ==========================
        public function approved_list(Request $request)
        {
            $user_appointments = UserAppointment::where('user_id', $request->user_id)->where('status',3)->get();

            $data = [];

            foreach($user_appointments as $list){

                $qoutes = Qoute::where('appointment_id', $list->id)->where('status',1)->first();
                $qoutes_details = QouteDetail::where('qoute_id', $qoutes->id)->get();

                $list->qoute = $qoutes;
                $list->qoute_details = $qoutes_details;
                array_push($data, $list);
       
            }

           if (count($data) == 0) {
     
              $res['status'] = false;
              $res['message'] = "Approved List Can't Found Against User!!";
              return response()->json($res);
              
           } else {
     
              $res['status'] = true;
              $res['message'] = "Approved List Against User!!";
              $res['data'] = $data;
              return response()->json($res);
           }
        }

         //=========================Both approved and qouted Against User =======================
         public function both(Request $request)
         {
            // $user_appointments = UserAppointment::where('user_id', $request->user_id)->where('status',2)->orWhere('status' ,3)->get();

            // $user_appointments = UserAppointment::where('user_id', $request->user_id)->where([ ['status', 2] , ['status', 3] ])->get();

            $user_appointments = UserAppointment::where([
                ['user_id', '=',  $request->user_id],
                ['status', '=', '1'],
            ])->orWhere([
                ['user_id', '=',  $request->user_id],
                ['status', '=', '3'],
            ])->get();
 
             $data = [];
 
             foreach($user_appointments as $list){
 
                 $qoutes = Qoute::where('appointment_id', $list->id)->first();

                 if($qoutes){

                    $qoutes_details = QouteDetail::where('qoute_id', $qoutes->id)->get();
 
                    $list->qoute = $qoutes;
                    $list->qoute_details = $qoutes_details;
                    array_push($data, $list);

                 } else {

                    $list->qoute = NULL;
                    $list->qoute_details = NULL;

                    array_push($data, $list);

                 }
            
             }
 
            if (count($data) == 0) {
      
               $res['status'] = false;
               $res['message'] = "Both Approved and Scheduled List Can't Found Against User!!";
               return response()->json($res);
               
            } else {
      
               $res['status'] = true;
               $res['message'] = "Both Approved and Scheduled List Against User!!";
               $res['data'] = $data;
               return response()->json($res);
            }
         }
 }


