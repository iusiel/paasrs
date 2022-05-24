import { createApp } from 'vue';
import CreateDeckForm from '../components/CreateDeckForm.vue';
import DeckStudy from '../components/DeckStudy.vue';

createApp({
  data() {
    return {
      // count: 0
    };
  },
  components: {
    CreateDeckForm,
    DeckStudy,
  },
}).mount('#app');
