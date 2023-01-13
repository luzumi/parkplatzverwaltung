@extends('layouts.admin')
@section('title', $viewData["title"])
@section('content')
<div class="card h-50">
    <div class="card mb-4">
        <div class="card-header">
            Neuen User erstellen
        </div>
        <div class="card-body">
            @if($errors->any())
                <ul class="alert alert-danger list-unstyled">
                    @foreach($errors->all() as $error)
                        <li> - {{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <form method="POST" action="{{ route('admin.user.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="mb-1 row">
                            <div class="col-lg-10 col-md-6 col-sm-12">
                                <label>
                                    <input name="name" value="{{ old('name') }}" type="text" class="form-control">
                                </label>
                                <label class="col-lg-10 col-sm-12 col-form-label">Name</label>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3 row">
                            <div class="col-lg-10 col-md-6 col-sm-12">
                                <label>
                                    <input name="email" value="{{ old('email') }}" type="text" class="form-control">
                                </label>
                                <label class="col-lg-10 col-md-6 col-sm-12 col-form-label">eMail</label>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3 row">
                            <div class="col-lg-10 col-md-6 col-sm-12">
                                <label>
                                    <input name="telefon" value="{{ old('telefon') }}" type="text" class="form-control">
                                </label>
                                <label class="col-lg-10 col-md-6 col-sm-12 col-form-label">Telefon</label>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        &nbsp;
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Absenden</button>
            </form>
        </div>
    </div>


    <div class="card mb-4">
        <div class="card-header">
            Manage Users
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th class="col-sm-1">ID</th>
                    <th class="col-sm-1">Vorschau</th>
                    <th class="col-sm-1">Name</th>
                    <th class="col-sm-1">email</th>
                    <th class="col-sm-1">Telefon</th>
                    <th class="col-sm-1">Role</th>
                    <th class="col-sm-1">Bild</th>
                    <th class="col-sm-1">Edit</th>
                    <th class="col-sm-1">Delete</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($viewData["users"] as $user)
                    <tr >
                        <td>{{ $user->id }}</td>
                        <td><img src="{{ asset('/storage/media/'. $user->image) }}"
                                 class="img-profile" alt="image not found"></td>
                        <td>{{ $user->getAttribute('name') }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->telefon }}</td>
                        <td>{{ $user->role }}</td>
                    {{-- verkürzte Ausgabe des Imagelinks --}}
                        <td>{{ '...' . substr($user->image, 40) }}</td>
                    {{-- Buttons edit und delete --}}
                        <td>
                            <a class="btn btn-primary" href="{{ route('admin.user.edit', ['id'=>$user->id]) }}">
                                <i class="bi-pencil"> </i>
                            </a>
                        </td>
                        <td>
                            <form action="{{ route('admin.user.delete', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">
                                    <i class="bi-trash"> </i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
