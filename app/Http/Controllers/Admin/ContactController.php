<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::get();
        return Inertia::render('Admin/Contacts/Index', ['contacts' => $contacts]);
    }
    
    public function destroy($id)
    {
        Contact::findOrFail($id)->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Contact deleted successfully');
    }
    
    public function showData($id)
    {
        $contact = Contact::findOrFail($id);
        return response()->json(['contact' => $contact]);
    }
    
}
