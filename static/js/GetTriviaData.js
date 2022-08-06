$(document).ready(function () {
    // make ajax call to get trivia data
    var urlParams = new URLSearchParams(window.location.search);

    //get the trivia id from url
    var trivia_id = urlParams.get('id');

    // get the GET variaibles from
    $.ajax({
        url: "get_trivia_info.php",
        type: "GET",
        dataType: "json",
        data: {
            "trivia_id": trivia_id.toString()
        },
        success:
            function (data) {
                const _question = document.getElementById('question');
                const _options = document.querySelector('.quiz-options');
                const _totalQuestion = document.getElementById('total-question');
                const _questionNum = document.getElementById('question-number');
                const _totalScore = document.getElementById('total-score');
                const _timer = document.getElementById('timer');
                const _checkBtn = document.getElementById('check-answer');
                const _playAgainBtn = document.getElementById('play-again');
                const _result = document.getElementById('result');

                let totalQuestion = Object.keys(data).length, questionNum = 1, time = 60;
                let correctAnswer = "", point = 1000, mutiplers = 1, rapidpoint = 0;
                var totalScore =0;
                //_questionNum.textContent = questionNum;
                //_totalQuestion.textContent = totalQuestion;
                //_totalScore.textContent = totalScore;

                loadQuestion();

                function loadQuestion() {
                    _result.innerHTML = "";
                    showQuestion(data);
                    console.log(data);
                }

                function showQuestion(data) {
                    var newData = data[questionNum].question
                    console.log(questionNum);
                    correctAnswer = data[questionNum].answers.correct;
                    //console.log(data[questionNum].answers.correct);
                    var options = []
                    var options1 = data[questionNum].answers[0], options2 = data[questionNum].answers[1], options3 = data[questionNum].answers[2], options4 = data[questionNum].answers.correct;
                    options.push(options1, options2, options3, options4)
                    function randomArrayShuffle(array) {
                        var currentIndex = array.length, temporaryValue, randomIndex;
                        while (0 !== currentIndex) {
                            randomIndex = Math.floor(Math.random() * currentIndex);
                            currentIndex -= 1;
                            temporaryValue = array[currentIndex];
                            array[currentIndex] = array[randomIndex];
                            array[randomIndex] = temporaryValue;
                        }
                        return array;
                    }

                    randomArrayShuffle(options);
                    console.log(options);
                    // display question and options
                    _question.innerHTML = `${newData} <br>`;
                    _options.innerHTML = `
                ${options.map((option, index) => `
                <li> ${index + 1}. <span>${option}</span> </li>
        `).join('')}
    `;
                    selectOption();
                };

                console.log(totalQuestion);


                //timer sections
                var interval = setInterval(function () {
                    document.getElementById('timer').innerHTML = time;
                    time--;
                    if (time === 0) {
                        document.getElementById('timer').innerHTML = 'Done';
                        mutiplers = 1;
                        quizconsolelog();
                        _result.innerHTML = `<p><i class = "fas fa-times"></i>Time is up!</p> <small><b>Correct Answer: </b>${correctAnswer}</small>`;
                        checkCount();
                        _checkBtn.disabled = false;

                    }
                }, 1000);

                // event listeners
                function eventListeners() {
                    _checkBtn.addEventListener('click', checkAnswer);
                    _playAgainBtn.addEventListener('click', restartQuiz);
                }

                document.addEventListener('DOMContentLoaded', function () {
                    loadQuestion();
                    eventListeners();
                    _totalQuestion.textContent = totalQuestion;
                    _questionNum.textContent = questionNum;
                    _totalScore.textContent = Math.round(totalScore);
                    _timer.textContent = time;
                });


                function quizconsolelog() {
                    console.log(`Question:#${questionNum}`);
                    console.log(`Next Question Mutiplers:${mutiplers}`)
                    console.log(`TotalScore:${totalScore}`);
                }


                // options selection
                function selectOption() {
                    _options.querySelectorAll('li').forEach(function (option) {
                        option.addEventListener('click', function () {
                            if (_options.querySelector('.selected')) {
                                const activeOption = _options.querySelector('.selected');
                                activeOption.classList.remove('selected');
                            }
                            option.classList.add('selected');
                        });
                    });
                }

                // answer checking
                $("#check-answer").on("click", function () {
                    console.log("clicked");
                    checkAnswer();
                });
                $("#play-again").on("click", function () {
                    restartQuiz();
                });
                function checkAnswer() {
                    _checkBtn.disabled = false;
                    console.log(correctAnswer);
                    if (_options.querySelector('.selected')) {
                        let selectedAnswer = _options.querySelector('.selected span').textContent;
                        if (selectedAnswer == HTMLDecode(correctAnswer)) {
                            rapidpoint = 1 / time * point;
                            totalScore += (point - rapidpoint) * mutiplers;
                            mutiplers += 0.1;
                            quizconsolelog();
                            _result.innerHTML = `<p><i class = "fas fa-check"></i>Correct Answer!</p>`;
                        } else {
                            mutiplers = 1;
                            quizconsolelog();
                            _result.innerHTML = `<p><i class = "fas fa-times"></i>Incorrect Answer!</p> <small><b>Correct Answer: </b>${correctAnswer}</small>`;
                        }
                        checkCount();
                    } else {
                        _result.innerHTML = `<p><i class = "fas fa-question"></i>Please select an option!</p>`;
                        _checkBtn.disabled = false;
                    }
                }

                // to convert html entities into normal text of correct answer if there is any
                function HTMLDecode(textString) {
                    let doc = new DOMParser().parseFromString(textString, "text/html");
                    return doc.documentElement.textContent;
                }


                function checkCount() {
                    time = 60;
                    if (questionNum < totalQuestion) {
                        questionNum++;
                        setTimeout(function () {
                            loadQuestion();
                            setCount();
                        }, 1000);
                    } else {
                        setTimeout(function () {
                            console.log("Trivia Over!");
                            setCount();
                        }, 5000);

                        totalScore = Math.round(totalScore);
                        _result.innerHTML += `<p>Your score is ${totalScore}!</p>`;
                        _playAgainBtn.style.display = "block";
                        _checkBtn.style.display = "none";
                        clearInterval(interval);
                    }
                }

                function setCount() {
                    _totalQuestion.textContent = totalQuestion;
                    _questionNum.textContent = questionNum;
                    _totalScore.textContent = Math.round(totalScore);
                    _timer.textContent = time;
                }


                function restartQuiz() {
                    questionNum = 1;
                    totalScore = 0;
                    _playAgainBtn.style.display = "none";
                    _checkBtn.style.display = "block";
                    _checkBtn.disabled = false;
                    setCount();
                    loadQuestion();
                }

            },
    });

});
