<?php
    namespace App\Views;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'layouts/header.php'; ?>
</head>
<body>
    <div class="topnav">
        <a class="active" href="/">Home</a>
        <a href="/plugin">Plugins</a>
        <a href="/theme">Themes</a>
        <a href="/settings">Settings</a>
    </div>
    
    <section class="index">
        <h2><img src="../public/favicon.svg" alt="icon" style="width:24px;height:24px;"> Manage plugins and themes</h2><div class="line-no-margin"></div>
        <div class="card-redirect plugins-card">
            <h3>Plugins</h3>
            <a href="/plugin"><i class="fa-solid fa-right-long fa-xl"></i></a>
        </div>
        <div class="card-redirect themes-card">
            <h3>Themes</h3>
            <a href="/theme"><i class="fa-solid fa-right-long fa-xl"></i></a>
        </div>
        <div class="card-redirect setting-card">
            <h3>Settings</h3>
            <a href="/settings"><i class="fa-solid fa-right-long fa-xl"></i></a>
        </div>
        <div class="line-no-margin"></div>
    </section>
</body>
</html>