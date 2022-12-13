@extends('layouts.admin')
@section('title', $viewData["title"])
@section('content')
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


    <div class="card">
        <div class="card-header">
            Manage Users
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Vorschau</th>
                    <th scope="col">Name</th>
                    <th scope="col">email</th>
                    <th scope="col">Telefon</th>
                    <th scope="col">Role</th>
                    <th scope="col">Bild</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($viewData["users"] as $user)
                    <tr >
                        <td>{{ $user->id }}</td>
                        <td><img src="{{ asset('/storage/media/'. $user->image) }}"
                                 class="img-profile" alt="image not found"></td>
                        <td>{{ $user->name }}</td>
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
@endsection
