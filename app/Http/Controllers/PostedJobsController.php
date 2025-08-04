<?php

namespace App\Http\Controllers;

use App\Models\PostedJobs;
use App\Models\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class PostedJobsController extends Controller
{
    public function index()
    {
        
    }

    public function create()
    {
        
    }
    
    public function get() {
        $postedJobs = PostedJobs::where('status', 1)->get();
        return response()->json(compact('postedJobs'));
    }
    
    public function getById($id) {
        $postedJobs = PostedJobs::where('id', $id)->first();
        return response()->json(compact('postedJobs'));
    }

    public function getForModerator() {
        $postedJobs = PostedJobs::get();
        return response()->json(compact('postedJobs'));
    }

    public function getNotification() {
        $notification_count = Notification::where('status', 0)->count();
        $notification_record = Notification::orderBy('id', 'desc')->limit(5)->get();

        return response()->json(compact('notification_count', 'notification_record'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'=>'required',
            'title'=>'required',
            'description'=>'required',
            'office'=>'required',
            'department'=>'required',
            'recruiting_category'=>'required',
            'employment_type'=>'required',
            'years_of_experience'=>'required',
        ]);
        
        if(PostedJobs::where('email', $request->email)->count() === 0) {
            $postedJobs = PostedJobs::create($request->all());
            
            $notif = array(
                'type' => 'new_email',
                'record_id' => $postedJobs->id,
                'message' => $request->email.' posted a new job.',
                'status' => 0
            );

        } 
        else {
            $postedJobs = PostedJobs::create($request->all());
            
            $notif = array(
                'type' => 'posted_job',
                'record_id' => $postedJobs->id,
                'message' => 'Looking for '.$postedJobs->title,
                'status' => 0
            );
        } 
        
        Notification::create($notif);

        event(new MessageSent('posted'));

        return response()->json(compact('postedJobs'));
    }

    public function show(PostedJobs $postedJobs)
    {
        
    }

    public function edit(PostedJobs $postedJobs)
    {
        
    }

    public function update(Request $request, $id)
    {
        PostedJobs::find($id)->update(['status'=>($request->actions==="Approve"?1:2)]);

        $postedJobs = PostedJobs::get();

        event(new MessageSent('front'));

        return response()->json(compact('postedJobs'));
    }

    public function destroy(PostedJobs $postedJobs)
    {

    }

    public function readNotification() {
        Notification::where('status', 0)->update(['status'=>1]);

        $notification_count = Notification::where('status', 0)->count();

        return response()->json(compact('notification_count'));
    }
}
