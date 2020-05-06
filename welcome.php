<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <title>Number Guessing Game</title>

   <style>
      html {
         font-family: sans-serif;
      }

      body {
         width: 50%;
         max-width: 800px;
         min-width: 480px;
         margin: 0 auto;
      }
   </style>
</head>

<body>
   <h1>Welcome <?php echo $_SESSION['id']; ?></h1>
   <h2><a href="logout.php">Sign Out</a></h2>

   <h1>Guess The Number</h1>

   <p>We have selected a random number between 1 - 1000.
      See if you can guess it.</p>

   <div class="form">
      <label for="guessField">Enter a guess: </label>
      <input type="text" id="guessField" class="guessField">
      <input type="submit" value="Submit guess" class="guessSubmit" id="submitguess">
   </div>

   <input type="checkbox" name="box" value="high">High
   <input type="checkbox" name="box" value="low">Low
   <input type="checkbox" name="box" value="hm">HolyMoly
   <br>
   <table>
      <br>
      <tr>
         <td class="try"></td>
      </tr>
      <tr>
         <td class="try"></td>
      </tr>
      <tr>
         <td class="try"></td>
      </tr>
      <tr>
         <td class="try"></td>
      </tr>
      <tr>
         <td class="try"></td>
      </tr>
   </table>


   <script type="text/javascript">
      // random value generated 
      var y = Math.floor(Math.random() * 1000 + 1);

      // counting the number of guesses 
      // made for correct Guess 
      var guess = 1;
      var i = 0;

      document.getElementById("submitguess").onclick = function() {
         box = document.getElementsByName('box');
         // number guessed by user	 
         var x = document.getElementById("guessField").value;
         document.getElementsByClassName("try")[i].innerHTML = x;
         if (x == y) {
            alert("CONGRATULATIONS!!! YOU GUESSED IT RIGHT IN " +
               guess + " GUESS ");
            box[2].checked = true;
            box[1].checked = false;
            box[0].checked = false;
            i++;
            if (guess == 6) {
               window.alert("Answer: " + y);
               alert('Game over! Fuck off');
               return;
            }
         } else if (x > y)
         /* if guessed number is greater 
				than actual number*/
         {
            guess++;
            alert("OOPS SORRY!! TRY A SMALLER NUMBER");
            box[0].checked = true;
            box[2].checked = false;
            box[1].checked = false;
            i++;
            if (guess == 6) {
               window.alert("Answer: " + y);
               alert('Game over! Fuck off');
               return;
            }
         } else {
            guess++;
            alert("OOPS SORRY!! TRY A GREATER NUMBER")
            box[1].checked = true;
            box[0].checked = false;
            box[2].checked = false;
            i++;
            if (guess == 6) {
               window.alert("Answer: " + y);
               alert('Game over! Fuck off');
               return;
            }
         }
      }
   </script>

   <a href="profile.html">edit profile</a>
</body>

</html>