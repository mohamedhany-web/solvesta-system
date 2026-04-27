<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientAccountController extends Controller
{
    public function index(Request $request)
    {
        $accounts = ClientAccount::query()
            ->with('client')
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhereHas('client', function ($qc) use ($request) {
                        $qc->where('name', 'like', '%' . $request->search . '%')
                           ->orWhere('company', 'like', '%' . $request->search . '%');
                    });
            })
            ->orderByDesc('id')
            ->paginate(15);

        return view('client-accounts.index', compact('accounts'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        return view('client-accounts.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id', 'unique:client_accounts,client_id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:client_accounts,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        ClientAccount::create([
            'client_id' => $validated['client_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        return redirect()->route('client-accounts.index')->with('success', 'تم إنشاء حساب العميل بنجاح');
    }

    public function edit(ClientAccount $clientAccount)
    {
        $clients = Client::orderBy('name')->get();
        return view('client-accounts.edit', compact('clientAccount', 'clients'));
    }

    public function update(Request $request, ClientAccount $clientAccount)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id', Rule::unique('client_accounts', 'client_id')->ignore($clientAccount->id)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('client_accounts', 'email')->ignore($clientAccount->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $clientAccount->client_id = $validated['client_id'];
        $clientAccount->name = $validated['name'];
        $clientAccount->email = $validated['email'];
        $clientAccount->is_active = (bool) ($validated['is_active'] ?? false);
        if (!empty($validated['password'])) {
            $clientAccount->password = $validated['password'];
        }
        $clientAccount->save();

        return redirect()->route('client-accounts.index')->with('success', 'تم تحديث حساب العميل بنجاح');
    }

    public function destroy(ClientAccount $clientAccount)
    {
        $clientAccount->delete();
        return redirect()->route('client-accounts.index')->with('success', 'تم حذف حساب العميل بنجاح');
    }
}

