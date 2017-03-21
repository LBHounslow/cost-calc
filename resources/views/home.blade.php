@extends('layouts/app')

@section('title')
    Welcome to Hounslow Council’s Cost Calculator
@endsection

@section('content')
    <p>Hello {{ Auth::user()->name }} ({{ Auth::user()->provider->display_name }})</p>
    <hr>

    <h3>About the Cost Calculator</h3>
    <p>
        This tool contains data from a variety of sources. It allows you to easily see and analyse what the council is
        spending on individuals, groups and services. For further information please contact:
    </p>
    <br>
    <p>
        Vinesh Govind (<a
                href="mailto:Vinesh.Govind@hounslow.gov.uk?subject=Cost Calculator info request">
            Vinesh.Govind@hounslow.gov.uk</a>)
    </p>
    <p>
        Fatima Ajia (<a
                href="mailto:Fatima.Ajia@hounslow.gov.uk?subject=Cost Calculator info request">
            Fatima.Ajia@hounslow.gov.uk</a>)
    </p>
@endsection
