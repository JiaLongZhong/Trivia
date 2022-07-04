CREATE TABLE Questions
(
    id        int auto_increment,
    question  varchar(200) not null,
    trivia_id int,
    user_id   int,	
    primary key (id),
    FOREIGN KEY (user_id) REFERENCES Users (id),
    FOREIGN KEY (trivia_id) REFERENCES Trivia (id)
)