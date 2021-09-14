"use strict";
var gainwpRedirectLink;
var gainwpRedirectCalled = false;
var gainwpDefaultPrevented = false;

function gainwpRedirect() {
  if (gainwpRedirectCalled) {
    return
  }
  gainwpRedirectCalled = true;
  if (gainwpDefaultPrevented == false) {
    document.location.href = gainwpRedirectLink
  } else {
    gainwpDefaultPrevented = false
  }
}

function gainwp_send_event(c, d, a, b) {
  if (window.gtag && gainwpUAEventsData.options.ga_with_gtag) {
    if (b) {
      if (gainwpUAEventsData.options.event_bouncerate) {
        gtag("event", d, {
          event_category: c,
          event_label: a,
          non_interaction: 1,
          event_callback: gainwpRedirect
        })
      } else {
        gtag("event", d, {
          event_category: c,
          event_label: a,
          event_callback: gainwpRedirect
        })
      }
    } else {
      if (gainwpUAEventsData.options.event_bouncerate) {
        gtag("event", d, {
          event_category: c,
          event_label: a,
          non_interaction: 1
        })
      } else {
        gtag("event", d, {
          event_category: c,
          event_label: a
        })
      }
    }
  } else if (window.ga) {
    if (b) {
      if (gainwpUAEventsData.options.event_bouncerate) {
        ga("send", "event", c, d, a, {
          nonInteraction: 1,
          hitCallback: gainwpRedirect
        })
      } else {
        ga("send", "event", c, d, a, {
          hitCallback: gainwpRedirect
        })
      }
    } else {
      if (gainwpUAEventsData.options.event_bouncerate) {
        ga("send", "event", c, d, a, {
          nonInteraction: 1
        })
      } else {
        ga("send", "event", c, d, a)
      }
    }
  }
}
jQuery(window).on("load", function() {
  if (gainwpUAEventsData.options.event_tracking) {
    jQuery("a").filter(function() {
      if (typeof this.href === "string") {
        var a = new RegExp(".*\\.(" + gainwpUAEventsData.options.event_downloads + ")(\\?.*)?$");
        return this.href.match(a)
      }
    }).click(function(d) {
      var b = this.getAttribute("data-vars-ga-category") || "download";
      var c = this.getAttribute("data-vars-ga-action") || "click";
      var a = this.getAttribute("data-vars-ga-label") || this.href;
      gainwp_send_event(b, c, a, false)
    });
    jQuery('a[href^="mailto"]').click(function(d) {
      var b = this.getAttribute("data-vars-ga-category") || "email";
      var c = this.getAttribute("data-vars-ga-action") || "send";
      var a = this.getAttribute("data-vars-ga-label") || this.href;
      gainwp_send_event(b, c, a, false)
    });
    jQuery('a[href^="tel"]').click(function(d) {
      var b = this.getAttribute("data-vars-ga-category") || "telephone";
      var c = this.getAttribute("data-vars-ga-action") || "call";
      var a = this.getAttribute("data-vars-ga-label") || this.href;
      gainwp_send_event(b, c, a, false)
    });
    if (gainwpUAEventsData.options.root_domain) {
      jQuery('a[href^="http"]').filter(function() {
        if (typeof this.href === "string") {
          var a = new RegExp(".*\\.(" + gainwpUAEventsData.options.event_downloads + ")(\\?.*)?$")
        }
        if (a && !this.href.match(a)) {
          if (this.href.indexOf(gainwpUAEventsData.options.root_domain) == -1 && this.href.indexOf("://") > -1) {
            return this.href
          }
        }
      }).click(function(d) {
        gainwpRedirectCalled = false;
        gainwpRedirectLink = this.href;
        var b = this.getAttribute("data-vars-ga-category") || "outbound";
        var c = this.getAttribute("data-vars-ga-action") || "click";
        var a = this.getAttribute("data-vars-ga-label") || this.href;
        if (this.target != "_blank" && gainwpUAEventsData.options.event_precision) {
          if (d.isDefaultPrevented()) {
            gainwpDefaultPrevented = true;
            gainwpRedirectCalled = false
          }
        } else {
          gainwpRedirectCalled = true;
          gainwpDefaultPrevented = false
        }
        if (this.target != "_blank" && gainwpUAEventsData.options.event_precision) {
          gainwp_send_event(b, c, a, true);
          setTimeout(gainwpRedirect, gainwpUAEventsData.options.event_timeout);
          return false
        } else {
          gainwp_send_event(b, c, a, false)
        }
      })
    }
  }
  if (gainwpUAEventsData.options.event_affiliates && gainwpUAEventsData.options.aff_tracking) {
    jQuery("a").filter(function() {
      if (gainwpUAEventsData.options.event_affiliates != "") {
        if (typeof this.href === "string") {
          var a = new RegExp("(" + gainwpUAEventsData.options.event_affiliates.replace(/\//g, "/") + ")");
          return this.href.match(a)
        }
      }
    }).click(function(d) {
      gainwpRedirectCalled = false;
      gainwpRedirectLink = this.href;
      var b = this.getAttribute("data-vars-ga-category") || "affiliates";
      var c = this.getAttribute("data-vars-ga-action") || "click";
      var a = this.getAttribute("data-vars-ga-label") || this.href;
      if (this.target != "_blank" && gainwpUAEventsData.options.event_precision) {
        if (d.isDefaultPrevented()) {
          gainwpDefaultPrevented = true;
          gainwpRedirectCalled = false
        }
      } else {
        gainwpRedirectCalled = true;
        gainwpDefaultPrevented = false
      }
      if (this.target != "_blank" && gainwpUAEventsData.options.event_precision) {
        gainwp_send_event(b, c, a, true);
        setTimeout(gainwpRedirect, gainwpUAEventsData.options.event_timeout);
        return false
      } else {
        gainwp_send_event(b, c, a, false)
      }
    })
  }
  if (gainwpUAEventsData.options.root_domain && gainwpUAEventsData.options.hash_tracking) {
    jQuery("a").filter(function() {
      if (this.href.indexOf(gainwpUAEventsData.options.root_domain) != -1 || this.href.indexOf("://") == -1) {
        return this.hash
      }
    }).click(function(d) {
      var b = this.getAttribute("data-vars-ga-category") || "hashmark";
      var c = this.getAttribute("data-vars-ga-action") || "click";
      var a = this.getAttribute("data-vars-ga-label") || this.href;
      gainwp_send_event(b, c, a, false)
    })
  }
  if (gainwpUAEventsData.options.event_formsubmit) {
    jQuery('input[type="submit"], button[type="submit"]').click(function(f) {
      var d = this;
      var b = d.getAttribute("data-vars-ga-category") || "form";
      var c = d.getAttribute("data-vars-ga-action") || "submit";
      var a = d.getAttribute("data-vars-ga-label") || d.name || d.value;
      gainwp_send_event(b, c, a, false)
    })
  }
  if (gainwpUAEventsData.options.ga_pagescrolldepth_tracking) {
    jQuery.scrollDepth({
      percentage: true,
      userTiming: false,
      pixelDepth: false,
      gtmOverride: true,
      nonInteraction: true
    })
  }
});