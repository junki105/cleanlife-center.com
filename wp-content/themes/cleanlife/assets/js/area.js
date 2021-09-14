// function accordion(x) {
//   x.classList.toggle('toggle');
//   console.log(x.parentElement.nextSibling);
//   x.parentElement.nextSibling.classList.toggle("hide");
// }
jQuery(function($){

  $('.sec-ttl').click(function() {
    $(this).closest('.ttl-accordion-btn').toggleClass("toggle");
    $(this).next().toggleClass("hide");
  });
})