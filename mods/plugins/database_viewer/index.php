<?php
    namespace App\Views;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include './Views/layouts/header.php'; ?>
    <style>
        :root{
            --table-th-background-color: #d2dae2;
            --table-header-background-color: rgba(210, 218, 226, 0.7);
            --table-hover: #ddd;
            --table-text-color: #000;
            --table-background-color: var(--background-color);
            --table-background-even: #f2f2f2;
            --table-border-color: #ddd;
        }
        table {
            border-collapse: collapse;
            width: calc(100% - 50px);
            background-color: var(--table-background-color);
            color: var(--table-text-color);
        }

        table td, table th {
            border: 1px solid var(--table-border-color);
            padding: 8px;
        }

        table tr:nth-child(even){background-color: var(--table-background-even);}

        table tr:has(:not(.table__header)):hover {background-color: var(--table-hover);}

        table th {
            padding: 8px;
            text-align: left;
            background-color: var(--table-th-background-color);
            color: var(--table-text-color);
        }
        .table__header{
            background-color: var(--table-header-background-color);
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="topnav">
        <a href="/">Home</a>
        <a href="/plugin">Plugins</a>
        <a href="/theme">Themes</a>
    </div>
    <section>
        <h2>Welcome to browsing the database</h2>
        <table>
            <tr>
                <td colspan="2" class="table__header">Plugins</td>
            </tr>
            <tr>
                <th>ID</th>
                <th>Name</th>
            </tr>
            <?php

                foreach($plugins as $plugin){
                    echo '<tr>';
                        echo '<td>'.$plugin->getId().'</td>';
                        echo '<td>'.$plugin->getName().'</td>';
                    echo '</tr>';
                }

            ?>
            <tr>
                <td colspan="2" class="table__header">Themes</td>
            </tr>
            <tr>
                <th>ID</th>
                <th>Name</th>
            </tr>
            <?php
            
                foreach($themes as $theme){
                    echo '<tr>';
                        echo '<td>'.$theme->getId().'</td>';
                        echo '<td>'.$theme->getName().'</td>';
                    echo '</tr>';
                }

            ?>
        </table>
    </section>
</body>
</html>