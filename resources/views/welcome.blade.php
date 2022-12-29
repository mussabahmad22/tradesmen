
<!DOCTYPE html>

<html lang="en">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="{{asset('assets/dist/logo.png')}}" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Code Coy">
        <meta name="keywords" content="Code Coy">
        <meta name="author" content="LEFT4CODE">
        <title>Tradesmen | App</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="{{asset('assets/dist/css/app.css')}}" />
        <!-- END: CSS Assets-->
    </head>
    <!-- END: Head -->
    <body class="login">
        <div class="container sm:px-10">
            <div class="block xl:grid grid-cols-2 gap-4">
                <!-- BEGIN: Login Info -->
                <div class="hidden xl:flex flex-col min-h-screen">
                    <a href="" class="-intro-x flex items-center pt-5">
                        <!--<img alt="EF Network ADMIN" class="img-fluid" src="dist/logof.png">-->
                      
                    </a>
                </div>
                <!-- END: Login Info -->
                <!-- BEGIN: Login Form -->
                <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0" style="    margin-left: -150px;">
                    <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto" action="" method="post">
                        <img alt="EF Network ADMIN" class="-intro-x w-1/2 -mt-16" src="{{asset('assets/dist/logo.png')}}" style="margin-left: -160px !important;
                        height: 220px !important;
                        width: 600px !important;">
                    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                            Welcome
                        </h2>
                      
                        <div class="intro-x flex text-gray-700 dark:text-gray-600 text-xs sm:text-sm mt-4">
                           
                             
                        </div>
                        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                          <a href="{{ route('dashboard') }}">  <button onclick="window.location.href=" name="login_user" class="button button--lg w-32 xl:w-32 text-white bg-dark-2 xl:mr-3" style="margin-left: -90px !important;">Proceed</button> </a>
                            
                        </div>
                        <div class="intro-x mt-10 xl:mt-24 text-gray-700 dark:text-gray-600 text-center xl:text-left">
                           
                        </div>
                    </div>
                </div>
                <!-- END: Login Form -->
            </div>
        </div>
        <!-- BEGIN: Dark Mode Switcher-->
       
        <!-- END: Dark Mode Switcher-->
        <!-- BEGIN: JS Assets-->
        <script src="{{asset('assets/dist/js/app.js')}}"></script>
        <!-- END: JS Assets-->
    </body>
</html>