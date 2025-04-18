<?php
session_start();
if(!isset($_SESSION['patient_email']))
{
    header("Location: ../pro/login.php");
    exit();
}
require_once '../pro/connection.php';

$patient_id = $_SESSION['patient_id'];
$current_date = date('Y-m-d');
$current_time = date('H:i:s');


$pending_appointment = null;
$upcoming_appointment = null;
$prev_appts_result = null;
$pending_appt_result = null;
$upcoming_appt_result = null;


// Appointment cancellation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $cancellation_reason = $_POST['cancellation_reason'];
    
    $stmt = $conn->prepare("UPDATE appointment_records SET 
                          appointment_status = 'cancelled',
                          cancelled_by = 'patient',
                          cancellation_reason = ?
                          WHERE appointment_id = ? AND patient_id = ?");
    if (!$stmt) 
    {
        $_SESSION['appt_error'] = "Something went wrong while updating your appointment. Please try again.";
        error_log("Prepare failed (cancel): " . $conn->error, 3, "error_log.txt");
    } 
    else 
    {
        $stmt->bind_param("sii", $cancellation_reason, $appointment_id, $patient_id);
        if (!$stmt->execute()) 
        {
            $_SESSION['appt_error'] = "Something went wrong while updating your appointment. Please try again.";
            error_log("Execute failed (cancel): " . $stmt->error, 3, "error_log.txt");
        }
    }
}


// completed appointments
$completed_status_query = "UPDATE appointment_records 
                       SET appointment_status = 'completed'
                       WHERE appointment_status = 'confirmed'
                       AND (
                           appointment_date < CURDATE() OR 
                           (appointment_date = CURDATE() AND appointment_time <= CURTIME())
                       )";
$stmt = $conn->prepare($completed_status_query);
if (!$stmt) 
{
    $_SESSION['appt_error'] = "Something went wrong while updating your appointment. Please try again.";
    error_log("Prepare failed (complete update): " . $conn->error, 3, "error_log.txt");
} 
else 
{
    if (!$stmt->execute()) 
    {
        $_SESSION['appt_error'] = "Something went wrong while updating your appointment. Please try again.";
        error_log("Execute failed (complete update): " . $stmt->error, 3, "error_log.txt");
    }
}


// current pending appointment 
$pending_appt_query = "SELECT * FROM appointment_records 
                      WHERE patient_id = ? 
                      AND appointment_status = 'pending'
                      AND appointment_date IS NULL
                      LIMIT 1";
$stmt = $conn->prepare($pending_appt_query);
if (!$stmt) 
{
    $_SESSION['appt_error'] = "Something went wrong while updating your appointment. Please try again.";
    error_log("Prepare failed (pending): " . $conn->error, 3, "error_log.txt");
} 
else 
{
    $stmt->bind_param("i", $patient_id);
    if ($stmt->execute()) 
    {
        $pending_appt_result = $stmt->get_result();
        $pending_appointment = $pending_appt_result->fetch_assoc();
    } 
    else 
    {
        $_SESSION['appt_error'] = "Something went wrong while updating your appointment. Please try again.";
        error_log("Execute failed (pending): " . $stmt->error, 3, "error_log.txt");
    }
}



// upcoming confirmed appointment
$upcoming_appt_query = "SELECT * FROM appointment_records 
                       WHERE patient_id = ? 
                       AND appointment_status = 'confirmed'
                       ORDER BY appointment_date, appointment_time ASC 
                       LIMIT 1";


$stmt = $conn->prepare($upcoming_appt_query);
if (!$stmt) 
{
    $_SESSION['appt_error'] = "Something went wrong while updating your appointment. Please try again.";
    error_log("Prepare failed (upcoming): " . $conn->error, 3, "error_log.txt");
} 
else 
{
    $stmt->bind_param("i", $patient_id);
    if ($stmt->execute()) 
    {
        $upcoming_appt_result = $stmt->get_result();
        $upcoming_appointment = $upcoming_appt_result->fetch_assoc();
    } 
    else 
    {
        $_SESSION['appt_error'] = "Something went wrong while updating your appointment. Please try again.";
        error_log("Execute failed (upcoming): " . $stmt->error, 3, "error_log.txt");
    }
}


// previous appointments (cancelled or completed)

$prev_appts_query = "SELECT * FROM appointment_records 
                    WHERE patient_id = ? 
                    AND (appointment_status = 'completed' OR appointment_status = 'cancelled')
                    ORDER BY appointment_date DESC, appointment_time DESC";

