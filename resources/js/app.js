require("./bootstrap");

import("../plugins/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js");

if (document.querySelector(".decks")) {
    import("./controllers/DeckController.js");
}

if (document.querySelector(".cards")) {
    import("./controllers/CardController.js");
}
