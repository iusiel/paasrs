<template>
  <form id="deckSettingsForm" @submit="submitForm">
    <div class="mb-3">
      <label for="name" class="form-label form-label__required">Name</label>
      <input v-model="formFields.name.value" type="text" name="name" id="name" class="form-control" />
      <div class="form__error-message">{{ formFields.name.errorMessage }}</div>
    </div>
    <div class="mb-3">
      <label for="number_of_cards_per_review" class="form-label form-label__required">Number of cards per review</label>
      <input v-model="formFields.numberOfCardsPerReview.value" type="text" name="number_of_cards_per_review" id="number_of_cards_per_review" class="form-control" />
      <div class="form__error-message">{{ formFields.numberOfCardsPerReview.errorMessage }}</div>
    </div>
    <div class="mb-3">
      <label for="hard_interval" class="form-label form-label__required">Hard interval (in days)</label>
      <input v-model="formFields.hardInterval.value" type="text" name="hard_interval" id="hard_interval" class="form-control" />
      <div class="form__error-message">{{ formFields.hardInterval.errorMessage }}</div>
    </div>
    <div class="mb-3">
      <label for="good_interval" class="form-label form-label__required">Good interval (in days)</label>
      <input v-model="formFields.goodInterval.value" type="text" name="good_interval" id="good_interval" class="form-control"/>
      <div class="form__error-message">{{ formFields.goodInterval.errorMessage }}</div>
    </div>
    <div class="mb-3">
      <label for="easy_interval" class="form-label form-label__required">Easy interval (in days)</label>
      <input v-model="formFields.easyInterval.value" type="text" name="easy_interval" id="easy_interval" class="form-control" />
      <div class="form__error-message">{{ formFields.easyInterval.errorMessage }}</div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <button type="button" class="btn btn-primary ms-3" @click="goToPreviousPage">Cancel</button>
    <button type="button" class="btn btn-primary ms-3" @click="deleteDeckPrompt">Delete Deck</button>
  </form>
</template>

<script>
import Swal from 'sweetalert2';
import JSONFetchClient from '../modules/JSONFetchClient.js';

export default {
  name: 'DeckSettingsForm',

  props: ['deck', 'formAction', 'deleteAction'],

  data() {
    return {
      formMethod: 'POST',
      formFields: {
        name: {
          value: '',
          errorMessage: '',
        },
        numberOfCardsPerReview: {
          value: '',
          errorMessage: '',
        },
        hardInterval: {
          value: '',
          errorMessage: '',
        },
        goodInterval: {
          value: '',
          errorMessage: '',
        },
        easyInterval: {
          value: '',
          errorMessage: '',
        },
      },
    };
  },

  mounted() {
    if (typeof this.deck !== 'undefined') {
      const deck = JSON.parse(atob(this.deck));
      this.formFields.name.value = deck.name;
      this.formFields.numberOfCardsPerReview.value = deck.number_of_cards_per_review;
      this.formFields.hardInterval.value = deck.hard_interval;
      this.formFields.goodInterval.value = deck.good_interval;
      this.formFields.easyInterval.value = deck.easy_interval;
    }
  },

  methods: {
    submitForm(event) {
      event.preventDefault();
      this.clearErrorMessages();

      const myForm = document.getElementById('deckSettingsForm');
      const formData = new FormData(myForm);
      formData.append('_method', 'PUT');

      JSONFetchClient(this.formAction, formData, this.formMethod)
        .then((result) => {
          if (result.message) {
            Swal.fire(
              result.message,
              '',
              'success',
            ).then(() => {
              window.location.reload();
            });
          }
        })
        .catch((error) => {
          error.json().then((result) => {
            const errors = Object.entries(result.errors);
            errors.forEach((fieldError) => {
              const [field, message] = fieldError;
              const [errorMessage] = message;

              const convertedFieldName = this.convertReturnFieldNameToFormFieldName(field);

              if (typeof this.formFields[convertedFieldName] !== 'undefined') {
                this.formFields[convertedFieldName].errorMessage = errorMessage;
              }
            });
          });
        });
    },

    convertReturnFieldNameToFormFieldName(fieldName) {
      if (fieldName === 'number_of_cards_per_review') {
        return 'numberOfCardsPerReview';
      }

      if (fieldName === 'hard_interval') {
        return 'hardInterval';
      }

      if (fieldName === 'good_interval') {
        return 'goodInterval';
      }

      if (fieldName === 'easy_interval') {
        return 'easyInterval';
      }

      return fieldName;
    },

    setErrorMessage(errorField1, errorFiel2, formField, message) {
      if (errorField1 === errorFiel2) {
        this.formFields[formField].errorMessage = message;
      }
    },

    clearErrorMessages() {
      this.formFields.name.errorMessage = '';
      this.formFields.numberOfCardsPerReview.errorMessage = '';
      this.formFields.hardInterval.errorMessage = '';
      this.formFields.goodInterval.errorMessage = '';
      this.formFields.easyInterval.errorMessage = '';
    },

    goToPreviousPage() {
      window.history.back();
    },

    deleteDeckPrompt() {
      Swal.fire({
        title: 'Are you sure you want to delete this deck? All the cards under this deck will also be deleted.',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteDeck();
        }
      });
    },

    deleteDeck() {
      const formData = new FormData();
      formData.append('_method', 'DELETE');

      JSONFetchClient(this.deleteAction, formData, 'POST')
      .then((result) => { //eslint-disable-line
          Swal.fire(
            'Deleted!',
            result.message,
            'success',
          ).then(() => {
            window.location.href = '/';
          });
        })
        .catch((error) => {
          error.json().then(() => {
            Swal.fire(
              'Warning',
              'An error has been encountered. Try deleting the deck again.',
              'warning',
            ).then(() => {
              window.location.reload();
            });
          });
        });
    },
  },
};
</script>
