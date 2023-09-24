<?php
session_start();

if (isset($_COOKIE['persistent_token'])) {
    header('Location: home.php');
    exit;
}

// Connect to the database
require_once('config.php');

// Check the database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the email and password from the form
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Prepare SQL statement to select user record with matching email
    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind email to SQL statement parameter
    mysqli_stmt_bind_param($stmt, "s", $email);

    // Execute SQL statement
    mysqli_stmt_execute($stmt);

    // Get query result
    $result = mysqli_stmt_get_result($stmt);

    // Check if there is a matching record
    if (mysqli_num_rows($result) > 0) {
        // Fetch user details from the database
        $row = mysqli_fetch_assoc($result);
        $hashedPassword = $row['password'];

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['unique_id'] = $row['unique_id'];
            $_SESSION['fname'] = $row['fname'];
            $_SESSION['lname'] = $row['lname'];
            $_SESSION['photo'] = $row['img'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['resized_photo'] = $row['resized_photo'];
            $_SESSION['thumbnail_photo'] = $row['thumbnail_photo'];
            $_SESSION['gender'] = $row['gender'];

            // Generate a persistent token
            $persistentToken = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 month'));

            // Store the persistent token in the database
            $insertTokenQuery = "INSERT INTO persistent_tokens (unique_id, token, expires_at) VALUES (?, ?, ?)";
            $insertTokenStmt = mysqli_prepare($conn, $insertTokenQuery);
            mysqli_stmt_bind_param($insertTokenStmt, "iss", $row['unique_id'], $persistentToken, $expiresAt);
            mysqli_stmt_execute($insertTokenStmt);

            // Set the persistent token as a cookie
            setcookie('persistent_token', $persistentToken, strtotime('+1 month'), '/');

            // Redirect to the home page
            header('Location: home.php');
            exit();
        } else {
            // Password is incorrect
            
            echo"  <div id='popup' class='popup'>
            <p>User credentials didnt match</p>
            <div class='user-error-div'>
            <img class='user-error' src='imgs/user-error.jpg'></img>
            </div>
            <div class='button-container'>
              <button onclick='dismissPopup()'>OK</button>
            </div>
          </div>
          <script>
          function dismissPopup() {
            var popup = document.getElementById('popup');
            popup.style.display = 'none';
          }
        </script>
          ";
        }
    } else {
        // Email not found
        //echo "Email not found";
        echo"  <div id='popup-second' class='popup'>
        <p>User credentials not found</p>
        <div class='user-error-div'>
        <img class='user-error' src='imgs/user-error.jpg'></img>
        </div>
        <div class='button-container'>
          <button onclick='dismissPopupsecond()'>OK</button>
        </div>
      </div>
      <script>
      function dismissPopupsecond() {
        var popupsec= document.getElementById('popup-second');
        popupsec.style.display = 'none';
      }
    </script>
      ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/295/295128.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Varela+Round&display=swap');
      .popup {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: #fff;
      padding: 20px;
      border: 1px solid #ccc;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      z-index: 9999;
      text-align: center;
    }
    .user-error{
      width: 20%;
     
    }
    .user-error-div{
   
   text-align: center
    }
   
   
    .button-container {
      text-align: center;
      margin-top: 10px;
    }
    .button-container button {
      padding: 8px 20px;
      border-radius: 5px;
      background-color: #4CAF50;
      color: #fff;
      cursor: pointer;
    }


    .popup-second{
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: #fff;
      padding: 20px;
      border: 1px solid #ccc;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      z-index: 9999;
      text-align: center;
    }
   
.container {

    display: flex;
    justify-content: center;
    font-family: Arial, Helvetica, sans-serif;
    align-items: center;

}

html {
    background-color: #FD5D5D;
}

.container-wrapper {
    padding: 3em;
    margin: 2em;
    width: max-content;
    height: max-content;
    background-color: #FF8080;
    border-radius: 30px;
}

.login-text {
    text-align: center;
    font-size: 2em;
    margin-top: -2%;
}

.item {
    padding: 0.2em;
}

.container-wrapper label,
button {
    padding: 0.2em;
}

.submit {
    text-align: center;
   margin-top: 20px;
}

.submit button {
    padding: 0.5em 1em 0.5em 1em;
    border-radius: 4.7em;
    border: none;
    width: 100%;
    background-color: #FD5D5D;
    font-size: 18px;
}

.submit button:hover {
    background-color: #ff3449;
    cursor: pointer;
    box-shadow: #b5b6fb;

}



form {
    margin: 2em auto 2em auto
}

.input {
    padding: 20px;
    border-radius: 30px;
    border: none;
    font-size: 20px;
    margin: 5px;
}

.remember {
    font-size: normal;
    margin-left: 40%;
    display: inline-block;
    margin-top: 17px;
}

.ac {
    display: inline-block;
    font-size: medium;
    margin-top: 24px;
    text-align: center;
}


* {
    font-family: 'Varela Round', sans-serif;
    color: #3a2d2d;
}

.ac a {
    text-decoration: underline;
    color: #3a2d2d;
}

.ac-logo {
    padding: 5px;
    size: 20px;
}

#toc {
    display: inline-block;
    margin-top: 10px;
}

