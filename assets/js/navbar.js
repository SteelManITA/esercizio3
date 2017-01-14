
function onDropdownClick() {
    this.classList.toggle('dropdown-open');
    this.addEventListener('mouseleave', function() {
        this.classList.remove('dropdown-open');
    });
}

function main() {
    var navbars = document.getElementsByClassName('navbar');
    for (var i = 0; i < navbars.length; ++i) {
        var dropdowns = navbars[i].getElementsByClassName('dropdown');
        for (var j = 0; j < dropdowns.length; ++j) {
            dropdowns[j].addEventListener('click', onDropdownClick);
        }
    }

    /* Burger */
    document.getElementById('navbar-burger').addEventListener('click', function(){
        this.parentElement.classList.toggle('navbar-side-open');
    });

    /* Search toggle*/
    var search_toggle = document.getElementsByClassName('navbar-search-toggle');
    for (var i = 0; i < search_toggle.length; ++i) {
        search_toggle[i].addEventListener('click', function(){
            document.getElementById('navbar-search').classList.toggle('navbar-search-active');
            document.getElementById('navbar-search-input').focus();
            var navmain = document.getElementById('navbar-main');
            if (navmain.classList.contains('navbar-side-open')) {
                navmain.classList.remove('navbar-side-open');
            }
        });
    }

    document.getElementById('navbar-search-clear').addEventListener('click', function() {
        document.getElementById('navbar-search-input').value = '';
        document.getElementById('navbar-search-input').focus();
    });




    /* Creazione dinamica menu */
    function addElements (typeElement, elementsName, event, func){
        if(typeElement && elementsName){
            for (i=0;i<elementsName.length;++i){
                newElement = document.createElement(typeElement);
                if(event && func){
                    newElement.addEventListener(event, func);
                }
                if(typeof elementsName === "string"){
                    newElement.textContent = elementsName;
                    this.appendChild(newElement);
                    break;
                }else{
                    newElement.textContent = elementsName[i];
                    this.appendChild(newElement);
                }
            }
        }else{
            console.error("Must define element's type and name!");
        }
    }

    function addElementsJSON(jsonElements){
        for(j=0;j<jsonElements.element.length;++j){
            if(jsonElements.element[j].typeElement && jsonElements.element[j].elementsName){
                for (i=0;i<jsonElements.element[j].elementsName.length;++i){
                    newElement = document.createElement(jsonElements.element[j].typeElement);
                    if(jsonElements.element[j].event && jsonElements.element[j].func){
                        newElement.addEventListener(jsonElements.element[j].event, jsonElements.element[j].func);
                    }
                    if(typeof jsonElements.element[j].elementsName === "string"){
                        newElement.textContent = jsonElements.element[j].elementsName;
                        this.appendChild(newElement);
                        break;
                    }else{
                        newElement.textContent = jsonElements.element[j].elementsName[i];
                        this.appendChild(newElement);
                    }
                }
            }else{
                console.error("Must define element's type and name!");
            }
        }

    }

    Element.prototype.addElements = addElements;
    Element.prototype.addElementsJSON = addElementsJSON;
    
    var navbarMenu = document.getElementsByClassName("navbar-menu")[0];

    /* Add from array/string */
    /*
    var elements = "Troll";
    //var elements = ["Asd","LoL","xD"];
    navbarMenu.addElements("li", elements);
    */

    /* Add from json */
    /*
    var elementJSON = {"element":[
        {
            "typeElement":"li",
            "elementsName":"LoL",
            "event":"",
            "func":""
        }
    ]};

    var elementsJSON = {"element":[
        {
            "typeElement":"li",
            "elementsName":"LoL",
            "event":"",
            "func":""
        },
        {
            "typeElement":"li",
            "elementsName":"Asd",
            "event":"",
            "func":""
        },
        {
            "typeElement":"li",
            "elementsName":"XD",
            "event":"",
            "func":""
        }
    ]};

    navbarMenu.addElementsJSON(elementsJSON);
    */

}

document.addEventListener('DOMContentLoaded', main);