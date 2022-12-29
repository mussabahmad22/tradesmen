<?php
$pagename="complete_appointment";
?>
@include('layouts.header')




<div class="intro-y flex items-center mt-8">

    <h2 class="text-lg font-medium ml-3">
        Complete Appointment Details
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">

    </div>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">

    </div>
</div>

<div class="intro-y box pb-10">
    <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
        <form action="" class="validate-form" method="post" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                <div class="intro-y col-span-12 sm:col-span-12 px-2">
                    <div class="mb-2">Appointment Title</div>
                    <input type="text" name="title" class="input w-full border flex-1"
                        value="{{ $users_appointment->title  }}" disabled>
                </div>
                <div class="intro-y col-span-12 sm:col-span-12 px-2">
                    <div class="mb-2">Appointment Description</div>
                    <textarea type="text" name="description" class="input w-full border flex-1"
                        placeholder="Enter Description..." disabled>{{ $users_appointment->description  }}</textarea>
                </div>
                <div class="intro-y col-span-12 sm:col-span-6 px-2">
                    <div class="mb-2">Address</div>
                    <input type="text" name="address" class="input w-full border flex-1"
                        value="{{ $users_appointment->address  }}" disabled>
                </div>

                <div class="intro-y col-span-6 sm:col-span-6 px-2">
                    <div class="mb-2">Date</div>
                    <input type="date" name="date" class="input w-full border flex-1"
                        value="{{ $user_name->start_date  }}" disabled>
                </div>
                <div class="intro-y col-span-6 sm:col-span-6 px-2">
                    <div class="mb-2">Employee Name</div>
                    <input type="text" name="time" class="input w-full border flex-1" value="{{ $user_name->name  }}"
                        disabled>
                </div>

                <div class="intro-y col-span-12 sm:col-span-12 px-2">
                    <div class="mb-2">Comment</div>
                    <textarea type="text" name="description" class="input w-full border flex-1"
                        placeholder="Enter Description..." disabled>{{ $users_appointment->comment  }}</textarea>
                </div>
            </div>
            {{-- <td class="w-40">
                <div class="flex">
                    <div class="w-10 h-10 image-fit zoom-in">
                        <img alt="Midone - HTML Admin Template" class="tooltip rounded-full"
                            src="http://rubick.left4code.com/dist/images/preview-4.jpg">
                    </div>
                    <div class="w-10 h-10 image-fit zoom-in -ml-5">
                        <img alt="Midone - HTML Admin Template" class="tooltip rounded-full"
                            src="http://rubick.left4code.com/dist/images/preview-5.jpg">
                    </div>
                    <div class="w-10 h-10 image-fit zoom-in -ml-5">
                        <img alt="Midone - HTML Admin Template" class="tooltip rounded-full"
                            src="http://rubick.left4code.com/dist/images/preview-15.jpg">
                    </div>
                </div>
            </td> --}}
            <br>
            <div class="intro-y col-span-12 sm:col-span-12 px-2">
                <div class="mb-2">Images Before Appointment</div>
                <td class="w-60">
                    <div class="flex">
                        @foreach($before as $detail)
                        <div class="w-10 h-10 image-fit zoom-in ">
                            <a class="example-image-link" href="{{asset('public/storage/'. $detail->images)}}"
                                data-lightbox="example-1">
                                <img alt="Midone - HTML Admin Template" class=" tooltip rounded-full"
                                    src="{{asset('public/storage/'. $detail->images)}}">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </td>
            </div>
            <br></br>
            <div class="intro-y col-span-12 sm:col-span-12 px-2">
                <div class="mb-2">Images After Appointment</div>
                <td class="w-60">
                    <div class="flex">
                        @foreach($after as $detail)
                        <div class="w-10 h-10 image-fit zoom-in ">
                            <a class="example-image-link" href="{{asset('public/storage/'. $detail->images)}}"
                                data-lightbox="example-2" data-title="Optional caption.">
                                <img alt="Midone - HTML Admin Template" class="tooltip rounded-full"
                                    src="{{asset('public/storage/'. $detail->images)}}">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </td>
            </div>
            <div>

            </div>
        </form>
    </div>
    <script src="{{asset('assets/dist/lightbox/js/lightbox.js')}}"></script>
    <script>
        lightbox.option({
            'resizeDuration': 100,
            'wrapAround': true
        })
    </script>

    @include('layouts.footer')