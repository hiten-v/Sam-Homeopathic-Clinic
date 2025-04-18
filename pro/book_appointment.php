<?php
session_start();
if(!isset($_SESSION['patient_email']))
{
    header("Location: ../pro/login.php");
    exit();
}
require_once '../pro/connection.php';

$patient_id = $_SESSION['patient_id'];

// existing active appointment
$active_appointment_query = "SELECT * FROM appointment_records 
                           WHERE patient_id = ? 
                           AND (appointment_status = 'pending' OR appointment_status = 'confirmed')";
$stmt = $conn->prepare($active_appointment_query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$active_appointment_result = $stmt->get_result();

if ($active_appointment_result->num_rows > 0) {
    header('Location: ../pro/user_dashboard.php');
    exit;
}



$chiefPrblmErr = $investigationErr = $appointModeErr = $FileErr = "";

// File upload configuration
$upload_dir = "../secure_uploads/patient_docs/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0750, true);
}

$allowed_types = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
$max_size = 5 * 1024 * 1024; // 5MB


function sanitize($data)
{
    $data=trim($data);
    $data=stripslashes($data);
    $data=htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $hasError = false; 
    if (empty($_POST['chief_problem'])) 
    {
        $chiefPrblmErr = 'This field cannot be empty';
        $hasError = true;
    }
    if(!isset($_POST['appointment_mode']))
    {
        $appointModeErr = 'Select any one appointment mode';
        $hasError = true;
    }

    if(!isset($_POST['investigation_done']))
    {
        $investigationErr = 'Select any one option';
        $hasError = true;
    }
    elseif ($_POST['investigation_done'] == 'yes') 
    {
        if (!isset($_FILES['investigation_file']) || 
            $_FILES['investigation_file']['error'] !== UPLOAD_ERR_OK || 
            !is_uploaded_file($_FILES['investigation_file']['tmp_name'])) {
            $error = 'Upload the reports';
            $hasError = true;
        }
    }

    if(!$hasError)
    {
        $chief_problem = sanitize($_POST['chief_problem']);
        $appointment_mode = sanitize($_POST['appointment_mode']);
        $investigation_done = sanitize($_POST['investigation_done']);
        $file_path = null;
        $past_problem_history = isset($_POST['past_problem_history']) ? sanitize($_POST['past_problem_history']) : null;
        $family_problem_history = isset($_POST['family_problem_history']) ? sanitize($_POST['family_problem_history']) : null;
        
        // Handle file upload
        if ($investigation_done == 'yes' && isset($_FILES['investigation_file']) && $_FILES['investigation_file']['error'] == UPLOAD_ERR_OK) 
        {
            $file_name = basename($_FILES["investigation_file"]["name"]);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $new_filename = uniqid('doc_', true) . '.' . $file_ext;
            $target_path = $upload_dir . $new_filename;
            
            // Validate file
            if (!in_array($file_ext, $allowed_types)) 
            {
                $error = "Only PDF, DOC, JPG, PNG files are allowed.";
            } 
            elseif ($_FILES["investigation_file"]["size"] > $max_size) 
            {
                $error = "File size must be less than 5MB.";
            } 
            elseif (move_uploaded_file($_FILES["investigation_file"]["tmp_name"], $target_path)) 
            {
                chmod($target_path, 0640);
                $file_path = $new_filename;
            } 
            else 
            {
                $error = "Error uploading file. Try again later";
            }
        }
        
        if (!isset($error)) 
        {
            if($past_problem_history==null && $family_problem_history==null)
            {
                $stmt = $conn->prepare("INSERT INTO appointment_records 
                                    (patient_id, appointment_mode, chief_problem, investigation_done, 
                                    file_path)
                                    VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $patient_id, $appointment_mode, $chief_problem, 
                                $investigation_done, $file_path);
            }
            else if($past_problem_history==null && $family_problem_history!=null)
            {
                $stmt = $conn->prepare("INSERT INTO appointment_records 
                                    (patient_id, appointment_mode, chief_problem, investigation_done, 
                                    file_path, family_problem_history)
                                    VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssss", $patient_id, $appointment_mode, $chief_problem, 
                                $investigation_done, $file_path, $family_problem_history);
            }
            else if($past_problem_history!=null && $family_problem_history==null)
            {
                $stmt = $conn->prepare("INSERT INTO appointment_records 
                                    (patient_id, appointment_mode, chief_problem, investigation_done, 
                                    file_path, past_problem_history)
                                    VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssss", $patient_id, $appointment_mode, $chief_problem, 
                                $investigation_done, $file_path, $past_problem_history);
            }
            else
            {
                $stmt = $conn->prepare("INSERT INTO appointment_records 
                                    (patient_id, appointment_mode, chief_problem, investigation_done, 
                                    file_path, past_problem_history, family_problem_history)
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issssss", $patient_id, $appointment_mode, $chief_problem, 
                                $investigation_done, $file_path, $past_problem_history, $family_problem_history);
            }

            if ($stmt->execute()) 
            {
                header('Location: ../pro/user_dashboard.php?success=appointment_created');
                exit;
            } 
            else 
            {
                $error = "Error creating appointment. Try again later";
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
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- <link rel="stylesheet" href="../src/output.css?v=<?php //echo time(); ?>"> -->
    <style>
        body
        {
            background-color: #DCD7C9;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M54.627 0l.83.828-1.415 1.415L51.8 0h2.827zM5.373 0l-.83.828L5.96 2.243 8.2 0H5.374zM48.97 0l3.657 3.657-1.414 1.414L46.143 0h2.828zM11.03 0L7.372 3.657 8.787 5.07 13.857 0H11.03zm32.284 0L49.8 6.485 48.384 7.9l-7.9-7.9h2.83zM16.686 0L10.2 6.485 11.616 7.9l7.9-7.9h-2.83zm20.97 0l9.315 9.314-1.414 1.414L34.828 0h2.83zM22.344 0L13.03 9.314l1.414 1.414L25.172 0h-2.83zM32 0l12.142 12.142-1.414 1.414L30 .828 17.272 13.556l-1.414-1.414L28 0h4zM.284 0l28 28-1.414 1.414L0 2.544V0h.284zM0 5.373l25.456 25.455-1.414 1.415L0 8.2V5.374zm0 5.656l22.627 22.627-1.414 1.414L0 13.86v-2.83zm0 5.656l19.8 19.8-1.415 1.413L0 19.514v-2.83zm0 5.657l16.97 16.97-1.414 1.415L0 25.172v-2.83zM0 28l14.142 14.142-1.414 1.414L0 30.828V28zm0 5.657L11.314 44.97 9.9 46.386l-9.9-9.9v-2.828zm0 5.657L8.485 47.8 7.07 49.212 0 42.143v-2.83zm0 5.657l5.657 5.657-1.414 1.415L0 47.8v-2.83zm0 5.657l2.828 2.83-1.414 1.413L0 53.456v-2.83zM54.627 60L30 35.373 5.373 60H8.2L30 38.2 51.8 60h2.827zm-5.656 0L30 41.03 11.03 60h2.828L30 43.858 46.142 60h2.83zm-5.656 0L30 46.686 16.686 60h2.83L30 49.515 40.485 60h2.83zm-5.657 0L30 52.343 22.343 60h2.83L30 55.172 34.828 60h2.83zM32 60l-2-2-2 2h4zM59.716 0l-28 28 1.414 1.414L60 2.544V0h-.284zM60 5.373L34.544 30.828l1.414 1.415L60 8.2V5.374zm0 5.656L37.373 33.656l1.414 1.414L60 13.86v-2.83zm0 5.656l-19.8 19.8 1.415 1.413L60 19.514v-2.83zm0 5.657l-16.97 16.97 1.414 1.415L60 25.172v-2.83zM60 28L45.858 42.142l1.414 1.414L60 30.828V28zm0 5.657L48.686 44.97l1.415 1.415 9.9-9.9v-2.828zm0 5.657L51.515 47.8l1.414 1.413 7.07-7.07v-2.83zm0 5.657l-5.657 5.657 1.414 1.415L60 47.8v-2.83zm0 5.657l-2.828 2.83 1.414 1.413L60 53.456v-2.83zM39.9 16.385l1.414-1.414L30 3.658 18.686 14.97l1.415 1.415 9.9-9.9 9.9 9.9zm-2.83 2.828l1.415-1.414L30 9.313 21.515 17.8l1.414 1.413 7.07-7.07 7.07 7.07zm-2.827 2.83l1.414-1.416L30 14.97l-5.657 5.657 1.414 1.415L30 17.8l4.243 4.242zm-2.83 2.827l1.415-1.414L30 20.626l-2.828 2.83 1.414 1.414L30 23.456l1.414 1.414zM56.87 59.414L58.284 58 30 29.716 1.716 58l1.414 1.414L30 32.544l26.87 26.87z' fill='%23d1b49c' fill-opacity='0.3' fill-rule='evenodd'/%3E%3C/svg%3E");
            background-attachment: fixed;
        }
    </style>
</head>
<body class="bg-[#DCD7C9]">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="flex justify-between max-[420px]:gap-6 max-[420px]:justify-items-end items-center mb-8">
            <h1 class="text-2xl font-bold text-[#3F4F44]">New Appointment</h1>
            <a href="../pro/user_dashboard.php" class="text-center flex justify-center items-center gap-1 rounded-lg text-[#3F4F44] p-2 font-semibold hover:text-[#DCD7C9] hover:bg-[#3F4F44] transition ease-in duration-200">
                <span class="material-symbols-outlined">
                arrow_back
                </span>
                Back to Dashboard
            </a>
        </div>

        <?php if (isset($error)): ?>
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0 text-red-400">
                    <span class="material-symbols-outlined">
                    error
                    </span>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700"><?= htmlspecialchars($error) ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="bg-[#5c7163] shadow-md rounded-lg overflow-hidden">
            <div class="border-5 rounded-t-lg border-[#5c7163] px-6 py-4 bg-[#DCD7C9]">
                <h2 class="text-lg font-bold text-[#3F4F44]">Appointment Details</h2>
            </div>
            
            <form method="POST" action="../pro/book_appointment.php" class="p-6" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="chief_problem" class="block text-sm font-medium text-[#DCD7C9] mb-1">
                        Chief Problem/Complaint <span class="text-red-500">*</span>
                    </label>
                    <textarea id="chief_problem" name="chief_problem" rows="3"
                              class="w-full bg-[#DCD7C9] text-[#3F4F44] focus:outline-none focus:shadow-inner focus:shadow-[#3F4F44] rounded-md p-2"></textarea>
                    <span class="inline-block mx-1 text-red-400">
                    <?= $chiefPrblmErr ?>
                    </span>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#DCD7C9] mb-1">
                        Appointment Mode <span class="text-red-500">*</span>
                    </label>

                    <div class="flex space-x-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="appointment_mode" value="online"
                                    class="peer absolute opacity-0 w-0 h-0">
                            <div class="w-4 h-4 rounded-full border-3 border-[#DCD7C9] bg-[#DCD7C9] peer-checked:bg-[#3F4F44]"></div>
                            <span class="ml-2 text-[#DCD7C9]">Online</span>
                        </label>


                        <label class="flex items-center cursor-pointer">
                        <input type="radio" name="appointment_mode" value="offline"
                                class="peer absolute opacity-0 w-0 h-0">
                        <div class="w-4 h-4 rounded-full border-3 border-[#DCD7C9] bg-[#DCD7C9] peer-checked:bg-[#3F4F44]"></div>
                        <span class="ml-2 text-[#DCD7C9]">In-Person</span>
                        </label>
                    </div>
                    <span class="inline-block mx-1 text-red-400">
                    <?= $appointModeErr ?>
                    </span>
                </div>

                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#DCD7C9] mb-1">
                        Have you done any investigations ? <span class="text-red-500">*</span>
                    </label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="investigation_done" value="yes" 
                                class="peer absolute opacity-0 w-0 h-0"
                                onclick="document.getElementById('fileUpload').classList.remove('hidden')">
                            <div class="w-4 h-4 rounded-full border-3 border-[#DCD7C9] bg-[#DCD7C9] peer-checked:bg-[#3F4F44]"></div>
                            <span class="ml-2 text-[#DCD7C9]">Yes</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="investigation_done" value="no" 
                                   class="peer absolute opacity-0 w-0 h-0"
                                   onclick="document.getElementById('fileUpload').classList.add('hidden')">
                            <div class="w-4 h-4 rounded-full border-3 border-[#DCD7C9] bg-[#DCD7C9] peer-checked:bg-[#3F4F44]"></div>
                            <span class="ml-2 text-[#DCD7C9]">No</span>
                        </label>
                    </div>
                    <span class="inline-block mx-1 text-red-400">
                    <?= $investigationErr ?>
                    </span>
                </div>
                
                <div id="fileUpload" class="mb-4 hidden">
                    <label for="investigation_file" class="block text-sm font-medium text-[#DCD7C9] mb-1">
                        Upload Reports (pdf, doc/docs, jpg/jpeg, png - max 5MB) <span class="text-red-500">*</span>
                    </label>
                    <!-- accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" -->
                    <input type="file" id="investigation_file" name="investigation_file"
                           class="w-full bg-[#DCD7C9] text-[#3F4F44] p-2 rounded-md"
                           >
                    <span class="inline-block mx-1 text-red-400">
                    </span>
                </div>
                
                <div class="mb-4">
                    <label for="past_problem_history" class="block text-sm font-medium text-[#DCD7C9] mb-1">
                        Past Medical History (if any)
                    </label>
                    <textarea id="past_problem_history" name="past_problem_history" rows="2" 
                              class="w-full rounded-md p-2 bg-[#DCD7C9] text-[#3F4F44] focus:outline-none focus:shadow-inner focus:shadow-[#3F4F44]"></textarea>
                </div>
                
                <div class="mb-6">
                    <label for="family_problem_history" class="block text-sm font-medium text-[#DCD7C9] mb-1">
                        Family Medical History (if any)
                    </label>
                    <textarea id="family_problem_history" name="family_problem_history" rows="2"
                              class="w-full bg-[#DCD7C9] text-[#3F4F44] focus:outline-none focus:shadow-inner focus:shadow-[#3F4F44] rounded-md p-2"></textarea>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" 
                            class="px-6 py-2 flex justify-center items-center gap-2 bg-[#DCD7C9] text-[#3F4F44] font-medium rounded-md hover:bg-[#b8b3a7] focus:ring-2 focus:ring-[#b8b3a7] focus:ring-offset-2 focus:ring-offset-[#5c7163]">
                            <span class="material-symbols-outlined">
                            add_to_queue
                            </span>Book Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>