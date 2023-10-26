import $ from "jquery";

class Search {
  // 1. Describe and create/initiate our object
  constructor() {
    /* this.openButton = document.querySelector(".js-search-trigger"); */
    this.openButton = $(".js-search-trigger");
    /* this.closeButton = document.querySelector(".search-overlay__close"); */
    this.closeButton = $(".search-overlay__close");
    /* this.searchOverlay = document.querySelector(".search-overlay"); */
    this.searchOverlay = $(".search-overlay");
    this.events();
  }

  // 2. Events
  events() {
    /* this.openButton.addEventListener("click", this.openOverlay.bind(this)); */
    this.openButton.on("click", this.openOverlay.bind(this));
    /* this.closeButton.addEventListener("click", this.closeOverlay.bind(this)); */
    this.closeButton.on("click", this.closeOverlay.bind(this));
  }

  // 3. Methods (functions, action...)
  openOverlay() {
    /* this.searchOverlay.classList.add("search-overlay--active"); */
    this.searchOverlay.addClass("search-overlay--active");
    /* document.querySelector("body").classList.add("body-no-scroll"); */
    $("body").addClass("body-no-scroll");
  }

  closeOverlay() {
    /* this.searchOverlay.classList.remove("search-overlay--active"); */
    this.searchOverlay.removeClass("search-overlay--active");
    /* document.querySelector("body").classList.remove("body-no-scroll"); */
    $("body").removeClass("body-no-scroll");
  }
}

export default Search;
