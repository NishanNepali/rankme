<div class="upper-section">
  <a href="home.php"><img class="icon" src="imgs/homeicon.png" alt="Home"></a>
  <a href="friends.php"><img class="icon" src="imgs/friends.png" alt="Friends"></a>
  <a href="users.php"><img class="icon" src="imgs/message.png" alt="Messages"></a>
  <a href="notifications.php">
    
    <img class="icon" src="imgs/notificatioin.png" alt="Notifications">
  </a>
  <div class="profile-dropdown">
  <a href="profile-menu.php">
    <img class="profile-picture" src="<?php echo $resized_photo ?>" alt="Profile Picture">
  </a>
</div>

</div>

<style>

    
.upper-section {
  background-color: #3b5998;
  padding: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.upper-section a {
  text-decoration: none;
}

.upper-section a img {
  height: 50px;
}

.icon:hover{
  filter: invert(100%);
        -webkit-filter: invert(100%);
}


.notification-badge {
  background-color: red;
  color: #fff;
  border-radius: 50%;
  padding: 2px 5px;
  font-size: 12px;
  position: absolute;
  top: 5px;
  right: 5px;
}

.profile-dropdown {
  position: relative;
}

.dropbtn {
  background-color: transparent;
  border: none;
  cursor: pointer;
}

.profile-picture {
  width: 50px;
  height: 50px;
  border-radius: 50%;
}



.dropdown-content {
  display: none;
  position: absolute;
  top: 100%;
  right: 0;
  background-color: #f9f9f9;
  min-width: 160px;
  z-index: 1;
}

.dropdown-content a {
  color: #333;
  padding: 8px 16px;
  text-decoration: none;
  display: flex;
  align-items: center;
}

.dropdown-content a:hover {
  background-color: #f1f1f1;
}

.username {
  margin-left: 10px;
  font-size: 14px;
}

.dropdown-icon {
  width: 16px;
  height: 16px;
  margin-right: 10px;
}

@media (max-width: 480px) {
  .upper-section {
    flex-wrap: wrap;
    padding: 10px 5px;
  }
  
  .upper-section a {
    margin-bottom: 5px;
  }
  
  .notification-badge {
    font-size: 10px;
    top: 3px;
    right: 3px;
  }
  
  .profile-dropdown {
    margin-top: 5px;
  }
  
  .username {
    font-size: 12px;
  }
  
  .dropdown-content {
    min-width: 120px;
  }
}




        @media (min-width:360px) and (max-width:600px){

            .logo{
                width: 100%;
                height: 100%;
            }
            .messageicon{
    width: 50px;
    height: 50px;
    padding-right: 0px;


}
.navbar {
    text-align: center;
  }
  
  .navlinks {
    width: 100%;
    max-width: 250px;
  }
  .username{
    display: none;
  }
}
</style>