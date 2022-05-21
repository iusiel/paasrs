import { createApp } from 'vue';
import CardForm from '../components/CardForm.vue';

createApp({
  data() {
    return {
      // count: 0
    };
  },
  components: {
    CardForm,
  },
}).mount('#app');
