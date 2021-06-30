<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicRequest;
use App\Models\Course;
use App\Models\Topic;
use App\Http\Resources\Topic as TopicResponse;

class TopicController extends Controller
{
    public function index(Course $course)
    {        
        return view('learning.topics.index', compact('course'));
    }

    public function json(Course $course)
    {
        if (request()->ajax()) {            
            $topics = Topic::where('course_id', $course->id)
            ->with('user', 'course.teacher')
            ->withCount('replies')
            ->orderBy('created_at', 'DESC')
            ->paginate();       
            return TopicResponse::collection($topics);
        }
        abort(401);
    }

    public function store(TopicRequest $request, Course $course)
    {
        Topic::create([
            'title' => $request->input('title'),
            'content' => clean($request->input('content')),
            'user_id' => auth()->id(),
            'course_id' => $course->id,
            'created_at' => now()
        ]);
        return response()->json(['message' => 'success']);
    }
}