.social-media {
    display: flex;
    justify-content: space-evenly;
}

.container h2 {
    width: 100%;
    text-align: center;
    border-bottom: 1px solid #000;
    line-height: 0.1em;
    margin: 10px 0 20px;
}

.container h2 span {
    background-color: #FF8080;
    padding: 0 10px;
}

.social-mediaImg {
    width: 48px;
}

@media only screen and (max-width: 500px) {

    .container-wrapper {
        padding: 2em;
        margin: 1em;
    }

    .login-text {
        font-size: 1.5em;
    }

    .item {
        padding: 0.1em;
        text-align: center;
    }

    .container-wrapper label,
    button {
        padding: 0.1em;
    }

    .submit {
        margin-top: 1em;
        margin-bottom: 2em;

    }

    .submit button {
        padding: 0.5em 1em;
        border-radius: 4.7em;
        font-size: 14px;
    }



    form {
        margin: 1em auto 1em auto
    }

    .input {
        padding: 10px;
        border-radius: 15px;
        font-size: 15px;

    }

    .remember {
        font-size: smaller;
        margin-left: 6em;
    }

    .ac {
        font-size: small;
    }

    #toc {
        font-size: small;
    }

    .social-mediaImg {
        width: 40px;
    }
    .container h2 span {
        font-size: medium;
    }
}



/* SVG  */

.icons8-google {
    display: inline-block;
    height: 48px;
    background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHg9IjBweCIgeT0iMHB4Igp3aWR0aD0iNDgiIGhlaWdodD0iNDgiCnZpZXdCb3g9IjAgMCA0OCA0OCIKc3R5bGU9IiBmaWxsOiMwMDAwMDA7Ij48cGF0aCBmaWxsPSIjRkZDMTA3IiBkPSJNNDMuNjExLDIwLjA4M0g0MlYyMEgyNHY4aDExLjMwM2MtMS42NDksNC42NTctNi4wOCw4LTExLjMwMyw4Yy02LjYyNywwLTEyLTUuMzczLTEyLTEyYzAtNi42MjcsNS4zNzMtMTIsMTItMTJjMy4wNTksMCw1Ljg0MiwxLjE1NCw3Ljk2MSwzLjAzOWw1LjY1Ny01LjY1N0MzNC4wNDYsNi4wNTMsMjkuMjY4LDQsMjQsNEMxMi45NTUsNCw0LDEyLjk1NSw0LDI0YzAsMTEuMDQ1LDguOTU1LDIwLDIwLDIwYzExLjA0NSwwLDIwLTguOTU1LDIwLTIwQzQ0LDIyLjY1OSw0My44NjIsMjEuMzUsNDMuNjExLDIwLjA4M3oiPjwvcGF0aD48cGF0aCBmaWxsPSIjRkYzRDAwIiBkPSJNNi4zMDYsMTQuNjkxbDYuNTcxLDQuODE5QzE0LjY1NSwxNS4xMDgsMTguOTYxLDEyLDI0LDEyYzMuMDU5LDAsNS44NDIsMS4xNTQsNy45NjEsMy4wMzlsNS42NTctNS42NTdDMzQuMDQ2LDYuMDUzLDI5LjI2OCw0LDI0LDRDMTYuMzE4LDQsOS42NTYsOC4zMzcsNi4zMDYsMTQuNjkxeiI+PC9wYXRoPjxwYXRoIGZpbGw9IiM0Q0FGNTAiIGQ9Ik0yNCw0NGM1LjE2NiwwLDkuODYtMS45NzcsMTMuNDA5LTUuMTkybC02LjE5LTUuMjM4QzI5LjIxMSwzNS4wOTEsMjYuNzE1LDM2LDI0LDM2Yy01LjIwMiwwLTkuNjE5LTMuMzE3LTExLjI4My03Ljk0NmwtNi41MjIsNS4wMjVDOS41MDUsMzkuNTU2LDE2LjIyNyw0NCwyNCw0NHoiPjwvcGF0aD48cGF0aCBmaWxsPSIjMTk3NkQyIiBkPSJNNDMuNjExLDIwLjA4M0g0MlYyMEgyNHY4aDExLjMwM2MtMC43OTIsMi4yMzctMi4yMzEsNC4xNjYtNC4wODcsNS41NzFjMC4wMDEtMC4wMDEsMC4wMDItMC4wMDEsMC4wMDMtMC4wMDJsNi4xOSw1LjIzOEMzNi45NzEsMzkuMjA1LDQ0LDM0LDQ0LDI0QzQ0LDIyLjY1OSw0My44NjIsMjEuMzUsNDMuNjExLDIwLjA4M3oiPjwvcGF0aD48L3N2Zz4=') 50% 50% no-repeat;
    background-size: 100%;
}


