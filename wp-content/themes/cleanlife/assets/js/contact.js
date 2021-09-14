function myFunction() {
    var checkBox = document.getElementById("check");
    var btn = document.getElementById("submit_btn");
    if (checkBox.checked == true){
        btn.disabled = false;
        btn.classList.remove("submit-btn-disable");
        btn.classList.add("submit-btn");
    } else {
        btn.disabled = true;
        btn.classList.add("submit-btn-disable");
        btn.classList.remove("submit-btn");
    }
  }
