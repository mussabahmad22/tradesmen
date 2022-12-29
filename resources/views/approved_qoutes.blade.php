<?php
$pagename="approved_qoute";
?>
@include('layouts.header')


<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto ml-2">
        List of Approved Qoutes
    </h2>

    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">

    </div>
</div>

<!-- END: Datatable -->
<!-- BEGIN: Datatable -->
<div class="intro-y datatable-wrapper box p-5 mt-5">
    <table class="table table-report table-report--bordered display datatable w-full">
        <thead>
            <tr>
                <th class="border-b-2  whitespace-no-wrap">
                    Sr.</th>

                <th class="border-b-2  whitespace-no-wrap">
                    Appointment ID*</th>

                <th class="border-b-2  whitespace-no-wrap">
                    Appointment Title*</th>

                <th class="border-b-2  whitespace-no-wrap">
                    User Name*</th>

                    <th class="border-b-2  whitespace-no-wrap">
                        Status*</th>

                <th class="border-b-2  whitespace-no-wrap">
                    Qoutes Details*</th>

            </tr>
        </thead>
        <tbody>
            <?php  $i = 0; ?>
            @foreach($qoute as $value)
            <?php $i++; ?>
            <tr>
                <th scope="row">{{ $i }}</th>

                <td>
                    {{ $value->appointment_id }}
                </td>
                <td>
                    {{ $value->title }}
                </td>
                <td>
                    {{ $value->name }}
                </td>
                <td>
                    Approved
                </td>
                <td>
                    <a class="flex items-center text-theme-1 mr-3  text-primary"
                        href="{{route('approved_qoute_details',['id' => $value->id])}}">
                        <i data-feather="eye" class="w-4 h-4 mr-1"></i> view </a>
                </td>
             
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });

    $(document).on('click', '.deletebtn', function () {
        var user_id = $(this).val();
        $('#deleting_id').val(user_id);
    });

</script>
@include('layouts.footer')