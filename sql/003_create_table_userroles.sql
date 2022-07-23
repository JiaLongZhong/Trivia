CREATE TABLE IF NOT EXISTS  `Userroles`
(
    `id`         int auto_increment not null,
    `user_id`    int not null,
    `role_id`  int not null,
    `created`    timestamp default current_timestamp,
    `modified`   timestamp default current_timestamp on update current_timestamp,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES Users(`id`),
    FOREIGN KEY (`role_id`) REFERENCES Roles(`id`),
    UNIQUE KEY (`user_id`, `role_id`)
)