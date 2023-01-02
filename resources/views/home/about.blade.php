@extends('layouts.app')
@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-4 ms-auto">
                <p id="text-output" class="lead"></p>
            </div>
            <div class="col-lg-4 me-auto">
                <p class="lead">{{ $viewData["author"] }}</p>
            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded",
        function () {
            const element = document.getElementById("text-output");
            const text = "Willkommen bei Laravel-Parkplatzvermietung, dem Ort, an dem Sie sichere und bequeme Parkplätze"
            +"für Ihr Auto finden können. Unser Team ist bestrebt, Ihnen den bestmöglichen Service zu bieten und dafür"
            +"zu sorgen, dass Sie sich bei uns wie zu Hause fühlen. Unsere Parkplätze befinden sich in zentraler Lage "
            +"und sind von vielen Sehenswürdigkeiten und Geschäften in der Nähe. Wir bieten flexible Mietoptionen und "
            +"erschwingliche Preise, damit Sie sich keine Sorgen über das Parken machen müssen, während Sie die Stadt "
            +"erkunden. Sicherheit ist für uns von größter Bedeutung, deshalb haben wir umfangreiche Sicherheitsmaßnahmen "
            +"wie Kameras und beleuchtete Parkplätze eingerichtet, damit Sie sich keine Gedanken über die Sicherheit "
            +"Ihres Autos machen müssen. Wir freuen uns darauf, Ihnen bei der Parkplatzsuche zu helfen und Ihnen einen "
            +"unvergesslichen Aufenthalt zu ermöglichen. Buchen Sie noch heute Ihren Parkplatz bei "
            +"Laravel-Parkplatzvermietung und genießen Sie die Stadt in vollen Zügen!";
            let index = 0;

            function type() {
                if (index < text.length) {
                    element.innerHTML += text[index];
                    index++;
                    setTimeout(type, Math.round(Math.random() * (50 - 10)) + 10);
                }
            }

            type();
        });
</script>

