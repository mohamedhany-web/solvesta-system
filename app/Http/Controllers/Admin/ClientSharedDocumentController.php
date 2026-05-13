<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientSharedDocument;
use App\Services\ClientPortalNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientSharedDocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientSharedDocument::with(['client', 'uploader'])->orderByDesc('created_at');

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->integer('client_id'));
        }
        if ($request->filled('search')) {
            $s = '%'.$request->string('search').'%';
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', $s)->orWhere('notes', 'like', $s);
            });
        }

        $documents = $query->paginate(20)->withQueryString();
        $clients = Client::query()->orderBy('name')->get(['id', 'name', 'company_name']);

        return view('admin.client-shared-documents.index', compact('documents', 'clients'));
    }

    public function create()
    {
        $clients = Client::query()->orderBy('name')->get(['id', 'name', 'company_name']);

        return view('admin.client-shared-documents.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'document_type' => 'nullable|string|max:64',
            'notes' => 'nullable|string|max:5000',
            'file' => 'required|file|max:25600|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,txt,jpg,jpeg,png,webp',
        ]);

        $client = Client::findOrFail((int) $validated['client_id']);
        $file = $request->file('file');
        $path = $file->store('client-shared-documents/'.$client->id, 'local');

        $doc = ClientSharedDocument::create([
            'client_id' => $client->id,
            'title' => $validated['title'],
            'document_type' => $validated['document_type'] ?? 'general',
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'notes' => $validated['notes'] ?? null,
            'uploaded_by' => $request->user()?->id,
        ]);

        ClientPortalNotifier::notify(
            (int) $client->id,
            'shared_document',
            'مستند مشترك جديد',
            'تمت مشاركة مستند: '.$doc->title,
            url('/client/documents'),
            ['document_id' => $doc->id]
        );

        return redirect()
            ->route('client-shared-documents.index')
            ->with('success', 'تم رفع المستند للعميل');
    }

    public function destroy(ClientSharedDocument $sharedDocument)
    {
        if (Storage::disk('local')->exists($sharedDocument->file_path)) {
            Storage::disk('local')->delete($sharedDocument->file_path);
        }
        $sharedDocument->delete();

        return redirect()
            ->route('client-shared-documents.index')
            ->with('success', 'تم حذف المستند');
    }

    public function download(ClientSharedDocument $sharedDocument)
    {
        if (! Storage::disk('local')->exists($sharedDocument->file_path)) {
            abort(404);
        }

        return Storage::disk('local')->download(
            $sharedDocument->file_path,
            $sharedDocument->original_filename ?? basename($sharedDocument->file_path)
        );
    }
}
