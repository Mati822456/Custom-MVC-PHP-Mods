<?php
    namespace App\Views;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'layouts/header.php'; ?>
    <link rel="stylesheet" href="../public/css/show.css">
</head>
<body>
    <div class="topnav">
        <a href="/">Home</a>
        <a <?php echo $mod->type == 1 ? 'class="active"' : '' ?> href="/plugin">Plugins</a>
        <a <?php echo $mod->type == 2 ? 'class="active"' : '' ?> href="/theme">Themes</a>
        <a href="/settings">Settings</a>
    </div>
    <section class="show">
        <?php
            if($mod->type == 1){
                if(isset($_SESSION['message']['plugin'])){
                    foreach($_SESSION['message']['plugin'] as $key => $type){
                        foreach($type as $message){
                            echo '<div class="alert '.$key.'"><strong>'.$key.'!</strong> <span>'.$message.'</span></div>';
                        }
                    }
                }
            }
            if($mod->type == 2){
                if(isset($_SESSION['message']['theme'])){
                    foreach($_SESSION['message']['theme'] as $key => $type){
                        foreach($type as $message){
                            echo '<div class="alert '.$key.'"><strong>'.$key.'!</strong> <span>'.$message.'</span></div>';
                        }
                    }
                }
            }
            
            if(isset($_SESSION['message'])){
                unset($_SESSION['message']);
            }
        ?>
        <div class="header">
            <a href="<?php echo $mod->type == 1 ? '/plugin' : '/theme'; ?>" class="back"><i class="fa-solid fa-arrow-left"></i> Go back</a>
            <div class="mod_info">
                <img src="../public/mods/<?php echo $mod->type == 1 ? 'plugins/' : 'themes/'; echo $mod->name; echo '/' . $mod->image ?>" alt="">
                
                <div class="line-1"></div>
                <div class="wrapper">
                    <label>Name:</label>
                    <p class="name"><?php echo $mod->name; ?></p>
                    <label>Type:</label>
                    <p class="type"><?php echo $mod->type == 1 ? 'Plugin' : 'Theme'; ?></p>
                    <label>Creation date:</label>
                    <p class="date"><?php echo $mod->created; ?></p>
                    <label>Version:</label>
                    <p class="version"><?php echo $mod->version; ?></p>
                    <label>Author:</label>
                    <p class="author"><?php echo $mod->author; ?></p>
                    <label>Short description:</label>
                    <p class="description"><?php echo $mod->description; ?></p>
                </div>
                <div class="actions">
                    <?php
                        if (!$cannotRun) {
                            if ($activated ) {
                                echo '<a href="/deactivate?name='.$mod->name.'&type='.$mod->type.'&referer=show" class="deactivate"><i class="fa-solid fa-stop"></i>Turn off</a>';
                            }else{
                                echo '<a href="/activate?name='.$mod->name.'&type='.$mod->type.'&referer=show" class="activate"><i class="fa-solid fa-play"></i>Turn on</a>';
                            }
                        }
                    ?>
                    <?php printf('<a onClick="showModal(\'%s\', \'%s\', \'%s\', \'%s\');" class="uninstall"><i class="fa-solid fa-trash"></i> Uninstall</a>', 3, 'Do you want to uninstall this ' . ($mod->type == 1 ? 'plugin' : 'theme') . '?', $mod->name, $mod->type); ?>
                </div>
            </div>
        </div>
        <?php 
            if(isset($mod->requirements) || isset($mod->incompatible)){
                echo '<div class="info">';
                    if(isset($mod->requirements)){
                        echo '<div class="card-requirements" ' . (isset($mod->incompatible) ? '' : 'style="width:100%;"') . '>';
                            echo '<h3>Required:</h3>';
                            foreach($mod->requirements as $require){
                                if($requiredMods[$require]){
                                    echo '<a href="/show?name='.$require.'"><div class="element">'.$require.' <i class="fa-solid fa-square-check" style="color: #26de81"></i></div></a>';
                                }else{
                                    echo '<div class="element">'.$require.' <i class="fa-solid fa-square-xmark" style="color: #eb3b5a"></i></div>';
                                }
                            }
                        echo '</div>';
                    }
                    if(isset($mod->incompatible)){
                        echo '<div class="card-incompatible" ' . (isset($mod->requirements) ? '' : 'style="width:100%"') . '>';
                            echo '<h3>Incompatible:</h3>';
                            foreach($mod->incompatible as $incompatible){
                                if($incompatibleMods[$incompatible]){
                                    echo '<a href="/show?name='.$incompatible.'"><div class="element">'.$incompatible.' <i class="fa-solid fa-circle-exclamation" style="color: #eb3b5a"></i></i></div></a>';
                                }else{
                                    echo '<a href="/show?name='.$incompatible.'"><div class="element">'.$incompatible.'</div></a>';
                                }
                            }
                        echo '</div>';
                    }
                echo '</div>';
            }
        ?>
        <div class="desc">
            <h1>Description</h1>
            <?php
                echo $mod->getDescription();
            ?>
        </div>
    </section>
</body>
</html>