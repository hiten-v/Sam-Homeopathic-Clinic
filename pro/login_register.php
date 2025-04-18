<?php
session_start();
require_once '../pro/connection.php';

//sanitize way 2
// $data=filter_input(INPUT_POST,"data",FILTER_SANATIZE_SPECIAL_CHARS);
// $data=filter_input(INPUT_POST,"data",FILTER_SANATIZE_NUMBER_INT);
// $data=filter_input(INPUT_POST,"data",FILTER_SANATIZE_EMAIL);


function sanitize($data)
{
    $data=trim($data);
    $data=stripslashes($data);
    $data=htmlspecialchars($data);
    return $data;
}
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (isset($_POST['register'])) 
    {
        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $password = sanitize($_POST['pass']);

        $hasError = false; // Flag to track validation errors

        if (empty($name)) 
        {
            $_SESSION['name_error'] = 'Name field cannot be empty';
            $hasError = true;
        }
        else
        {
            if (!preg_match("/^[a-zA-Z ]+$/", $name))
            {
                $_SESSION['name_error'] = 'Name can only have alphabets';
                $hasError = true;
            }
        }
        if (empty($email)) 
        {
            $_SESSION['email_error'] = 'Email field cannot be empty';
            $hasError = true;
        }
        else
        {
            //email validation
            $email=filter_input(INPUT_POST,"email",FILTER_VALIDATE_EMAIL); //if not pass validation empty string is returned
            if(empty($email))
            {
                $_SESSION['email_error'] = 'Email id cannot be like this';
                $hasError = true;
            }
        }
        if (empty($password)) 
        {
            $_SESSION['pass_error'] = 'Password cannot be empty';
            $hasError = true;
        }
        
        if ($hasError) 
        {
            $_SESSION['active_form'] = 'register';
            header("Location: ../pro/login.php");
            exit();
        }

        $password = password_hash($password, PASSWORD_DEFAULT);
        try
        {
            $qry="SELECT email FROM patient_records WHERE email=?";
            $stmt = $conn->prepare($qry);
            if (!$stmt) 
            {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $checkEmail = $stmt->get_result();
        
            if ($checkEmail->num_rows > 0) 
            {
                $_SESSION['register_error'] = 'Email is already registered!';
                $_SESSION['active_form'] = 'register';
            } 
            else 
            {
                $qry="INSERT INTO patient_records (name, email, password) VALUES (?,?,?)";
                $stmt = $conn->prepare($qry);
                if (!$stmt) 
                {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                $stmt->bind_param("sss", $name,$email,$password);
                if (!$stmt->execute()) 
                {
                    throw new Exception("Execute failed: " . $stmt->error);
                }
                $_SESSION['register_success'] = 'Registration successful! Please login.';
                $_SESSION['active_form'] = 'login';
            }
        }
        catch (Exception $e) 
        {
            error_log("Registration Error: " . $e->getMessage(), 3, "error_log.txt");
            $_SESSION['register_error'] = 'Something went wrong during registration. Please try again later.';
            $_SESSION['active_form'] = 'register';
        }
        header("Location: ../pro/login.php");
        exit();
    }

    if (isset($_POST['login'])) 
    {
        $email = sanitize($_POST['email']);
        $password = sanitize($_POST['pass']);

        $hasError = false; // Flag to track validation errors

        if (empty($email)) 
        {
            $_SESSION['login_email_error'] = 'Email field cannot be empty';
            $hasError = true;
        }
        else
        {
            //email validation
            $email=filter_input(INPUT_POST,"email",FILTER_VALIDATE_EMAIL);
            if(empty($email))
            {
                $_SESSION['login_email_error'] = 'Email id cannot be like this';
                $hasError = true;
            }
        }
        if (empty($password)) 
        {
            $_SESSION['login_pass_error'] = 'Password cannot be empty';
            $hasError = true;
        }
        if ($hasError) {
            $_SESSION['active_form'] = 'login';
            header("Location: ../pro/login.php");
            exit();
        }

        if (isset($_POST["remember"])) 
        {
            setcookie("email", $_POST["email"], time() + (86400 * 10), "/");
        }
        else
        {
            setcookie("email", $_POST["email"], time() - 3600, "/");
        }

        try
        {
            $qry="SELECT * FROM patient_records WHERE email=?";
            $stmt=$conn->prepare($qry);
            if (!$stmt) 
            {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) 
            {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password'])) 
                {
                    $_SESSION['patient_name'] = $user['name'];
                    $_SESSION['patient_email'] = $user['email'];
                    $_SESSION['patient_id']= $user['patient_id'];
                    session_write_close();
                    header("Location: ../pro/user_dashboard.php");
                    exit();
                } 
                else 
                {
                    $_SESSION['login_error'] = 'Invalid email or password!';
                }
            } 
            else 
            {
                $_SESSION['login_error'] = 'Invalid email or password!';
            }
        }
        catch (Exception $e) 
        {
            error_log("Login Error: " . $e->getMessage(), 3, "error_log.txt");
            $_SESSION['login_error'] = 'Something went wrong during login. Please try again later.';
        }
        $_SESSION['active_form'] = 'login';
        header("Location: ../pro/login.php");
        exit();
    }
}
?>