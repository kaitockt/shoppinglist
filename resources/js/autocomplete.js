// Reference: https://www.w3schools.com/howto/howto_js_autocomplete.asp
window.autocomplete = function(input, whitelist) {
    let currentFocus;
    input.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        closeAllLists();
        if(!val){return false}
        currentFocus = -1;

        a = document.createElement("div");
        a.id=this.id+"autocomplete-list"
        a.classList.add("autocomplete-items")

        this.parentNode.appendChild(a)

        whitelist.forEach(function(item){
            if(item.substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                b = document.createElement("div")

                //bold the matching letters
                b.innerHTML =
                "<strong>"+
                item.substr(0, val.length)+
                "</strong>"+
                item.substr(val.length)+
                "<input type='hidden' value='"+item+"'>"

                b.addEventListener("click", function(e){
                    input.value = this.getElementsByTagName("input")[0].value
                    closeAllLists();
                })
                a.appendChild(b);
            }
        })
    })

    input.addEventListener("keydown", function(e) {
        let x = document.getElementById(this.id + "autocomplete-list");
        if(x) x = x.getElementsByTagName("div");
        if(e.keyCode == 40){
            //Arrow Down Key is pressed
            ++currentFocus;
            addActive(x)
        } else if (e.keyCode == 38) {
            //Up Key is pressed
            --currentFocus;
            addActive(x)
        } else if(e.keyCode == 13) {
            //Enter Key is pressed
            e.preventDefault()
            if(currentFocus > -1){
                if(x) x[currentFocus].click();
            }
        }
    })

    function addActive(x){
        if(!x) return false;
        removeActive(x);
        if(currentFocus >= x.length) currentFocus = 0;
        if(currentFocus < 0) currentFocus = (x.length - 1);
        x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x) {
        for(var i = 0; i < x.length; ++i){
            x[i].classList.remove("autocomplete-active");
        }
    }

    function closeAllLists(element){
        let x = document.getElementsByClassName("autocomplete-items");
        for(i = 0; i < x.length; ++i){
            if(element != x[i] && element != input){
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }

    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
}