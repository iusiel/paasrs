import { createApp } from 'vue';
import Swal from 'sweetalert2';
import CardForm from '../components/CardForm.vue';

createApp({
  data() {
    return {
      card: (typeof window.card !== 'undefined') ? window.card : '',
      decks: (typeof window.decks !== 'undefined') ? window.decks : '',
    };
  },
  components: {
    CardForm,
  },
}).mount('#app');

window.onload = function loadDataTable() {
  if (document.getElementById('cardsTable')) {
    $('#cardsTable').DataTable();
  }
};

const deleteButtons = document.querySelectorAll('.card__delete');
if (deleteButtons.length > 0) {
  deleteButtons.forEach((button) => {
    button.addEventListener('click', (event) => {
      event.preventDefault();
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire(
            'Deleted!',
            'Your file has been deleted.',
            'success',
          );
        }
      });
    });
  });
}
