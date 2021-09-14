function faqClick(x) {
    x.parentElement.classList.toggle('faq-show');
    var faq = document.getElementsByClassName('faq-box');
    var i;
    for(i = 0; i< faq.length; i++) {
        if (faq[i] != x.parentElement) {
            if (faq[i].classList.contains('faq-show')) {
                faq[i].classList.remove('faq-show');
            }
        }
    }
}