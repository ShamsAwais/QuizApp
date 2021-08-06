<?php
  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style1.css">
	<style>
	.question{
  font-size: 30px;
  margin-bottom: 10px;
}
.answers {
  margin-bottom: 20px;
  text-align: left;
  display: inline-block;
}
.answers label{
  display: block;
  margin-bottom: 10px;
}
button{
  font-family: 'Work Sans', sans-serif;
	font-size: 22px;
	background-color: #279;
	color: #fff;
	border: 0px;
	border-radius: 3px;
	padding: 20px;
	cursor: pointer;
	margin-bottom: 20px;
}
button:hover{
	background-color: #38a;
}
.slide{
  position: absolute;
  left: 0px;
  top: 0px;
  width: 100%;
  z-index: 1;
  opacity: 0;
  transition: opacity 0.5s;
}
.active-slide{
  opacity: 1;
  z-index: 2;
}
.quiz-container{
  position: relative;
  height: 200px;
  margin-top: 40px;
}
            
progress {
    width: 70%;
    height: 30px;
    color: #0000FF;
    background: #efefef;
}
progress::-webkit-progress-bar {
    background: #efefef;
}
progress::-webkit-progress-value {
    background: #0000FF;
} 
progress::-moz-progress-bar {
    color: #0000FF;
    background: #efefef;
}

	</style>
</head>
<body>

<div class="header">
	<h2><u>Quiz Page</u></h2>
</div>
<div class="content">
<?php  if (isset($_SESSION['username'])) : ?>
		<p> <a href="index.php?logout='1'" class="mybtn">logout</a> </p><br/>
		<h2>Welcome <strong><?php echo $_SESSION['username']; ?></strong></h2><br/><br/>
	<?php endif ?>
</div>
<?php
	include 'quizclass.php';
	$db = new Quiz();
	$quesions = $db->get_questions();

?>
<div class="container">
<h1>Multiple Choice Questions Answers</h1>
<h3><i>Please fill the details and answers the all questions-</i></h3>
<div class="form-group">
<div class="quiz-container">
  <div id="quiz"></div>
</div>
<button id="previous">Previous Question</button>
<button id="next">Next Question</button>
<button id="submit">Submit Quiz</button>
<div id="myDiv"></div>
<div id="results"></div>
<div id="myprogress"></div>
</div>
<div class="content">
  	<!-- notification message 
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>-->
	<!-- logged in user information -->
</div>

