<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{

    protected $user;


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $contact = $this->user->contact()->get(['name','surname','phone','email']);
        return response()->json($contact->toArray());
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
        //
        $validator = Validator::make(
            $request->all(),
            [
                'name'     => 'required',
                'surname'  => 'required',
                'phone'    =>'required',
                'email'    => 'required',
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

        $contact            = new contact();
        $contact->name      = $request->name;
        $contact->surname   = $request->surname;
        $contact->phone     = $request->phone;
        $contact->email     = $request->email;

        if ($this->user->contacts()->save($contact)) {
            return response()->json(
                [
                    'status' => true,
                    'contact'   => $contact,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the contact could not be saved.',
                ]
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        //
        return $contact;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
        $validator = Validator::make(
            $request->all(),
            [
                'name'     => 'required',
                'surname'      => 'required',
                'phone' =>'required',
                'email' => 'required',
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

        $contact->name      = $request->name;
        $contact->surname   = $request->surname;
        $contact->phone     = $request->phone;
        $contact->email     = $request->email;

        if ($this->user->contacts()->save($contact)) {
            return response()->json(
                [
                    'status' => true,
                    'contact'   => $contact,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Oops, the contact could not be saved.',
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        //
        if ($contact->delete()) {
            return response()->json(
                [
                    'status' => true,
                    'contact'   => $contact,
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
