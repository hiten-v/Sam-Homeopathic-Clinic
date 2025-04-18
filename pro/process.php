<?php 
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sam Homeopathic Clinic</title>
    <link rel="stylesheet" href="../src/output.css">
    <!-- <link rel="stylesheet" href="../src/output.css?v=<?php //echo time(); ?>"> -->
</head>
<body class="bg-[#DCD7C9]">

     <!-- navabar -->
     <header class="bg-[#3F4F44] sticky z-50 top-0 w-full transition-all ease-in transition-duration-1000">
        <div class="flex max-md:flex-col gap-5 max-md:relative">
            <div class="flex">
                <a href="../pro/main.php" id="logo" class="mr-auto flex justify-center items-center">
                    <img class="h-10 m-7" src="../images/shclogo.png">
                </a>
                <button id="menubtn" class="rounded-xl ml-auto transition-all ease-in m-7 text-[#DCD7C9] duration-800 hidden opacity-0 max-md:block max-md:opacity-100" onclick="column_nav_disp()">
                    <!-- <img class="h-8 m-7" src="../images/menu_light.svg"> -->
                    <span class="material-symbols-outlined-nav">
                        menu
                    </span>
                </button>
                <button id="closebtn" class="rounded-xl ml-auto transition-all ease-in m-7 text-[#DCD7C9] duration-800 hidden opacity-0" onclick="column_nav_hide()">
                    <!-- <img class="h-8 m-7" src="../images/close_light.svg"> -->
                    <span class="material-symbols-outlined-nav">
                        close
                    </span>
                </button>
            </div>
            <div id="nav_row"
            class=" max-md:bg-[#3F4F44]/92 max-md:backdrop-blur-4xl flex justify-center items-center gap-3 max-md:text-2xl max-md:gap-1 ml-auto text-[#e0ddd6]
                    max-md:flex-col max-md:fixed max-md:top-24 max-md:left-0 max-md:h-[calc(100vh-5rem)] max-md:w-full
                    max-md:opacity-0 max-md:pointer-events-none max-md:transition-all max-md:duration-500 max-md:z-40">

                <a href="../pro/main.php#about" class="rounded-lg text-center font-semibold p-2 transition-all ease-in duration-200 hover:bg-[#DCD7C9] hover:text-[#3F4F44] hover:shadow-md">About</a>
                <a href="../pro/process.php" class="rounded-lg text-center font-semibold p-2 transition-all ease-in duration-200 hover:bg-[#DCD7C9] hover:text-[#3F4F44] hover:shadow-md">Process & Charges</a>
                <a href="../pro/contactUs.php" class="rounded-lg text-center font-semibold p-2 transition-all ease-in duration-200 hover:bg-[#DCD7C9] hover:text-[#3F4F44] hover:shadow-md">Contact US</a>
                <a href="../pro/user_dashboard.php" class="rounded-lg text-center font-semibold p-2 mr-5 max-md:mr-0 transition-all ease-in duration-200 hover:bg-[#DCD7C9] hover:text-[#3F4F44] hover:shadow-md">Your Appontments</a>
            </div>
        </div>
    </header>


    <section id="process" class="bg-[url(../images/process_bg.webp)] bg-cover bg-no-repeat bg-center">
    
        <div class="flex flex-col gap-5 p-20 justify-center items-center">
            <div class="flex flex-col gap-10 rounded-xl p-20 max-lg:p-10 backdrop-blur-xs bg-[#e7e3d8]/30 ring-2 ring-[#e7e3d8] shadow-lg shadow-slate-500 w-full max-w-4xl">
    

                <h1 class="text-5xl font-bold text-[#2C3930] underline underline-offset-4 decoration-green-800 text-center">Process</h1>
    
                <!-- Ordered list -->
                <ol class="flex flex-col gap-10 text-2xl max-lg:text-xl max-sm:text-lg rounded-xl list-decimal list-inside text-[#061f0e] marker:text-green-900 marker:font-bold reveal-list">
                    <li style="--i: 1">The patient first login/register's into the website for booking an appointment</li>
                    <li style="--i: 2">While Booking an appointment, the patient has to fill necessary initial details which includes Chief Problem,
                        appointment mode - online/offline , previous reports/investigations (if any), past medical history and
                        family medical history (if any).
                    </li>
                    <li style="--i: 3">The reports must be uploaded in PDF,DOC,JPG,PNG format for Max upto 5MB. If there are more 
                        than 1 documents they must be compiled in pdf or doc format.
                    </li>
                    <li style="--i: 4">After booking the appointment the doctor will confirm it by allocating a date, time and meeting id(if online mode) within a day.</li>
                    
                    <li style="--i: 5">The patient has the option to cancel the appointment only before doctor has confirmed it.</li>
                    <li style="--i: 6">The first consulation will include a 1 to 2 hr long session for recording the patient's case history along with mental, emotional, spiritual details
                        of the personality.
                    </li>
                    <li style="--i: 7">After the first consultation, the patient will be given medicine for a particular amount of time set by the doctor which he has to take from the clinic in any manner on 
                        the set time and date based on mutual convinience (if online mode).
                    </li>
                    <li style="--i: 8">The charges are 300/- Rs. for first consultation and for the medicine it varies as per the patient's disease.
                    </li>
                </ol>
    
            </div>
        </div>
    </section>
    




    <footer class="flex max-md:flex-col justify-center gap-5 p-5 max-sm:p-1 items-center bg-[#3F4F44]">
        <div class="max-md:mr-0 mr-auto">
            <a href="../pro/main.php" id="logo" class="p-3 m-5 flex justify-center items-center">
                <img class="h-30 max-lg:h-25 max-sm:h-20 max-[440px]:h-15 max-[355px]:h-12" src="../images/shclogo.png">
            </a>
        </div>

        <div class="rounded-lg text-center text-[#DCD7C9] p-1 shadow-[#DCD7C9] hover:shadow flex max-[920px]:flex-col max-md:flex-row gap-2 justify-center items-center transition-all ease-in duration-300">
            <p class="text-center">© 2025</p>
            <p class="text-center">Developed by</p>
            <p class="text-center">Hiten Vaid</p>
        </div>
        <div class="bg-[#3F4F44] m-5 max-md:ml-0 ml-auto flex max-md:flex-row flex-col justify-center items-center gap-1 max-md:gap-5 max-sm:flex-col max-sm:gap-1 text-[#DCD7C9] transition-all ease-in duration-1000">
            <a href="../pro/main.php#about" class="rounded-lg text-center font-semibold p-2 transition-all ease-in duration-200 hover:ring-2 hover:ring-[#DCD7C9]">About</a>
            <a href="../pro/process.php" class="rounded-lg text-center font-semibold p-2 transition-all ease-in duration-200 hover:ring-2 hover:ring-[#DCD7C9]">Process & Charges</a>
            <a href="../pro/contactUs.php" class="rounded-lg text-center font-semibold p-2 transition-all ease-in duration-200 hover:ring-2 hover:ring-[#DCD7C9]">Contact US</a>
            <a href="../pro/user_dashboard.php" class="rounded-lg text-center font-semibold p-2 transition-all ease-in duration-200 hover:ring-2 hover:ring-[#DCD7C9]">Your Appontments</a>
        </div>
    </footer>
    <script src="../pro/process.js"></script>
</body>
</html>