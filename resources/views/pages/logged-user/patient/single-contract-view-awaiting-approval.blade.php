@extends('layout')
@section('content')
    @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']))
    @php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->dentist_id))

@endsection