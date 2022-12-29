<?php
$pagename="qoute";
?>
@include('layouts.header')


<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto ml-2">
        List of Qoutes
    </h2>

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
                    Appointment ID*</th>

                <th class="border-b-2  whitespace-no-wrap">
                    Appointment Title*</th>

                <th class="border-b-2  whitespace-no-wrap">
                    User Name*</th>

                <th class="border-b-2  whitespace-no-wrap">
                     Date*</th>

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
                    {{ $value->start_date }}
                </td>
               
                <td>
                    <select name="status" onchange="changeStatus({{$value->id}},this.value)" class=" border-0 " id=""
                        class="category" aria-label value="">
                        <option value="0" name="status_value" <?php echo ( $value->status ==
                            0)?'selected' : ""; ?> >Not Approved</option>

                        <option value="1" <?php echo ( $value->status == 1) ? 'selected' : "" ; ?>
                            >Approved
                        </option>
                        <option value="2" <?php echo ( $value->status == 2) ? 'selected' : "" ; ?>
                            >Reject
                        </option>
                    </select>
                </td>
                <td>
                    <a class="flex items-center text-theme-1 mr-3  text-primary"
                        href="{{route('qoute_details',['id' => $value->id])}}">
                        <i data-feather="eye" class="w-4 h-4 mr-1"></i> view </a>
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
            <form type="submit" action="{{ route('delete_user') }}" method="post">
                @csrf
                @method('DELETE')
                <input type="hidden" name="delete_user_id" id="deleting_id"></input>
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
        var user_id = $(this).val();
        $('#deleting_id').val(user_id);
    });

    function changeStatus(id, val) {
        console.log(id);
        console.log(val);
        $.ajax({
            url: "{{route('status')}}",
            data: {
                id: id,
                val: val
            },
            success: function (result) {
                swal({
                    title: "Status Changed",
                    text: "You have Successfully Change this Status",
                    icon: "success",
                    button: "OK",
                });
            }
        });
    }

</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@include('layouts.footer')