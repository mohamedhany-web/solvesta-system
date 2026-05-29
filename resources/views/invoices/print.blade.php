@extends('layouts.invoice-print')

@section('content')
    @include('invoices._document', ['invoice' => $invoice])
@endsection
