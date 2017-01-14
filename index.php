<?php
/*
form
	scegli nome sfidante e colore
	aggiungi avversario / elimina avversario (js)
	avvia

while tutti sono su arrivo (in metri) (una static dove sommo man mano che arrivano)
	ad ogni partecipante settiamo uno spostamento in metri random
	e lo spostiamo in avanti

	salviamo tutto all'interno di una struttura dati che passeremo a javascript:
		turno 1:
			auto1
			metri percorsi

			auto2
			metri percorsi
		turno 2:
			auto1
			metri percorsi

			auto2
			metri percorsi

restituisco le posizioni e la struttura dati

le faccio vedere con js *-*
*/



class Car {
	const OFF = 0;
	const ON = 1;

	private static $carsCount = 0;

	private $color;
	private $owner;

	private $state;
	private $speed;
	private $mileage;

	private $position;
	private $arrived;
	private $trend = [];

	public static function getCarsCount(){
		return self::$carsCount;
	}

	public function __construct() {
		$this::$carsCount++;
		$this->state = $this::OFF;
		$this->mileage = 0;
		$this->position = 0;
		$this->arrived = false;
		if (func_num_args() === 2) {
			$this->owner = func_get_arg(0);
			$this->color = func_get_arg(1);
		} else {
			$this->owner = 'user';
			$this->color = 'random';
		}
	}

	public function turnOn() {
		if ($this->state === $this::OFF) {
			$this->state = $this::ON;
			$this->speed = 0;
		}
	}

	public function turnOff() {
		if ($this->state === $this::ON) {
			$this->state = $this::OFF;
		}
	}

	public function accelerate() {
		if ($this->state === $this::ON) {
			++$this->speed;
		}
	}

	public function getState() {
		return $this->state;
	}

	public function getSpeed() {
		return $this->speed;
	}

	public function move() {
		if ($this->state === $this::ON && !$this->arrived) {
			$speed = rand(0, 50);
			array_push($this->trend, $speed);
			$this->position += $speed;
			if ($this->position >= GameController::TOTAL_SPACE) {
				$this->arrived = true;
				$info = [];
				array_push($info, $this->owner);
				array_push($info, $this->color);
				array_push($info, $this->trend);
				GameController::arrived($info);
			}
		}
	}
}

class GameController {
	private static $finishingOrder = [];
	const TOTAL_SPACE = 1000;

	public static function arrived($info) {
		array_push(self::$finishingOrder, $info);
	}

	public function __construct() {
		
	}

	public function start() {

	}

	public static function getArrived() {
		return self::$finishingOrder;
	}
}

$gaetano = new Car('Gaetano', '');
$nicolo = new Car('Nicolò', '');
$luca = new Car('Luca', '');
$andrea = new Car('Andrea', '');
$salvatore = new Car('Salvatore', '');
$mario = new Car('Mario', '');
$giovanni = new Car('Giovanni', '');
$concetto = new Car('Concetto', '');


$gaetano->turnOn();
$nicolo->turnOn();
$luca->turnOn();
$andrea->turnOn();
$salvatore->turnOn();
$mario->turnOn();
$giovanni->turnOn();
$concetto->turnOn();

$gc = new GameController();

while (count(GameController::getArrived()) < Car::getCarsCount()) {
	$gaetano->move();
	$nicolo->move();
	$luca->move();
	$andrea->move();
	$salvatore->move();
	$mario->move();
	$giovanni->move();
	$concetto->move();
}

$finishingOrder = GameController::getArrived();

$finishingOrder = json_encode($finishingOrder);

// foreach ($finishingOrder as $position => $competitor) {
// 	echo $position + 1 . '° ' . $competitor[0] . ' ' . $competitor[1] . '<br>';
// 	echo 'Trend: ';
// 	foreach ($competitor[2] as $value) {
// 		echo $value . ' - ';
// 	}
// 	echo '<br>';
// }

// echo json_encode($finishingOrder);

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="./assets/css/style.css">
</head>
<body>

	<div id="container">
	</div>

	<script type="text/javascript">
		function main() {
		    var gc = GameController.init(JSON.parse('<?=$finishingOrder?>'));
		}
	</script>
	<script src="./assets/js/script.js"></script>

</body>
</html>
