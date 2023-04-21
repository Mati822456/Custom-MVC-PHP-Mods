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
        <a href="/plugin">Plugins</a>
        <a class="active" href="/theme">Themes</a>
    </div>
    <section>
        <?php
            echo '<h2>Detected: '. count($themes). (count($themes) > 1 ? ' themes' : ' theme') .'</h2><div class="line"></div>';
            if(isset($_SESSION['error']['theme'])){
                echo '<div class="error">';
                foreach($_SESSION['error']['theme'] as $error){
                    echo $error.'</br>';
                }
                echo "</div>";
                unset($_SESSION['error']['theme']);
            }
            echo '<div class="themes">';
            foreach($themes as $theme){
                
                echo '<div class="card">';
                    echo '<img src=./public/mods/Themes/' . $theme->name . '/' . $theme->image . '>';
                    echo '<div class="line-1"> </div>';
                    echo '<div class="wrapper">';
                        echo '<p class="name">'.$theme->name.'</p>';
                        echo '<p class="date">Creation date: '.$theme->created.'</p>';
                        echo '<p class="version">Version: '.$theme->version.'</p>';
                        echo '<p class="description">'.$theme->description.'</p>';
                        echo '<div class="actions">';
                            if(in_array($theme->name, $active)){
                                echo '<a href="/deactivate?name='.$theme->name.'&type=2" class="deactivate">Turn off</a>';
                            }else{
                                echo '<a href="/activate?name='.$theme->name.'&type=2" class="activate">Turn on</a>';
                            }
                            // echo '<a href="/uninstall?name='.$theme->name.'&type=2" class="uninstall">Uninstall</a>';
                            printf('<a onClick="showModal(\'%s\', \'%s\', \'%s\', \'%s\');" class="uninstall">Uninstall</a>', 3, 'Do you want to uninstall this theme?', $theme->name, 2);
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        ?>
    </section>
</body>
</html>