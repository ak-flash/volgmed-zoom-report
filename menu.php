<?php
switch ($menu) {
    case 'report': $report = 'success disabled'; 
                    $planner = 'secondary'; 
                    $rating = 'secondary';
        break;
    case 'planner': $report = 'secondary'; 
                    $planner = 'success disabled'; 
                    $rating = 'secondary';
        break;
    case 'rating': $report = 'secondary'; 
                    $planner = 'secondary'; 
                    $rating = 'success disabled';
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
        </a>';
