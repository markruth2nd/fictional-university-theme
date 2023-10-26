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
    /* this.searchField = document.querySelector("#search-term"); */
    this.searchField = $("#search-term");
    this.events();
    this.isOverlayOpen = false;
    this.typingTimer;
  }

  // 2. Events
  events() {
    /* this.openButton.addEventListener("click", this.openOverlay.bind(this)); */
    this.openButton.on("click", this.openOverlay.bind(this));
    /* this.closeButton.addEventListener("click", this.closeOverlay.bind(this)); */
    this.closeButton.on("click", this.closeOverlay.bind(this));

    $(document).on("keydown", this.keyPressDispatcher.bind(this));
    this.searchField.on("keydown", this.typingLogic.bind(this));
  }

  // 3. Methods (functions, action...)

  typingLogic() {
    setTimeout(() => {
      clearTimeout(this.typingTimer);
      this.typingTimer = setTimeout(() => {
        console.log("This is where we run the search logic");
      }, 2000);
    });
  }

  keyPressDispatcher(e) {
    if (e.keyCode == 83 && !this.isOverlayOpen) {
      this.openOverlay();
    }
    if (e.keyCode == 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }
  }

  openOverlay() {
    /* this.searchOverlay.classList.add("search-overlay--active"); */
    this.searchOverlay.addClass("search-overlay--active");
    /* document.querySelector("body").classList.add("body-no-scroll"); */
    $("body").addClass("body-no-scroll"); //This add CSS to stop the scroll when the search is open

    this.isOverlayOpen = true;
  }

  closeOverlay() {
    /* this.searchOverlay.classList.remove("search-overlay--active"); */
    this.searchOverlay.removeClass("search-overlay--active");
    /* document.querySelector("body").classList.remove("body-no-scroll"); */
    $("body").removeClass("body-no-scroll"); //This remove CSS to stop the scroll when the search is closed

    this.isOverlayOpen = false;
  }
}

export default Search;
