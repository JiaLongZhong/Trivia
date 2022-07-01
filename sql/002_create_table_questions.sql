CREATE TABLE IF NOT EXISTS 'Questions' (
    'id' INTEGER NOT NULL AUTO_INCREMENT
    ,'question' VARCHAR(255) NOT NULL
    ,'correct_answer' VARCHAR(100) NOT NULL
    ,'category' VARCHAR(30) NOT NULL
    ,'Difficulty' VARCHAR(30) NOT NULL
    ,'incorrect_answers' VARCHAR(255) NOT NULL
    ,'created' TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,'modified' TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,PRIMARY KEY('id')
    ,UNIQUE ('question')
);