<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactMessageAdminController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->get();
        return response()->json(['data' => $messages]);
    }

    public function markRead(ContactMessage $message)
    {
        $message->update(['is_read' => true]);
        return response()->json(['message' => 'تم التحديد كمقروء']);
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return response()->json(['message' => 'تم حذف الرسالة']);
    }
}
