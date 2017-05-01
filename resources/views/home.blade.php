@extends('layouts/app')

@section('title')
    Welcome to the Cost Calculator
@endsection

@section('content')
    <p>Hello {{ Auth::user()->name }} ({{ Auth::user()->provider->display_name }})</p>
    <hr>

    <h3>About the Cost Calculator</h3>
    <p>The Cost Calculator is a web based intelligence tool designed to support local authorities in analysing the use of the services they provide. It uses the cost of service provision as the primary measure of service use.</p>
    <p>The tool is capable of:</p>
    <ul>
      <li>processing data sets that contain financial information about the users of services;</li>
      <li>matching individuals across the different data sets to produce a "client index", which is used to calculate all costs associated with those clients;</li>
      <li>producing three types of reports about service use, which can be filtered in different ways to aid analysis.</li>
    </ul>
    <p>The objective of the tool is to facilitate those working in local authorities and other public services to be able to analyse financial information and to gain an understanding of the pattern of service use of an individual, or group of individuals, beyond one particular service.</p>
@endsection