.icons8-facebook-circled {
    display: inline-block;
    height: 48px;
    background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHg9IjBweCIgeT0iMHB4Igp3aWR0aD0iNDgiIGhlaWdodD0iNDgiCnZpZXdCb3g9IjAgMCA0OCA0OCIKc3R5bGU9IiBmaWxsOiMwMDAwMDA7Ij48bGluZWFyR3JhZGllbnQgaWQ9IkNYYW51d0Q5RUdrQmdUbjc2XzFteGFfcDYyQVNQSzJLcHFwX2dyMSIgeDE9IjkuOTkzIiB4Mj0iNDAuNjE1IiB5MT0iLTI5OS45OTMiIHkyPSItMzMwLjYxNSIgZ3JhZGllbnRUcmFuc2Zvcm09Im1hdHJpeCgxIDAgMCAtMSAwIC0yOTApIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHN0b3Agb2Zmc2V0PSIwIiBzdG9wLWNvbG9yPSIjMmFhNGY0Ij48L3N0b3A+PHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjMDA3YWQ5Ij48L3N0b3A+PC9saW5lYXJHcmFkaWVudD48cGF0aCBmaWxsPSJ1cmwoI0NYYW51d0Q5RUdrQmdUbjc2XzFteGFfcDYyQVNQSzJLcHFwX2dyMSkiIGQ9Ik0yNCw0QzEyLjk1NCw0LDQsMTIuOTU0LDQsMjRjMCwxMC4wMjgsNy4zNzksMTguMzMxLDE3LjAwNCwxOS43NzcJQzIxLjk4MSw0My45MjQsMjIuOTgyLDQxLDI0LDQxYzAuOTE5LDAsMS44MjQsMi45MzgsMi43MTEsMi44MThDMzYuNDc1LDQyLjQ5NSw0NCwzNC4xMjcsNDQsMjRDNDQsMTIuOTU0LDM1LjA0Niw0LDI0LDR6Ij48L3BhdGg+PHBhdGggZD0iTTI3LjcwNywyMS4xNjljMC0xLjQyNCwwLjMwNS0zLjEyMSwxLjc1Ny0zLjEyMWg0LjI4M2wtMC4wMDEtNS42MTdsLTAuMDUtMC44NTJsLTAuODQ2LTAuMTE0CWMtMC42MDgtMC4wODItMS44NzMtMC4yNTMtNC4yMDYtMC4yNTNjLTUuNTY5LDAtOC42MzYsMy4zMTUtOC42MzYsOS4zMzR2Mi40OThIMTUuMDZ2Ny4yNThoNC45NDhWNDMuNglDMjEuMjk4LDQzLjg2MSwyMi42MzMsNDQsMjQsNDRjMS4yNjgsMCwyLjUwNC0wLjEzMSwzLjcwNy0wLjM1N1YzMC4zMDFoNS4wMzNsMS4xMjItNy4yNThoLTYuMTU1VjIxLjE2OXoiIG9wYWNpdHk9Ii4wNSI+PC9wYXRoPjxwYXRoIGQ9Ik0yNy4yMDcsMjEuMTY5YzAtMS4zNTMsMC4yOTMtMy42MjEsMi4yNTctMy42MjFoMy43ODNWMTIuNDZsLTAuMDI2LTAuNDRsLTAuNDMzLTAuMDU5CWMtMC41OTctMC4wODEtMS44MzgtMC4yNDktNC4xNDMtMC4yNDljLTUuMzIzLDAtOC4xMzYsMy4wNTUtOC4xMzYsOC44MzR2Mi45OThIMTUuNTZ2Ni4yNThoNC45NDh2MTMuODc0CUMyMS42NDQsNDMuODc2LDIyLjgwNiw0NCwyNCw0NGMxLjA5NCwwLDIuMTYtMC4xMTIsMy4yMDctMC4yODFWMjkuODAxaDUuMTA0bDAuOTY3LTYuMjU4aC02LjA3MlYyMS4xNjl6IiBvcGFjaXR5PSIuMDUiPjwvcGF0aD48cGF0aCBmaWxsPSIjZmZmIiBkPSJNMjYuNzA3LDI5LjMwMWg1LjE3NmwwLjgxMy01LjI1OGgtNS45ODl2LTIuODc0YzAtMi4xODQsMC43MTQtNC4xMjEsMi43NTctNC4xMjFoMy4yODNWMTIuNDYJYy0wLjU3Ny0wLjA3OC0xLjc5Ny0wLjI0OC00LjEwMi0wLjI0OGMtNC44MTQsMC03LjYzNiwyLjU0Mi03LjYzNiw4LjMzNHYzLjQ5OEgxNi4wNnY1LjI1OGg0Ljk0OHYxNC40NzUJQzIxLjk4OCw0My45MjMsMjIuOTgxLDQ0LDI0LDQ0YzAuOTIxLDAsMS44Mi0wLjA2MiwyLjcwNy0wLjE4MlYyOS4zMDF6Ij48L3BhdGg+PC9zdmc+') 50% 50% no-repeat;
    background-size: 100%;
}

