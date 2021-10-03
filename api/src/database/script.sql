CREATE TABLE `tbLocations`
(
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `description` TEXT NOT NULL
);

CREATE TABLE `tbExtinguishers`
(
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `idLocation` INTEGER NOT NULL,
    `validate` DATETIME NOT NULL,
    FOREIGN KEY (`idLocation`) REFERENCES `tbLocations` (`id`)
);
