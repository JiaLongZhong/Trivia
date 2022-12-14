
CREATE TABLE IF NOT EXISTS `Roles` (
    `id`         int auto_increment not null,
    `name`      varchar(100)       not null unique,
    `description` varchar(100) default '',
    `created`    timestamp NOT NULL default current_timestamp,
    `modified`   timestamp NOT NULL default current_timestamp on update current_timestamp,
    PRIMARY KEY (`id`),
    UNIQUE (`name`)
);
