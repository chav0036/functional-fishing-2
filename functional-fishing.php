<?php

/**********************************************
 * STARTER CODE
 **********************************************/

/**
 * clearSession
 * This function will clear the session.
 */

session_start();

function clearSession()
{
  session_unset();
  header("Location: " . $_SERVER['PHP_SELF']);
}

/**
 * Invokes the clearSession() function.
 * This should be used if your session becomes wonky
 */
if (isset($_GET['clear'])) {
  clearSession();
}

/**
 * getResponse
 * Gets the response history array from the session and converts to a string
 * 
 * This function should be used to get the full response array as a string
 * 
 * @return string
 */
function getResponse()
{
  return implode("<br><br>",$_SESSION['functional_fishing']['response']);
}

/**
 * updateResponse
 * Adds a new response to the response array found in session
 * Returns the full response array as a string
 * 
 * This function should be used each time an action returns a response
 * 
 * @param [string] $response
 * @return string
 */
function updateResponse($response)
{
  if (!isset($_SESSION['functional_fishing'])) {
    createGameData();
  }

  array_push($_SESSION['functional_fishing']['response'], $response);

  return getResponse();
}

/**
 * help
 * Returns a formatted string of game instructions
 * 
 * @return string
 */
function help()
{
  return 'Welcome to Functional Fishing, the text based fishing game. Use the following commands to play the game: <span class="red">eat</span>, <span class="red">fish</span>, <span class="red">fire</span>, <span class="red">wood</span>, <span class="red">bait</span>. To restart the game use the <span class="red">restart</span> command For these instruction again use the <span class="red">help</span> command';
}

/**********************************************
 * YOUR CODE BELOW
 **********************************************/

/**
 * createGameData
 * 
 */
function createGameData(){
  $_SESSION['functional_fishing'] = [

    'response' => [],
    'fish' => 0,
    'wood' => 0,
    'bait' => 0,
    'fire' => false
  ];
  return isset($_SESSION['functional_fishing']);
}

/**
 * fire
 *
 */
function fire()
{
  if ($_SESSION['functional_fishing']['fire']) {
    $_SESSION['functional_fishing']['fire'] = false;
    return "You have put out the fire";
  } else {
    if ($_SESSION['functional_fishing']['wood'] > 0) {
      $_SESSION['functional_fishing']['wood']--;
      $_SESSION['functional_fishing']['fire'] = true;
      return "You have started a fire";
    } else {
      return "You do not have enough wood";
    }
  }
}

/**
 * bait
 * 
 */
function bait(){
  if($_SESSION['functional_fishing']["fire"] === false){
    $_SESSION['functional_fishing']['bait'] ++;
    return "You have found {$_SESSION['functional_fishing']['bait']} bait";
  }else {
    return "You must put out the fire";
  }
}

/**
 * wood
 * 
 */
function wood(){
  if($_SESSION['functional_fishing']['fire'] === false){
    $_SESSION['functional_fishing']['wood'] ++;
    return "You have found {$_SESSION['functional_fishing']['wood']} piece of wood";
  }else{
    return "You must put out the fire'";
  }
}


/**
 * fish
 * 
 */
function fish(){
  if($_SESSION['functional_fishing']['fire'] ===  false){
   if($_SESSION['functional_fishing']['bait'] > 0){
   $_SESSION['functional_fishing']['bait'] --;
   $_SESSION['functional_fishing']['fish'] ++;
   return "You have found {$_SESSION['functional_fishing']['fish']} fish";
  }else{
    return "You must go and find bait";
  }
  }else{
    return "You must put out the fire";
  }
}

/**
 * eat
 * 
 */
function eat(){
  if($_SESSION['functional_fishing']['fire'] === true){
    if($_SESSION['functional_fishing']['fish'] > 0){
      $_SESSION['functional_fishing']['fish']--;
      return "Your fish is being cooked";
    }else if($_SESSION['functional_fishing']['fish'] <= 0){
      return "You do not have any fish";
    }
  }else{
    return "You must start the fire";
  }
}

/**
 * inventory
 * 
 */
function inventory(){
    $responses = '';
  
    foreach ($_SESSION['functional_fishing'] as $item => $value) {
      if ($item === 'fire') {
        if ($value) {
          $responses .= "The fire is going";
        } else {
          $responses .= "The fire is out";
        }
      } else if (!is_array($value)) {
        $responses .= "{$value} {$item}<br>";
      }
    }
      return $responses;
  }

/**
 * restart
 * 
 */
function restart()
{
  createGameData();

  return "The game has restarted";
}

/**
 * Create a response based on the players commands
 * - If the player has entered a command 
 *    - extract the command from the player's input
 *      - the explode function will split the input on the space separate the 
 *        command from the option
 *    - check if the entered command is a valid function using function_exists
 *      - check for a command option
 *        - execute command with option using the variable function technique
 *        - updateResponse with function's results
 *      - else
 *        - execute command using the variable function technique
 *        - updateResponse with function's results
 *    - else
 *      - updateResponse with invalid command  
 */
if (isset($_POST['command'])) {
  $command = explode(' ', strtolower($_POST['command']));
  if (function_exists($command[0])) {
    if (isset($command[1])) {
      updateResponse($command[0]($command[1]));
    } else {
      updateResponse($command[0]());
    }
  } else {
    updateResponse("{$_POST['command']} is not a valid command");
  }
}

?>