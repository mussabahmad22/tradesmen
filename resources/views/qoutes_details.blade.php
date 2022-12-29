<?php
$pagename="qoute";
?>
@include('layouts.header')


<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto ml-2">
        Qoutes Details
    </h2>

    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <strong>Total Cost </strong> &nbsp;&nbsp; <p> ${{ $total_cost }} </p>
    </div>
    &nbsp;&nbsp; &nbsp;&nbsp;
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <strong>Total Hours </strong> &nbsp;&nbsp; <p> {{ $hours }}</p>
    </div>
</div>
<!-- BEGIN: Datatable -->
<div class="intro-y datatable-wrapper box p-5 mt-5">
    <h2 class="text-lg font-medium mr-auto ml-2">
        <strong>Address</strong> <p>{{ $qoutes->address}} <p>
    </h2> <br>
    <h2 class="text-lg font-medium mr-auto ml-2">
        <strong>Date</strong> <p>{{ $qoutes->start_date}} <p>
    </h2>
  
</div>
@foreach($qoutes_details as $list)
    <div class="intro-y datatable-wrapper box p-5 mt-5">
    <p><strong>Job Description </strong>  &nbsp;&nbsp; {{ $list->job_desc }}</p>
    <p><strong>Material Cost</strong> &nbsp;&nbsp; ${{ $list->material_cost }}</p>
    <p><strong>Hours</strong> &nbsp;&nbsp; {{ $list->hours }}</p>
    </div>
@endforeach

@include('layouts.footer')