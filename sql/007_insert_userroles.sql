if not EXISTS (SELECT * FROM Roles WHERE name = 'admin') then
    INSERT INTO `Roles` (`id`, `name`, `description`, `created`, `modified`) VALUES ('1', 'admin', 'administrator', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
    INSERT INTO `Roles` (`id`, `name`, `description`, `created`, `modified`) VALUES ('2', 'trivia_creator', 'trivia creator', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
end if;