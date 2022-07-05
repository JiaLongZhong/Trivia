window.onload = sendApiRequest

async function sendApiRequest(){
    let response = await fetch('https://opentdb.com/api.php?amount=10&type=multiple');
    console.log(response)
    let data = await response.json()
    console.log(data)
    sendQuestion(data)
    useApiData(data)
}

function sendQuestion(data) {
	var category = data.results[0].category;
	var type = data.results[0].type;
	var difficulty= data.results[0].difficulty;
	var question = data.results[0].question;
	var correct_answer = data.results[0].correct_answer;
	var incorrect_answers1 = data.results[0].incorrect_answers[0];
	var incorrect_answers2 = data.results[0].incorrect_answers[1];
	var incorrect_answers3 = data.results[0].incorrect_answers[2];
	
	var window,location,href = "game.php?c=" + category + "&t=" + type + "&d=" + difficulty + "&q=" + question + "&ca=" + correct_answer + "&ia1=" + incorrect_answers1 + "&ia2=" + incorrect_answers2 + "&ia3=" + incorrect_answers3;
}

function useApiData(data){

    document.querySelector("#category").innerHTML = `Category: ${data.results[0].category}`
    document.querySelector("#difficulty").innerHTML = `Difficulty: ${data.results[0].difficulty}`
    document.querySelector("#question").innerHTML = `Question: ${data.results[0].question}`
    document.querySelector("#answer1").innerHTML = data.results[0].correct_answer
    document.querySelector("#answer2").innerHTML = data.results[0].incorrect_answers[0]
    document.querySelector("#answer3").innerHTML = data.results[0].incorrect_answers[1]
    document.querySelector("#answer4").innerHTML = data.results[0].incorrect_answers[2]
    /*$.ajax({
        type: "POST",
        url: "game.php",
        data: {
            category: data.results[0].category,
            difficulty: data.results[0].difficulty,
            question: data.results[0].question,
            answer1: data.results[0].correct_answer,
            answer2: data.results[0].incorrect_answers[0],
            answer3: data.results[0].incorrect_answers[1],
            answer4: data.results[0].incorrect_answers[2],
            trivia_id: data.results[0].id
        }
    });*/
    //$.post("game.php", {"question": data.results[0].question, "answer1": data.results[0].correct_answer, "answer2": data.results[0].incorrect_answers[0], "answer3": data.results[0].incorrect_answers[1], "answer4": data.results[0].incorrect_answers[2], "trivia_id": data.results[0].id});
}

let correctButton = document.querySelector("#answer1")
    correctButton.addEventListener("click",()=>{
        alert("Correct!")
        sendApiRequest()
     })