.icons8-twitter {
    display: inline-block;
    height: 48px;
    background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHg9IjBweCIgeT0iMHB4Igp3aWR0aD0iNDgiIGhlaWdodD0iNDgiCnZpZXdCb3g9IjAgMCA0OCA0OCIKc3R5bGU9IiBmaWxsOiMwMDAwMDA7Ij48bGluZWFyR3JhZGllbnQgaWQ9Il9vc245eklOMmY2UmhUc1k4V2hZNGFfNU1RMGdQQVlZeDdhX2dyMSIgeDE9IjEwLjM0MSIgeDI9IjQwLjc5OCIgeTE9IjguMzEyIiB5Mj0iMzguNzY5IiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHN0b3Agb2Zmc2V0PSIwIiBzdG9wLWNvbG9yPSIjMmFhNGY0Ij48L3N0b3A+PHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjMDA3YWQ5Ij48L3N0b3A+PC9saW5lYXJHcmFkaWVudD48cGF0aCBmaWxsPSJ1cmwoI19vc245eklOMmY2UmhUc1k4V2hZNGFfNU1RMGdQQVlZeDdhX2dyMSkiIGQ9Ik00Ni4xMDUsMTEuMDJjLTEuNTUxLDAuNjg3LTMuMjE5LDEuMTQ1LTQuOTc5LDEuMzYyYzEuNzg5LTEuMDYyLDMuMTY2LTIuNzU2LDMuODEyLTQuNzU4CWMtMS42NzQsMC45ODEtMy41MjksMS43MDItNS41MDIsMi4wODJDMzcuODYsOC4wMzYsMzUuNjEyLDcsMzMuMTIyLDdjLTQuNzgzLDAtOC42NjEsMy44NDMtOC42NjEsOC41ODIJYzAsMC42NzEsMC4wNzksMS4zMjQsMC4yMjYsMS45NThjLTcuMTk2LTAuMzYxLTEzLjU3OS0zLjc4Mi0xNy44NDktOC45NzRjLTAuNzUsMS4yNjktMS4xNzIsMi43NTQtMS4xNzIsNC4zMjIJYzAsMi45NzksMS41MjUsNS42MDIsMy44NTEsNy4xNDdjLTEuNDItMC4wNDMtMi43NTYtMC40MzgtMy45MjYtMS4wNzJjMCwwLjAyNiwwLDAuMDY0LDAsMC4xMDFjMCw0LjE2MywyLjk4Niw3LjYzLDYuOTQ0LDguNDE5CWMtMC43MjMsMC4xOTgtMS40ODgsMC4zMDgtMi4yNzYsMC4zMDhjLTAuNTU5LDAtMS4xMDQtMC4wNjMtMS42MzItMC4xNThjMS4xMDIsMy40MDIsNC4yOTksNS44ODksOC4wODcsNS45NjMJYy0yLjk2NCwyLjI5OC02LjY5NywzLjY3NC0xMC43NTYsMy42NzRjLTAuNzAxLDAtMS4zODctMC4wNC0yLjA2NS0wLjEyMkM3LjczLDM5LjU3NywxMi4yODMsNDEsMTcuMTcxLDQxCWMxNS45MjcsMCwyNC42NDEtMTMuMDc5LDI0LjY0MS0yNC40MjZjMC0wLjM3Mi0wLjAxMi0wLjc0Mi0wLjAyOS0xLjEwOEM0My40ODMsMTQuMjY1LDQ0Ljk0OCwxMi43NTEsNDYuMTA1LDExLjAyIj48L3BhdGg+PC9zdmc+') 50% 50% no-repeat;
    background-size: 100%;
}
.create-account-div{
    margin-top: 10px;
    text-align: center;
}
.create-account-button {
  padding: 10px 20px; /* Add padding around the button */
  background-color: #4CAF50; /* Set the background color of the button */
  color: white; /* Set the text color to white */
  border: none; /* Remove the button border */
  cursor: pointer; /* Add a cursor pointer on hover */
  font-size: 16px; /* Set the font size of the button text */
  border-radius: 4px; /* Add some border radius to soften the corners */
}

