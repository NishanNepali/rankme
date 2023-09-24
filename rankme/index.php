<?php
session_start();
if(isset($_COOKIE['persistent_token'])){
  header('location:home.php');
}else{
  
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Start Global Rules */
* {
  box-sizing: border-box;
}
body {
  font-family: 'Open Sans', sans-serif;
  background-color:#ffa2f7;
}
a {
  text-decoration: none;
}
ul {
  list-style: none;
  padding: 0;
  margin: 0;
}
.container {
  padding-left: 15px;
  padding-right: 15px;
  margin-left: auto;
  margin-right: auto;
}
/* Small */
@media (min-width: 768px) {
  .container {
    width: 750px;
  }
}
/* Medium */
@media (min-width: 992px) {
  .container {
    width: 970px;
  }
}
/* Large */
@media (min-width: 1200px) {
  .container {
    width: 1170px;
  }
}
/* End Global Rules */

/* Start Landing Page */
.landing-page header {
  min-height: 80px;
  display: flex;
}
@media (max-width: 767px) {
  .landing-page header {
    min-height: auto;
    display: initial;
  }
}
.landing-page header .container {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
@media (max-width: 767px) {
  .landing-page header .container {
    flex-direction: column;
    justify-content: center;
  }
}
.landing-page header .logo {
  color: #ffffff;
  font-style: italic;
  text-transform: uppercase;
  font-size: 20px;
}
@media (max-width: 767px) {
  .landing-page header .logo {
    margin-top: 20px;
    margin-bottom: 20px;
  }
}
.landing-page header .links {
  display: flex;
  align-items: center;
}
@media (max-width: 767px) {
  .landing-page header .links {
    text-align: center;
    gap: 10px;
  }
}
.landing-page header .links li {
  margin-left: 30px;
  color: #5d5d5d;
  cursor: pointer;
  transition: .3s;
}
@media (max-width: 767px) {
  .landing-page header .links li {
    margin-left: auto;
  }
}
.landing-page header .links li:last-child {
  border-radius: 20px;
  padding: 10px 20px;
  color: #FFF;
  background-color: #6c63ff;
}
.landing-page header .links li:not(:last-child):hover {
  color: #6c63ff;
}
.landing-page .content .container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 140px;
  min-height: calc(100vh - 80px);
}
@media (max-width: 767px) {
  .landing-page .content .container {
    gap: 0;
    min-height: calc(100vh - 101px);
    justify-content: center;
    flex-direction: column-reverse;
  }
}
@media (max-width: 767px) {
  .landing-page .content .info {
    text-align: center;
    margin-bottom: 15px 
  }
}
.landing-page .content .info h1 {
  color: #000000;
  font-size: 44px;
}
.landing-page .content .info p {
  margin: 0;
  line-height: 1.6;
  font-size: 15px;
  color: #ffffff;
}
.landing-page .content .info button {
  border: 0;
  border-radius: 20px;
  padding: 12px 30px;
  margin-top: 30px;
  cursor: pointer;
  color: #FFF;
  background-color: #6c63ff;
}
.landing-page .content .image img {
  max-width: 100%;
}
/* End Landing Page */

    </style>
</head>
<body>
          <!-- Start Landing Page -->
          <div class="landing-page">
            <header>
              <div class="container">
                <a href="index" class="logo">Rank<b>me</b></a>
                <ul class="links">
                    <a href="login">
                 <li>Login</li>
                </a>
                <a href="signup">
                  <li>Get Started</li>
                </a>
                </ul>
              </div>
            </header>
            <div class="content">
              <div class="container">
                <div class="info">
                  <h1>Introducing Rankme!</h1>
                  <p>With our innovative upvote and downvote system, Rankme ranks users based on their contributions, their popularity, and the quality of their content.</p>
                  
                  <a href="signup">
                  <button>Join Rankme</button>
                </a>
                </div>
                <div class="image">
                  <img src="imgs/image-removebg-preview-1.png">
                </div>
              </div>
            </div>
          </div>
          <!-- End Landing Page -->
</body>
</html>