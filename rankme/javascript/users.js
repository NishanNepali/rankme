const searchBar = document.querySelector(".search input");
const searchIcon = document.querySelector(".search button");
const usersList = document.querySelector(".users-list");

// Toggle search bar
searchIcon.onclick = () => {
  searchBar.classList.toggle("show");
  searchIcon.classList.toggle("active");
  searchBar.focus();
  if (searchBar.classList.contains("active")) {
    searchBar.value = "";
    searchBar.classList.remove("active");
  }
};

// Search users
searchBar.onkeyup = () => {
  let searchTerm = searchBar.value;
  if (searchTerm !== "") {
    searchBar.classList.add("active");
  } else {
    searchBar.classList.remove("active");
  }
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/search.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        usersList.innerHTML = data;
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("searchTerm=" + searchTerm);
};

// Fetch users and update every 500ms
setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "php/users.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        if (!searchBar.classList.contains("active")) {
          usersList.innerHTML = data;
        }
      }
    }
  };
  xhr.send();
}, 500);

// Real-time message notifications
setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "php/update-is-seen.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        let unreadCount = JSON.parse(data);
        let userListItems = usersList.querySelectorAll("a");

        userListItems.forEach((userListItem) => {
          let userID = userListItem.dataset.userId;
          let notificationElement = userListItem.querySelector(".notification");

          if (unreadCount.hasOwnProperty(userID) && unreadCount[userID] > 0) {
            notificationElement.style.display = "block";
            notificationElement.textContent = unreadCount[userID];
          } else {
            notificationElement.style.display = "none";
            notificationElement.textContent = "";
          }
        });
      }
    }
  };
  xhr.send();
}, 500);
