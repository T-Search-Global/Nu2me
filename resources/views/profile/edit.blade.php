@extends('Dashboard.Layouts.master_dashboard')
@section('heading')
    Profile
@endsection
@section('content')

@include('profile.partials.update-profile-information-form')  {{-- Bootstrap version --}}
@include('profile.partials.update-password-form')             {{-- Bootstrap version --}}
@include('profile.partials.delete-user-form')                 {{-- Bootstrap version --}}

@endsection
