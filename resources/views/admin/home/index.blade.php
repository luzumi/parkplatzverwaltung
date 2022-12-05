@extends('layouts.admin')
@section('title', $viewData['title'])
@section('content')
    <div class="card h-auto">
        <div class="card-header">
            Adminpanel - Admin - Parkplatzverwaltung
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <td class="col-sm-1">ID</td>
                    <td class="col-sm-1">Sender-ID</td>
                    <td class="col-sm-1">Empfänger-ID</td>
                    <td class="col-sm-3">Message</td>
                    <td class="col-sm-1">Status</td>
                    <td class="col-sm-2">Zeit</td>
                </tr>
                </thead>
                <tbody class="overflow-auto table-hover table-striped" >
                @foreach ($viewData["messages"] as $message)
                    <tr >
                        {{-- Fahrzeug hat Parkplatz reserviert? wenn ja, Anzeige der Parkplatznummer --}}
                        <td class="col-sm-1">{{ $message->id }}</td>
                        <td class="col-sm-1">{{ $message->sender_user_id }}</td>
                        <td class="col-sm-1">{{ $message->receiver_user_id }}</td>
                        <td class="col-sm-3">{{ $message->message }}</td>
                        <td class="col-sm-1">{{ $message->status }}</td>
                        <td class="col-sm-2">{{ $message->updated_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-body">
            {{-- Hier kommt eine Messageliste hin--}}
            Willkommen im Admin-Panel. Weiter Optionen sind über die Sidebar zu erreichen
        </div>
    </div>
@endsection
