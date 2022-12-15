<?php

namespace App\Enums;

enum MessageType: string
{
    case AddUser = ' neuer User hinzugefügt ';
    case EditUser = ' User editiert ';
    Case DeleteUser = ' User gelöscht ';
    case AddCar = ' neues Fahrzeug hinzugefügt ';
    case EditCar = ' Fahrzeug editiert ';
    case DeleteCar = ' Fahrzeug gelöscht ';
    case AddParkingSpot = ' neuer Parkplatz hinzugefügt ';
    case EditParkingSpot = ' Parkplatz editiert ';
    case ReserveParkingSpot = ' Anfrage Parkplatzreservierung';
    case ResetParkingSpot = ' Parkplatz zurückgesetzt ';
    case DeleteParkingSpot = ' Parkplatz gelöscht ';
    case EditAddress = ' Adresse editiert ';
    case AntwortMessage = ' Anliegen bearbeitet ';
    case FreeMessage = ' Mitteilung ';
}
