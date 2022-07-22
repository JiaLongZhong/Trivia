const _question = document.getElementById('question');
const _options = document.querySelector('.quiz-options');
const _checkBtn = document.getElementById('check-answer');
const _playAgainBtn = document.getElementById('play-again');
const _result = document.getElementById('result');
const _questionNum = document.getElementById('question-number');
const _totalQuestion = document.getElementById('total-question');
const _timer = document.getElementById('timer');
const _totalScore = document.getElementById('total-score');

let correctAnswer = "", questionNum = 0, totalQuestion = 10, point=1000, totalScore=0, mutiplers=1, time = 60, rapidpoint=0;

// load question from API
async function loadQuestion(){
    const APIUrl = 'https://opentdb.com/api.php?amount=1&type=multiple';
    const result = await fetch(`${APIUrl}`)
    const data = await result.json();
    _result.innerHTML = "";
    showQuestion(data.results[0]);
}

//timer sections
var interval = setInterval(function(){
  document.getElementById('timer').innerHTML=time;
  time--;
  if (time === 0){
    document.getElementById('timer').innerHTML='Done';
    mutiplers = 1;
    //quizconsolelog();
    _result.innerHTML = `<p><i class = "fas fa-times"></i>Time is up!</p> <small><b>Correct Answer: </b>${correctAnswer}</small>`;
    checkCount();
    _checkBtn.disabled = false;

  }
}, 1500);

// event listeners
function eventListeners(){
    _checkBtn.addEventListener('click', checkAnswer);
    _playAgainBtn.addEventListener('click', restartQuiz);
}

document.addEventListener('DOMContentLoaded', function(){
    loadQuestion();
    eventListeners();
    _totalQuestion.textContent = totalQuestion;
    _questionNum.textContent = questionNum;
    _totalScore.textContent = Math.round(totalScore);
    _timer.textContent = time;
});


// display question and options
function showQuestion(data){
    _checkBtn.disabled = false;
    correctAnswer = data.correct_answer;
    let incorrectAnswer = data.incorrect_answers;
    let optionsList = incorrectAnswer;
    optionsList.splice(Math.floor(Math.random() * (incorrectAnswer.length + 1)), 0, correctAnswer);
    // console.log(correctAnswer);

    
    _question.innerHTML = `${data.question} <br> <span class = "category"> ${data.category} <br> <span class = "difficulty">Difficulty: ${data.difficulty}</span>`;
    _options.innerHTML = `
        ${optionsList.map((option, index) => `
            <li> ${index + 1}. <span>${option}</span> </li>
        `).join('')}
    `;
    selectOption();

}

function quizconsolelog(){
    console.log(`Question:#${questionNum}`); 
    console.log(`Next Question Mutiplers:${mutiplers}`)
    console.log(`TotalScore:${totalScore}`);
}


// options selection
function selectOption(){
    _options.querySelectorAll('li').forEach(function(option){
        option.addEventListener('click', function(){
            if(_options.querySelector('.selected')){
                const activeOption = _options.querySelector('.selected');
                activeOption.classList.remove('selected');
            }
            option.classList.add('selected');
        });
    });
}

// answer checking
function checkAnswer(){
    _checkBtn.disabled = true;
    if(_options.querySelector('.selected')){
        let selectedAnswer = _options.querySelector('.selected span').textContent;
        if(selectedAnswer == HTMLDecode(correctAnswer)){
            rapidpoint = 1/time * point;
            totalScore += (point-rapidpoint) * mutiplers;
            mutiplers += 0.1;
            //quizconsolelog();
            _result.innerHTML = `<p><i class = "fas fa-check"></i>Correct Answer!</p>`;
        } else {
            mutiplers = 1;
            //quizconsolelog();
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


function checkCount(){
   questionNum++;
    time = 60;
    setCount();
    
    if(questionNum == totalQuestion){
        setTimeout(function(){
            console.log("");
        }, 5000);

        totalScore = Math.round(totalScore);
        _result.innerHTML += `<p>Your score is ${totalScore}!</p>`;
        _playAgainBtn.style.display = "block";
        _checkBtn.style.display = "none";
        clearInterval(interval);
    } else {
        setTimeout(function(){
            loadQuestion();
        }, 1000);
    }
}

function setCount(){
    _totalQuestion.textContent = totalQuestion;
    _questionNum.textContent = questionNum;
    _totalScore.textContent = Math.round(totalScore);
    _timer.textContent = time;
}


function restartQuiz(){
    questionNum = 0;
    totalScore = 0;
    _playAgainBtn.style.display = "none";
    _checkBtn.style.display = "block";
    _checkBtn.disabled = false;
    setCount();
    loadQuestion();
}