<script>
(function(){
  // Functions
  function buildQuiz(){
    // variable to store the HTML output
    const output = [];

    // for each question...
    myQuestions.forEach(
      (currentQuestion, questionNumber) => {

        // variable to store the list of possible answers
        const answers = [];

        // and for each available answer...
        for(letter in currentQuestion.answers){

          // ...add an HTML radio button
          answers.push(
            `<label>
              <input type="radio" name="question${questionNumber}" value="${letter}">
              ${letter} :
              ${currentQuestion.answers[letter]}
            </label>`
          );
        }

        // add this question and its answers to the output
        output.push(
          `<div class="slide">
            <div class="question"> ${currentQuestion.question} </div>
            <div class="answers"> ${answers.join("")} </div>
          </div>`
        );
      }
    );

    // finally combine our output list into one string of HTML and put it on the page
    quizContainer.innerHTML = output.join('');
  }

  function showResults(){

    // gather answer containers from our quiz
    const answerContainers = quizContainer.querySelectorAll('.answers');

    // keep track of user's answers
    let numCorrect = 0;
	<?php $score=0; ?>
    // for each question...
    myQuestions.forEach( (currentQuestion, questionNumber) => {

      // find selected answer
      const answerContainer = answerContainers[questionNumber];
      const selector = `input[name=question${questionNumber}]:checked`;
      const userAnswer = (answerContainer.querySelector(selector) || {}).value;

      // if answer is correct
      if(userAnswer === currentQuestion.correctAnswer){
        // add to the number of correct answers
        numCorrect++;
        // color the answers green
        answerContainers[questionNumber].style.color = 'lightgreen';
      }
      // if answer is wrong or blank
      else{
        // color the answers red
        answerContainers[questionNumber].style.color = 'red';
      }
    });

    // show number of correct answers out of total
    resultsContainer.innerHTML = `${numCorrect} out of ${myQuestions.length}`;
	progressContainer.innerHTML = `<progress min="0" max="100" value="${numCorrect*20}"></progress>`;
	//location.assign(`#?score=${numCorrect*20}`);
	window.open(`myscore.php?score=${numCorrect*20}`, "_blank");
	mydiv.innerHTML=`<button id="reset" onclick="location.reload();">Restart Quiz</button>`;
  }

  function showSlide(n) {
    slides[currentSlide].classList.remove('active-slide');
    slides[n].classList.add('active-slide');
    currentSlide = n;
    if(currentSlide === 0){
      previousButton.style.display = 'none';
    }
    else{
      previousButton.style.display = 'inline-block';
    }
    if(currentSlide === slides.length-1){
      nextButton.style.display = 'none';
      submitButton.style.display = 'inline-block';
    }
    else{
      nextButton.style.display = 'inline-block';
      submitButton.style.display = 'none';
    }
  }

  function showNextSlide() {
    showSlide(currentSlide + 1);
  }

  function showPreviousSlide() {
    showSlide(currentSlide - 1);
  }<?php
  /*$i=1;
  foreach($quesions as $ques) {
$options = $db->quiz_options($ques[0]);
$q=$ques[1];
$i++;
}*/?>

  // Variables
  const quizContainer = document.getElementById('quiz');
  const resultsContainer = document.getElementById('results');
  const submitButton = document.getElementById('submit');
  const progressContainer = document.getElementById('myprogress');
  const mydiv = document.getElementById('myDiv');
  const myQuestions = [
    { 
      question: "<?php echo $quesions[0][1];?>",
	  <?php $options = $db->quiz_options($quesions[0][0]);?>
      answers: {
        a: "<?php echo $options[0][2];?>",
        b: "<?php echo $options[1][2];?>",
        c: "<?php echo $options[2][2];?>",
		d: "<?php echo $options[3][2];?>"
      },
      correctAnswer: "b"
    },
    {
      question: "<?php echo $quesions[1][1];?>",
      <?php $options = $db->quiz_options($quesions[1][0]);?>
      answers: {
        a: "<?php echo $options[0][2];?>",
        b: "<?php echo $options[1][2];?>",
        c: "<?php echo $options[2][2];?>",
		d: "<?php echo $options[3][2];?>"
      },
      correctAnswer: "a"
    },
    {
      question: "<?php echo $quesions[2][1];?>",
      <?php $options = $db->quiz_options($quesions[2][0]);?>
      answers: {
        a: "<?php echo $options[0][2];?>",
        b: "<?php echo $options[1][2];?>",
        c: "<?php echo $options[2][2];?>",
		d: "<?php echo $options[3][2];?>"
      },
      correctAnswer: "c"
    },
	{
      question: "<?php echo $quesions[3][1];?>",
      <?php $options = $db->quiz_options($quesions[3][0]);?>
      answers: {
        a: "<?php echo $options[0][2];?>",
        b: "<?php echo $options[1][2];?>",
        c: "<?php echo $options[2][2];?>",
		d: "<?php echo $options[3][2];?>"
      },
      correctAnswer: "d"
    },
	{
      question: "<?php echo $quesions[4][1];?>",
      <?php $options = $db->quiz_options($quesions[4][0]);?>
      answers: {
        a: "<?php echo $options[0][2];?>",
        b: "<?php echo $options[1][2];?>",
        c: "<?php echo $options[2][2];?>",
		d: "<?php echo $options[3][2];?>"
      },
      correctAnswer: "c"
    }
  ];

  // Kick things off
  buildQuiz();

  // Pagination
  const previousButton = document.getElementById("previous");
  const nextButton = document.getElementById("next");
  const slides = document.querySelectorAll(".slide");
  let currentSlide = 0;

  // Show the first slide
  showSlide(currentSlide);

  // Event listeners
  submitButton.addEventListener('click', showResults);
  previousButton.addEventListener("click", showPreviousSlide);
  nextButton.addEventListener("click", showNextSlide);
})();

</script>

</body>
</html>