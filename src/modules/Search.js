import $ from "jquery";

class Search {
  // 1. Describe and create/initiate our object
  constructor() {
    this.addSearchHTML();
    /* this.openButton = document.querySelector("#search-overlay__results"); */
    this.resultsDiv = $("#search-overlay__results");
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
    this.isSpinnerVisible = false;
    this.previousValue;
    this.typingTimer;
  }

  // 2. Events
  events() {
    /* this.openButton.addEventListener("click", this.openOverlay.bind(this)); */
    this.openButton.on("click", this.openOverlay.bind(this));
    /* this.closeButton.addEventListener("click", this.closeOverlay.bind(this)); */
    this.closeButton.on("click", this.closeOverlay.bind(this));

    $(document).on("keydown", this.keyPressDispatcher.bind(this));
    this.searchField.on("keyup", this.typingLogic.bind(this));
  }

  // 3. Methods (functions, action...)

  typingLogic() {
    if (this.searchField.val() != this.previousValue) {
      clearTimeout(this.typingTimer);

      if (this.searchField.val()) {
        if (!this.isSpinnerVisible) {
          /* this.resultsDiv.innerHTML = '<div class="spinner-loader"></div>'; */
          this.resultsDiv.html('<div class="spinner-loader"></div>');
          this.isSpinnerVisible = true;
        }
        this.typingTimer = setTimeout(this.getResults.bind(this), 750);
      } else {
        /* this.resultsDiv.innerHTML = ""; */
        this.resultsDiv.html("");
        this.isSpinnerVisible = false;
      }
    }
    this.previousValue = this.searchField.val();
  }

  getResults() {
    $.getJSON(
      universityData.root_url +
        "/wp-json/university/v1/search?term=" +
        this.searchField.val(),
      (results) => {
        this.resultsDiv.html(`
      <div class="row">
      <div class="one-third">

      <h2 class="search-overlay__section-title">General Information</h2>
      ${
        results.generalInfo.length
          ? '<ul class="link-list min-list">'
          : "<p>No general information matches that search.</p>"
      }
          ${results.generalInfo
            .map(
              (item) =>
                `<li><a href="${item.permalink}">${item.title}</a> ${
                  item.postType == "post" ? `by ${item.authorName}` : ""
                } </li>`
            )
            .join("")}
        ${results.generalInfo.length ? "</ul>" : ""}
      </div>
      <div class="one-third">
      <h2 class="search-overlay__section-title">Programs</h2>
        ${
          results.programs.length
            ? '<ul class="link-list min-list">'
            : `<p>No programs matches that search. <a href="${universityData.root_url}/programs">View all programs</a></p>`
        }
        ${results.programs
          .map(
            (item) => `<li><a href="${item.permalink}">${item.title}</a></li>`
          )
          .join("")}
      ${results.programs.length ? "</ul>" : ""}
      <h2 class="search-overlay__section-title">Professors</h2>
        ${
          results.professors.length
            ? '<ul class="professor-cards">'
            : `<p>No professors matches that search.</p>`
        }
        ${results.professors
          .map(
            (item) => `
            <li class="professor-card__list-item">
              <a class="professor-card" href="${item.permalink}">
                  <img class="professor-card__image" src="${item.image}">
                  <span class="professor-card__name">${item.title}</span>
              
              </a>
            </li>
            `
          )
          .join("")}
      ${results.professors.length ? "</ul>" : ""}
      </div>
      <div class="one-third">
      <h2 class="search-overlay__section-title">Campuses</h2>
        ${
          results.campuses.length
            ? '<ul class="link-list min-list">'
            : `<p>No campuses matches that search. <a href="${universityData.root_url}/campuses">View all Campuses</a></p>`
        }
        ${results.campuses
          .map(
            (item) => `<li><a href="${item.permalink}">${item.title}</a></li>`
          )
          .join("")}
      ${results.campuses.length ? "</ul>" : ""}
      <h2 class="search-overlay__section-title">Events</h2>
        ${
          results.events.length
            ? ''
            : `<p>No events matches that search. <a href="${universityData.root_url}/events">View all Events</a></p>`
        }
        ${results.events
          .map(
            (item) => `
            <div class="event-summary">
              <a class="event-summary__date t-center" href="${item.permalink}">
                <span class="event-summary__month">${item.month}</span>
                <span class="event-summary__day">${item.day}</span>  
              </a>
              <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                <p>${item.description}<a href="${item.permalink}" class="nu gray">Learn more</a></p>
              </div>
            </div>
            `
          )
          .join("")}
      </div>
      </div>  
      `);
        this.isSpinnerVisible = false;
      }
    );

    

  }

  
  keyPressDispatcher(e) {
    if (
      e.keyCode == 83 &&
      !this.isOverlayOpen &&
      !$("input, textarea").is(":focus")
    ) {
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
    this.searchField.val(""); //This clears the search field when user closes the search

    setTimeout(() => this.searchField.focus(), 301); //This focus the cursor on the search field
    this.isOverlayOpen = true;
  }

  closeOverlay() {
    /* this.searchOverlay.classList.remove("search-overlay--active"); */
    this.searchOverlay.removeClass("search-overlay--active");
    /* document.querySelector("body").classList.remove("body-no-scroll"); */
    $("body").removeClass("body-no-scroll"); //This remove CSS to stop the scroll when the search is closed

    this.isOverlayOpen = false;
  }

  addSearchHTML() {
    $("body").append(`
      <div class="search-overlay">
        <div class="search-overlay__top">
          <div class="container">
            <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
            <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
            <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
          </div>
        </div>
        <div class="container">
          <div id="search-overlay__results"></div>
        </div>
      </div>
    `);
  }
}

export default Search;
