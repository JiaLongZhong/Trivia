ALTER TABLE `Questions`
    DROP FOREIGN KEY `Questions_ibfk_2`;

ALTER TABLE `Questions`
    FOREIGN KEY (`trivia_id`) REFERENCES `Trivia` (`id`),
    ON DELETE CASCADE;
