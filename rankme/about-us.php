<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Merriweather:wght@900&family=Sumana:wght@700&display=swap");

.person {
  align-items: center;
  display: flex;
  flex-direction: column;
  width: 280px;
}
.container {
  border-radius: 50%;
  height: 312px;
  -webkit-tap-highlight-color: transparent;
  transform: scale(0.48);
  transition: transform 250ms cubic-bezier(0.4, 0, 0.2, 1);
  width: 400px;
}
.container:after {
  background-color: #f2f2f2;
  content: "";
  height: 10px;
  position: absolute;
  top: 390px;
  width: 100%;
}
.container:hover {
  transform: scale(0.54);
}
.container-inner {
  clip-path: path(
    "M 390,400 C 390,504.9341 304.9341,590 200,590 95.065898,590 10,504.9341 10,400 V 10 H 200 390 Z"
  );
  position: relative;
  transform-origin: 50%;
  top: -200px;
}
.circle {
  background-color: #fee7d3;
  border-radius: 50%;
  cursor: pointer;
  height: 380px;
  left: 10px;
  pointer-events: none;
  position: absolute;
  top: 210px;
  width: 380px;
}
.img {
  pointer-events: none;
  position: relative;
  transform: translateY(20px) scale(1.15);
  transform-origin: 50% bottom;
  transition: transform 300ms cubic-bezier(0.4, 0, 0.2, 1);
}
.container:hover .img {
  transform: translateY(0) scale(1.2);
}
.img1 {
  left: 22px;
  top: 164px;
  width: 340px;
}
.img2 {
  left: -46px;
  top: 174px;
  width: 444px;
}
.img3 {
  left: -16px;
  top: 144px;
  width: 466px;
}
.divider {
  background-color: #ca6060;
  height: 1px;
  width: 160px;
}
.name {
  color: #404245;
  font-size: 36px;
  font-weight: 600;
  margin-top: 16px;
  text-align: center;
}
.title {
  color: #6e6e6e;
  font-family: arial;
  font-size: 14px;
  font-style: italic;
  margin-top: 4px;
}

.body-div{
  align-items: center;
  background-color: #f2f2f2;
  display: flex;
  font-family: "Merriweather", serif;
  flex-wrap: wrap;
  justify-content: center;
  height: 100vh;
  margin: 0;
}
    </style>
</head>
<body>

<?php

include_once('header.php');
?>
<div class="body-div">
<div class="person">
      <div class="container">
        <div class="container-inner">
          <img
            class="circle"
        src="avatar/avatar1.jpg"/>
          <img
            class="img img1"
            src="avatar/avatar1.jpg"
            />
        </div>
      </div>
      <div class="divider"></div>
      <div class="name">S</div>
      <div class="title">Hidden Partner</div>
    </div>
    <div class="person">
      <div class="container">
        <div class="container-inner">
          <img
            class="circle"
            src="avatar/avatar2.png"
            />
          <img
            class="img img2"
           src="avatar/avatar2.png"
            />
        </div>
      </div>
      <div class="divider"></div>
      <div class="name">N</div>
      <div class="title">Senior Developer</div>
    </div>
    <div class="person">
      <div class="container">
        <div class="container-inner">
          <img
            class="circle"
          src="avatar/avatar1.jpg"
          />
          <img
            class="img img3"
            src="avatar/avatar1.jpg"
          />
        </div>
      </div>
      <div class="divider"></div>
      <div class="name">A</div>
      <div class="title">Brand Ambassador</div>
    </div>

   
    <div class="person">
      <div class="container">
        <div class="container-inner">
          <img
            class="circle"
          src="avatar/avatar1.jpg"
          />
          <img
            class="img img3"
            src="avatar/avatar1.jpg"
          />
        </div>
      </div>
      <div class="divider"></div>
      <div class="name">V</div>
      <div class="title">Senior UX</div>
    </div>
</div>
    <script>

    </script>
</body>
</html>