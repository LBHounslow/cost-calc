@extends('layouts/app')

@section('title')
    Client Lookup
@endsection

@section('content')
    <form id="clientForm" class="form-inline" role="form" method="GET" action="/client/$clientId">
        <div class="form-group">
            <label for="clientId">Client ID</label>
            <input type="text" class="form-control" id="clientId" required>
        </div>
        <button id="clientSubmit" type="submit" class="btn btn-default">Get Details</button>
    </form>
    <br>

    @if(isset($client))
        <ul class="list-group">
            <li class="list-group-item"><strong>ID:</strong> {{ $client->id }}</li>
            <li class="list-group-item"><strong>Surname:</strong> {{ $client->surname }}</li>
            <li class="list-group-item"><strong>DOB:</strong> {{ $client->dob }}</li>
            <li class="list-group-item"><strong>Postcode:</strong> {{ $client->postcode }}</li>
        </ul>
    @endif

@stop


@section('footerScripts')
    <script>
        $("#clientForm").submit(function (event) {
            $(this).attr("action", "/client/" + $("#clientId").val());
        });
    </script>
@stop