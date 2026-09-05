@extends('layouts.app')

@section('title', 'تعديل عميل')

@section('content')
<div class="page-header"><h2>تعديل عميل</h2></div>
@include('customers._form', ['formAction' => route('customers.update', $customer), 'formMethod' => 'PUT'])
@endsection