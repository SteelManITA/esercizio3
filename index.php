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
</head>
<body>
<style type="text/css">
	html, body {
		padding: 0;
		margin: 0;
	}
	#container,
	#container > div.car {
		-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
        -moz-box-sizing: border-box;    /* Firefox, Gecko */
        box-sizing: border-box;         /* Opera/IE 8+ */
	}

	#container {
		position: relative;
		width: 98vw;
		height: 98vh;
		left: 1vw;
		top: 1vh;
		border-left: 2px solid black;
		border-right: 2px solid black;
		overflow: hidden;
	}

	#container > div.car {
		position: absolute;
		background-color: blue;
		border-top: 5px solid white;
		border-bottom: 5px solid white;
		width: 100px;
		height: 100px;
	}

</style>
	<div id="container">
	</div>

	<script type="text/javascript">

		var GameController = (function() {
		    "use strict";
		    var instance;
		    var participants;
		    return {
		        init: function(r) {
		            if (!instance) {
		            	participants = r;
		            	var maxMoves = 0;
		            	var cars = [];

						for (var participant of participants) {
							console.log(participant)
							var car = new Car(participant[0],participant[1],participant[2]);
							if (maxMoves < participant[2].length) {
								maxMoves = participant[2].length;
							}
							cars.push(car);
						}

						var i = 0;
						var interval = setInterval(function(){
							if (i < maxMoves) {
								for (var car of cars){
									car.move(i);
								}
								++i;
							} else {
								clearInterval(interval);
							}
						}, 100);

						setCarsLocation();

		                instance = true;
		            }
		            return instance;
		        }
		    };
		})();

		var Car = (function() {
			
		    function Car(_nick, _color, _trend) {
		        var nick = _nick;
		        var color = _color;
		        var trend = _trend;

		        var car;
		        createCar();

		        this.move = function(i) { // pubblica
		        	if (trend[i]) {
		        		car.style.left = car.offsetLeft + trend[i] + "px"; // ** trend[i] in percentuale rispetto alla corsa totale
		        	}
		        };

		        function createCar(){ // privata
		        	car = document.createElement('div');
		        	car.classList.add('car');
		        	car.textContent = nick;
		        	document.getElementById('container').appendChild(car);
				};
		    }
		    return Car;
		})();

		function setCarLocation(offset, participant, position, participants){
			participant.style.width = (offset) + "px";
			participant.style.height = (offset) + "px";
			participant.style.top = (position * offset) + "px";
		}

		function setCarsLocation(){
			var container = document.getElementById('container');
			var participants = container.querySelectorAll('.car');
			var offset = container.offsetHeight / participants.length;
			participants.forEach(setCarLocation.bind(this, offset));
		}


		

		// function main(){
			// var gc = GameController.init(object);


		function main(){
			var gc = GameController.init(JSON.parse('<?=$finishingOrder?>'));
		}


		document.addEventListener('DOMContentLoaded', main);
		window.addEventListener('resize', setCarsLocation);
	</script>
</body>
</html>
