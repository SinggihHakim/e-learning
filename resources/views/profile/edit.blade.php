@extends('layouts.app')
@section('title', __('app.profile'))
@section('page-title', __('app.profile'))

@section('content')
    <div class="py-12" style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 24px;">
        <div class="card">
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        @if(auth()->user()->isAdmin())
        <div class="card">
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
        @endif
    </div>
@endsection
