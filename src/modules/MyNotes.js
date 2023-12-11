import $ from "jquery";

class MyNotes {
    constructor() {
        this.notes = [];
    }

    events() {
        $(".delete-note").on("click", this.delete);
        /* document.querySelector("#my-notes").addEventListener("click", (e) => {
            this.delete(e);
        }); */
    }

    // Methods will go here
    delete(e) {
        //alert("test");
        var thisNote = $(e.target).parents("li");
        //console.log(thisNote);
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader("X-WP-Nonce", universityData.nonce);
            },
            url: universityData.root_url + "/wp-json/wp/v2/note/" + thisNote.data("id"),
            type: "DELETE",
            success: (response) => {
                thisNote.slideUp();
                console.log("Congrats");
                console.log(response);
                console.log("Congrats");
            },
            error: (response) => {
                console.log("Sorry");
                console.log(response);
                console.log("Sorry");
            }
        });
    }
}

export default MyNotes;