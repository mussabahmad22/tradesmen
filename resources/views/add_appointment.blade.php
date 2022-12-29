<?php
$pagename="user_appointment";
?>
@include('layouts.header')


<div class="intro-y flex items-center mt-8">

    <h2 class="text-lg font-medium ml-3">
    {{ $title }}
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
    
    </div>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">

    </div>
</div>

<div class="intro-y box pb-10">
    <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
        <form action="{{ $url }}" class="validate-form" method="post" enctype="multipart/form-data">
            @csrf
     
                <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                    <input type="hidden" class="form-control" id="query_id" name="user_id"
                        value="{{ isset($record->id)?$record->id: ''  }}">
                    <div class="intro-y col-span-12 sm:col-span-12 px-2">
                        <div class="mb-2">Appointment Title</div>
                        <input type="text" name="title" class="input w-full border flex-1"
                            value="{{ isset($record->title)?$record->title: ''  }}"
                            placeholder="Enter the name..." required>
                        <span class="text-theme-6"> 
                            @error('title')
                            {{'Appointment title is required'}}
                            @enderror
                        </span>
                    </div>

                    <div class="intro-y col-span-12 sm:col-span-12 px-2">
                        <div class="mb-2">Appointment Description</div>
                        <textarea type="text" name="description" class="input w-full border flex-1" 
                             placeholder="Enter Description..."
                            required>{{ isset($record->description)?$record->description: ''  }}</textarea>
                        <span class="text-theme-6">
                            @error('description')
                            {{$message}}
                            @enderror
                        </span>
                    </div>

                    <div class="intro-y col-span-12 sm:col-span-6 px-2">
                        <div class="mb-2">Address</div>
                        <input type="text" name="address" class="input w-full border flex-1"
                            value="{{ isset($record->address)?$record->address: ''  }}"
                            placeholder="Enter Address..." required>
                        <span class="text-theme-6">
                            @error('address')
                            {{'Agent phone is required'}}
                            @enderror
                        </span>
                    </div>
           
                    <div class="intro-y col-span-6 sm:col-span-6 px-2">
                        <td>
                            <div class="mb-2">Employee</div>
                            <select name="user_id" id="" class="input w-full border flex-1" aria-label>
                                <option disabled selected>Select Employee</option>
                                @foreach($users as $user)

                                <option value="{{ $user->id }}" <?php if(isset($record)) if($record->user_id == $user->id) { echo 'selected'; } ?>
                                    >{{ $user->name}}</option>

                                @endforeach
                            </select>
                            <span class="text-theme-6">
                                @error('cat_id')
                                {{ $message }}
                                @enderror
                            </span>
                        </td>
                    </div>

                    <div class="intro-y col-span-12 items-center justify-center sm:justify-end mt-5">
                        <button class="button w-full justify-center block bg-theme-1 text-white ml-2">{{ $text }}</button>
                    </div>
                </div>
        </form>
    </div>
</div>


<script>

</script>


@include('layouts.footer')