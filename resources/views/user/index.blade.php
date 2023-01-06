@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.admin')
@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])
@section('content')

    <div class="row">
        @foreach ($viewData["users"] as $user)
            <div class="col-md-4 col-lg-3 mb-2">
                <div class="card">
                    <img src="{{ asset( './storage/media/' . $user->image) }}" class="card-img-top img-card"
                         alt="{{asset('/storage/media/unregistered_user.png')}}">
                    <div class="card-body text-center">
                        <a href="{{ route('user.show', [$user->id]) }}"
                           class="btn bg-primary text-white">
                            {{ $user['name'] }} <br> {{ $user->role }}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
