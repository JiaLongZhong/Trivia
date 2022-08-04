CREATE TABLE Score
(
    id        int auto_increment,
    user_id   int,
    trivia_id int,
    score     int,
    `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,`modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    primary key (id),
    FOREIGN KEY (user_id) REFERENCES Users (id),
    FOREIGN KEY (trivia_id) REFERENCES Trivia (id)
);