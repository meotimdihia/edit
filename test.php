<?php
class EventLoop
{
  protected $tick = 0;
  protected $callbacksForTick = array();
 
  public function start()
  {
    while ($this->callbacksForTick) {
      $this->tick++;
      $this->executeCallbacks();
    }
  }
 
  public function executeCallbacks()
  {
    echo "Tick is " . $this->tick . "<br/>";
    if (!isset($this->callbacksForTick[$this->tick])) {
      return; // no callback to execute
    }
    foreach ($this->callbacksForTick[$this->tick] as $callback) {
      call_user_func($callback, $this);
    }
    // clean up
    unset($this->callbacksForTick[$this->tick]);
  }
 
  public function executeLater($delay, $callback)
  {
    $this->callbacksForTick[$this->tick + $delay] []= $callback;
  }
}

class AsynchronousPan
{
  protected $eventLoop;
 
  public function __construct(EventLoop $eventLoop)
  {
    $this->eventLoop = $eventLoop;
  }
 
  public function warm($duration, $callback)
  {
    $this->eventLoop->executeLater($duration, $callback);
  }
}

$eventLoop = new EventLoop();
 
$plate = new stdClass();
 
$pastaPan = new AsynchronousPan($eventLoop);
$water = new stdClass();
echo "pastaPan: Starting to boil water<br/>";
$pastaPan->warm($duration = 10, function() use ($pastaPan, $plate, $water) {
  echo "pastaPan: Water is boiling<br/>";
  echo "pastaPan: Starting to boil spaghetti<br/>";
  $pastaPan->warm($duration = 8, function() use ($pastaPan, $plate, $water) {
    echo "pastaPan: Spaghetti is ready<br/>";
  });
});
 
$eventLoop->executeLater($delay = 7, function() use ($plate, $eventLoop) {
  $saucePan = new AsynchronousPan($eventLoop);  
  echo "saucePan: Starting to warm olive oil<br/>";
  $saucePan->warm($duration = 2, function() use($saucePan, $plate) {
    echo "saucePan: Olive oil is warm<br/>";
    echo "saucePan: Starting to cook the Mirepoix<br/>";
    $saucePan->warm($duration = 5, function() use($saucePan, $plate) {
      echo "saucePan: Mirepoix is ready to welcome tomato<br/>";
      echo "saucePan: Starting to cook tomato<br/>";
      $saucePan->warm($duration = 4, function() use($saucePan, $plate) {
        echo "saucePan: Tomato sauce is ready<br/>";
      });
    });
  });
});
 
nl2br($eventLoop->start());
