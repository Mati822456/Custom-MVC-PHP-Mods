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
            if(isset($_SESSION['message']['theme'])){
                foreach($_SESSION['message']['theme'] as $key => $type){
                    foreach($type as $message){
                        echo '<div class="alert '.$key.'"><strong>'.$key.'!</strong> <span>'.$message.'</span></div>';
                    }
                }
            }
            if(isset($_SESSION['message'])){
                unset($_SESSION['message']);
            }
            echo '<div class="themes">';
            foreach($themes as $theme){
                
                echo '<div class="card">';
                    echo '<img src=./public/mods/themes/' . $theme->name . '/' . $theme->image . '>';
                    echo '<div class="line-1"> </div>';
                    echo '<a href="/show?name='.$theme->name.'" class="info"><i class="fa-solid fa-circle-info"></i></a>';
                    echo '<div class="wrapper">';
                        echo '<p class="name">'.$theme->name.'</p>';
                        echo '<p class="date">Creation date: '.$theme->created.'</p>';
                        echo '<p class="version">Version: '.$theme->version.'</p>';
                        if(isset($theme->requirements)){
                            if(!empty($theme->requirements)){
                                echo '<p class="requirements">';
                                    echo '<span class="warning">!</span> Require: ' . $theme->requirements[0] . (count($theme->requirements) > 1 ? ' +' . count($theme->requirements) - 1 : '');
                                    if(count($theme->requirements) > 1){
                                        echo '<span class="tooltip">';
                                        foreach(array_slice($theme->requirements, 1) as $element){
                                            echo '<span class="element">' . $element . '</span>';
                                        }
                                        echo '</span>';
                                    }
                                echo '</p>';
                            }
                        }
                        if(isset($theme->incompatible)){
                            if(!empty($theme->incompatible)){
                                echo '<p class="incompatible">';
                                    echo '<span class="warning">!</span> Incompatible: ' . $theme->incompatible[0] . (count($theme->incompatible) > 1 ? ' +' . count($theme->incompatible) - 1 : '');
                                    if(count($theme->incompatible) > 1){
                                        echo '<span class="tooltip">';
                                        foreach(array_slice($theme->incompatible, 1) as $element){
                                            echo '<span class="element">' . $element . '</span>';
                                        }
                                        echo '</span>';
                                    }
                                echo '</p>';
                            }
                        }
                        echo '<p class="description">'.$theme->description.'</p>';
                        echo '<div class="actions">';
                            if(!in_array($theme->name, $cannotRun)){
                                if(in_array($theme->name, $active)){
                                    echo '<a href="/deactivate?name='.$theme->name.'&type=2" class="deactivate">Turn off</a>';
                                }else{
                                    echo '<a href="/activate?name='.$theme->name.'&type=2" class="activate">Turn on</a>';
                                }
                            }
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