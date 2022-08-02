CREATE TABLE scores
(
    id        int auto_increment,
    user_id   int,
    trivia_id int,
    score     int,
    primary key (id),
    FOREIGN KEY (user_id) REFERENCES Users (id),
    FOREIGN KEY (trivia_id) REFERENCES Trivia (id)
);