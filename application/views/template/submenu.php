<!DOCTYPE html>
<html>
<head>
<style>
#mySidenav a {
    position: absolute;
    left: -30px;
    transition: 0.3s;
    padding: 10px;
    width: 100px;
    text-align: right;
    text-decoration: none;
    font-size: 14px;
    color: white;
    border-radius: 0 5px 5px 0;
}

#mySidenav a:hover {
    left: 0px;   
}

#programbucket {
    top: 60px;
    background-color: #4CAF50;
}

#program {
    top: 121px;
    background-color: #2196F3;
}

#donor {
    top: 162px;
    background-color: #555;
}

#unittype {
    top: 203px;
    background-color: #f44336;
}

#state {
    top: 244px;
    background-color: #4CAF50;
}

#district {
    top: 285px;
    background-color: #2196F3;
}

#block {
    top: 326px;
    background-color: #555;
}

#village {
    top: 367px;
    background-color: #f44336;
}

#unit {
    top: 408px;
    background-color: #4CAF50;    
}


</style>

</head>
<body>
<div id="mySidenav" class="sidenav">
  <a href="<?php echo base_url('index.php/Pbucket')?>" id="programbucket" class='target'>Program Bucket</a>
  <a href="<?php echo base_url('index.php/Program')?>" id="program" class='target'>Program</a>
  <a href="<?php echo base_url('index.php/Donor')?>" id="donor" class='target'>Donor</a>
  <a href="<?php echo base_url('index.php/Unittype')?>" id="unittype" class='target'>Unit Type</a>
  <a href="<?php echo base_url('index.php/State')?>" id="state" class='target'>State</a>
  <a href="<?php echo base_url('index.php/District')?>" id="district" class='target'>District</a>
  <a href="<?php echo base_url('index.php/Block')?>" id="block" class='target'>Block</a>
  <a href="<?php echo base_url('index.php/Village')?>" id="village">Village</a>
  <a href="<?php echo base_url('index.php/Unit')?>" id="unit">Unit</a>  
</div>    
