function conditionSpot(x) {
    var body = document.getElementsByTagName('body');
    if (body[0].classList.contains('amount-modal-open')) {
    	body[0].classList.remove('amount-modal-open');
	}
    var bannerSelect01 =  document.getElementById('banner-condition-select01');
    var bannerSelect02 =  document.getElementById('banner-condition-select02');
    var bannerSelect03 =  document.getElementById('banner-condition-select03');
    var bannerSelect04 =  document.getElementById('banner-condition-select04');
    var amountResult =  document.getElementById('banner-amount-result');
    var symptomBtn = document.getElementById('banner-condition-symptom');
    var typeBtn = document.getElementById('banner-condition-type');
    var ageBtn = document.getElementById('banner-condition-age');
    if (symptomBtn.classList.contains('banner-condition-clicked')) {
        symptomBtn.classList.remove('banner-condition-clicked');
    }
    if (typeBtn.classList.contains('banner-condition-clicked')) {
        typeBtn.classList.remove('banner-condition-clicked');
    }
    if (ageBtn.classList.contains('banner-condition-clicked')) {
        ageBtn.classList.remove('banner-condition-clicked');
    }
    if (amountResult.classList.contains('amount-result-show')) {
        amountResult.classList.remove('amount-result-show');
    }
    if (bannerSelect02.classList.contains('banner-select-show')) {
        bannerSelect02.classList.remove('banner-select-show');
    }
    if (bannerSelect03.classList.contains('banner-select-show')) {
        bannerSelect03.classList.remove('banner-select-show');
    }
    if (bannerSelect04.classList.contains('banner-select-show')) {
        bannerSelect04.classList.remove('banner-select-show');
    }
    if (bannerSelect01.classList.contains('banner-select-show')) {
        bannerSelect01.classList.remove('banner-select-show');
        if (x.classList.contains('banner-condition-clicked')) {
            x.classList.remove('banner-condition-clicked');
        }
    }
    else {
        bannerSelect01.classList.add('banner-select-show');
        x.classList.add('banner-condition-clicked');
    }
}
function conditionSymptom(x) {
    var body = document.getElementsByTagName('body');
    if (body[0].classList.contains('amount-modal-open')) {
    	body[0].classList.remove('amount-modal-open');
	}
    var bannerSelect01 =  document.getElementById('banner-condition-select01');
    var bannerSelect02 =  document.getElementById('banner-condition-select02');
    var bannerSelect03 =  document.getElementById('banner-condition-select03');
    var bannerSelect04 =  document.getElementById('banner-condition-select04');
    var amountResult =  document.getElementById('banner-amount-result');
    var spotBtn = document.getElementById('banner-condition-spot');
    var typeBtn = document.getElementById('banner-condition-type');
    var ageBtn = document.getElementById('banner-condition-age');
    if (spotBtn.classList.contains('banner-condition-clicked')) {
        spotBtn.classList.remove('banner-condition-clicked');
    }
    if (typeBtn.classList.contains('banner-condition-clicked')) {
        typeBtn.classList.remove('banner-condition-clicked');
    }
    if (ageBtn.classList.contains('banner-condition-clicked')) {
        ageBtn.classList.remove('banner-condition-clicked');
    }
    if (amountResult.classList.contains('amount-result-show')) {
        amountResult.classList.remove('amount-result-show');
    }
    if (bannerSelect01.classList.contains('banner-select-show')) {
        bannerSelect01.classList.remove('banner-select-show');
    }
    if (bannerSelect03.classList.contains('banner-select-show')) {
        bannerSelect03.classList.remove('banner-select-show');
    }
    if (bannerSelect04.classList.contains('banner-select-show')) {
        bannerSelect04.classList.remove('banner-select-show');
    }
    if (bannerSelect02.classList.contains('banner-select-show')) {
        bannerSelect02.classList.remove('banner-select-show');
        if (x.classList.contains('banner-condition-clicked')) {
            x.classList.remove('banner-condition-clicked');
        }
    }
    else {
        bannerSelect02.classList.add('banner-select-show');
        x.classList.add('banner-condition-clicked');
    }
}
function conditionType(x) {
    var body = document.getElementsByTagName('body');
    if (body[0].classList.contains('amount-modal-open')) {
    	body[0].classList.remove('amount-modal-open');
	}
    var bannerSelect01 =  document.getElementById('banner-condition-select01');
    var bannerSelect02 =  document.getElementById('banner-condition-select02');
    var bannerSelect03 =  document.getElementById('banner-condition-select03');
    var bannerSelect04 =  document.getElementById('banner-condition-select04');
    var amountResult =  document.getElementById('banner-amount-result');
    var symptomBtn = document.getElementById('banner-condition-symptom');
    var spotBtn = document.getElementById('banner-condition-spot');
    var ageBtn = document.getElementById('banner-condition-age');
    if (symptomBtn.classList.contains('banner-condition-clicked')) {
        symptomBtn.classList.remove('banner-condition-clicked');
    }
    if (spotBtn.classList.contains('banner-condition-clicked')) {
        spotBtn.classList.remove('banner-condition-clicked');
    }
    if (ageBtn.classList.contains('banner-condition-clicked')) {
        ageBtn.classList.remove('banner-condition-clicked');
    }
    if (amountResult.classList.contains('amount-result-show')) {
        amountResult.classList.remove('amount-result-show');
    }
    if (bannerSelect01.classList.contains('banner-select-show')) {
        bannerSelect01.classList.remove('banner-select-show');
    }
    if (bannerSelect02.classList.contains('banner-select-show')) {
        bannerSelect02.classList.remove('banner-select-show');
    }
    if (bannerSelect04.classList.contains('banner-select-show')) {
        bannerSelect04.classList.remove('banner-select-show');
    }
    if (bannerSelect03.classList.contains('banner-select-show')) {
        bannerSelect03.classList.remove('banner-select-show');
        if (x.classList.contains('banner-condition-clicked')) {
            x.classList.remove('banner-condition-clicked');
        }
    }
    else {
        bannerSelect03.classList.add('banner-select-show');
        x.classList.add('banner-condition-clicked');
    }
}
function conditionAge(x) {
    var body = document.getElementsByTagName('body');
    if (body[0].classList.contains('amount-modal-open')) {
    	body[0].classList.remove('amount-modal-open');
	}
    var bannerSelect01 =  document.getElementById('banner-condition-select01');
    var bannerSelect02 =  document.getElementById('banner-condition-select02');
    var bannerSelect03 =  document.getElementById('banner-condition-select03');
    var bannerSelect04 =  document.getElementById('banner-condition-select04');
    var amountResult =  document.getElementById('banner-amount-result');
    var symptomBtn = document.getElementById('banner-condition-symptom');
    var typeBtn = document.getElementById('banner-condition-type');
    var spotBtn = document.getElementById('banner-condition-spot');
    if (symptomBtn.classList.contains('banner-condition-clicked')) {
        symptomBtn.classList.remove('banner-condition-clicked');
    }
    if (typeBtn.classList.contains('banner-condition-clicked')) {
        typeBtn.classList.remove('banner-condition-clicked');
    }
    if (spotBtn.classList.contains('banner-condition-clicked')) {
        spotBtn.classList.remove('banner-condition-clicked');
    }
    if (amountResult.classList.contains('amount-result-show')) {
        amountResult.classList.remove('amount-result-show');
    }
    if (bannerSelect01.classList.contains('banner-select-show')) {
        bannerSelect01.classList.remove('banner-select-show');
    }
    if (bannerSelect02.classList.contains('banner-select-show')) {
        bannerSelect02.classList.remove('banner-select-show');
    }
    if (bannerSelect03.classList.contains('banner-select-show')) {
        bannerSelect03.classList.remove('banner-select-show');
    }
    if (bannerSelect04.classList.contains('banner-select-show')) {
        bannerSelect04.classList.remove('banner-select-show');
        if (x.classList.contains('banner-condition-clicked')) {
            x.classList.remove('banner-condition-clicked');
        }
    }
    else {
        bannerSelect04.classList.add('banner-select-show');
        x.classList.add('banner-condition-clicked');
    }
}
function resultShow() {
    var body = document.getElementsByTagName('body');
    if (!body[0].classList.contains('amount-modal-open')) {
    	body[0].classList.add('amount-modal-open');
	}
    var money1 = document.getElementById('money-result01');
    var spot = document.querySelector('input[name="spot"]:checked').id;
    var symptom = document.querySelector('input[name="symptom"]:checked').id;
    var formSymptom = document.getElementById('formSymptom');
    var formSpot = document.getElementById('formSpot');
    if (spot == 'spot1') {
        formSymptom.innerHTML = '<option value="詰まっている">詰まっている</option>'
            +'<option value="水が漏れている">水が漏れている</option>'
            +'<option value="異臭がする">異臭がする</option>'
            +'<option value="異音がする">異音がする</option>'
            +'<option value="水が止まらない">水が止まらない</option>'
            +'<option value="水が逆流した">水が逆流した</option>'
            +'<option value="異物を流した">異物を流した</option>'
            +'<option value="落としたものの回収">落としたものの回収</option>'
            +'<option value="水が溢れそう">水が溢れそう</option>'
            +'<option value="水位がおかしい">水位がおかしい</option>'
            +'<option value="便器・便座等の破損">便器・便座等の破損</option>'
            +'<option value="ウォシュレットの故障">ウォシュレットの故障</option>'
            +'<option value="蛇口の異常">蛇口の異常</option>'
            +'<option value="わからない・それ以外">わからない・それ以外</option>';
        var i =symptom.toString().substring(7);
        if (i == 1) {
            money1.innerHTML = '8,800~';
        }
        else if (i < 5) {
            money1.innerHTML = '8,800~16,500';
        }
        else if (i < 11) {
            money1.innerHTML = '8,800~33,000';
        }
        else if (i < 14) {
            money1.innerHTML = '8,800~';
        }
        else {
            money1.innerHTML = '3,300~';
        }
    }
    else if (spot == 'spot2') {
        formSymptom.innerHTML = '<option value="詰まっている">詰まっている</option>'
            +'<option value="水が漏れている">水が漏れている</option>'
            +'<option value="異臭がする">異臭がする</option>'
            +'<option value="異音がする">異音がする</option>'
            +'<option value="水が止まらない">水が止まらない</option>'
            +'<option value="水が逆流した">水が逆流した</option>'
            +'<option value="異物を流した">異物を流した</option>'
            +'<option value="落としたものの回収">落としたものの回収</option>'
            +'<option value="水が溢れそう">水が溢れそう</option>'
            +'<option value="水位がおかしい">水位がおかしい</option>'
            +'<option value="シンクの破損">シンクの破損</option>'
            +'<option value="蛇口の異常">蛇口の異常</option>'
            +'<option value="排水溝の異臭">排水溝の異臭</option>'
            +'<option value="わからない・それ以外">わからない・それ以外</option>';
        var i =symptom.toString().substring(7);
        if (i < 3) {
            money1.innerHTML = '8,800~30,500';
        }
        else if (i < 6) {
            money1.innerHTML = '8,800~16,500';
        }
        else if (i < 11) {
            money1.innerHTML = '8,800~30,500';
        }
        else if (i < 13) {
            money1.innerHTML = '8,800~';
        }
        else if (i == 13) {
            money1.innerHTML = '8,800~16,500';
        }
        else {
            money1.innerHTML = '3,300~';
        }
    }
    else if (spot == 'spot3') {
        formSymptom.innerHTML = '<option value="詰まっている">詰まっている</option>'
            +'<option value="水が漏れている">水が漏れている</option>'
            +'<option value="異臭がする">異臭がする</option>'
            +'<option value="異音がする">異音がする</option>'
            +'<option value="水が止まらない">水が止まらない</option>'
            +'<option value="水が逆流した">水が逆流した</option>'
            +'<option value="異物を流した">異物を流した</option>'
            +'<option value="落としたものの回収">落としたものの回収</option>'
            +'<option value="水が溢れそう">水が溢れそう</option>'
            +'<option value="水位がおかしい">水位がおかしい</option>'
            +'<option value="ユニットバスの破損">ユニットバスの破損</option>'
            +'<option value="蛇口の異常">蛇口の異常</option>'
            +'<option value="浴槽の故障">浴槽の故障</option>'
            +'<option value="わからない・それ以外">わからない・それ以外</option>';
        var i =symptom.toString().substring(7);
        if (i < 7) {
            money1.innerHTML = '8,800~16,500';
        }
        else if (i == 7) {
            money1.innerHTML = '8,800~30,500';
        }
        else if (i == 8 && i == 11) {
            money1.innerHTML = '8,800~';
        }
        else if (i < 11) {
            money1.innerHTML = '8,800~30,500';
        }
        else if (i < 14) {
            money1.innerHTML = '8,800~16,500';
        }
        else {
            money1.innerHTML = '3,300~';
        }
    }
    else if (spot == 'spot4') {
        formSymptom.innerHTML = '<option value="詰まっている">詰まっている</option>'
            +'<option value="水が漏れている">水が漏れている</option>'
            +'<option value="異臭がする">異臭がする</option>'
            +'<option value="異音がする">異音がする</option>'
            +'<option value="水が止まらない">水が止まらない</option>'
            +'<option value="水が逆流した">水が逆流した</option>'
            +'<option value="異物を流した">異物を流した</option>'
            +'<option value="落としたものの回収">落としたものの回収</option>'
            +'<option value="水が溢れそう">水が溢れそう</option>'
            +'<option value="水位がおかしい">水位がおかしい</option>'
            +'<option value="蛇口の異常">蛇口の異常</option>'
            +'<option value="わからない・それ以外">わからない・それ以外</option>';
        var i =symptom.toString().substring(7);
        if (i < 3) {
            money1.innerHTML = '8,800~30,500';
        }
        else if (i < 6) {
            money1.innerHTML = '8,800~16,500';
        }
        else if (i < 11) {
            money1.innerHTML = '8,800~30,500';
        }
        else if (i == 11) {
            money1.innerHTML = '8,800~';
        }
        else {
            money1.innerHTML = '3,300~';
        }
    }
    else if (spot == 'spot5') {
        formSymptom.innerHTML = '<option value="水が漏れている">水が漏れている</option>'
            +'<option value="異臭がする">異臭がする</option>'
            +'<option value="異音がする">異音がする</option>'
            +'<option value="お湯が出ない">お湯が出ない</option>'
            +'<option value="給湯器の破損">給湯器の破損</option>'
            +'<option value="わからない・それ以外">わからない・それ以外</option>';
        var i =symptom.toString().substring(7);
        if (i < 6) {
            money1.innerHTML = '8,800~';
        }
        else {
            money1.innerHTML = '3,300~';
        }
    }
    else if (spot == 'spot6') {
        formSymptom.innerHTML = '<option value="詰まっている">詰まっている</option>'
            +'<option value="水が漏れている">水が漏れている</option>'
            +'<option value="異臭がする">異臭がする</option>'
            +'<option value="異音がする">異音がする</option>'
            +'<option value="水が逆流した">水が逆流した</option>'
            +'<option value="異物を流した">異物を流した</option>'
            +'<option value="落としたものの回収">落としたものの回収</option>'
            +'<option value="排水管のひび割れ・破裂">排水管のひび割れ・破裂</option>'
            +'<option value="わからない・それ以外">わからない・それ以外</option>';
        var i =symptom.toString().substring(7);
        if (i < 3) {
            money1.innerHTML = '8,800~30,500';
        }
        else if (i < 6) {
            money1.innerHTML = '8,800~16,500';
        }
        else if (i < 8) {
            money1.innerHTML = '8,800~30,500';
        }
        else if (i == 8) {
            money1.innerHTML = '8,800~';
        }
        else {
            money1.innerHTML = '3,300~';
        }
    }
    else if (spot == 'spot6') {
        formSymptom.innerHTML = '<option value="詰まっている">詰まっている</option>'
            +'<option value="水が漏れている">水が漏れている</option>'
            +'<option value="異臭がする">異臭がする</option>'
            +'<option value="異音がする">異音がする</option>'
            +'<option value="水が止まらない">水が止まらない</option>'
            +'<option value="落としたものの回収">落としたものの回収</option>'
            +'<option value="水が溢れている">水が溢れている</option>'
            +'<option value="蛇口の異常">蛇口の異常</option>'
            +'<option value="わからない・それ以外">わからない・それ以外</option>';
        var i =symptom.toString().substring(7);
        if (i < 3) {
            money1.innerHTML = '8,800~30,500';
        }
        else if (i < 6) {
            money1.innerHTML = '8,800~16,500';
        }
        else if (i < 8) {
            money1.innerHTML = '8,800~30,500';
        }
        else if (i == 8) {
            money1.innerHTML = '8,800~';
        }
        else {
            money1.innerHTML = '3,300~';
        }
    }

    selectSymptomIndex = symptom.toString().substring(7);
    formSymptom.selectedIndex = selectSymptomIndex - 1;
    var selectSpotIndex = spot.toString().slice(-1);
    formSpot.selectedIndex = selectSpotIndex - 1;
    var type = document.querySelector('input[name="type"]:checked');
    if (type != null) {
        var selectTypeIndex = type.id.toString().slice(-1);
        document.getElementById('formType').selectedIndex = selectTypeIndex - 1;
    }
    var age = document.querySelector('input[name="age"]:checked');
    if (age != null) {
        var selectAgeIndex = age.id.toString().slice(-1);
        document.getElementById('formAge').selectedIndex = selectAgeIndex - 1;
    }

    var bannerSelect01 =  document.getElementById('banner-condition-select01');
    var bannerSelect02 =  document.getElementById('banner-condition-select02');
    var bannerSelect03 =  document.getElementById('banner-condition-select03');
    var bannerSelect04 =  document.getElementById('banner-condition-select04');
    var amountResult =  document.getElementById('banner-amount-result');
    if (bannerSelect01.classList.contains('banner-select-show')) {
        bannerSelect01.classList.remove('banner-select-show');
    }
    if (bannerSelect02.classList.contains('banner-select-show')) {
        bannerSelect02.classList.remove('banner-select-show');
    }
    if (bannerSelect03.classList.contains('banner-select-show')) {
        bannerSelect03.classList.remove('banner-select-show');
    }
    if (bannerSelect04.classList.contains('banner-select-show')) {
        bannerSelect04.classList.remove('banner-select-show');
    }
    if (!amountResult.classList.contains('amount-result-show')) {
        amountResult.classList.add('amount-result-show');
    }
    var bannerConditionSpot = document.getElementById('banner-condition-spot');
    var bannerConditionSymptom = document.getElementById('banner-condition-symptom');
    var bannerConditionType = document.getElementById('banner-condition-type');
    var bannerConditionAge = document.getElementById('banner-condition-age');
    if (bannerConditionSpot.classList.contains('banner-condition-clicked')) {
        bannerConditionSpot.classList.remove('banner-condition-clicked');
    }
    if (bannerConditionSymptom.classList.contains('banner-condition-clicked')) {
        bannerConditionSymptom.classList.remove('banner-condition-clicked');
    }
    if (bannerConditionType.classList.contains('banner-condition-clicked')) {
        bannerConditionType.classList.remove('banner-condition-clicked');
    }
    if (bannerConditionAge.classList.contains('banner-condition-clicked')) {
        bannerConditionAge.classList.remove('banner-condition-clicked');
    }
}
function spotCheck() {
    var conditionSymptom = document.getElementById('banner-condition-select02');
    var spot = document.querySelector('input[name="spot"]:checked').id;
    var bannerConditionSpot = document.getElementById('banner-condition-spot');
    if (spot == 'spot1') {
        conditionSymptom.innerHTML = '<div><input type="radio" id="symptom1" name="symptom" onchange="symptomCheck()"><label for="symptom1">詰まっている</label></div>'
        +'<div><input type="radio" id="symptom2" name="symptom" onchange="symptomCheck()"><label for="symptom2">水が漏れている</label></div>'
        +'<div><input type="radio" id="symptom3" name="symptom" onchange="symptomCheck()"><label for="symptom3">異臭がする</label></div>'
        +'<div><input type="radio" id="symptom4" name="symptom" onchange="symptomCheck()"><label for="symptom4">異音がする</label></div>'
        +'<div><input type="radio" id="symptom5" name="symptom" onchange="symptomCheck()"><label for="symptom5">水が止まらない</label></div>'
        +'<div><input type="radio" id="symptom6" name="symptom" onchange="symptomCheck()"><label for="symptom6">水が逆流した</label></div>'
        +'<div><input type="radio" id="symptom7" name="symptom" onchange="symptomCheck()"><label for="symptom7">異物を流した</label></div>'
        +'<div><input type="radio" id="symptom8" name="symptom" onchange="symptomCheck()"><label for="symptom8">落としたものの回収</label></div>'
        +'<div><input type="radio" id="symptom9" name="symptom" onchange="symptomCheck()"><label for="symptom9">水が溢れそう</label></div>'
        +'<div><input type="radio" id="symptom10" name="symptom" onchange="symptomCheck()"><label for="symptom10">水位がおかしい</label></div>'
        +'<div><input type="radio" id="symptom11" name="symptom" onchange="symptomCheck()"><label for="symptom11">便器・便座等の破損</label></div>'
        +'<div><input type="radio" id="symptom13" name="symptom" onchange="symptomCheck()"><label for="symptom12">ウォシュレットの故障</label></div>'
        +'<div><input type="radio" id="symptom12" name="symptom" onchange="symptomCheck()"><label for="symptom13">蛇口の異常</label></div>'
        +'<div><input type="radio" id="symptom14" name="symptom" onchange="symptomCheck()"><label for="symptom14">わからない・それ以外</label></div>';
        bannerConditionSpot.innerHTML = 'トイレ&#x25BE;';
    }
    else if (spot == 'spot2') {
        conditionSymptom.innerHTML = '<div><input type="radio" id="symptom1" name="symptom" onchange="symptomCheck()"><label for="symptom1">詰まっている</label></div>'
        +'<div><input type="radio" id="symptom2" name="symptom" onchange="symptomCheck()"><label for="symptom2">水が漏れている</label></div>'
        +'<div><input type="radio" id="symptom3" name="symptom" onchange="symptomCheck()"><label for="symptom3">異臭がする</label></div>'
        +'<div><input type="radio" id="symptom4" name="symptom" onchange="symptomCheck()"><label for="symptom4">異音がする</label></div>'
        +'<div><input type="radio" id="symptom5" name="symptom" onchange="symptomCheck()"><label for="symptom5">水が止まらない</label></div>'
        +'<div><input type="radio" id="symptom6" name="symptom" onchange="symptomCheck()"><label for="symptom6">水が逆流した</label></div>'
        +'<div><input type="radio" id="symptom7" name="symptom" onchange="symptomCheck()"><label for="symptom7">異物を流した</label></div>'
        +'<div><input type="radio" id="symptom8" name="symptom" onchange="symptomCheck()"><label for="symptom8">落としたものの回収</label></div>'
        +'<div><input type="radio" id="symptom9" name="symptom" onchange="symptomCheck()"><label for="symptom9">水が溢れそう</label></div>'
        +'<div><input type="radio" id="symptom10" name="symptom" onchange="symptomCheck()"><label for="symptom10">水位がおかしい</label></div>'
        +'<div><input type="radio" id="symptom11" name="symptom" onchange="symptomCheck()"><label for="symptom11">シンクの破損</label></div>'
        +'<div><input type="radio" id="symptom12" name="symptom" onchange="symptomCheck()"><label for="symptom12">蛇口の異常</label></div>'
        +'<div><input type="radio" id="symptom13" name="symptom" onchange="symptomCheck()"><label for="symptom13">排水溝の異臭</label></div>'
        +'<div><input type="radio" id="symptom14" name="symptom" onchange="symptomCheck()"><label for="symptom14">わからない・それ以外</label></div>';
        bannerConditionSpot.innerHTML = 'キッチン&#x25BE;';
    }
    else if (spot == 'spot3') {
        conditionSymptom.innerHTML = '<div><input type="radio" id="symptom1" name="symptom" onchange="symptomCheck()"><label for="symptom1">詰まっている</label></div>'
        +'<div><input type="radio" id="symptom2" name="symptom" onchange="symptomCheck()"><label for="symptom2">水が漏れている</label></div>'
        +'<div><input type="radio" id="symptom3" name="symptom" onchange="symptomCheck()"><label for="symptom3">異臭がする</label></div>'
        +'<div><input type="radio" id="symptom4" name="symptom" onchange="symptomCheck()"><label for="symptom4">異音がする</label></div>'
        +'<div><input type="radio" id="symptom5" name="symptom" onchange="symptomCheck()"><label for="symptom5">水が止まらない</label></div>'
        +'<div><input type="radio" id="symptom6" name="symptom" onchange="symptomCheck()"><label for="symptom6">水が逆流した</label></div>'
        +'<div><input type="radio" id="symptom7" name="symptom" onchange="symptomCheck()"><label for="symptom7">異物を流した</label></div>'
        +'<div><input type="radio" id="symptom8" name="symptom" onchange="symptomCheck()"><label for="symptom8">落としたものの回収</label></div>'
        +'<div><input type="radio" id="symptom9" name="symptom" onchange="symptomCheck()"><label for="symptom9">水が溢れそう</label></div>'
        +'<div><input type="radio" id="symptom10" name="symptom" onchange="symptomCheck()"><label for="symptom10">水位がおかしい</label></div>'
        +'<div><input type="radio" id="symptom11" name="symptom" onchange="symptomCheck()"><label for="symptom11">ユニットバスの破損</label></div>'
        +'<div><input type="radio" id="symptom12" name="symptom" onchange="symptomCheck()"><label for="symptom12">蛇口の異常</label></div>'
        +'<div><input type="radio" id="symptom13" name="symptom" onchange="symptomCheck()"><label for="symptom13">浴槽の故障</label></div>'
        +'<div><input type="radio" id="symptom14" name="symptom" onchange="symptomCheck()"><label for="symptom14">わからない・それ以外</label></div>';
        bannerConditionSpot.innerHTML = 'お風呂&#x25BE;';
    }
    else if (spot == 'spot4') {
        conditionSymptom.innerHTML = '<div><input type="radio" id="symptom1" name="symptom" onchange="symptomCheck()"><label for="symptom1">詰まっている</label></div>'
        +'<div><input type="radio" id="symptom2" name="symptom" onchange="symptomCheck()"><label for="symptom2">水が漏れている</label></div>'
        +'<div><input type="radio" id="symptom3" name="symptom" onchange="symptomCheck()"><label for="symptom3">異臭がする</label></div>'
        +'<div><input type="radio" id="symptom4" name="symptom" onchange="symptomCheck()"><label for="symptom4">異音がする</label></div>'
        +'<div><input type="radio" id="symptom5" name="symptom" onchange="symptomCheck()"><label for="symptom5">水が止まらない</label></div>'
        +'<div><input type="radio" id="symptom6" name="symptom" onchange="symptomCheck()"><label for="symptom6">水が逆流した</label></div>'
        +'<div><input type="radio" id="symptom7" name="symptom" onchange="symptomCheck()"><label for="symptom7">異物を流した</label></div>'
        +'<div><input type="radio" id="symptom8" name="symptom" onchange="symptomCheck()"><label for="symptom8">落としたものの回収</label></div>'
        +'<div><input type="radio" id="symptom9" name="symptom" onchange="symptomCheck()"><label for="symptom9">水が溢れそう</label></div>'
        +'<div><input type="radio" id="symptom10" name="symptom" onchange="symptomCheck()"><label for="symptom10">水位がおかしい</label></div>'
        +'<div><input type="radio" id="symptom11" name="symptom" onchange="symptomCheck()"><label for="symptom11">蛇口の異常</label></div>'
        +'<div><input type="radio" id="symptom12" name="symptom" onchange="symptomCheck()"><label for="symptom12">わからない・それ以外</label></div>';
        bannerConditionSpot.innerHTML = '洗面所&#x25BE;';
    }
    else if (spot == 'spot5') {
        conditionSymptom.innerHTML = '<div><input type="radio" id="symptom1" name="symptom" onchange="symptomCheck()"><label for="symptom1">水が漏れている</label></div>'
        +'<div><input type="radio" id="symptom2" name="symptom" onchange="symptomCheck()"><label for="symptom2">異臭がする</label></div>'
        +'<div><input type="radio" id="symptom3" name="symptom" onchange="symptomCheck()"><label for="symptom3">異音がする</label></div>'
        +'<div><input type="radio" id="symptom4" name="symptom" onchange="symptomCheck()"><label for="symptom4">お湯が出ない</label></div>'
        +'<div><input type="radio" id="symptom5" name="symptom" onchange="symptomCheck()"><label for="symptom5">給湯器の破損</label></div>'
        +'<div><input type="radio" id="symptom6" name="symptom" onchange="symptomCheck()"><label for="symptom6">わからない・それ以外</label></div>';
        bannerConditionSpot.innerHTML = '給湯器&#x25BE;';
    }
    else if (spot == 'spot6') {
        conditionSymptom.innerHTML = '<div><input type="radio" id="symptom1" name="symptom" onchange="symptomCheck()"><label for="symptom1">詰まっている</label></div>'
        +'<div><input type="radio" id="symptom2" name="symptom" onchange="symptomCheck()"><label for="symptom2">水が漏れている</label></div>'
        +'<div><input type="radio" id="symptom3" name="symptom" onchange="symptomCheck()"><label for="symptom3">異臭がする</label></div>'
        +'<div><input type="radio" id="symptom4" name="symptom" onchange="symptomCheck()"><label for="symptom4">異音がする</label></div>'
        +'<div><input type="radio" id="symptom5" name="symptom" onchange="symptomCheck()"><label for="symptom5">水が逆流した</label></div>'
        +'<div><input type="radio" id="symptom6" name="symptom" onchange="symptomCheck()"><label for="symptom6">異物を流した</label></div>'
        +'<div><input type="radio" id="symptom7" name="symptom" onchange="symptomCheck()"><label for="symptom7">落としたものの回収</label></div>'
        +'<div><input type="radio" id="symptom8" name="symptom" onchange="symptomCheck()"><label for="symptom8">排水管のひび割れ・破裂</label></div>'
        +'<div><input type="radio" id="symptom9" name="symptom" onchange="symptomCheck()"><label for="symptom9">わからない・それ以外</label></div>';
        bannerConditionSpot.innerHTML = '排水管&#x25BE;';
    }
    else {
        conditionSymptom.innerHTML = '<div><input type="radio" id="symptom1" name="symptom" onchange="symptomCheck()"><label for="symptom1">詰まっている</label></div>'
        +'<div><input type="radio" id="symptom2" name="symptom" onchange="symptomCheck()"><label for="symptom2">水が漏れている</label></div>'
        +'<div><input type="radio" id="symptom3" name="symptom" onchange="symptomCheck()"><label for="symptom3">異臭がする</label></div>'
        +'<div><input type="radio" id="symptom4" name="symptom" onchange="symptomCheck()"><label for="symptom4">異音がする</label></div>'
        +'<div><input type="radio" id="symptom5" name="symptom" onchange="symptomCheck()"><label for="symptom5">水が止まらない</label></div>'
        +'<div><input type="radio" id="symptom6" name="symptom" onchange="symptomCheck()"><label for="symptom6">落としたものの回収</label></div>'
        +'<div><input type="radio" id="symptom7" name="symptom" onchange="symptomCheck()"><label for="symptom7">水が溢れそう</label></div>'
        +'<div><input type="radio" id="symptom8" name="symptom" onchange="symptomCheck()"><label for="symptom8">蛇口の異常</label></div>'
        +'<div><input type="radio" id="symptom9" name="symptom" onchange="symptomCheck()"><label for="symptom9">わからない・それ以外</label></div>';
        bannerConditionSpot.innerHTML = '水道管&#x25BE;';
    }
    var spotBtn = document.getElementById('banner-condition-spot');
    var conditionSpot = document.getElementById('banner-condition-select01');
    var symptomBtn = document.getElementById('banner-condition-symptom');
    if (spotBtn.classList.contains('banner-condition-clicked')) {
        spotBtn.classList.remove('banner-condition-clicked');
        conditionSpot.classList.remove('banner-select-show');
    }
    symptomBtn.disabled = false;
    symptomBtn.classList.add('banner-condition-clicked');
    conditionSymptom.classList.add('banner-select-show');
}
function symptomCheck() {
    var resultBtn = document.getElementById('amount-show');
    var type = document.querySelector('input[name="type"]:checked');
    var age = document.querySelector('input[name="age"]:checked');
    if (type != null && age != null) {
        resultBtn.disabled = false;
    }
    var symptomBtn = document.getElementById('banner-condition-symptom');
    var conditionSymptom = document.getElementById('banner-condition-select02');
    if(symptomBtn.classList.contains('banner-condition-clicked')) {
        symptomBtn.classList.remove('banner-condition-clicked');
        conditionSymptom.classList.remove('banner-select-show');
    }
    var typeBtn = document.getElementById('banner-condition-type');
    var conditionType = document.getElementById('banner-condition-select03');
    typeBtn.classList.add('banner-condition-clicked');
    conditionType.classList.add('banner-select-show');
	
    var spot = document.querySelector('input[name="spot"]:checked').id;
    var symptom = document.querySelector('input[name="symptom"]:checked').id;
    var bannerConditionSymptom = document.getElementById('banner-condition-symptom');
    if (spot == 'spot1') {
        if (symptom == 'symptom1') {
            bannerConditionSymptom.innerHTML = "詰まっている&#x25BE;";
        }
        else if (symptom == 'symptom2') {
            bannerConditionSymptom.innerHTML = "水が漏れている&#x25BE;";
        }
        else if (symptom == 'symptom3') {
            bannerConditionSymptom.innerHTML = "異臭がする&#x25BE;";
        }
        else if (symptom == 'symptom4') {
            bannerConditionSymptom.innerHTML = "異音がする&#x25BE;";
        }
        else if (symptom == 'symptom5') {
            bannerConditionSymptom.innerHTML = "水が止まらない&#x25BE;";
        }
        else if (symptom == 'symptom6') {
            bannerConditionSymptom.innerHTML = "水が逆流した&#x25BE;";
        }
        else if (symptom == 'symptom7') {
            bannerConditionSymptom.innerHTML = "異物を流した&#x25BE;";
        }
        else if (symptom == 'symptom8') {
            bannerConditionSymptom.innerHTML = "落としたものの回収&#x25BE;";
        }
        else if (symptom == 'symptom9') {
            bannerConditionSymptom.innerHTML = "水が溢れそう&#x25BE;";
        }
        else if (symptom == 'symptom10') {
            bannerConditionSymptom.innerHTML = "水位がおかしい&#x25BE;";
        }
        else if (symptom == 'symptom11') {
            bannerConditionSymptom.innerHTML = "便器・便座等の破損&#x25BE;";
        }
        else if (symptom == 'symptom12') {
            bannerConditionSymptom.innerHTML = "ウォシュレットの故障&#x25BE;";
        }
        else if (symptom == 'symptom13') {
            bannerConditionSymptom.innerHTML = "蛇口の異常&#x25BE;";
        }
        else {
            bannerConditionSymptom.innerHTML = "わからない・それ以外&#x25BE;";
        }
    }
    else if (spot == 'spot2') {
        if (symptom == 'symptom1') {
            bannerConditionSymptom.innerHTML = "詰まっている&#x25BE;";
        }
        else if (symptom == 'symptom2') {
            bannerConditionSymptom.innerHTML = "水が漏れている&#x25BE;";
        }
        else if (symptom == 'symptom3') {
            bannerConditionSymptom.innerHTML = "異臭がする&#x25BE;";
        }
        else if (symptom == 'symptom4') {
            bannerConditionSymptom.innerHTML = "異音がする&#x25BE;";
        }
        else if (symptom == 'symptom5') {
            bannerConditionSymptom.innerHTML = "水が止まらない&#x25BE;";
        }
        else if (symptom == 'symptom6') {
            bannerConditionSymptom.innerHTML = "水が逆流した&#x25BE;";
        }
        else if (symptom == 'symptom7') {
            bannerConditionSymptom.innerHTML = "異物を流した&#x25BE;";
        }
        else if (symptom == 'symptom8') {
            bannerConditionSymptom.innerHTML = "落としたものの回収&#x25BE;";
        }
        else if (symptom == 'symptom9') {
            bannerConditionSymptom.innerHTML = "水が溢れそう&#x25BE;";
        }
        else if (symptom == 'symptom10') {
            bannerConditionSymptom.innerHTML = "水位がおかしい&#x25BE;";
        }
        else if (symptom == 'symptom11') {
            bannerConditionSymptom.innerHTML = "シンクの破損&#x25BE;";
        }
        else if (symptom == 'symptom12') {
            bannerConditionSymptom.innerHTML = "蛇口の異常&#x25BE;";
        }
        else if (symptom == 'symptom13') {
            bannerConditionSymptom.innerHTML = "排水溝の異臭&#x25BE;";
        }
        else {
            bannerConditionSymptom.innerHTML = "わからない・それ以外&#x25BE;";
        }
    }
    else if (spot == 'spot3') {
        if (symptom == 'symptom1') {
            bannerConditionSymptom.innerHTML = "詰まっている&#x25BE;";
        }
        else if (symptom == 'symptom2') {
            bannerConditionSymptom.innerHTML = "水が漏れている&#x25BE;";
        }
        else if (symptom == 'symptom3') {
            bannerConditionSymptom.innerHTML = "異臭がする&#x25BE;";
        }
        else if (symptom == 'symptom4') {
            bannerConditionSymptom.innerHTML = "異音がする&#x25BE;";
        }
        else if (symptom == 'symptom5') {
            bannerConditionSymptom.innerHTML = "水が止まらない&#x25BE;";
        }
        else if (symptom == 'symptom6') {
            bannerConditionSymptom.innerHTML = "水が逆流した&#x25BE;";
        }
        else if (symptom == 'symptom7') {
            bannerConditionSymptom.innerHTML = "異物を流した&#x25BE;";
        }
        else if (symptom == 'symptom8') {
            bannerConditionSymptom.innerHTML = "落としたものの回収&#x25BE;";
        }
        else if (symptom == 'symptom9') {
            bannerConditionSymptom.innerHTML = "水が溢れそう&#x25BE;";
        }
        else if (symptom == 'symptom10') {
            bannerConditionSymptom.innerHTML = "水位がおかしい&#x25BE;";
        }
        else if (symptom == 'symptom11') {
            bannerConditionSymptom.innerHTML = "ユニットバスの破損&#x25BE;";
        }
        else if (symptom == 'symptom12') {
            bannerConditionSymptom.innerHTML = "蛇口の異常&#x25BE;";
        }
        else if (symptom == 'symptom13') {
            bannerConditionSymptom.innerHTML = "浴槽の故障&#x25BE;";
        }
        else {
            bannerConditionSymptom.innerHTML = "わからない・それ以外&#x25BE;";
        }
    }
    else if (spot == 'spot4') {
        if (symptom == 'symptom1') {
            bannerConditionSymptom.innerHTML = "詰まっている&#x25BE;";
        }
        else if (symptom == 'symptom2') {
            bannerConditionSymptom.innerHTML = "水が漏れている&#x25BE;";
        }
        else if (symptom == 'symptom3') {
            bannerConditionSymptom.innerHTML = "異臭がする&#x25BE;";
        }
        else if (symptom == 'symptom4') {
            bannerConditionSymptom.innerHTML = "異音がする&#x25BE;";
        }
        else if (symptom == 'symptom5') {
            bannerConditionSymptom.innerHTML = "水が止まらない&#x25BE;";
        }
        else if (symptom == 'symptom6') {
            bannerConditionSymptom.innerHTML = "水が逆流した&#x25BE;";
        }
        else if (symptom == 'symptom7') {
            bannerConditionSymptom.innerHTML = "異物を流した&#x25BE;";
        }
        else if (symptom == 'symptom8') {
            bannerConditionSymptom.innerHTML = "落としたものの回収&#x25BE;";
        }
        else if (symptom == 'symptom9') {
            bannerConditionSymptom.innerHTML = "水が溢れそう&#x25BE;";
        }
        else if (symptom == 'symptom10') {
            bannerConditionSymptom.innerHTML = "水位がおかしい&#x25BE;";
        }
        else if (symptom == 'symptom11') {
            bannerConditionSymptom.innerHTML = "蛇口の異常&#x25BE;";
        }
        else {
            bannerConditionSymptom.innerHTML = "わからない・それ以外&#x25BE;";
        }
    }
    else if (spot == 'spot5') {
        if (symptom == 'symptom1') {
            bannerConditionSymptom.innerHTML = "水が漏れている&#x25BE;";
        }
        else if (symptom == 'symptom2') {
            bannerConditionSymptom.innerHTML = "異臭がする&#x25BE;";
        }
        else if (symptom == 'symptom3') {
            bannerConditionSymptom.innerHTML = "異音がする&#x25BE;";
        }
        else if (symptom == 'symptom4') {
            bannerConditionSymptom.innerHTML = "お湯が出ない&#x25BE;";
        }
        else if (symptom == 'symptom5') {
            bannerConditionSymptom.innerHTML = "給湯器の破損&#x25BE;";
        }
        else {
            bannerConditionSymptom.innerHTML = "わからない・それ以外&#x25BE;";
        }
    }
    else if (spot == 'spot6') {
        if (symptom == 'symptom1') {
            bannerConditionSymptom.innerHTML = "詰まっている&#x25BE;";
        }
        else if (symptom == 'symptom2') {
            bannerConditionSymptom.innerHTML = "水が漏れている&#x25BE;";
        }
        else if (symptom == 'symptom3') {
            bannerConditionSymptom.innerHTML = "異臭がする&#x25BE;";
        }
        else if (symptom == 'symptom4') {
            bannerConditionSymptom.innerHTML = "異音がする&#x25BE;";
        }
        else if (symptom == 'symptom5') {
            bannerConditionSymptom.innerHTML = "水が逆流した&#x25BE;";
        }
        else if (symptom == 'symptom6') {
            bannerConditionSymptom.innerHTML = "異物を流した&#x25BE;";
        }
        else if (symptom == 'symptom7') {
            bannerConditionSymptom.innerHTML = "落としたものの回収&#x25BE;";
        }
        else if (symptom == 'symptom8') {
            bannerConditionSymptom.innerHTML = "排水管のひび割れ・破裂&#x25BE;";
        }
        else {
            bannerConditionSymptom.innerHTML = "わからない・それ以外&#x25BE;";
        }
    }
    else {
        if (symptom == 'symptom1') {
            bannerConditionSymptom.innerHTML = "詰まっている&#x25BE;";
        }
        else if (symptom == 'symptom2') {
            bannerConditionSymptom.innerHTML = "水が漏れている&#x25BE;";
        }
        else if (symptom == 'symptom3') {
            bannerConditionSymptom.innerHTML = "異臭がする&#x25BE;";
        }
        else if (symptom == 'symptom4') {
            bannerConditionSymptom.innerHTML = "異音がする&#x25BE;";
        }
        else if (symptom == 'symptom5') {
            bannerConditionSymptom.innerHTML = "水が止まらない&#x25BE;";
        }
        else if (symptom == 'symptom6') {
            bannerConditionSymptom.innerHTML = "落としたものの回収&#x25BE;";
        }
        else if (symptom == 'symptom7') {
            bannerConditionSymptom.innerHTML = "水が溢れそう&#x25BE;";
        }
        else if (symptom == 'symptom8') {
            bannerConditionSymptom.innerHTML = "蛇口の異常&#x25BE;";
        }
        else {
            bannerConditionSymptom.innerHTML = "わからない・それ以外&#x25BE;";
        }
    }
}
function typeCheck() {
    var resultBtn = document.getElementById('amount-show');
    var spot = document.querySelector('input[name="spot"]:checked');
	var symptom = document.querySelector('input[name="symptom"]:checked');
    var age = document.querySelector('input[name="age"]:checked');
    if (spot != null && symptom != null && age != null) {
        resultBtn.disabled = false;
    }
    var typeBtn = document.getElementById('banner-condition-type');
    var conditionType = document.getElementById('banner-condition-select03');
    if(typeBtn.classList.contains('banner-condition-clicked')) {
        typeBtn.classList.remove('banner-condition-clicked');
        conditionType.classList.remove('banner-select-show');
    }
    var ageBtn = document.getElementById('banner-condition-age');
    var conditionAge = document.getElementById('banner-condition-select04');
    ageBtn.classList.add('banner-condition-clicked');
    conditionAge.classList.add('banner-select-show');
	
    var type = document.querySelector('input[name="type"]:checked').id;
    var bannerConditionType = document.getElementById('banner-condition-type');
    if (type == "type1") {
        bannerConditionType.innerHTML = "一戸建て&#x25BE;"
    }
    else if (type == "type2") {
        bannerConditionType.innerHTML = "マンション・アパート&#x25BE;"
    }
    else if (type == "type3") {
        bannerConditionType.innerHTML = "事務所・オフィス&#x25BE;"
    }
    else if (type == "type4") {
        bannerConditionType.innerHTML = "店舗・レストラン&#x25BE;"
    }
    else if (type == "type5") {
        bannerConditionType.innerHTML = "ビル・商業施設&#x25BE;"
    }
    else {
        bannerConditionType.innerHTML = "宿泊施設&#x25BE;"
    }
}
function ageCheck() {
    var resultBtn = document.getElementById('amount-show');
    var spot = document.querySelector('input[name="spot"]:checked');
	var symptom = document.querySelector('input[name="symptom"]:checked');
    var type = document.querySelector('input[name="type"]:checked');
    if (spot != null && symptom != null && type != null) {
        resultBtn.disabled = false;
    }
    var ageBtn = document.getElementById('banner-condition-age');
    var conditionAge = document.getElementById('banner-condition-select04');
	if (ageBtn.classList.contains('banner-condition-clicked')) {
		ageBtn.classList.remove('banner-condition-clicked');
	}
	if (conditionAge.classList.contains('banner-select-show')) {
		conditionAge.classList.remove('banner-select-show');
	}
	
    var age = document.querySelector('input[name="age"]:checked').id;
    var bannerConditionAge = document.getElementById('banner-condition-age');
    if (age == "age1") {
        bannerConditionAge.innerHTML = "1~5 年&#x25BE;"
    }
    else if (age == "age2") {
        bannerConditionAge.innerHTML = "6~10 年&#x25BE;"
    }
    else if (age == "age3") {
        bannerConditionAge.innerHTML = "10~15 年&#x25BE;"
    }
    else if (age == "age4") {
        bannerConditionAge.innerHTML = "16~20 年&#x25BE;"
    }
    else if (age == "age5") {
        bannerConditionAge.innerHTML = "21~30 年&#x25BE;"
    }
    else if (age == "age6") {
        bannerConditionAge.innerHTML = "30 年以上&#x25BE;"
    }
    else {
        bannerConditionAge.innerHTML = "わからない&#x25BE;"
    }
}
function resultClose() {
    var body = document.getElementsByTagName('body');
    if (body[0].classList.contains('amount-modal-open')) {
    	body[0].classList.remove('amount-modal-open');
	}
    document.getElementById('banner-amount-result').classList.remove('amount-result-show');
    var spot = document.querySelector('input[name="spot"]:checked');
    var symptom = document.querySelector('input[name="symptom"]:checked');
    var type = document.querySelector('input[name="type"]:checked');
    var age = document.querySelector('input[name="age"]:checked');
    if (spot != null) {
        spot.checked = false;
    }
    if (symptom != null) {
        symptom.checked = false;
    }
    if (type != null) {
        type.checked = false;
    }
    if (age != null) {
        age.checked = false;
    }
    document.querySelector('select[id="formSpot"]').value="トイレ";
    document.querySelector('select[id="formSymptom"]').value="詰まっている";
    document.querySelector('select[id="formType"]').value="一戸建て";
    document.querySelector('select[id="formAge"]').value="1~5 年";
    document.querySelector('input[name="c-name"]').value="";
    document.querySelector('input[name="email"]').value="";
    document.querySelector('input[name="phone"]').value="";
    document.querySelector('input[name="postal"]').value="";
    var symptomBtn = document.getElementById('banner-condition-symptom');
    var resultBtn = document.getElementById('amount-show');
    symptomBtn.disabled = true;
    resultBtn.disabled = true;
	
	
    var bannerConditionSpot = document.getElementById('banner-condition-spot');
    var bannerConditionSymptom = document.getElementById('banner-condition-symptom');
    var bannerConditionType = document.getElementById('banner-condition-type');
    var bannerConditionAge = document.getElementById('banner-condition-age');
    bannerConditionSpot.innerHTML = "トラブルの箇所&#x25BE;";
    bannerConditionSymptom.innerHTML = "トラブルの症状&#x25BE;";
    bannerConditionType.innerHTML = "物件の種類&#x25BE;";
    bannerConditionAge.innerHTML = "築年数&#x25BE;";
}
function formSpotSelect(x) {
    if (x.selectedIndex == '0') {
        formSymptom.innerHTML = '<option value="詰まっている">詰まっている</option>'
            +'<option value="水が漏れている">水が漏れている</option>'
            +'<option value="異臭がする">異臭がする</option>'
            +'<option value="異音がする">異音がする</option>'
            +'<option value="水が止まらない">水が止まらない</option>'
            +'<option value="水が逆流した">水が逆流した</option>'
            +'<option value="異物を流した">異物を流した</option>'
            +'<option value="落としたものの回収">落としたものの回収</option>'
            +'<option value="水が溢れそう">水が溢れそう</option>'
            +'<option value="水位がおかしい">水位がおかしい</option>'
            +'<option value="便器・便座等の破損">便器・便座等の破損</option>'
            +'<option value="ウォシュレットの故障">ウォシュレットの故障</option>'
            +'<option value="蛇口の異常">蛇口の異常</option>'
            +'<option value="わからない・それ以外">わからない・それ以外</option>';
    }
    else if (x.selectedIndex == '1') {
        formSymptom.innerHTML = '<option value="詰まっている">詰まっている</option>'
            +'<option value="水が漏れている">水が漏れている</option>'
            +'<option value="異臭がする">異臭がする</option>'
            +'<option value="異音がする">異音がする</option>'
            +'<option value="水が止まらない">水が止まらない</option>'
            +'<option value="水が逆流した">水が逆流した</option>'
            +'<option value="異物を流した">異物を流した</option>'
            +'<option value="落としたものの回収">落としたものの回収</option>'
            +'<option value="水が溢れそう">水が溢れそう</option>'
            +'<option value="水位がおかしい">水位がおかしい</option>'
            +'<option value="シンクの破損">シンクの破損</option>'
            +'<option value="蛇口の異常">蛇口の異常</option>'
            +'<option value="排水溝の異臭">排水溝の異臭</option>'
            +'<option value="わからない・それ以外">わからない・それ以外</option>';
    }
    else if (x.selectedIndex == '2') {
        formSymptom.innerHTML = '<option value="詰まっている">詰まっている</option>'
            +'<option value="水が漏れている">水が漏れている</option>'
            +'<option value="異臭がする">異臭がする</option>'
            +'<option value="異音がする">異音がする</option>'
            +'<option value="水が止まらない">水が止まらない</option>'
            +'<option value="水が逆流した">水が逆流した</option>'
            +'<option value="異物を流した">異物を流した</option>'
            +'<option value="落としたものの回収">落としたものの回収</option>'
            +'<option value="水が溢れそう">水が溢れそう</option>'
            +'<option value="水位がおかしい">水位がおかしい</option>'
            +'<option value="ユニットバスの破損">ユニットバスの破損</option>'
            +'<option value="蛇口の異常">蛇口の異常</option>'
            +'<option value="浴槽の故障">浴槽の故障</option>'
            +'<option value="わからない・それ以外">わからない・それ以外</option>';
    }
    else if (x.selectedIndex == '3') {
        formSymptom.innerHTML = '<option value="詰まっている">詰まっている</option>'
            +'<option value="水が漏れている">水が漏れている</option>'
            +'<option value="異臭がする">異臭がする</option>'
            +'<option value="異音がする">異音がする</option>'
            +'<option value="水が止まらない">水が止まらない</option>'
            +'<option value="水が逆流した">水が逆流した</option>'
            +'<option value="異物を流した">異物を流した</option>'
            +'<option value="落としたものの回収">落としたものの回収</option>'
            +'<option value="水が溢れそう">水が溢れそう</option>'
            +'<option value="水位がおかしい">水位がおかしい</option>'
            +'<option value="蛇口の異常">蛇口の異常</option>'
            +'<option value="わからない・それ以外">わからない・それ以外</option>';
    }
    else if (x.selectedIndex == '4') {
        formSymptom.innerHTML = '<option value="水が漏れている">水が漏れている</option>'
            +'<option value="異臭がする">異臭がする</option>'
            +'<option value="異音がする">異音がする</option>'
            +'<option value="お湯が出ない">お湯が出ない</option>'
            +'<option value="給湯器の破損">給湯器の破損</option>'
            +'<option value="わからない・それ以外">わからない・それ以外</option>';
    }
    else if (x.selectedIndex == '5') {
        formSymptom.innerHTML = '<option value="詰まっている">詰まっている</option>'
            +'<option value="水が漏れている">水が漏れている</option>'
            +'<option value="異臭がする">異臭がする</option>'
            +'<option value="異音がする">異音がする</option>'
            +'<option value="水が逆流した">水が逆流した</option>'
            +'<option value="異物を流した">異物を流した</option>'
            +'<option value="落としたものの回収">落としたものの回収</option>'
            +'<option value="排水管のひび割れ・破裂">排水管のひび割れ・破裂</option>'
            +'<option value="わからない・それ以外">わからない・それ以外</option>';
    }
    else if (x.selectedIndex == '6') {
        formSymptom.innerHTML = '<option value="詰まっている">詰まっている</option>'
            +'<option value="水が漏れている">水が漏れている</option>'
            +'<option value="異臭がする">異臭がする</option>'
            +'<option value="異音がする">異音がする</option>'
            +'<option value="水が止まらない">水が止まらない</option>'
            +'<option value="落としたものの回収">落としたものの回収</option>'
            +'<option value="水が溢れている">水が溢れている</option>'
            +'<option value="蛇口の異常">蛇口の異常</option>'
            +'<option value="わからない・それ以外">わからない・それ以外</option>';
    }
}

function areaClick(x) {
    x.parentElement.classList.toggle('area-show');
    var area = document.getElementsByClassName('area-box');
    var i;
    for(i = 0; i< area.length; i++) {
        if (area[i] != x.parentElement) {
            if (area[i].classList.contains('area-show')) {
                area[i].classList.remove('area-show');
            }
        }
    }
}


  jQuery(document).ready(function($){
    $('.munic-con-title').click(function(){
        // $('.munic-con-title').not($(this)).next('.munic-con-main').slideUp();
        // $('.munic-con-title').not($(this)).parent().removeClass(".munic-con-show");
        $(this).next('.munic-con-main').slideToggle();
        $(this).parent().toggleClass("munic-con-show");
    });
  });