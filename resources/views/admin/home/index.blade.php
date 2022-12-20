@extends('layouts.admin')
@section('title', $viewData['title'])
@section('content')
    <div class="card h-50">
        <div class="card-header">
            Adminpanel - Admin - Parkplatzverwaltung
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-header">
                <tr>
                    <td class="col-sm-1">ID</td>
                    <td class="col-sm-1">Sender-ID</td>
                    <td class="col-sm-1">Empfänger-ID</td>
                    <td class="col-sm-3">Message</td>
                    <td class="col-sm-1">Status</td>
                    <td class="col-sm-2">Zeit</td>
                </tr>
                </thead>
                <tbody class="table-body overflow-auto table-hover h-75 index1" >
                @foreach ($viewData["messages"] as $mess)
                    <tr >
                        {{-- Fahrzeug hat Parkplatz reserviert? wenn ja, Anzeige der Parkplatznummer --}}
                        <td class="col-sm-1">{{ $mess->id }}</td>
                        <td class="col-sm-1">{{ $mess->user_id }}</td>
                        <td class="col-sm-1">{{ $mess->receiver_user_id }}</td>
                        <td class="col-sm-3">
{{-- Admin Message Übersicht - Ausgabe je nach MessageStatus angepasst--}}
                            @if($mess->message == \App\Enums\MessageType::AddCar->value)
                                {!! 'Neues Fahrzeug : User ' . $mess->user->getAttribute('name') . ' [USER-ID ('.$mess->user->getAttribute('id') . ')] </br>' !!}
                                {!! 'CAR-ID: '. $mess->car_id . ' | Kennzeichen: '. $mess->car->sign !!}
                            @elseif($mess->message == \App\Enums\MessageType::EditCar->value)
                                {!! 'Fahrzeug editiert : User ' . $mess->user->getAttribute('name') . ' [USER-ID ('.$mess->user->getAttribute('id') . ')] </br>' !!}
                                {!! 'CAR-ID: '. $mess->car_id . ' | Kennzeichen: '. $mess->car->sign !!}
                            @elseif($mess->message == \App\Enums\MessageType::DeleteCar->value)
                                {!! 'Fahrzeug gelöscht : User ' . $mess->user->getAttribute('name') . ' [USER-ID ('.$mess->user->getAttribute('id') . ')] </br>' !!}
                                {!! 'CAR-ID: '. $mess->car_id . ' | Kennzeichen: '. $mess->car->sign !!}
                            @elseif($mess->message == \App\Enums\MessageType::AddUser->value)
                                {!! 'Neuer User </br>' !!}
                                {!! $mess->user->getAttribute('name') . ' [USER-ID ('.$mess->user->getAttribute('id') . ')] </br>' !!}
                            @elseif($mess->message == \App\Enums\MessageType::EditAddress->value || $mess->message == \App\Enums\MessageType::EditUser->value)
                                {!! 'Userdaten geändert </br>' !!}
                                {!! $mess->user->getAttribute('name') . ' [USER-ID ('.$mess->user->getAttribute('id') . ')] </br>' !!}
                            @elseif($mess->message == \App\Enums\MessageType::DeleteUser->value)
                                {!! 'User Gelöscht: [USER-ID ('.$mess->user->getAttribute('id') . ') - Name: ' . $mess->user->getAttribute('name') . ' -  ] </br>' !!}

                            @elseif($mess->message == \App\Enums\MessageType::AddParkingSpot->value)
                                {!! 'Parkplatz hinzugefügt : Parkplatz ' . $mess->parkingSpot->getAttribute('number') . ' [ID ('.$mess->parkingSpot->getAttribute('id') . ')] </br>' !!}

                            @elseif($mess->message == \App\Enums\MessageType::EditParkingSpot->value)
                                {!! 'Parkplatz editiert : Parkplatz ' . $mess->parkingSpot->getAttribute('number') . ' [ID ('.$mess->parkingSpot->getAttribute('id') . ')] </br>' !!}

                            @elseif($mess->message == \App\Enums\MessageType::ReserveParkingSpot->value)
                                {!! 'Parkplatz-Reservierungsanfrage : User ' . $mess->user->getAttribute('name') . ' [USER-ID ('.$mess->user->getAttribute('id') . ')] </br>' !!}
                                {!! 'CAR-ID: '. $mess->car_id . ' | Kennzeichen: '. $mess->car->sign . ' PARKPLATZ NR: ' . $mess->parking_spot_id !!}
                            @elseif($mess->message == \App\Enums\MessageType::ResetParkingSpot->value)
                                {!! 'Parkplatz zurückgesetzt : Parkplatz ' . $mess->parkingSpot->getAttribute('number') . ' [ID ('.$mess->parkingSpot->getAttribute('id') . ')] </br>' !!}

                            @elseif($mess->message == \App\Enums\MessageType::DeleteParkingSpot->value)
                                {!! 'Parkplatz gelöscht : Parkplatz ' . $mess->parkingSpot->getAttribute('number') . ' [ID ('.$mess->parkingSpot->getAttribute('id') . ')] </br>' !!}

                            @else
                                    {{$mess->message}}
                            @endif
                        </td>
                        <td class="col-sm-1">{!!  \App\Actions\Admin\StatusLink::createLink($mess)  !!}</td>
                        <td class="col-sm-2">{{ $mess->updated_at }}</td>
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
