<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\HomepageVideo;
use Illuminate\Http\Request;

class HomepageVideoController extends Controller
{
    public function index()
    {
        $videos = HomepageVideo::latest()->get();

        return view('admin.e-commerce.video.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.e-commerce.video.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',
            'video' => 'nullable|file|mimes:mp4,mov,avi,webm',
            'thumbnail' => 'nullable|image',
            'status' => 'nullable',
        ]);

        if ($request->hasFile('video')) {
            $data['video'] = $request->file('video')->store('videos', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('videos', 'public');
        }

        $data['status'] = $request->has('status') ? 1 : 0;

        HomepageVideo::create($data);

        notify()->success('Video added');

        return redirect()->route('admin.video.index');
    }

    public function edit(HomepageVideo $video)
    {
        return view('admin.e-commerce.video.edit', compact('video'));
    }

    public function update(Request $request, HomepageVideo $video)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',
            'video' => 'nullable|file|mimes:mp4,mov,avi,webm',
            'thumbnail' => 'nullable|image',
            'status' => 'nullable',
        ]);

        if ($request->hasFile('video')) {
            $data['video'] = $request->file('video')->store('videos', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('videos', 'public');
        }

        $data['status'] = $request->has('status') ? 1 : 0;

        $video->update($data);

        notify()->success('Updated');

        return redirect()->route('admin.video.index');
    }

    public function destroy(HomepageVideo $video)
    {
        $video->delete();

        notify()->success('Deleted');

        return back();
    }
}
