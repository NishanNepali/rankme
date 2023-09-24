// Wrap your code in a DOMContentLoaded event listener to ensure the DOM is fully loaded before executing the JavaScript code
document.addEventListener('DOMContentLoaded', function() {
    // Get all elements with the class "accept-button"
    var acceptButtons = document.querySelectorAll('.accept-button');
    // Loop through each accept button and attach an event listener
    acceptButtons.forEach(function(button) {
      button.addEventListener('click', handleAcceptRequest);
    });
  
    // Get all elements with the class "reject-button"
    var rejectButtons = document.querySelectorAll('.reject-button');
    // Loop through each reject button and attach an event listener
    rejectButtons.forEach(function(button) {
      button.addEventListener('click', handleRejectRequest);
    });
  
    // Event handler for accept button click
    function handleAcceptRequest(event) {
      event.preventDefault();
      // Get the requester id from the data attribute
      var requesterId = event.target.dataset.requesterId;
      // Perform an AJAX request to accept_request.php with the requester id
      var url = 'accept_request.php?requester_id=' + requesterId;
      fetch(url)
        .then(function(response) {
          return response.json();
        })
        .then(function(data) {
          // Handle the response from the server
          if (data.success) {
            console.log(data.message); // Request accepted successfully
            // You can update the UI or perform any necessary actions here
          } else {
            console.error(data.error); // Error accepting friend request
          }
        })
        .catch(function(error) {
          console.error('An error occurred:', error);
        });
    }
  
    // Event handler for reject button click
    function handleRejectRequest(event) {
      event.preventDefault();
      // Get the requester id from the data attribute
      var requesterId = event.target.dataset.requesterId;
      // Perform an AJAX request to reject_request.php with the requester id
      var url = 'reject_request.php?requester_id=' + requesterId;
      fetch(url)
        .then(function(response) {
          return response.json();
        })
        .then(function(data) {
          // Handle the response from the server
          if (data.success) {
            console.log(data.message); // Request rejected successfully
            // You can update the UI or perform any necessary actions here
          } else {
            console.error(data.error); // Error rejecting friend request
          }
        })
        .catch(function(error) {
          console.error('An error occurred:', error);
        });
    }
  });
  
  
  
  
  
  function handleSendRequest(event) {
      event.preventDefault();
      const receiverId = event.target.dataset.receiverId;
      
      // Create an AJAX request
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'send_request.php');
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function () {
        if (xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          if (response.success) {
            // Update the button's status to "Request Sent"
            event.target.textContent = 'Request Sent';
            event.target.classList.add('request-sent');
            event.target.removeEventListener('click', handleSendRequest);
          } else {
            // Display the error message
            console.error(response.error);
          }
        }
      };
      xhr.send(`receiver_id=${receiverId}`);
    }
  
    // Attach event listeners to all "Send Friend Request" buttons
    const sendRequestButtons = document.querySelectorAll('.send-request-button');
    sendRequestButtons.forEach(button => {
      button.addEventListener('click', handleSendRequest);
    });