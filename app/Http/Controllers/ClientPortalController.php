<?php

namespace App\Http\Controllers;

use App\Models\FinancialInvoice;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ClientPortalController extends Controller
{
    public function dashboard(Request $request)
    {
        $account = $request->user('client');
        $client = $account?->client;

        if (!$client) {
            abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');
        }

        $projectsCount = Project::where('client_id', $client->id)->count();
        $ticketsOpenCount = Ticket::where('client_id', $client->id)->whereIn('status', ['open', 'in_progress', 'pending_client'])->count();

        $invoicesCount = Invoice::where('client_id', $client->id)->count();
        $invoicesUnpaidAmount = Invoice::where('client_id', $client->id)->where('status', '!=', 'paid')->sum('balance_amount');

        $financialInvoicesCount = FinancialInvoice::where('client_id', $client->id)->count();
        $financialInvoicesUnpaidAmount = FinancialInvoice::where('client_id', $client->id)->where('payment_status', '!=', 'paid')->sum('balance_due');

        return view('client-portal.dashboard', compact(
            'client',
            'projectsCount',
            'ticketsOpenCount',
            'invoicesCount',
            'invoicesUnpaidAmount',
            'financialInvoicesCount',
            'financialInvoicesUnpaidAmount',
        ));
    }

    public function projects(Request $request)
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (!$client) abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');

        $projects = Project::where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('client-portal.projects', compact('client', 'projects'));
    }

    public function invoices(Request $request)
    {
        $account = $request->user('client');
        $client = $account?->client;
        if (!$client) abort(403, 'لا يوجد عميل مرتبط بهذا الحساب');

        $invoices = Invoice::where('client_id', $client->id)->orderBy('created_at', 'desc')->paginate(10, ['*'], 'invoices_page');
        $financialInvoices = FinancialInvoice::where('client_id', $client->id)->orderBy('created_at', 'desc')->paginate(10, ['*'], 'fin_invoices_page');

        return view('client-portal.invoices', compact('client', 'invoices', 'financialInvoices'));
    }
}

