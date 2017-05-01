@extends('layouts/app')

@section('title')
    Help
@endsection

@section('content')
<p>The Cost Calculator is a web-based intelligence tool designed to support local authorities in analysing the use of the services they provide. It uses the cost of service provision as the primary measure of service use.</p>
<p><a href="/userguide.pdf">Download the detailed User Guide</a></p>
<p>The tool has three main features:</p>
<ul>
  <li>The ability to upload cost data</li>
  <li>The ability to query the uploaded data to produce reports</li>
  <li>And a client look up function that allows the user to see details about an individual that can help to identify them</li>
</ul>
<p>The tool allows the user to upload and import standard Microsoft Excel files containing data. Each data file needs to be associated with one of the file types already set up in the tool. Each of these file types is associated with one of the included data schemas and its accompanying import script.</p>
<p>In order for the import process to work the structure of each data file needs to conform to the relevant data schemas. These schemas define what columns need to be included in the file and the format of the data in each column. Full definitions for these schemas <a href="https://github.com/LBHounslow/cost-calc-schema">can be found online</a>.</p>
<p>The three types of included report are:</p>
<ul>
  <li>Total Spend by Client</li>
  <li>Breakdown of Spend by Client</li>
  <li>Total Spend by Service</li>
</ul>
<p>These reports can have the following filters applied to them:</p>
<ul>
  <li>The date range covered by the report</li>
  <li>The type of services to be included in the output</li>
</ul>
<p>In addition, an advanced filter can be used to change the pool of clients that the reports are looking at. These filters can be used to include or exclude clients based on whether they have ever received the specified service or group of services.</p>
<p>The tool also has a number of settings that a user can use to configure different aspects of the tools operation, and some logs that monitor activity as the tool is being used.</p>
<p>Access to the tool is controlled by authentication against user accounts. Users need an email address and password in order to login to the tool. Access to the different elements of the tool is also controlled by setting permissions for each user account. So not every user will be able to see all the features of the tool.</p>
<p>Full technical documentation for the tool is <a href="https://github.com/LBHounslow/cost-calc/wiki">available online</a>.</p>
@stop
