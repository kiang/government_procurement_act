<?php
$f = file_get_contents(__DIR__ . '/base.txt');
$lines = explode("\n", $f);
$headerBegin = false;
$headerCount = 0;
$header = $quiz = $qa = array();
foreach($lines AS $line) {
  if(substr($line, -3) === '題' && strlen($line) === 9) {
    $headerBegin = true;
    $header = $quiz = array();
    $headerCount = 0;
  } elseif($headerBegin) {
    if($line == 1) {
      $headerCount = count($header);
      $headerBegin = false;
    } else {
      $header[] = trim($line);
    }
  }
  if(!$headerBegin && $headerCount > 0) {
    if(count($quiz) < $headerCount) {
      $quiz[] = $line;
    } else {
      $lineQuiz = array_combine($header, $quiz);
      if(isset($lineQuiz['依據法源'])) {
        $lineQuiz['試題'] = "({$lineQuiz['依據法源']}){$lineQuiz['試題']}";
      }
      switch($lineQuiz['答案']) {
        case 'O':
        case 'X':
          $qa[] = array(
            'quiz' => $lineQuiz['試題'],
            'options' => array('O' => 'O', 'X' => 'X'),
            'answer' => $lineQuiz['答案'],
          );
          break;
        default:
          $chunks = preg_split('/\\([1-4]\\)/', $lineQuiz['試題']);
          $qa[] = array(
            'quiz' => $chunks[0],
            'options' => array(
              '1' => $chunks[1],
              '2' => $chunks[2],
              '3' => $chunks[3],
              '4' => $chunks[4],
            ),
            'answer' => $lineQuiz['答案'],
          );
      }
      $quiz = array();
      $quiz[] = $line;
    }
  }
}
file_put_contents(__DIR__ . '/qa.json', json_encode($qa, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
