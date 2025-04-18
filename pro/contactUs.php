<?php
    $nameErr=$subErr=$msgErr=$emailErr="";
    function sanitize($data)
    {
        $data=trim($data);
        $data=stripslashes($data);
        $data=htmlspecialchars($data);
        return $data;
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        if (isset($_POST['submit'])) 
        {
            $name = sanitize($_POST['name']);
            $email = sanitize($_POST['email']);
            $subject = sanitize($_POST['subject']);
            $message = sanitize($_POST['message']);
            $hasError = false;
            if (empty($name)) 
            {
                $nameErr = 'Name field cannot be empty';
                $hasError = true;
            }
            else
            {
                if (!preg_match("/^[a-zA-Z ]+$/", $name))
                {
                    $nameErr = 'Name can only have alphabets';
                    $hasError = true;
                }
            }
            if (empty($email)) 
            {
                $emailErr = 'Email field cannot be empty';
                $hasError = true;
            }
            else
            {
                $email=filter_input(INPUT_POST,"email",FILTER_VALIDATE_EMAIL); 
                if(empty($email))
                {
                    $emailErr = 'Email id cannot be like this';
                    $hasError = true;
                }
            }
            if (empty($subject)) 
            {
                $subErr = 'Subject field cannot be empty';
                $hasError = true;
            }
            if (empty($message)) 
            {
                $msgErr = 'Message field cannot be empty';
                $hasError = true;
            }
            if ($hasError==false) 
            {
                $to = "samhomeopethicclinic@gmail.com"; // YOUR email address
                $headers = "From: $email\r\n";
                $headers .= "Reply-To: $email\r\n";
                $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
                $body = "You received a new message from your website contact form:\n\n".
                        "Name: $name\n".
                        "Email: $email\n".
                        "Subject: $subject\n\n".
                        "Message:\n$message";
        
                if (mail($to, $subject, $body, $headers)) 
                {
                    echo "<script>alert('Message sent successfully.'); window.location.href = '../pro/contactUs.php'; </script>";
                } 
                else 
                {
                    echo "<script>alert('Message could not be sent. Please try again later.'); window.location.href = '../pro/contactUs.php'; </script>";
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sam Homeopathic Clinic</title>
    <link rel="stylesheet" href="../src/output.css">
    <!-- <link rel="stylesheet" href="../src/output.css?v=<?php //echo time(); ?>"> -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body
        {
            background-color: #DCD7C9;
            background-attachment: fixed;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M54.627 0l.83.828-1.415 1.415L51.8 0h2.827zM5.373 0l-.83.828L5.96 2.243 8.2 0H5.374zM48.97 0l3.657 3.657-1.414 1.414L46.143 0h2.828zM11.03 0L7.372 3.657 8.787 5.07 13.857 0H11.03zm32.284 0L49.8 6.485 48.384 7.9l-7.9-7.9h2.83zM16.686 0L10.2 6.485 11.616 7.9l7.9-7.9h-2.83zm20.97 0l9.315 9.314-1.414 1.414L34.828 0h2.83zM22.344 0L13.03 9.314l1.414 1.414L25.172 0h-2.83zM32 0l12.142 12.142-1.414 1.414L30 .828 17.272 13.556l-1.414-1.414L28 0h4zM.284 0l28 28-1.414 1.414L0 2.544V0h.284zM0 5.373l25.456 25.455-1.414 1.415L0 8.2V5.374zm0 5.656l22.627 22.627-1.414 1.414L0 13.86v-2.83zm0 5.656l19.8 19.8-1.415 1.413L0 19.514v-2.83zm0 5.657l16.97 16.97-1.414 1.415L0 25.172v-2.83zM0 28l14.142 14.142-1.414 1.414L0 30.828V28zm0 5.657L11.314 44.97 9.9 46.386l-9.9-9.9v-2.828zm0 5.657L8.485 47.8 7.07 49.212 0 42.143v-2.83zm0 5.657l5.657 5.657-1.414 1.415L0 47.8v-2.83zm0 5.657l2.828 2.83-1.414 1.413L0 53.456v-2.83zM54.627 60L30 35.373 5.373 60H8.2L30 38.2 51.8 60h2.827zm-5.656 0L30 41.03 11.03 60h2.828L30 43.858 46.142 60h2.83zm-5.656 0L30 46.686 16.686 60h2.83L30 49.515 40.485 60h2.83zm-5.657 0L30 52.343 22.343 60h2.83L30 55.172 34.828 60h2.83zM32 60l-2-2-2 2h4zM59.716 0l-28 28 1.414 1.414L60 2.544V0h-.284zM60 5.373L34.544 30.828l1.414 1.415L60 8.2V5.374zm0 5.656L37.373 33.656l1.414 1.414L60 13.86v-2.83zm0 5.656l-19.8 19.8 1.415 1.413L60 19.514v-2.83zm0 5.657l-16.97 16.97 1.414 1.415L60 25.172v-2.83zM60 28L45.858 42.142l1.414 1.414L60 30.828V28zm0 5.657L48.686 44.97l1.415 1.415 9.9-9.9v-2.828zm0 5.657L51.515 47.8l1.414 1.413 7.07-7.07v-2.83zm0 5.657l-5.657 5.657 1.414 1.415L60 47.8v-2.83zm0 5.657l-2.828 2.83 1.414 1.413L60 53.456v-2.83zM39.9 16.385l1.414-1.414L30 3.658 18.686 14.97l1.415 1.415 9.9-9.9 9.9 9.9zm-2.83 2.828l1.415-1.414L30 9.313 21.515 17.8l1.414 1.413 7.07-7.07 7.07 7.07zm-2.827 2.83l1.414-1.416L30 14.97l-5.657 5.657 1.414 1.415L30 17.8l4.243 4.242zm-2.83 2.827l1.415-1.414L30 20.626l-2.828 2.83 1.414 1.414L30 23.456l1.414 1.414zM56.87 59.414L58.284 58 30 29.716 1.716 58l1.414 1.414L30 32.544l26.87 26.87z' fill='%23d1b49c' fill-opacity='0.3' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        .shad
        {
            text-shadow: 0.2rem 0.2rem 1rem #3d4e42b7;
            color: #3d4e42b7;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-[#DCD7C9] scroll-smooth">
     <!-- navabar -->
     <header class="bg-[#3F4F44] sticky z-50 top-0 w-full transition-all ease-in transition-duration-1000">
        <div class="flex max-md:flex-col gap-5">
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


    <section id="mail" class="flex flex-col gap-30 pt-20 p-10">
        <div class="flex max-sm:flex-col gap-10 justify-evenly items-center reveal-on-scroll text-[#3F4F44]">
            <div>
                <h1 class="text-5xl">CONTACT </h1>
            </div>
            <form action="../pro/contactUs.php" method="post" class="rounded-xl flex flex-col w-1/3 max-xl:w-4/10 max-lg:w-5/10 max-sm:w-7/9 max-[480px]:!w-[20rem] gap-5 max-sm:gap-7 p-10 max-sm:p-7 bg-[#3F4F44] text-[#DCD7C9] shadow-lg shadow-[#535f57]">
                <div class="flex flex-col gap-3">
                    <label for="name">Name: </label>
                    <input type="text" name="name" value="<?= isset($name) ? $name : '' ?>" id="name" class="rounded-lg bg-[#DCD7C9] text-[#3F4F44] focus:outline-none focus:shadow-inner focus:shadow-[#3F4F44]">
                    <span class="inline-block mx-5 text-red-400">
                        <?= $nameErr ?>
                    </span>
                </div>
                <div class="flex flex-col gap-3">
                    <label for="email">Your Email: </label>
                    <input type="text" name="email" value="<?= isset($email) ? $email : '' ?>" id="email" class="rounded-lg bg-[#DCD7C9] text-[#3F4F44] focus:outline-none focus:shadow-inner focus:shadow-[#3F4F44]">
                    <span class="inline-block mx-5 text-red-400">
                        <?= $emailErr ?>
                    </span>
                </div>
                <div class="flex flex-col gap-3">
                    <label for="subject">Subject :</label>
                    <input type="text" name="subject" value="<?= isset($subject) ? $subject : '' ?>" id="subject" class="rounded-lg bg-[#DCD7C9] text-[#3F4F44] focus:outline-none focus:shadow-inner focus:shadow-[#3F4F44]">
                    <span class="inline-block mx-5 text-red-400">
                        <?= $subErr ?>
                    </span>
                </div>
                <div class="flex flex-col gap-3">
                    <label for="email_body">Message :</label>
                    <textarea name="message" rows="5" value="<?= isset($message) ? $message : '' ?>" class="bg-[#DCD7C9] rounded-lg text-[#3F4F44] focus:outline-none focus:shadow-inner focus:shadow-[#3F4F44]"></textarea>
                    <span class="inline-block mx-5 text-red-400">
                        <?= $msgErr ?>
                    </span>
                </div>
                <button name="submit" class="bg-[#DCD7C9] rounded-lg text-[#3F4F44] p-2 transion ease-in duration-100 hover:bg-[#b8b3a7] focus:ring-2 focus:ring-[#DCD7C9] focus:ring-offset-2 focus:ring-offset-[#3F4F44]">Submit</button>
            </form>
        </div>


        <div class="flex max-sm:flex-col gap-10 mt-10 mb-10 justify-evenly items-center reveal-on-scroll">
            <div class="hidden max-sm:block">
                <h1 class="text-5xl text-[#3F4F44]">LOCATION</h1>
            </div>
            <div class="h-[30rem] w-full max-w-[50rem] max-sm:w-[25rem] max-[480px]:!w-[20rem] max-[480px]:h-[25rem] relative rounded-xl shadow-lg shadow-[#637a69] overflow-hidden">
                <div id="location" class="h-full w-full">
                </div>
            </div>
            <div class="max-sm:hidden">
                <h1 class="text-5xl text-[#3F4F44]">LOCATION</h1>
            </div>
        </div>


        <div class="flex max-md:flex-col gap-10 mb-20 justify-evenly items-center p-10 rounded-xl shadow-sm hover:shadow-lg transion ease-in duration-300 shadow-[#637a69] reveal-on-scroll">
            <div class="flex flex-col gap-5">
                <h1 class="text-5xl text-[#3F4F44] mb-4 text-center">ADDRESS</h1>
                <div class="shad flex flex-col text-center text-lg">
                    <p>51, Babbar Akali Market, Central Town</p>
                    <p>Phagwara, Punjab, India</p>
                </div>
            </div>
            <div class="flex flex-col gap-5">
                <h1 class="text-5xl text-[#3F4F44] text-center">CLINIC TIMINGS</h1>
                <div class="flex gap-8 text-lg max-[520px]:flex-col">
                    <div class="flex flex-col shad">
                        <p class="text-left max-md:text-center">MONDAY - FRIDAY</p>
                        <p class="text-left max-md:text-center">Morning : 11.00 A.M. to 1.00 P.M.</p>
                        <p class="text-left max-md:text-center">Evening : 5.30 P.M. to 7.00 P.M.</p>
                    </div>
                    <div class="flex flex-col shad">
                        <p class="text-left max-md:text-center">SATURDAY & SUNDAY</p>
                        <p class="text-left max-md:text-center">Closed</p>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    
    


    <footer class="flex max-md:flex-col justify-center gap-5 p-5 max-sm:p-1 items-center bg-[#3F4F44] reveal-on-scroll">
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
    <script src="../pro/contactUs.js"></script>
</body>
</html>