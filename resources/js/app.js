require('./bootstrap');

import('../plugins/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js');
import('../plugins/prism/prism.js');

if (document.querySelector('.decks')) {
  import('./controllers/DeckController.js');
}
