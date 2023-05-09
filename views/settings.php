<?php
    namespace App\Views;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'layouts/header.php'; ?>
    <link rel="stylesheet" href="../public/css/settings.css">
</head>
<body>
    <div class="topnav">
        <a href="/">Home</a>
        <a href="/plugin">Plugins</a>
        <a href="/theme">Themes</a>
        <a class="active" href="/settings">Settings</a>
    </div>
    <section class="settings">
        <?php
            if(isset($_SESSION['message']['setting'])){
                foreach($_SESSION['message']['setting'] as $key => $type){
                    foreach($type as $message){
                        echo '<div class="alert '.$key.'"><strong>'.$key.'!</strong> <span>'.$message.'</span></div>';
                    }
                }
            }
            if(isset($_SESSION['message'])){
                unset($_SESSION['message']);
            }
        ?>
        <div class="setting header">
            <h2><i class="fa-solid fa-gear"></i> Settings</h2>
        </div>
        <form action="/save-settings" method="POST">
            <input type="hidden" name="_method" value="PATCH">
            <div class="setting">
                <span class="name">Alerts:</span>
                <div class="options">
                    <span class="option_name">Errors</span>
                    <label class="switch">
                        <input type="checkbox" <?php echo $settings['alerts-error'] == 1 ? 'checked' : '' ?> name="alerts-error">
                        <span class="slider error"></span>
                    </label>
                    <span class="option_name">Warnings</span>
                    <label class="switch">
                        <input type="checkbox" <?php echo $settings['alerts-warning'] == 1 ? 'checked' : '' ?> name="alerts-warning">
                        <span class="slider warning"></span>
                    </label>
                    <span class="option_name">Infos</span>
                    <label class="switch">
                        <input type="checkbox" <?php echo $settings['alerts-info'] == 1 ? 'checked' : '' ?> name="alerts-info">
                        <span class="slider info"></span>
                    </label>
                    <span class="option_name">Successes</span>
                    <label class="switch">
                        <input type="checkbox" <?php echo $settings['alerts-success'] == 1 ? 'checked' : '' ?> name="alerts-success">
                        <span class="slider success"></span>
                    </label>
                </div>
            </div>
            <div class="setting">    
                <button class="save"><i class="fa-solid fa-floppy-disk"></i>Save</button>
            </div>
        </form>
    </section>
</body>
</html