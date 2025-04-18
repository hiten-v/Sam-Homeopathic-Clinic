<?php
    session_start(); 
    $errors=[
        'login' => $_SESSION['login_error'] ?? '',
        'register' => $_SESSION['register_error'] ?? ''
    ];

    $activeForm=$_SESSION['active_form'] ?? 'login';
    $nameErr = $_SESSION['name_error'] ?? "";
    $emailErr = $_SESSION['email_error'] ?? "";
    $passErr = $_SESSION['pass_error'] ?? "";
    $loginEmailErr = $_SESSION['login_email_error'] ?? "";
    $loginPassErr = $_SESSION['login_pass_error'] ?? "";
    $register_success=$_SESSION['register_success']?? "";
    session_unset();


    function isActiveForm($formName, $activeForm)
    {
        return $formName === $activeForm ? 'active': '';
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
    <style>
        body
        {
            background-color: #fee3cc;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M54.627 0l.83.828-1.415 1.415L51.8 0h2.827zM5.373 0l-.83.828L5.96 2.243 8.2 0H5.374zM48.97 0l3.657 3.657-1.414 1.414L46.143 0h2.828zM11.03 0L7.372 3.657 8.787 5.07 13.857 0H11.03zm32.284 0L49.8 6.485 48.384 7.9l-7.9-7.9h2.83zM16.686 0L10.2 6.485 11.616 7.9l7.9-7.9h-2.83zm20.97 0l9.315 9.314-1.414 1.414L34.828 0h2.83zM22.344 0L13.03 9.314l1.414 1.414L25.172 0h-2.83zM32 0l12.142 12.142-1.414 1.414L30 .828 17.272 13.556l-1.414-1.414L28 0h4zM.284 0l28 28-1.414 1.414L0 2.544V0h.284zM0 5.373l25.456 25.455-1.414 1.415L0 8.2V5.374zm0 5.656l22.627 22.627-1.414 1.414L0 13.86v-2.83zm0 5.656l19.8 19.8-1.415 1.413L0 19.514v-2.83zm0 5.657l16.97 16.97-1.414 1.415L0 25.172v-2.83zM0 28l14.142 14.142-1.414 1.414L0 30.828V28zm0 5.657L11.314 44.97 9.9 46.386l-9.9-9.9v-2.828zm0 5.657L8.485 47.8 7.07 49.212 0 42.143v-2.83zm0 5.657l5.657 5.657-1.414 1.415L0 47.8v-2.83zm0 5.657l2.828 2.83-1.414 1.413L0 53.456v-2.83zM54.627 60L30 35.373 5.373 60H8.2L30 38.2 51.8 60h2.827zm-5.656 0L30 41.03 11.03 60h2.828L30 43.858 46.142 60h2.83zm-5.656 0L30 46.686 16.686 60h2.83L30 49.515 40.485 60h2.83zm-5.657 0L30 52.343 22.343 60h2.83L30 55.172 34.828 60h2.83zM32 60l-2-2-2 2h4zM59.716 0l-28 28 1.414 1.414L60 2.544V0h-.284zM60 5.373L34.544 30.828l1.414 1.415L60 8.2V5.374zm0 5.656L37.373 33.656l1.414 1.414L60 13.86v-2.83zm0 5.656l-19.8 19.8 1.415 1.413L60 19.514v-2.83zm0 5.657l-16.97 16.97 1.414 1.415L60 25.172v-2.83zM60 28L45.858 42.142l1.414 1.414L60 30.828V28zm0 5.657L48.686 44.97l1.415 1.415 9.9-9.9v-2.828zm0 5.657L51.515 47.8l1.414 1.413 7.07-7.07v-2.83zm0 5.657l-5.657 5.657 1.414 1.415L60 47.8v-2.83zm0 5.657l-2.828 2.83 1.414 1.413L60 53.456v-2.83zM39.9 16.385l1.414-1.414L30 3.658 18.686 14.97l1.415 1.415 9.9-9.9 9.9 9.9zm-2.83 2.828l1.415-1.414L30 9.313 21.515 17.8l1.414 1.413 7.07-7.07 7.07 7.07zm-2.827 2.83l1.414-1.416L30 14.97l-5.657 5.657 1.414 1.415L30 17.8l4.243 4.242zm-2.83 2.827l1.415-1.414L30 20.626l-2.828 2.83 1.414 1.414L30 23.456l1.414 1.414zM56.87 59.414L58.284 58 30 29.716 1.716 58l1.414 1.414L30 32.544l26.87 26.87z' fill='%23d1b49c' fill-opacity='0.3' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        #login_section 
        {
            overflow: hidden; 
        }
        .form_box
        {
            display:none;
        }
        .form_box.active
        {
            display:flex;
        }
    </style>

</head>
<body>
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
    <!-- bg-[#ecbf9757] -->
     <!-- max-sm:h-fit max-sm:my-20  -->
    <section id="login_section" class="h-screen flex flex-col justify-center items-center z-50">
        <div id="login" class="form_box <?= isActiveForm('login',$activeForm) ?> rounded-xl text-[#3f4f44] flex-col w-1/3 max-xl:w-4/10 max-lg:w-5/10 max-sm:w-7/9 gap-10 max-sm:gap-7 p-10 max-sm:p-7 bg-[#f7d3b4] shadow-lg shadow-[#637a69] reveal-on-scroll"> 
            <h1 class="text-4xl text-center font-bold">Login</h1>
            <?php if ($errors['login']!=""): ?>
                <div class="bg-red-100 border-l-4 border-red-500 p-2 rounded-xl">
                    <div class="flex rounded-xl items-center">
                        <div class="flex flex-shrink-0 justify-center items-center text-red-500">
                            <span class="material-symbols-outlined">
                                error
                            </span>
                        </div>
                        <div class="ml-3">
                            <p class="text-md text-red-500"><?= htmlspecialchars($errors['login']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($register_success!=""): ?>
                <div class="bg-green-50 border-l-4 border-[#3f4f44] p-2 rounded-xl">
                    <div class="flex rounded-xl items-center">
                        <div class="flex-shrink-0 text-green-700">
                            <span class="material-symbols-outlined">
                                check_circle
                            </span>
                        </div>
                        <div class="ml-3">
                            <p class="text-md text-[#3f4f44]"><?= htmlspecialchars($register_success) ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form action="../pro/login_register.php" method="post" class="flex flex-col gap-5">
                <input type="text" placeholder="Enter registered email id" name="email" value="<?= isset($_COOKIE["email"]) ? $_COOKIE["email"] : "" ?>" class="rounded-xl p-2 ring-2 ring-[#3F4F44] placeholder-[#3F4F44] focus:outline-none focus:shadow-inner focus:shadow-[#3F4F44]">
                <span class="inline-block mx-5 text-red-400">
                    <?= $loginEmailErr ?>
                </span>
                <input type="password" placeholder="Enter registered password" name="pass" class="rounded-xl p-2 ring-2 ring-[#3F4F44] placeholder-[#3F4F44] focus:outline-none focus:shadow-inner focus:shadow-[#3F4F44]">
                <span class="inline-block mx-5 text-red-400">
                    <?= $loginPassErr ?>
                </span>
                <span>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="peer hidden" <?= isset($_COOKIE["email"]) ? "checked" : "" ?>>
                        <div class="w-5 h-5 border-2 border-[#3F4F44] rounded-md peer-checked:bg-[#3F4F44] peer-checked:border-[#3F4F44] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#f7d3b4]">
                                check
                            </span>
                        </div>
                        <span>Remember Me</span>
                    </label>
                </span>
                <button type="submit" name="login" class="p-3 text-[#DCD7C9] flex gap-2 justify-center items-center rounded-xl bg-[#3F4F44] hover:bg-[#505c54] focus:ring-2 focus:ring-[#3F4F44] focus:ring-offset-2 focus:ring-offset-[#DCD7C9]">
                    Login
                    <span class="material-symbols-outlined">login</span>
                </button>
                <div class="flex gap-5 justify-center items-center">
                    <p>
                        Don't have an account?
                    </p>
                    <a href="#" onclick="show_form('register')" class="font-bold hover:text-[#DCD7C9] p-2 rounded-lg hover:bg-[#3F4F44] transition-all ease-in duration-200">Register</a>
                </div>
            </form>
        </div>

        <div id="register" class="z-10 form_box <?= isActiveForm('register',$activeForm) ?> rounded-xl text-[#3F4F44] flex-col w-1/3 max-xl:w-4/10 max-lg:w-5/10 max-sm:w-7/9 gap-10 max-sm:gap-7 p-10 max-sm:p-7 bg-[#f7d3b4] shadow-lg shadow-[#637a69] reveal-on-scroll"> 
            <h1 class="text-4xl text-center font-bold">Register</h1>
            <?php if ($errors['register']!=""): ?>
                <div class="bg-red-100 border-l-4 border-red-500 p-2 rounded-xl">
                    <div class="flex rounded-xl items-center">
                        <div class="flex flex-shrink-0 justify-center items-center text-red-500">
                            <span class="material-symbols-outlined">
                                error
                            </span>
                        </div>
                        <div class="ml-3">
                            <p class="text-md text-red-500"><?= htmlspecialchars($errors['register']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <form action="../pro/login_register.php" method="post" class="flex flex-col gap-5">

                <input type="text" placeholder="Enter full name" name="name" class="rounded-xl p-2 ring-2 ring-[#3F4F44] placeholder-[#3F4F44] focus:outline-none focus:shadow-inner focus:shadow-[#3F4F44]">
                <span class="inline-block mx-5 text-red-400">
                    <?= $nameErr ?>
                </span>
                <input type="text" placeholder="Create email id" name="email" class="rounded-xl p-2 ring-2 ring-[#3F4F44] placeholder-[#3F4F44] focus:outline-none focus:shadow-inner focus:shadow-[#3F4F44]">
                <span class="inline-block mx-5 text-red-400">
                    <?= $emailErr ?>
                </span>
                <input type="password" placeholder="Create password" name="pass" class="rounded-xl p-2 ring-2 ring-[#3F4F44] placeholder-[#3F4F44] focus:outline-none focus:shadow-inner focus:shadow-[#3F4F44]">
                <span class="inline-block mx-5 text-red-400">
                    <?= $passErr ?>
                </span>
                
                <button type="submit" name="register" class="p-3 text-[#DCD7C9] flex gap-2 justify-center items-center rounded-xl bg-[#3F4F44] hover:bg-[#505c54] focus:ring-2 focus:ring-[#3F4F44] focus:ring-offset-2 focus:ring-offset-[#DCD7C9]">
                    Register
                    <span class="material-symbols-outlined">person_add</span>
                </button>
                
                <div class="flex gap-5 justify-center items-center">
                    <p>
                        Already an existing user?
                    </p>
                    <a href="#" onclick="show_form('login')" class="font-bold hover:text-[#DCD7C9] p-2 rounded-lg hover:bg-[#3F4F44] transition-all ease-in duration-200">Login</a>
                </div>
            </form>
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
    <script src="../pro/login.js"></script>
</html>