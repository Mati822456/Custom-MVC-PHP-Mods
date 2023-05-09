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
        <a href="/">Home</a>
        <a class="active" href="/plugin">Plugins</a>
        <a href="/theme">Themes</a>
        <a href="/settings">Settings</a>
    </div>
    <section>
        <?php
            echo '<h2>Detected: '. count($plugins) . (count($plugins) > 1 ? ' plugins' : ' plugin') .'</h2><div class="line"></div>';
            if(isset($_SESSION['message']['plugin'])){
                foreach($_SESSION['message']['plugin'] as $key => $type){
                    foreach($type as $message){
                        echo '<div class="alert '.$key.'"><strong>'.$key.'!</strong> <span>'.$message.'</span></div>';
                    }
                }
            }
            if(isset($_SESSION['message'])){
                unset($_SESSION['message']);
            }
            echo '<div class="plugins">';
            foreach($plugins as $plugin){
                
                echo '<div class="card">';
                    echo '<img src=./public/mods/plugins/' . $plugin->name . '/' . $plugin->image . '>';
                    echo '<div class="line-1"> </div>';
                    echo '<a href="/show?name='.$plugin->name.'" class="info"><i class="fa-solid fa-circle-info"></i></a>';
                    echo '<div class="wrapper">';
                        echo '<p class="name">'.$plugin->name.'</p>';
                        echo '<p class="date">Creation date: '.$plugin->created.'</p>';
                        echo '<p class="version">Version: '.$plugin->version.'</p>';
                        if(isset($plugin->requirements)){
                            if(!empty($plugin->requirements)){
                                echo '<p class="requirements">';
                                    echo '<span class="warning">!</span> Require: ' . $plugin->requirements[0] . (count($plugin->requirements) > 1 ? ' +' . count($plugin->requirements) - 1 : '');
                                    if(count($plugin->requirements) > 1){
                                        echo '<span class="tooltip">';
                                        foreach(array_slice($plugin->requirements, 1) as $element){
                                            echo '<span class="element">' . $element . '</span>';
                                        }
                                        echo '</span>';
                                    }
                                echo '</p>';
                            }
                        }
                        if(isset($plugin->incompatible)){
                            if(!empty($plugin->incompatible)){
                                echo '<p class="incompatible">';
                                    echo '<span class="warning">!</span> Incompatible: ' . $plugin->incompatible[0] . (count($plugin->incompatible) > 1 ? ' +' . count($plugin->incompatible) - 1 : '');
                                    if(count($plugin->incompatible) > 1){
                                        echo '<span class="tooltip">';
                                        foreach(array_slice($plugin->incompatible, 1) as $element){
                                            echo '<span class="element">' . $element . '</span>';
                                        }
                                        echo '</span>';
                                    }
                                echo '</p>';
                            }
                        }
                        echo '<p class="description">'.$plugin->description.'</p>';
                        echo '<div class="actions">';
                            if(!in_array($plugin->name, $cannotRun)){
                                if(in_array($plugin->name, $active)){
                                    echo '<a href="/deactivate?name='.$plugin->name.'&type=1" class="deactivate">Turn off</a>';
                                }else{
                                    echo '<a href="/activate?name='.$plugin->name.'&type=1" class="activate">Turn on</a>';
                                }
                            }
                            printf('<a onClick="showModal(\'%s\', \'%s\', \'%s\', \'%s\');" class="uninstall">Uninstall</a>', 3, 'Do you want to uninstall this plugin?', $plugin->name, 1);
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        ?>
    </section>
    
</body>
</html>