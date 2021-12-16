<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    protected $user;


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();

    }//end __construct()
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $appointment = $this->user->appointment()->get(['date','postal_code','start_time','completed','contact_id']);
        return response()->json($appointment->toArray());

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'date'            =>'required',
                'postal_code'     =>'required',
                'start_time'      =>'required',
                'completed'       =>'required',
                'contact_name'    =>'required',
                'contact_surname' =>'required',
                'contact_email'   =>'required',
                'contact_phone'   =>'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors(),
                ],
                400
            );
        }

        $isContact = Contact::where('email',$request->email)->where('phone',$request->phone)->get();


        $appointment             = new appointment();
        $appointment->date       = $request->date;
        $appointment->postal_code= $request->postal_code;
        $appointment->start_time = $request->start_time;
        $appointment->completed  = $request->completed;
        $appointment->user_id    = $this->guard()->user()->id;
        if (count($isContact)) {
            $appointment->contact_id = $isContact->id;
        }else{
            $contact             = new contact();
            $contact->name       = $request->contact_name;
            $contact->surname    = $request->contact_surname;
            $contact->phone      = $request->contact_phone;
            $contact->email      = $request->contact_email;
            $contact->save();
            $appointment->contact_id = $contact->id;
        }


        if ($this->user->appointments()->save($appointment)) {
            return response()->json(
                [
                    'status' => true,
                    'appointment'   => $appointment,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the appointment could not be saved.',
                ]
            );
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        //
        return $appointment;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
        $validator = Validator::make(
            $request->all(),
            [
                'date'     => 'required',
                'postal_code'      => 'required',
                'start_time' =>'required',
                'completed' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors(),
                ],
                400
            );
        }

        $appointment->date     = $request->date;
        $appointment->postal_code      = $request->postal_code;
        $appointment->start_time     = $request->start_time;
        $appointment->completed = $request->completed;

        if ($this->user->appointments()->save($appointment)) {
            return response()->json(
                [
                    'status' => true,
                    'appointment'   => $appointment,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the appointment could not be saved.',
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        //

        if ($appointment->delete()) {
            return response()->json(
                [
                    'status' => true,
                    'appointment'   => $appointment,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the appoinment could not be deleted.',
                ]
            );
        }

    }

    protected function guard()
    {
        return Auth::guard();

    }//end guard()
}
