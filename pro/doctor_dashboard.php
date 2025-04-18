<?php
session_start();
require_once '../pro/connection.php';

if (!isset($_SESSION['doc_email'])) {
    header('Location: ../pro/doc_login.php');
    exit;
}

$current_date = date('Y-m-d');
$current_time = date('H:i:s');



// Handle appointment confirmation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_appointment'])) 
{
    $appointment_id = $_POST['appointment_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $meeting_id = $_POST['appointment_mode'] == 'online' ? $_POST['meeting_id'] : null;
    
    $stmt = $conn->prepare("UPDATE appointment_records SET 
                          appointment_status = 'confirmed',
                          appointment_date = ?,
                          appointment_time = ?,
                          meeting_id = ?
                          WHERE appointment_id = ?");
    $stmt->bind_param("sssi", $appointment_date, $appointment_time, $meeting_id, $appointment_id);
    $stmt->execute();
}

// Handle appointment cancellation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_appointment'])) 
{
    $appointment_id = $_POST['appointment_id'];
    $cancellation_reason = $_POST['cancellation_reason'];
    
    $stmt = $conn->prepare("UPDATE appointment_records SET 
                          appointment_status = 'cancelled',
                          cancelled_by = 'doctor',
                          cancellation_reason = ?
                          WHERE appointment_id = ?");
    $stmt->bind_param("si", $cancellation_reason, $appointment_id);
    $stmt->execute();
}


// Fetch pending appointments
$pending_query = "SELECT a.*, p.name, p.email 
                 FROM appointment_records a
                 JOIN patient_records p ON a.patient_id = p.patient_id
                 WHERE a.appointment_status = 'pending'
                 ORDER BY a.appointment_id ASC";
$pending_result = $conn->query($pending_query);



// Fetch upcoming confirmed appointments
$confirmed_query = "SELECT a.*, p.name, p.email 
                   FROM appointment_records a
                   JOIN patient_records p ON a.patient_id = p.patient_id
                   WHERE a.appointment_status = 'confirmed'
                   AND (
                       a.appointment_date > ? OR 
                       (a.appointment_date = ? AND a.appointment_time > ?)
                   )
                   ORDER BY a.appointment_date, a.appointment_time ASC";
$stmt = $conn->prepare($confirmed_query);
$stmt->bind_param("sss", $current_date, $current_date, $current_time);
$stmt->execute();
$confirmed_result = $stmt->get_result();






// Handle patient history lookup
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['view_patient_history'])) {
    $search_patient_id = $_POST['patient_id'];
    $search_patient_name = $_POST['patient_name'];
    
    $history_query = "SELECT a.*, p.name, p.email 
                     FROM appointment_records a
                     JOIN patient_records p ON a.patient_id = p.patient_id
                     WHERE a.patient_id = ? 
                     AND p.name LIKE ?
                     AND (
                         a.appointment_status = 'cancelled' OR 
                         (a.appointment_status = 'confirmed' AND 
                          (a.appointment_date < ? OR 
                           (a.appointment_date = ? AND a.appointment_time <= ?)))
                        )
                     ORDER BY a.appointment_date DESC";
    $stmt = $conn->prepare($history_query);
    $search_name = "%$search_patient_name%";
    $stmt->bind_param("issss", $search_patient_id, $search_name, $current_date, $current_date, $current_time);
    $stmt->execute();
    $history_result = $stmt->get_result();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#DCD7C9]">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Doctor Dashboard</h1>
            <a href="../pro/doc_logout.php" class="text-red-600 hover:text-red-800">
                <i class="fas fa-sign-out-alt mr-1"></i> Logout
            </a>
        </div>

        <!-- Pending Appointments Section -->
        <section class="mb-12">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-700">Pending Appointments</h2>
                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                    <?= $pending_result->num_rows ?> pending
                </span>
            </div>

            <?php if ($pending_result->num_rows > 0): ?>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chief Problem</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documents</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medical History</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = $pending_result->fetch_assoc()): ?>
                            <tr class="hover:bg-yellow-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900"><?= htmlspecialchars($row['name']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    <?= htmlspecialchars($row['email']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    <?= htmlspecialchars($row['chief_problem']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?= $row['appointment_mode'] == 'online' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' ?>">
                                        <?= strtoupper($row['appointment_mode']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <?php if ($row['file_path']): ?>
                                        <a href="download_file.php?appointment_id=<?= $row['appointment_id'] ?>" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-file-download mr-1"></i> Download
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-400">None</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    <div class="text-sm">
                                        <span class="font-medium">Past:</span> <?php if(is_null($row['past_problem_history'])) { echo "-"; } else { echo htmlspecialchars($row['past_problem_history']); } ?><br>
                                        <span class="font-medium">Family:</span> <?php if(is_null($row['family_problem_history'])) { echo "-"; } else { echo htmlspecialchars($row['family_problem_history']); } ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <form method="POST" action="doctor_dashboard.php" class="space-y-2">
                                        <input type="hidden" name="appointment_id" value="<?= $row['appointment_id'] ?>">
                                        <input type="hidden" name="appointment_mode" value="<?= $row['appointment_mode'] ?>">
                                        
                                        <div class="flex items-center space-x-2">
                                            <label class="w-20 text-gray-600">Date:</label>
                                            <input type="date" name="appointment_date" required min="<?= date('Y-m-d') ?>"
                                                class="border border-gray-300 rounded px-2 py-1 text-sm">
                                        </div>
                                        
                                        <div class="flex items-center space-x-2">
                                            <label class="w-20 text-gray-600">Time:</label>
                                            <input type="time" name="appointment_time" required
                                                class="border border-gray-300 rounded px-2 py-1 text-sm">
                                        </div>
                                        
                                        <?php if ($row['appointment_mode'] == 'online'): ?>
                                        <div class="flex items-center space-x-2">
                                            <label class="w-20 text-gray-600">Meeting ID:</label>
                                            <input type="text" name="meeting_id" required
                                                class="border border-gray-300 rounded px-2 py-1 text-sm flex-grow">
                                        </div>
                                        <?php endif; ?>
                                        
                                        <button type="submit" name="confirm_appointment"
                                            class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                            Confirm Appointment
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php else: ?>
            <div class="bg-white shadow-md rounded-lg p-6 text-center text-gray-500">
                <i class="fas fa-check-circle text-4xl text-green-400 mb-3"></i>
                <p class="text-lg">No pending appointments to review</p>
            </div>
            <?php endif; ?>
        </section>

        <!-- Confirmed Appointments Section -->
        <section class="mb-12">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-700">Upcoming Appointments</h2>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                    <?= $confirmed_result->num_rows ?> confirmed
                </span>
            </div>

            <?php if ($confirmed_result->num_rows > 0): ?>
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documents</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meeting ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = $confirmed_result->fetch_assoc()): ?>
                            <tr class="hover:bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900"><?= htmlspecialchars($row['name']) ?></div>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($row['email']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?= htmlspecialchars($row['appointment_date']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    <?= htmlspecialchars($row['appointment_time']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?= $row['appointment_mode'] == 'online' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' ?>">
                                        <?= strtoupper($row['appointment_mode']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <?php if ($row['file_path']): ?>
                                        <a href="download_file.php?appointment_id=<?= $row['appointment_id'] ?>" 
                                           class="text-indigo-600 hover:text-indigo-900 flex justify-center items-center gap-2">
                                           <span class="material-symbols-outlined">
                                            download
                                            </span> Download
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-400">None</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    <?= $row['meeting_id'] ? htmlspecialchars($row['meeting_id']) : '—' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="toggleCancelForm('cancel_form_<?= $row['appointment_id'] ?>')"
                                        class="text-red-600 hover:text-red-900 mr-3">
                                        <i class="fas fa-times-circle mr-1"></i> Cancel
                                    </button>
                                    
                                    <form id="cancel_form_<?= $row['appointment_id'] ?>" method="POST" 
                                        action="doctor_dashboard.php" class="hidden mt-2 space-y-2 bg-red-50 p-3 rounded">
                                        <input type="hidden" name="appointment_id" value="<?= $row['appointment_id'] ?>">
                                        <textarea name="cancellation_reason" placeholder="Reason for cancellation" 
                                                required rows="3" cols="30" class="w-full p-2"></textarea><br>
                                        <div class="flex justify-end space-x-2">
                                            <button type="button" onclick="toggleCancelForm('cancel_form_<?= $row['appointment_id'] ?>')"
                                                    class="px-3 py-1 bg-gray-300 text-gray-800 rounded text-sm">
                                                Back
                                            </button>
                                            <button type="submit" name="cancel_appointment"
                                                    class="px-3 py-1 bg-red-600 text-white rounded text-sm">
                                                Confirm Cancellation
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php else: ?>
            <div class="bg-white shadow-md rounded-lg p-6 text-3xl text-center text-blue-500">
                <span class="material-symbols-outlined">
                calendar_month
                </span>
                <p class="text-lg text-gray-500">No upcoming appointments scheduled</p>
            </div>
            <?php endif; ?>
        </section>

        <!-- Patient History Lookup Section -->
        <section class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-4 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-700">View Patient History</h2>
            </div>
            
            <div class="p-6">
                <form method="POST" action="doctor_dashboard.php" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Patient ID
                            </label>
                            <input type="number" id="patient_id" name="patient_id" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label for="patient_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Patient Name
                            </label>
                            <input type="text" id="patient_name" name="patient_name" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" name="view_patient_history"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            <i class="fas fa-search mr-2"></i> View History
                        </button>
                    </div>
                </form>
                
                <?php if (isset($history_result) && $history_result->num_rows > 0): ?>
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Appointment History for <?= htmlspecialchars($search_patient_name) ?> (ID: <?= $search_patient_id ?>)
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Problem</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documents</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php while ($row = $history_result->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?= htmlspecialchars($row['appointment_date']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?= htmlspecialchars($row['appointment_time']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($row['appointment_status'] == 'cancelled'): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Cancelled (by <?= $row['cancelled_by'] ?>)
                                            </span>
                                            <?php if ($row['cancellation_reason']): ?>
                                                <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($row['cancellation_reason']) ?></p>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= htmlspecialchars($row['chief_problem']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <?php if ($row['file_path']): ?>
                                            <a href="download_file.php?appointment_id=<?= $row['appointment_id'] ?>" 
                                               class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-file-download mr-1"></i> Download
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-400">None</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= ucfirst($row['appointment_mode']) ?>
                                        <?php if ($row['appointment_mode'] == 'online' && $row['meeting_id']): ?>
                                            <p class="text-xs">ID: <?= htmlspecialchars($row['meeting_id']) ?></p>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php elseif (isset($history_result)): ?>
                <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                No appointment history found for patient ID <?= $search_patient_id ?> with name "<?= htmlspecialchars($search_patient_name) ?>"
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <script>
        function toggleCancelForm(formId) {
            const form = document.getElementById(formId);
            form.classList.toggle('hidden');
        }
    </script>
</body>
</html>