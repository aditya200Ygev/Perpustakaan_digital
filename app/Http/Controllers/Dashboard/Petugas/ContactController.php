<?php

namespace App\Http\Controllers\Dashboard\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('is_read', $request->status === 'read');
        }

        $contacts = $query->latest()->paginate(10)->withQueryString();

        return view('dashboard.petugas.contact.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        $contact->update(['is_read' => true]);
        return view('dashboard.petugas.contact.show', compact('contact'));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return back()->with('success', '🗑️ Pesan berhasil dihapus.');
    }

    public function destroyAll(Request $request)
    {
        if ($request->has('ids')) {
            Contact::whereIn('id', $request->ids)->delete();
            return back()->with('success', '🗑️ Pesan terpilih berhasil dihapus.');
        }
        return back()->with('error', '⚠️ Tidak ada pesan yang dipilih.');
    }
}
