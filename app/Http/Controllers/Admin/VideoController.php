<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\File;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::orderBy('created_at', 'desc')->get();
        return view('admin.videos.index', compact('videos')); 
    }

    public function store(Request $request)
    {
        $rules = [
            'link-video'  => 'required|url|max:255',
            'thumbnail'   => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        $messages = [
            'link-video.required'  => 'Link video YouTube wajib diisi.',
            'link-video.url'       => 'Format link tidak valid (harus berupa URL valid).',
            'thumbnail.required'   => 'Kamu belum memilih gambar thumbnail.',
            'thumbnail.image'      => 'File harus berupa gambar.',
            'thumbnail.mimes'      => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'thumbnail.max'        => 'Ukuran thumbnail maksimal 2MB.'
        ];

        $request->validate($rules, $messages);

        $data = $request->all();
        $data['is_active'] = 1;

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = 'video_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/videos'), $filename);
            $data['thumbnail'] = $filename;
        }

        Video::create($data);

        return redirect()->route('admin.video.index')
            ->with('success', 'Video baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        $rules = [
            'link-video'  => 'required|url|max:255',
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Boleh kosong saat edit
        ];

        $messages = [
            'link-video.required'  => 'Link video YouTube wajib diisi.',
            'link-video.url'       => 'Format link tidak valid (harus berupa URL valid).',
            'thumbnail.image'      => 'File harus berupa gambar.',
            'thumbnail.mimes'      => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'thumbnail.max'        => 'Ukuran thumbnail maksimal 2MB.'
        ];

        $request->validate($rules, $messages);

        $data = $request->only(['link-video']);

        if ($request->hasFile('thumbnail')) {
            $oldPath = public_path('uploads/videos/' . $video->thumbnail);
            if ($video->thumbnail && File::exists($oldPath)) {
                File::delete($oldPath);
            }

            $file = $request->file('thumbnail');
            $filename = 'video_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/videos'), $filename);
            $data['thumbnail'] = $filename;
        }

        $video->update($data);

        return redirect()->route('admin.video.index')
            ->with('success', 'Data video berhasil diperbarui.');
    }

    public function toggleStatus($id)
    {
        $video = Video::findOrFail($id);
        $video->is_active = $video->is_active ? 0 : 1;
        $video->save();
        $statusText = $video->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.video.index')
            ->with('success', "Video berhasil $statusText.");
    }

    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $oldPath = public_path('uploads/videos/' . $video->thumbnail);
        if ($video->thumbnail && File::exists($oldPath)) {
            File::delete($oldPath);
        }

        $video->delete();
        return redirect()->route('admin.video.index')->with('success', 'Data video berhasil dihapus.');
    }
}