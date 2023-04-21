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
    </div>
    <section>
        <?php
            echo '<h2>Detected: '. count($plugins) . (count($plugins) > 1 ? ' plugins' : ' plugin') .'</h2><div class="line"></div>';
            if(isset($_SESSION['error']['plugin'])){
                echo '<div class="error">';
                foreach($_SESSION['error']['plugin'] as $error){
                    echo $error.'</br>';
                }
                echo "</div>";
                unset($_SESSION['error']['plugin']);
            }
            echo '<div class="plugins">';
            foreach($plugins as $plugin){
                
                echo '<div class="card">';
                    echo '<img src=./public/mods/plugins/' . $plugin->name . '/' . $plugin->image . '>';
                    echo '<div class="line-1"> </div>';
                    echo '<div class="wrapper">';
                        echo '<p class="name">'.$plugin->name.'</p>';
                        echo '<p class="date">Creation date: '.$plugin->created.'</p>';
                        echo '<p class="version">Version: '.$plugin->version.'</p>';
                        echo '<p class="description">'.$plugin->description.'</p>';
                        echo '<div class="actions">';
                            if(in_array($plugin->name, $active)){
                                echo '<a href="/deactivate?name='.$plugin->name.'&type=1" class="deactivate">Turn off</a>';
                            }else{
                                echo '<a href="/activate?name='.$plugin->name.'&type=1" class="activate">Turn on</a>';
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