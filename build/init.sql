CREATE TABLE `alunni` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(20) NOT NULL,
  `cognome` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `certificazioni` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `alunno_id` INT(11) NOT NULL,
  `titolo` VARCHAR(100) NOT NULL,
  `votazione` INT(3) NOT NULL CHECK (`votazione` BETWEEN 0 AND 100),
  `ente` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`alunno_id`) REFERENCES `alunni`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `alunni` (`nome`, `cognome`) VALUES
('claudio', 'benve'),
('ivan', 'bruno');

INSERT INTO `certificazioni` (`alunno_id`, `titolo`, `votazione`, `ente`) VALUES
(1, 'Certificazione Python', 85, 'Coursera'),
(1, 'Certificazione SQL', 90, 'Udemy'),
(2, 'Certificazione Java', 78, 'Oracle');
