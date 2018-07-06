document.addEventListener("DOMContentLoaded", function() {
    // var t = document.createElement("p");

    t = document.querySelector("#container");
    for(i =0; i< localStorage.length; i++){
        k = localStorage.key(i);
        if(/^imgs:/.test(k)){
            imgs = JSON.parse(localStorage.getItem(k));
            line = document.createElement("tr");
            line_child = document.createElement("td");
            content = document.createElement("span");
            content.innerHTML = k;
            line_child.col_span = 3;

            line_child.append(content)
            line.append(line_child)
            t.append(line);

            var count = 0;
            var last = 0;
            var td, pic;

            imgs.forEach(function (i) {
                if(count % 3 == 0){
                    tr = document.createElement("tr");
                }

                pic = document.createElement("img");
                pic.src = i;
                if(pic.naturalHeight < 200 || pic.naturalWidth < 200){

                }else{
                    pic.height=200;
                    pic.width=200;
                    td = document.createElement("td")
                    td.append(pic)
                    tr.append(td)
                    count++;
                    if(count% 3 == 0){
                        t.append(tr);
                    }
                }
                t.append(tr)

            });

        }
    }


    // consol
    // imgs = JSON.parse(localStorage.getItem("imgs"))



});