function inputStar(x) {
    var inputStar = document.getElementsByClassName('input-star');
    var i;
    var j;
    for(i=0; i<5; i++) {
        if(inputStar[i] == x) {
            document.querySelector('input[name="remark"]').value = i + 1;
            for(j=0; j<i+1; j++) {
                if (!inputStar[j].classList.contains('input-star-selected')) {
                    inputStar[j].classList.add('input-star-selected');
                }
            }
            for(j=i+1; j<5; j++) {
                if (inputStar[j].classList.contains('input-star-selected')) {
                    inputStar[j].classList.remove('input-star-selected');
                }
            }
        }
    }
}
function inputAvatar(x) {
    var inputAvatar = document.getElementsByClassName('input-avatar');
    var i;
    for(i=0; i<4; i++) {
        if(inputAvatar[i] == x) {
            document.querySelector('input[name="avatar"]').value = i + 1;
            if(!x.classList.contains('input-avatar-selected')) {
                x.classList.add('input-avatar-selected');
            }
            for(j=0; j<i; j++) {
                if (inputAvatar[j].classList.contains('input-avatar-selected')) {
                    inputAvatar[j].classList.remove('input-avatar-selected');
                }
            }
            for(j=i+1; j<4; j++) {
                if (inputAvatar[j].classList.contains('input-avatar-selected')) {
                    inputAvatar[j].classList.remove('input-avatar-selected');
                }
            }
        }
    }
}

function reviewShow() {
    document.getElementById('review').classList.toggle('review-show');
    document.querySelector('input[name="reviewName"]').value='';
    document.querySelector('input[name="reviewEmail"]').value='';
    document.querySelector('select[name="reviewSpot"]').selectedIndex = '0';
    document.querySelector('input[name="request"]').value='';
    document.querySelector('input[name="reviewTitle"]').value='';
    document.querySelector('input[name="remark"]').value='1';
    document.querySelector('input[name="avatar"]').value='';
    document.querySelector('textarea[name="reviewText"]').value='';
    var inputStar = document.getElementsByClassName('input-star');
    var inputAvatar = document.getElementsByClassName('input-avatar');
    var i;
    if (!inputStar[0].classList.contains('input-star-selected')) {
        inputStar[0].classList.add('input-star-selected');
    }
    for(i=1; i<5; i++) {
        if(inputStar[i].classList.contains('input-star-selected')) {
            inputStar[i].classList.remove('input-star-selected');
        }
        if(inputAvatar[i-1].classList.contains('input-avatar-selected')) {
            inputAvatar[i-1].classList.remove('input-avatar-selected');
        }
    }
}

function goReview(num) {
    document.getElementById('review').classList.toggle('review-show');
    document.querySelector('input[name="reviewName"]').value='';
    document.querySelector('input[name="reviewEmail"]').value='';
    document.querySelector('select[name="reviewSpot"]').selectedIndex = num;
    document.querySelector('input[name="request"]').value='';
    document.querySelector('input[name="reviewTitle"]').value='';
    document.querySelector('input[name="remark"]').value='1';
    document.querySelector('input[name="avatar"]').value='';
    document.querySelector('textarea[name="reviewText"]').value='';
    var inputStar = document.getElementsByClassName('input-star');
    var inputAvatar = document.getElementsByClassName('input-avatar');
    var i;
    if (!inputStar[0].classList.contains('input-star-selected')) {
        inputStar[0].classList.add('input-star-selected');
    }
    for(i=1; i<5; i++) {
        if(inputStar[i].classList.contains('input-star-selected')) {
            inputStar[i].classList.remove('input-star-selected');
        }
        if(inputAvatar[i-1].classList.contains('input-avatar-selected')) {
            inputAvatar[i-1].classList.remove('input-avatar-selected');
        }
    }
}

function feedback() {
    if (document.querySelector('input[name="reviewName"]').value !='') {
        if(document.querySelector('input[name="reviewEmail"]').value !='' && document.querySelector('input[name="reviewEmail"]').value.includes('@')) {
            if (document.querySelector('input[name="request"]').value != '') {
                if (document.querySelector('input[name="reviewTitle"]').value != '') {
                    if (document.querySelector('textarea[name="reviewText"]').value != '') {
                        if (document.querySelector('textarea[name="reviewText"]').value != '') {
                            if (document.querySelector('input[name="avatar"]').value != ''){
                                document.getElementById('thanksModal').classList.add('modal-show');
                            }
                        }
                    }
                }
            }
        }
    }
}
function modalClose() {
    document.getElementById('thanksModal').classList.remove('modal-show');
    if (document.getElementById(review).classList.contains('review-show')) {
        document.getElementById(review).classList.remove('review-show');
    }
}