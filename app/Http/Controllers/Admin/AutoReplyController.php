<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AutoResponceContent;

class AutoReplyController extends Controller
{
    /**
     * Display a listing of email autoreplies.
     */
    public function index()
    {
        $items = AutoResponceContent::orderBy('form_name', 'asc')->get()->map(function($item) {
            return [
                'id' => $item->id,
                'formName' => trim($item->form_name),
                'mailSubject' => trim($item->mail_subject),
                'mailContent' => strip_tags($item->mail_content),
                'defaultContent' => trim($item->default_content),
                'mailPreview' => trim($item->mail_preview),
                'status' => $item->content_status === '1' ? 'Active' : 'Inactive',
            ];
        });

        return view('admin.website.autoreply.index', compact('items'));
    }

    /**
     * Show the form for editing the specified email autoreply.
     */
    public function edit($id)
    {
        $item = AutoResponceContent::findOrFail($id);
        return view('admin.website.autoreply.edit', compact('item'));
    }

    /**
     * Update the specified email autoreply in storage.
     */
    public function update(Request $request, $id)
    {
        $item = AutoResponceContent::findOrFail($id);

        $request->validate([
            'form_name' => 'required|string|max:255',
            'mail_subject' => 'required|string|max:255',
            'mail_preview' => 'nullable|string',
            'mail_content' => 'required|string',
            'default_content' => 'nullable|string',
            'content_status' => 'required|in:Active,Inactive',
        ]);

        $item->update([
            'form_name' => $request->form_name,
            'mail_subject' => $request->mail_subject,
            'mail_preview' => $request->mail_preview ?? '',
            'mail_content' => $request->mail_content,
            'default_content' => $request->default_content ?? '',
            'content_status' => $request->content_status === 'Active' ? '1' : '0',
        ]);

        return response()->json(['success' => true, 'item' => $item]);
    }
}