$stmt = $conn->prepare($prev_appts_query);
if (!$stmt) 
{
    $_SESSION['appt_error'] = "Something went wrong while updating your appointment. Please try again.";
    error_log("Prepare failed (upcoming): " . $conn->error, 3, "error_log.txt");
} 
else 
{
    $stmt->bind_param("i", $patient_id);
    if ($stmt->execute()) 
    {
        $prev_appts_result = $stmt->get_result();
    } 
    else 
    {
        $_SESSION['appt_error'] = "Something went wrong while updating your appointment. Please try again.";
        error_log("Execute failed (previous): " . $stmt->error, 3, "error_log.txt");
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

            <!-- bg-[#343935]/80 -->
            <div id="nav_row"
            class=" max-md:bg-[#3F4F44]/92 max-md:backdrop-blur-4xl flex justify-center items-center gap-3 max-md:text-2xl max-md:gap-1 ml-auto text-[#e0ddd6]
                    max-md:flex-col max-md:fixed max-md:top-24 max-md:left-0 max-md:h-[calc(100vh-5rem)] max-md:w-full
                    max-md:opacity-0 max-md:pointer-events-none max-md:transition-all max-md:duration-500 max-md:z-40">

                <a href="../pro/main.php#about" class="rounded-lg text-center font-semibold p-2 transition-all ease-in duration-200 hover:bg-[#DCD7C9] hover:text-[#3F4F44] hover:shadow-md">About</a>
                <a href="../pro/process.php" class="rounded-lg text-center font-semibold p-2 transition-all ease-in duration-200 hover:bg-[#DCD7C9] hover:text-[#3F4F44] hover:shadow-md">Process & Charges</a>
                <a href="../pro/contactUs.php" class="rounded-lg text-center font-semibold p-2 transition-all ease-in duration-200 hover:bg-[#DCD7C9] hover:text-[#3F4F44] hover:shadow-md">Contact US</a>                                                                                                         
                <a href="#" onclick="window.location.href='../pro/logout.php'" class="flex gap-2 justify-center items-center rounded-lg text-center font-semibold p-2 mr-5 max-md:mr-0 transition-all ease-in duration-200  text-[#fa5959] hover:bg-[#D44B4B] hover:text-[#DCD7C9] hover:shadow-md">  
                    <span class="material-symbols-outlined">
                        logout
                    </span>Logout
                </a>
            </div>
        </div>
    </header>
    <div class="flex flex-col min-h-screen">
    <div class="container flex-grow mx-auto px-4 py-8 max-w-4xl">
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center">
                <span class="text-[#3F4F44] text-3xl font-bold">Welcome, <?= htmlspecialchars($_SESSION['patient_name']) ?></span>
            </div>
        </div>
        <?php if (isset($_SESSION['appt_error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 p-2 rounded-xl">
                <div class="flex rounded-xl items-center">
                    <div class="flex flex-shrink-0 justify-center items-center text-red-500">
                        <span class="material-symbols-outlined">
                            error
                        </span>
                    </div>
                    <div class="ml-3">
                        <p class="text-xl text-red-500"><?= $_SESSION['appt_error']; unset($_SESSION['appt_error']); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>





        <!-- Current Appointment Section -->
        <section class="mb-8 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-4 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-700">Current Appointment Status</h2>
            </div>
            
            <div class="p-6">
                <?php if ($pending_appointment): ?>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <!-- <i class="fas fa-clock text-yellow-400 text-xl"></i> -->
                                <span class="material-symbols-outlined text-yellow-400">
                                schedule
                                </span>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Appointment Pending Approval</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Your appointment request is waiting for doctor's confirmation.</p>
                                    <p class="mt-1"><strong>Chief Problem:</strong> <?= htmlspecialchars($pending_appointment['chief_problem']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button id="cancelbtn" onclick="toggleCancelForm()" 
                                class="px-4 py-2 bg-red-400 text-white flex justify-center items-center gap-2 rounded-md hover:bg-red-700">
                            <!-- <i class="fas fa-times mr-2"></i> -->
                            <span class="material-symbols-outlined">
                            event_busy
                            </span>
                            Cancel Appointment
                        </button>
                        
                        <form id="cancelForm" method="POST" action="user_dashboard.php" class="hidden mt-4 bg-red-50 p-4 rounded-md">
                            <input type="hidden" name="appointment_id" value="<?= $pending_appointment['appointment_id'] ?>">
                            <div class="mb-4">
                                <label for="cancellation_reason" class="block text-sm font-medium text-red-700 mb-1">
                                    Reason for Cancellation
                                </label>
                                <textarea id="cancellation_reason" name="cancellation_reason" rows="3" 
                                          class="w-full border border-red-200 text-red-700 rounded-md p-2 focus:outline-none focus:shadow-inner focus:shadow-red-200" required></textarea>
                            </div>
                            <div class="flex self-end justify-end space-x-3">
                                <button type="button" onclick="toggleCancelForm()" 
                                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                                    Back
                                </button>
                                <button type="submit" name="cancel_appointment" 
                                        class="px-4 py-2 bg-red-400 text-white rounded-md hover:bg-red-700">
                                    Confirm Cancellation
                                </button>
                            </div>
                        </form>
                    </div>

                
                <?php elseif ($upcoming_appointment): ?>

                    <div class="bg-green-50 border-l-4 border-green-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0 text-xl">
                                <!-- <i class="fas fa-calendar-check text-green-400 text-xl"></i> -->
                                <span class="material-symbols-outlined text-green-400 ">
                                event_available
                                </span>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Upcoming Appointment</h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p><strong>Date:</strong> <?= htmlspecialchars($upcoming_appointment['appointment_date']) ?></p>
                                            <p><strong>Time:</strong> <?= htmlspecialchars($upcoming_appointment['appointment_time']) ?></p>
                                            <p><strong>Mode:</strong> <?= ucfirst($upcoming_appointment['appointment_mode']) ?></p>
                                        </div>
                                        <div>
                                            <?php if ($upcoming_appointment['appointment_mode'] == 'online' && $upcoming_appointment['meeting_id']): ?>
                                                <p><strong>Meeting ID:</strong> <?= htmlspecialchars($upcoming_appointment['meeting_id']) ?></p>
                                            <?php endif; ?>
                                            <p><strong>Status:</strong> 
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Confirmed
                                                </span>
                                            </p>
                                            <p><strong>Chief Problem:</strong> <?= htmlspecialchars($upcoming_appointment['chief_problem']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                <?php else: ?>

                    <div class="bg-[#f7d3b499] border-l-4 border-[#3F4F44] p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <!-- <i class="fas fa-info-circle text-[#3F4F44] text-xl"></i> -->
                                <span class="material-symbols-outlined text-[#3F4F44]">
                                info
                                </span>
                            </div>
                            <div class="ml-3">
                                    <h3 class="text-md font-medium text-[#3F4F44]">No Current Appointment</h3>
                                <div class="mt-2 text-sm text-[#3F4F44]">
                                    <p>You don't have any scheduled appointments.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="mt-6 text-center">
                        <a href="../pro/book_appointment.php" 
                           class="inline-flex items-center px-6 py-3 bg-[#3F4F44] text-[#DCD7C9] font-medium rounded-md hover:bg-[#5c7163]">
                           <div class="flex gap-2">    
                                <span class="material-symbols-outlined">
                                add_circle
                                </span> 
                                <span>Book New Appointment</span>
                           </div>
                        </a>
                    </div>

                <?php endif; ?>
            </div>
        </section>





        <!-- Previous Appointments Section -->
        <section class="bg-white rounded-lg shadow-md overflow-hidden mb-20">
            <div class="border-b border-gray-200 px-6 py-4 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-700">Appointment History</h2>
            </div>
            
            <div class="p-6">
                <?php if ($prev_appts_result && $prev_appts_result->num_rows > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Problem</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                </tr>
                            </thead>


                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php while ($appt = $prev_appts_result->fetch_assoc()): ?>
                                <?php
                                    $appt_datetime = $appt['appointment_date'] ? $appt['appointment_date'].' '.$appt['appointment_time'] : null;
                                ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?= $appt['appointment_date'] ? htmlspecialchars($appt['appointment_date']) : '—' ?>
                                        <?= $appt['appointment_time'] ? '<br>'.htmlspecialchars($appt['appointment_time']) : '' ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($appt['appointment_status'] == 'cancelled'): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Cancelled (by <?= $appt['cancelled_by'] ?>)
                                            </span>
                                            <?php if ($appt['cancellation_reason']): ?>
                                                <p class="text-xs text-gray-500 mt-1">Reason: <?= htmlspecialchars($appt['cancellation_reason']) ?></p>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <?= ucfirst($appt['appointment_status']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= htmlspecialchars($appt['chief_problem']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= ucfirst($appt['appointment_mode']) ?>
                                        <?php if ($appt['appointment_mode'] == 'online' && $appt['meeting_id']): ?>
                                            <p class="text-xs">ID: <?= htmlspecialchars($appt['meeting_id']) ?></p>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center font-semibold text-[#3F4F44] py-4">
                        <!-- <i class="fas fa-history text-3xl mb-2"></i> -->
                        <span class="material-symbols-outlined">
                            history
                        </span>
                        <p>No previous appointments found</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- max-[1025px]:mt-77 max-[821px]:mt-[7.5rem] -->
    <footer class="flex max-md:flex-col justify-center gap-5 p-5 max-sm:p-1 items-center bg-[#3F4F44] mt-auto">
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
            <a href="#" onclick="window.location.href='../pro/logout.php'" class="flex gap-2 justify-center items-center rounded-lg text-center font-semibold p-2 transition-all ease-in duration-200 hover:ring-2 focus:bg-red-200 focus:text-[#3F4F44] hover:ring-red-400 ">
                <span class="material-symbols-outlined">
                    logout
                </span>Logout
            </a>
        </div>
    </footer>
    </div>
    <script src="../pro/user_dashboard.js"></script>
</body>
</html>