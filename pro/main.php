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
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" /> -->
</head>
<body class="bg-[#DCD7C9] scroll-smooth">

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

    <!-- first screen -->
    <section id="home_sec1" class="rounded-xl relative flex flex-col p-10 h-fit">


        <div id="home_first" class="relative z-10 max-[769px]:static max-[769px]:flex-col flex flex-wrap justify-center items-center gap-50 max-xl:gap-44 max-md:gap-20">
            <div class="absolute max-[769px]:static top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 max-[769px]:-translate-x-0 max-[769px]:-translate-y-0 rounded-xl flex flex-col gap-10 justify-center items-center transition-all ease-in duration-2000">
                <img src="../images/samhomeopathicclinic.png" class="h-70 max-xl:h-60 max-lg:h-50 max-[430px]:h-30 transition-all ease-in duration-1000">
                <a href="#" onclick="window.location.href='../pro/user_dashboard.php'" class="text-[#DCD7C9] flex gap-2 justify-center items-center transition-all ease-in duration-200 text-lg hover:shadow-[1rem 1rem 1rem] hover:shadow-[#242423] font-black p-5 bg-[#2C3930] rounded-full hover:ring-3 hover:ring-[#2C3930] text-center hover:ring-offset-2 hover:ring-offset-[#fee3cc]">
                    Book An Appointment
                </a>
            </div> 

            <div id="home_first_child1" class="home_first_child rounded-xl text-[#2C3930] p-10 max-lg:p-13 w-1/4 max-[900px]:w-[12rem] max-md:w-[25rem] max-sm:w-[18rem] bg-[#f7d3b4] shadow-lg shadow-[#637a69] flex flex-col justify-center items-center transition-all ease-in duration-1000
            ">
                <div class="text-5xl max-lg:text-4xl font-extrabold p-2 transition-all ease-in duration-500"> EGO </div> 
                <div class="text-3xl max-lg:text-2xl text-center"><span>is the mother of all diseases</span></div>
            </div>
            <div id="home_first_child2" class="home_first_child rounded-xl text-[#2C3930] p-7 max-[920px]:p-5 max-md:p-7 w-1/4 max-[900px]:w-[12rem] max-md:w-[25rem] max-sm:w-[18rem] bg-[#f7d3b4] shadow-lg shadow-[#637a69] flex flex-col justify-center items-center transition-all ease-in duration-1000
            ">
                <div class="text-2xl p-2 flex flex-col gap-6 max-[1025px]:gap-3">
                    <div class="text-4xl max-[1025px]:text-3xl max-[920px]:text-2xl max-md:text-3xl font-extrabold text-center">Like cures like</div> 
                    <div class="text-2xl text-center max-[1025px]:text-xl max-[920px]:text-lg max-md:text-xl">the body’s wisdom knows the path to healing</div>
                </div>
            </div>

            <div id="home_first_child3" class="home_first_child rounded-xl text-[#2C3930] p-10 max-[920px]:p-6 w-1/4  max-[900px]:w-[12rem] max-md:w-[25rem] max-sm:w-[18rem]  bg-[#f7d3b4] shadow-lg shadow-[#637a69] flex flex-col justify-center items-center transition-all ease-in duration-1000
            ">
                <div class="text-3xl text-center max-lg:text-2xl max-[920px]:text-xl font-extrabold p-3">Nature holds the remedy for every illness </div> 
                <div class="text-xl max-lg:text-lg self-end"><span>– Paracelsus</span></div>
            </div>
            <div id="home_first_child4" class="home_first_child rounded-xl text-[#2C3930] p-6 w-1/4  max-[900px]:w-[12rem] max-md:w-[25rem] max-sm:w-[18rem] bg-[#f7d3b4] shadow-lg shadow-[#637a69] flex flex-col justify-center items-center transition-all ease-in duration-1000
            ">
                <div class="text-2xl text-center max-lg:text-xl max-[920px]:text-lg max-md:text-xl font-bold p-3"> The highest ideal of cure is rapid, gentle, and permanent restoration of health </div> 
                <div class="text-xl max-lg:text-lg max-[920px]:text-md flex flex-col items-end self-end">
                    <span>– Samuel</span>
                    <span>Hahnemann</span>
                </div>
                
            </div>

        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent via-[90%] to-[#DCD7C9]"></div>
    </section>


    <!-- second screen -->
    <section id="about" class="mt-30">

        <div class="h-screen max-[1025px]:h-fit bg-[#DCD7C9] flex justify-center items-center p-10 max-md:p-5">
            <div class="max-w-7xl mx-auto flex max-lg:flex-col justify-between items-center gap-16">
=
              <div class="flex-1 min-w-[300px] reveal-on-scroll">
                <div class="rounded-xl ring-2 ring-[#2C3930] shadow-lg shadow-[#637a69] ring-offset-2 ring-offset-[#DCD7C9]">
                  <img class="rounded-xl w-full h-auto max-h-[600px] object-cover" src="../images/DrSamirVaid.jpeg" alt="Dr. Samir Vaid">
                </div>
              </div>
          

              <div class="flex-1 flex flex-col gap-8 max-lg:gap-6 reveal-on-scroll">
                <h1 class="text-5xl max-md:text-4xl max-md:text-center font-extrabold text-[#2C3930]">Dr. Samir Vaid</h1>
                

                <div class="bg-[#f5f3ee] p-8 max-md:p-6 rounded-lg shadow-md border-l-4 border-[#A27B5C]">
                  <p class="text-xl max-md:text-lg text-neutral-700">
                        Completed his B.H.M.S degree in 2000, from Sri. Guru Nanak Dev Homeopethic Medical College & Hospital, Ludhiana 
                        affiliated with Baba Farid University of Health Sciences. For the last 25 years, he has been practicing classical homeopathy
                        and has successfully cured cases of depression, fatty liver, dyspepsia, allergies etc.
                        His approach focuses on understanding the root cause of illness rather than just alleviating symptoms. 
                        Over the years, he has built a reputation for accurate case analysis and compassionate care. 
                        He strongly believes in the principles of classical homeopathy, 
                        which emphasize the body's innate ability to heal itself when guided with the right remedy.
                  </p>
                </div>
          
              </div>
            </div>
          </div>

          <div class="h-screen max-[1025px]:h-fit bg-[#DCD7C9] flex justify-center items-center p-10 max-md:p-5">
            <div class="max-w-7xl mx-auto flex max-lg:flex-col justify-between items-center gap-16">
              

              <div class="hidden max-lg:flex min-w-[300px] reveal-on-scroll">
                <div class="rounded-xl ring-2 ring-[#2C3930] shadow-lg shadow-[#637a69] ring-offset-2 ring-offset-[#DCD7C9]">
                  <img class="rounded-xl w-full h-auto max-h-[600px] object-cover" src="../images/BalramVaid.jpeg" alt="Prof. Balram Vaid">
                </div>
              </div>


              <div class="flex-1 flex flex-col gap-8 max-lg:gap-6 reveal-on-scroll">
                <h1 class="text-5xl max-md:text-4xl max-md:text-center font-extrabold text-[#2C3930]">Prof.(retd.) Balram Vaid</h1>
                

                <div class="bg-[#f5f3ee] p-8 max-md:p-6 rounded-lg shadow-md border-l-4 border-[#A27B5C]">
                  <p class="text-xl max-md:text-lg text-neutral-700">
                        Father of Dr. Samir Vaid, a multifaceted personality, verstile, conscencious, non envious, foresighted workholic with
                        scholarly bent of mind. After matriculation in 1956, he worked as a dispenser in Ayurvedic Dispensary of Jalandhar Municipality,
                        worked in clerical line for 13 years in Punjab Govt  and shifted to teaching Economics in DN College, Hisar. For 27 years he served
                        as professor of Economics in Guru Nanak College Phagwara, including 6 years as officiating principal. Topped in first year LLB examination of Guru Nanak Dev University in 1980. 
                        After retiring in 1997, he has been working as advocate. He is assisting in management of his son's Homeopathic Clinic. 
                  </p>
                </div>
              </div>

 
              <div class="flex-1 max-lg:hidden min-w-[300px] reveal-on-scroll">
                <div class="rounded-xl ring-2 ring-[#2C3930] shadow-lg shadow-[#637a69] ring-offset-2 ring-offset-[#DCD7C9]">
                  <img class="rounded-xl w-full h-auto max-h-[600px] object-cover" src="../images/BalramVaid.jpeg" alt="Prof. Balram Vaid">
                </div>
              </div>

            </div>
          </div>
    </section>



    <section class="relative z-10 bg-[#97bfa3ba] text-[#2C3930] py-20 px-10 max-md:px-6 reveal-on-scroll">
        <h1 class="text-5xl font-extrabold text-center mb-16 max-md:text-3xl">
            Why Choose Homeopathy?
        </h1>
    
        <div class="grid grid-cols-3 max-lg:grid-cols-2 max-sm:grid-cols-1 gap-10">

            <div class="bg-[#f7d3b4] rounded-2xl p-6 shadow-lg shadow-[#637a69] transition-transform duration-500 hover:scale-105 reveal-on-scroll">
                <h2 class="text-2xl font-bold mb-2">Gentle & Natural Healing</h2>
                <p>Homeopathy works with the body, not against it. The ultra-diluted remedies stimulate innate healing response without harsh chemicals or side effects.</p>
            </div>
    
            <div class="bg-[#f7d3b4] rounded-2xl p-6 shadow-lg shadow-[#637a69] transition-transform duration-500 hover:scale-105 reveal-on-scroll">
                <h2 class="text-2xl font-bold mb-2">Personalized Care</h2>
                <p>Homeopathy treats the patient, not just the symptoms. The unique physical, emotional, and lifestyle factors guide every prescription.</p>
            </div>

            <div class="bg-[#f7d3b4] rounded-2xl p-6 shadow-lg shadow-[#637a69] transition-transform duration-500 hover:scale-105 reveal-on-scroll">
                <h2 class="text-2xl font-bold mb-2">Safe for All Ages</h2>
                <p>From infants to seniors, even during pregnancy - homeopathy offers non-toxic, non-addictive solutions tailored to delicate constitutions.</p>
            </div>
    
            <div class="bg-[#f7d3b4] rounded-2xl p-6 shadow-lg shadow-[#637a69] transition-transform duration-500 hover:scale-105 reveal-on-scroll">
                <h2 class="text-2xl font-bold mb-2">Holistic Approach</h2>
                <p>Addresses the root cause of illness (like stress or immunity) rather than suppressing symptoms, promoting long-term wellness.</p>
            </div>
    
            <div class="bg-[#f7d3b4] rounded-2xl p-6 shadow-lg shadow-[#637a69] transition-transform duration-500 hover:scale-105 reveal-on-scroll">
                <h2 class="text-2xl font-bold mb-2">No Known Drug Interactions</h2>
                <p>Can be safely used alongside conventional treatments when needed.</p>
            </div>
    
            <div class="bg-[#f7d3b4] rounded-2xl p-6 shadow-lg shadow-[#637a69] transition-transform duration-500 hover:scale-105 reveal-on-scroll">
                <h2 class="text-2xl font-bold mb-2">Sustainable & Ethical</h2>
                <p>Plant/mineral-based remedies align with eco-conscious values.</p>
            </div>
        </div>
    </section>



    <section class="bg-[#f4f1ea] text-[#2C3930] py-16 px-6 md:px-20">
        <div class="flex flex-col gap-6 m-3">
            <h2 class="text-4xl md:text-5xl text-center font-extrabold text-[#1f2d1d] reveal-on-scroll">
                The Spiritual Aspect in Homeopathy
            </h2>
            <p class="text-lg md:text-xl mb-10 text-center reveal-on-scroll">
                Homeopathy recognizes that true healing goes beyond the physical body—it embraces the mind, emotions, and spirit as interconnected parts of your whole being.
            </p>
        </div>
        
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-10 items-center">
              
            <div class="gap-6">
                <img src="../images/spirtual_aspect.webp" alt="Spiritual Healing" class="rounded-xl shadow-lg shadow-slate-500 object-cover h-full w-full reveal-on-scroll">
            </div>
        

            <div class="space-y-8">
            
                <div class="space-y-6 text-[1.125rem] md:text-[1.25rem] leading-relaxed">
                    <div class="reveal-on-scroll">
                    <h3 class="font-bold text-green-800">1. Healing the Deeper Self</h3>
                    <p>Emotional patterns, stress, and spiritual well-being are key to remedy selection. It helps in healing the soul’s distress.</p>
                    </div>
            
                    <div class="reveal-on-scroll">
                    <h3 class="font-bold text-green-800">2. Vital Force & Energy Medicine</h3>
                    <p>Homeopathy works with your “vital force”—restoring energetic harmony through gentle remedies.</p>
                    </div>
            
                    <div class="reveal-on-scroll">
                    <h3 class="font-bold text-green-800">3. Personalized Soul-Level Care</h3>
                    <p>It honors your story, emotions and dreams to help release emotional blockages.</p>
                    </div>
            
                    <div class="reveal-on-scroll">
                    <h3 class="font-bold text-green-800">4. Beyond Religion—Universal Wellness</h3>
                    <p>This path supports your spirit, life force or consciousness —regardless of belief.</p>
                    </div>
            
                    <div class="reveal-on-scroll">
                    <h3 class="font-bold text-green-800">5. A Path to Wholeness</h3>
                    <p>Physical symptoms often reflect spiritual unrest. Homeopathy addresses the root, guiding you toward emotional clarity and peace.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
          



    <section>
        <div class="mt-40 p-10 text-[#2C3930] h-screen max-xl:h-fit flex flex-col gap-20 justify-center items-center">
            <h1 class="text-6xl font-extrabold text-center reveal-on-scroll">
                How it Works?
            </h1>
            <div class="flex gap-10 max-xl:gap-20 flex-wrap justify-evenly transition ease-in duration-2000">
                <div id="work_div1" class="work_div flex flex-col gap-10 rounded-xl bg-[#f7d3b4] p-10 shadow-lg shadow-[#637a69] w-[20rem] max-[1480px]:w-[30rem] max-[1135px]:w-[25rem] max-md:w-[36rem] max-sm:w-9/10 transition-all ease-in duration-1000">
                    <h1 class="text-3xl font-bold text-center">Login/Register</h1>
                    <div class="text-lg">
                        First step is to login / register into the website by setting up with required details. Further you will be redirected to a dashboard page
                        where you can book an appointment.
                    </div>
                </div>
                <div id="work_div2" class="work_div flex flex-col gap-5 rounded-xl bg-[#3F4F44] text-[#f7d3b4] p-10 shadow-lg shadow-[#848383] w-[20rem] max-[1480px]:w-[30rem] max-[1135px]:w-[25rem] max-md:w-[36rem] max-sm:w-9/10 transition-all ease-in duration-1000">
                    <h1 class="text-3xl font-bold text-[#ecbf97] text-center">Appointment Booking & <br>Confirmation</h1>
                    <div class="text-md text-[#DCD7C9]">
                        For booking an appointment you will be required to provide initial details about the disease, history of patient and medicial
                        investigations (if any). Then you have to wait for a confirmation from the doctor with the appointed date, time & meeting id(if choosen online mode).
                    </div>
                </div>
                <div id="work_div3" class="work_div flex flex-col gap-10 rounded-xl bg-[#f7d3b4] p-10 shadow-lg shadow-[#637a69] w-[20rem] max-[1480px]:w-[30rem] max-[1135px]:w-[25rem] max-md:w-[36rem] max-sm:w-9/10 transition-all ease-in duration-1000">
                    <h1 class="text-3xl font-bold text-center">First Consultation</h1>
                    <div class="text-lg">
                        On the appointed date and time, In the first consultation the patient's case history will be recorded along with mental, emotional, spiritual details
                        of the personality which may take from 1 - 2 hrs time either in person or on video call.
                    </div>
                </div>
                <div id="work_div4" class="work_div flex flex-col gap-10 rounded-xl bg-[#3F4F44] text-[#f7d3b4] p-10 shadow-lg shadow-[#848383] w-[20rem] max-[1480px]:w-[30rem] max-[1135px]:w-[25rem] max-md:w-[36rem] max-sm:w-9/10 transition-all ease-in duration-1000">
                    <h1 class="text-3xl font-bold text-[#ecbf97] text-center">Prescribed medicine pickup</h1>
                    <div class="text-lg text-[#DCD7C9]">
                        After the first session, doctor will prescribe the medicine and it is required from the patient's side to collect it from the Clinic if meeting done
                        virtually on a set date and time based on mutual convinience.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="text-[#2C3930] bg-[url(../images/instructions_bg.jpg)] bg-cover bg-no-repeat max-xl:bg-center flex flex-col justify-center items-center p-20 max-lg:p-10 reveal-on-scroll">
            <div class="flex flex-col gap-10 rounded-xl p-20 max-lg:p-10 backdrop-blur-xs bg-[#e7e3d8]/60 ring-2 ring-[#e7e3d8] shadow-lg shadow-slate-400 w-full max-w-4xl">
                <h1 class="text-5xl max-sm:text-3xl font-bold text-[#2C3930] text-center reveal-on-scroll">
                    Instructions for Homeopathic Medicines
                </h1>
   
                <ol class="flex flex-col gap-10 text-2xl text-[#191e1a] text-semibold max-lg:text-xl max-sm:text-lg rounded-xl list-decimal list-inside marker:text-green-900 marker:font-bold">
                    <li style="--i: 1" class="reveal-on-scroll">
                        Avoid touching the pills directly. Instead, pour the recommended dose into the bottle cap first, then transfer them to your tongue.
                    </li>
                    <li style="--i: 2" class="reveal-on-scroll">
                        For best absorption, take medicines when your mouth is clean and free of strong flavors (e.g., food, toothpaste, or drinks).
                        Ideal: 10 minutes before/after eating, drinking, or brushing your teeth.
                    </li>
                    <li style="--i: 3" class="reveal-on-scroll">
                        Except Caffine/Coffee No dietary restrictions unless advised by the doctor.
                    </li>
                    <li style="--i: 4" class="reveal-on-scroll">
                        The order and time of medicines must be followed as prescribed by the doctor.
                    </li>
                    <li style="--i: 5" class="reveal-on-scroll">
                        Liquid medicines are to be consumed with 1 to 2 tbsp water dilution.
                    </li>
                </ol>
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
    <script src="../pro/main.js"></script>
</body>
</html>