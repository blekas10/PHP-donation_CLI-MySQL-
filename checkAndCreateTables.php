<?php

function checkAndCreateTables($pdo) {
    $createCharitiesTable = "
        CREATE TABLE IF NOT EXISTS `charities` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `representativeEmail` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    $createDonationsTable = "
        CREATE TABLE IF NOT EXISTS `donations` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `donorName` VARCHAR(255) NOT NULL,
            `amount` DECIMAL(10, 2) NOT NULL,
            `charityId` INT(11) NOT NULL,
            `date` DATE NOT NULL,
            `time` TIME NOT NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`charityId`) REFERENCES `charities` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    try {
        // Execute table creation queries
        $pdo->exec($createCharitiesTable);
        $pdo->exec($createDonationsTable);
        echo "Checked and ensured tables exist.\n";
    } catch (PDOException $e) {
        echo "An error occurred while creating tables: " . $e->getMessage() . "\n";
        exit;
    }
}
?>