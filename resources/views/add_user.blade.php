<?php
$pagename="users";
?>
@include('layouts.header')
<style>
    /* body {
  background-color: #efefef;
} */

.profile-pic {
    width: 200px;
    max-height: 200px;
    display: inline-block;
}

 .file-upload {
    display: none;
}
.circle {
    border-radius: 100% !important;
    overflow: hidden;
    width: 128px;
    height: 128px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    /* position: absolute;
    top: 72px; */
}

img {
    max-width: 100%;
    height: auto;
}
.p-image {
  /* position: absolute;
  top: 167px;
  right: 30px; */
  color: #666666;
  transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
}
.p-image:hover {
  transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
}
.upload-button {
  font-size: 1.2em;
}

.upload-button:hover {
  transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
  color: #999;
} 
</style> 

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
            <div class="mb-2">Profile Picture <strong>(Optional)</strong></div>  
            <div class="small-12 medium-2 large-2 columns">
                <div class="circle">
                    
                  <img class="profile-pic" src="{{ isset($record->profile_img)?asset('public/storage/'.$record->profile_img): 'https://t3.ftcdn.net/jpg/03/46/83/96/360_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg'  }}">
           
                </div>
                <div class="p-image">
                  <i class="fa fa-camera upload-button"></i>
                   <input class="file-upload" type="file" name="profile_img" accept="image/*"/>
                </div>
             </div>

                <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                    <input type="hidden" class="form-control" id="query_id" name="user_id"
                        value="{{ isset($record->id)?$record->id: ''  }}">
                    <div class="intro-y col-span-12 sm:col-span-12 px-2">
                        <div class="mb-2">User Name*</div>
                        <input type="text" name="name" class="input w-full border flex-1"
                            value="{{ isset($record->name)?$record->name: ''  }}"
                            placeholder="Enter the name..." required>
                        <span class="text-theme-6">
                            @error('name')
                            {{'User Name is required'}}
                            @enderror
                        </span>
                    </div>

                    <div class="intro-y col-span-12 sm:col-span-12 px-2">
                        <div class="mb-2">Email*</div>
                        <input type="email" name="email" class="input w-full border flex-1" size="59"
                            value="{{ isset($record->email)?$record->email: ''  }}" placeholder="Enter Email..."
                            required>
                        <span class="text-theme-6">
                            @error('email')
                            {{$message}}
                            @enderror
                        </span>
                    </div>

                    <div class="intro-y col-span-12 sm:col-span-12 px-2">
                        <div class="mb-2">Phone Number*</div>
                        <input type="text" name="phone" class="input w-full border flex-1"
                            value="{{ isset($record->phone)?$record->phone: ''  }}"
                            placeholder="Enter Number..." required>
                        <span class="text-theme-6">
                            @error('phone')
                            {{'User phone is required'}}
                            @enderror
                        </span>
                    </div>
             
                    <div class="intro-y col-span-12 sm:col-span-6 px-2">
                        <div class="mb-2">Password*</div>
                        <input type="password" name="password" id="password" class="input  border flex-1" size="59"
                            value="{{ isset($record->password)?$record->password: ''  }}" placeholder="Enter Password..."
                            required>
                            <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                        <span class="text-theme-6">
                            @error('password')
                            {{$message}}
                            @enderror
                        </span>
                    </div>
                    <script>
                        $(".toggle-password").click(function() {

                            $(this).toggleClass("fa-eye fa-eye-slash");
                           var input = $('#password');
                            if (input.attr("type") == "password") {
                            input.attr("type", "text");
                            } else {
                            input.attr("type", "password");
                            }
                        });
                    </script>

                    <div class="intro-y col-span-12 items-center justify-center sm:justify-end mt-5">
                        <button class="button w-full justify-center block bg-theme-1 text-white ml-2">{{ $text }}</button>
                    </div>
                </div>
        </form>
    </div>
</div>


<script>
$(document).ready(function() {

    
var readURL = function(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.profile-pic').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}


$(".file-upload").on('change', function(){
    readURL(this);
});

$(".upload-button").on('click', function() {
   $(".file-upload").click();
});
});
</script>


@include('layouts.footer')