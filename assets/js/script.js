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
                    var car = new Car(participant[0], participant[1], participant[2]);
                    if (maxMoves < participant[2].length) {
                        maxMoves = participant[2].length;
                    }
                    cars.push(car);
                }

                var i = 0;
                var interval = setInterval(function() {
                    if (i < maxMoves) {
                        for (var car of cars) {
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

        function createCar() { // privata
            car = document.createElement('div');
            car.classList.add('car');
            car.textContent = nick;
            document.getElementById('container').appendChild(car);
        };
    }
    return Car;
})();

function setCarLocation(offset, participant, position, participants) {
    participant.style.width = (offset) + "px";
    participant.style.height = (offset) + "px";
    participant.style.top = (position * offset) + "px";
}

function setCarsLocation() {
    var container = document.getElementById('container');
    var participants = container.querySelectorAll('.car');
    var offset = container.offsetHeight / participants.length;
    participants.forEach(setCarLocation.bind(this, offset));
}




// function main(){
// var gc = GameController.init(object);


// function main() {
//     var gc = GameController.init(JSON.parse('<?=$finishingOrder?>'));
// }


document.addEventListener('DOMContentLoaded', main);
window.addEventListener('resize', setCarsLocation);
