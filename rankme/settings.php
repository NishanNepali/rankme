<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <style>
     /* Body styles */
body {
  background-color: #f5f5f5;
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
}

/* Account settings container */
.container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
}

/* Account settings heading */
.container h1 {
  font-size: 24px;
  color: #333333;
  margin-top: 0;
  margin-bottom: 20px;
}

/* Delete account button */
.delete-account-btn-div{
display: flex;
justify-content: center;
align-items: center;
height: 100vh;

}
#delete-account-btn {

  background-color: #ff0000;
  color: #ffffff;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
  margin-bottom: 20px;
}

.popup-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
}

.popup {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    max-width: 400px;
}

.popup h2 {
    font-size: 24px;
    color: #333333;
    margin-top: 0;
}

.popup p {
    margin-bottom: 20px;
    font-size: 16px;
    color: #666666;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    font-size: 16px;
    color: #333333;
    margin-bottom: 5px;
}

input[type="password"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #cccccc;
    border-radius: 5px;
    font-size: 16px;
}

.delete-btn {
    background-color: #ff0000;
    color: #ffffff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    float: right;
}

/* Add your own styles as needed */


/* Header */
.top {
  background-color: #ff96fa;
  padding: 20px;
  display: flex;
 

}

.header {
  color: #fff;
  margin: 0;
}

.logo {
  height: 55px;
  vertical-align: middle;
 
}
.top h1{
 margin: auto;

}
/* Add your own styles as needed */

.cancel {
  background-color: #dddddd;
  color: #333333;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
  margin-right: 10px;
  float: left;
}

.cancel:hover {
  background-color: #cccccc;
}


@media only screen and (max-width: 600px) {
    .popup-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.0);
    z-index: 9999;
}

  .cancel {
    padding: 8px 16px;
    font-size: 14px;
    margin-right: 5px;
  }
  .top h1{
font-size: 20px;

}
}
    </style>
</head>
<body>

<div class="top">
  <div class="header">
  <a href="index.php" onclick="goBack(event);">
  <img class="logo" src="imgs/logo.png" alt="">
</a>

</div>
    <h1>Account Settings</h1>
</div>

    <!-- Delete account button -->
<div class="delete-account-btn-div">
    <button id="delete-account-btn">Delete Account</button>
    </div>


    <div class="popup-container" id="popup-container" style="display: none;">
    <div class="popup">
        <h2>Confirm Account Deletion</h2>
        <p>Please enter your password to confirm the account deletion:</p>
        <!-- Account deletion form -->
        <form id="delete-account-form" method="POST" action="delete-account.php">
            <div class="form-group">
                <label for="password-input">Password:</label>
                <input type="password" id="password-input" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit" name="delete_account" class="delete-btn">Delete Account</button>
                <button type="button" class="cancel" onclick="closePopup()">Cancel</button>
            </div>
        </form>
    </div>
</div>


    <script>
        // Get the delete account button and the popup container
        const deleteAccountBtn = document.getElementById('delete-account-btn');
        const popupContainer = document.getElementById('popup-container');

        // Add event listener to the delete account button
        deleteAccountBtn.addEventListener('click', () => {
            // Show the popup
            popupContainer.style.display = 'flex';
        });

        // Get the confirm delete button and the password input field
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
        const passwordInput = document.getElementById('password-input');

        // Add event listener to the confirm delete button
        confirmDeleteBtn.addEventListener('click', () => {
            const password = passwordInput.value;

            // Send an AJAX request to the server to validate the password and delete the account
            // Replace the URL with the actual server endpoint
            fetch('validate-password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ password }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Password matches, show the thank you message
                        showThankYouMessage();
                    } else {
                        // Password doesn't match, show an error message
                        alert('Incorrect password. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        // Function to show the thank you message and delete the account
        function showThankYouMessage() {
            // Hide the popup
            popupContainer.style.display = 'none';

            // Show the thank you message
            const thankYouMessage = document.createElement('p');
            thankYouMessage.textContent = 'Thank you for using our service. Your account has been deleted.';
            document.body.appendChild(thankYouMessage);

            // Send an AJAX request to the server to delete the account
            // Replace the URL with the actual server endpoint
            fetch('delete-account.php', {
                method: 'POST',
            })
                .then(response => response.text())
                .then(data => {
                    console.log(data); // Handle the server response as needed
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
        function closePopup() {
    document.getElementById("popup-container").style.display = "none";
}

    </script>
</body>
</html>
