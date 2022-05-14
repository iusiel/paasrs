import { createApp } from 'vue';
import CreateDeckForm from '../components/CreateDeckForm.vue';

createApp({
  data() {
    return {
      // count: 0
    };
  },
  components: {
    CreateDeckForm,
  },
}).mount('#app');
