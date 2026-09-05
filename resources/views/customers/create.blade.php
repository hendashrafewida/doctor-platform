@extends('layouts.app')

@section('title', 'إضافة عميل')

@section('content')
<div class="page-header"><h2>إضافة عميل</h2></div>
@include('customers._form', ['customer' => null, 'formAction' => route('customers.store'), 'formMethod' => 'POST'])
@endsection