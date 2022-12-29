<?php
$pagename="user_appointment";
?>
@include('layouts.header')


<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto ml-2">
        List of Appointments Added By Admin
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <button class="button text-white bg-theme-42 shadow-md mr-2"><a href="{{route('user_appointment_show')}}">Add New
                Appointment</a></button>
    </div>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">

    </div>
</div>
<!-- BEGIN: Datatable -->
<div class="intro-y datatable-wrapper box p-5 mt-5">
    <table class="table table-report table-report--bordered display datatable w-full">
        <thead>
            <tr>
                <th class="border-b-2  whitespace-no-wrap">
                    Sr.</th>

                <th class="border-b-2  whitespace-no-wrap">
                    Title*</th>

                <th class="border-b-2  whitespace-no-wrap">
                    Description*</th>
                    
                <th class="border-b-2  whitespace-no-wrap">
                    Address*</th>

                <th class="border-b-2  whitespace-no-wrap">
                    Date*</th>

                <th class="border-b-2  whitespace-no-wrap">
                    Time*</th>

                <th class="border-b-2  whitespace-no-wrap">
                    Employee Name*</th>
                    
                <th class="border-b-2  whitespace-no-wrap">
                    Appointment Status*</th>


                <th class="border-b-2  whitespace-no-wrap">
                    Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php  $i = 0; ?>
            @foreach($user_appointments as $value)
            <?php $i++; ?>
            <tr>
                <th scope="row">{{ $i }}</th>
                <td>
                    {{ $value->title }}
                </td>
                <td>
                    {{ $value->description }}
                </td>
                <td>
                    {{ $value->address }}
                </td>
                <td>
                    {{ $value->date }}
                </td>
                <td>
                    {{ $value->time }}
                </td>
                <td>
                    {{ $value->name }}
                </td>
                <td>
                    <?php if(($value->status) == 0) {
                        echo 'Unattended';
                    }else if(($value->status) == 1){
                        echo 'Scheduled';
                    }else if(($value->status) == 2){
                        echo 'Qouted';
                    }else if(($value->status) == 3){
                        echo 'Approved';
                    }else if(($value->status) == 4){
                        echo 'Completed';
                    }  ?>
                </td>
        
                <td>
                    <a class="flex items-center text-theme-1 mr-1"
                        href="{{route('edit_appointment' , ['id' =>  $value->id])}}"><i data-feather="edit"
                            class="w-4 h-4 mr-1"></i>
                        Edit </a>
                    <button style="border:none;" type="button" value="{{ $value->id }}" class="deletebtn btn"><a
                            class=" flex items-center text-theme-6" href="javascript:;" data-toggle="modal"
                            data-target="#delete-modal-preview"> <i data-feather="trash-2" class="w-4 h-4 mr-1"></i>
                            Delete </a>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- END: Datatable -->
<div class="modal" id="delete-modal-preview">
    <div class="modal__content">
        <div class="p-5 text-center">
            <i data-feather="x-circle" class="w-16 h-16 text-theme-6 mx-auto mt-3"></i>
            <div class="text-3xl mt-5">Are you sure?</div>
            <div class="text-gray-600 mt-2">Do you really want to delete these records? This process cannot be
                undone.
            </div>
        </div>
        <div class="px-5 pb-8 text-center">
            <form type="submit" action="{{ route('delete_appointment') }}" method="post">
                @csrf
                @method('DELETE')
                <input type="hidden" name="delete_appointment_id" id="deleting_id"></input>
                <button type="button" data-dismiss="modal" class="button w-24 border text-gray-700 mr-1">Cancel</button>
                <button type="submit" class="button w-24 bg-theme-6 text-white p-3 pl-5 pr-5">Delete</button>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });

    $(document).on('click', '.deletebtn', function () {
        var appointment_id = $(this).val();
        $('#deleting_id').val(appointment_id);
    });

</script>
@include('layouts.footer')