.create-account-button:hover {
  background-color: #45a049; /* Change the background color on hover */
}
    </style>
</head>

<body>
    <div class="create-account-div">
        <a href="signup">
        <Button class="create-account-button">Create Account</Button>
        </a>
    </div>
    <div class="container">
        <div class="container-wrapper">

            <h3 class="login-text"><i class="bi bi-person-circle ac-logo"></i>Rankme Login</h3>
        
            <form method="post">
                <div class="item"><input class="input" name="email" type="text" placeholder="Username"></div> 
                <div class="item"><input class="input" name="password" type="password" placeholder="Password"> </div>
                <a href="#forget-msg">  <span class="remember" id="forget-pass" onclick="forget()"> Forgot Password? </span></a>

                <div class="item submit"><button type="submit">Submit</button></div>
            </form>
            <h2><span>OR</span></h2>
         
            <div class="social-media">

                <a href="#social-login"><div class="icons8-google social-mediaImg"></div></a> 
                <a href="#social-login"><div class="icons8-facebook-circled social-mediaImg"></div></a> 
                <a href="#social-login"><div class="icons8-twitter social-mediaImg"></div></a> 
  
            </div>
            <span class="ac">Don't have an Account? <a href="signup.php">Sign Up</a></span>
        </div>

    </div>

       
<style>
    #forget-msg {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 9999;
    }
  
    .forget-msg-class {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      text-align: center;
    }
  
    .forget-msg-class h2 {
      margin-top: 0;
    }
  
    .forget-msg-class p {
      margin-bottom: 20px;
    }
  
    .forget-msg-class button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
  






  <div id="forget-msg">
    <div class="forget-msg-class">
      <h2>Forget Password?</h2>
      <p>Relax and try to remember your password.</p>
      <button onclick="cancel()">Got It</button>
    </div>
  </div>
  
  <script>
    function forget() {
      var forgetMsgDiv = document.getElementById("forget-msg");
      forgetMsgDiv.style.display = "block";
      // document.body.style.filter = "blur(3px)";
    }
  
    function cancel() {
      var forgetMsgDiv = document.getElementById("forget-msg");
      forgetMsgDiv.style.display = "none";
      // document.body.style.filter = "none";
    }
    
  
  </script>

  
</body>

</html>