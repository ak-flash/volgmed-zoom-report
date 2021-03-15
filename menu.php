<?php
switch ($menu) {
    case 'report': $report = 'success disabled'; 
                    $planner = 'secondary'; 
                    $rating = 'secondary';
                    $moodle = 'secondary';
        break;
    case 'planner': $report = 'secondary'; 
                    $planner = 'success disabled'; 
                    $rating = 'secondary';
                    $moodle = 'secondary';
        break;
    case 'rating': $report = 'secondary'; 
                    $planner = 'secondary'; 
                    $rating = 'success disabled';
                    $moodle = 'secondary';
        break;
    case 'moodle': $report = 'secondary'; 
                    $planner = 'secondary'; 
                    $rating = 'secondary';
                    $moodle = 'success disabled';
        break;
}

echo    '<a href="index.php" class="btn btn-'.$report.' my-2">
            Отчёт о посещении
        </a>
        <a href="planner.php" class="btn btn-'.$planner.' my-2">
            Планирование лекций
        </a>
        <a href="rating.php" class="btn btn-'.$rating.' my-2">
            Рассчёт рейтинга
        </a>
        <a href="moodle.php" class="btn btn-'.$moodle.' my-2">
            Moodle
        </a>';
