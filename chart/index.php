<?php

// Debug level
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// DB config
define('DB_HOSTNAME', 'localhost');
define('DB_PORT', '3306');
define('DB_DATABASE', 'twister-stat');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'password');

// Init DB connection
try {
  $db = new PDO('mysql:dbname=' . DB_DATABASE . ';host=' . DB_HOSTNAME . ';port=' . DB_PORT . ';charset=utf8', DB_USERNAME, DB_PASSWORD, [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
  trigger_error($e->getMessage());
}

// Get total blocks
$query       = $db->query('SELECT COUNT(*) AS `total` FROM `block`');
$blocksTotal = $query->fetch()['total'];

// Get total users
$query       = $db->query('SELECT COUNT(*) AS `total` FROM `user`');
$usersTotal  = $query->fetch()['total'];

// Get stats
$query       = $db->query('SELECT YEAR(FROM_UNIXTIME(`time`)) AS `year`,
                                  MONTH(FROM_UNIXTIME(`time`)) AS `month`,
                                  COUNT(`block`.`blockId`) AS `blocks`,

                                  SUM((SELECT COUNT(`user`.`userId`) FROM `user`
                                              WHERE `user`.`blockId` = `block`.`blockId`
                                              GROUP BY `user`.`blockId`)) AS `users`

                                  FROM `block`

                                  GROUP BY `year`, `month`
                                  ORDER BY `year`, `month`
');

$stats      = $query->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <meta charset="UTF-8" />
      <title>twister-stat</title>
    </head>
<body>
  <canvas id="chart"></canvas>
  <script>

    const labels = [];

    <?php foreach ($stats as $stat) { ?>
      labels.push('<?php echo $stat['year']; ?>.<?php echo $stat['month']; ?>');
    <?php } ?>

    const blocks = [<?php foreach ($stats as $stat) { echo sprintf('%s,', $stat['blocks']); } ?>];
    const users  = [<?php foreach ($stats as $stat) { echo sprintf('%s,', $stat['users']); } ?>];

    const data = {
      labels: labels,
      datasets: [
        {
          label: 'blocks',
          data: blocks,
          borderColor: '#555',
          fill: true,
          scaleOverride : false,
        }, {
          label: 'users',
          data: users,
          borderColor: '#999',
          fill: true,
          scaleOverride : false,
        }
      ]
    };

    const config = {
      type: 'line',
      data: data,
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: "blocks: <?php echo $blocksTotal; ?> | users: <?php echo $usersTotal; ?>"
          },
        },
        interaction: {
          intersect: false,
        },
        scales: {
          x: {
            display: true,
            title: {
              display: true
            }
          },
          y: {
            display: true,
            title: {
              display: true,
              text: 'Quantity'
            }
          }
        }
      },
    };

    const twisterStat = new Chart(document.getElementById('chart'), config);

  </script>
</body>
</html>
