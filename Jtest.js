function loadDoc() {
  let s = document.getElementById("search-bar").value;
  let g = document.getElementsByTagName('input');
  for(i = 0; i < g.length; i++) {
      if(g[i].type=="radio"){
      if(g[i].checked)
          var cust = g[i].value;
  }
}
  let sent = "action=searchfunc&searched=" + s + "&cust=" + cust;

var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
document.getElementById("main-search").innerHTML = this.responseText;
}
};
xhttp.open("POST", "Jtest.php", true);
xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhttp.send(sent);
fadeIn();

}

function execute(rid){

  let sent = "action=resinfo&rid="+rid;

  var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
document.getElementById("res-table").innerHTML = this.responseText;

}
};
xhttp.open("POST", "Jtest.php", true);
xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhttp.send(sent);
fadeIn();
}

function fadeIn(){
  var ele = document.getElementsByClassName("search-div")
  var op = 0;
  var id=setInterval(fade,100)
  function fade(){
    
    op += 0.1;
        for (var i = 0; i < ele.length; i++) {
            ele[i].style.opacity = op;
        }
        if (op >= 1) {
            clearInterval(id);
        }

  }

}

