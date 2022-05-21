<template>
  <form id="cardForm" @submit="submitForm">
    <div class="mb-3">
      <label for="question" class="form-label form-label__required">Question</label>
      <textarea v-model="formFields.question.value" id="question" rows=5 class="form-control" name="question"></textarea>
      <div class="form__error-message">{{ formFields.question.errorMessage }}</div>
    </div>
    <div class="mb-3">
      <label for="answer" class="form-label form-label__required">Answer</label>
      <textarea v-model="formFields.answer.value" id="answer" rows=5 class="form-control" name="answer"></textarea>
      <div class="form__error-message">{{ formFields.answer.errorMessage }}</div>
    </div>
    <div class="mb-3">
      <label for="extra-info" class="form-label">Extra Information</label>
      <textarea v-model="formFields.extraInformation.value" id="extra-info" rows=5 class="form-control" name="extra_information"></textarea>
      <div class="form__error-message">{{ formFields.extraInformation.errorMessage }}</div>
    </div>
    <div class="mb-3">
      <label for="tags" class="form-label">Tags</label>
      <input type="text" class="form-control" id="tags" name="tags" placeholder="Tag 1, Tag 2">
      <div class="form-text">Comma separated string (e.g. tag 1,tag 2)</div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</template>

<script>
import Swal from 'sweetalert2';

export default {
  name: 'CardForm',

  props: ['deck'],

  data() {
    return {
      formMethod: 'POST',
      formAction: `${document.querySelector('meta[name="base_url"]').content}/cards`,
      csrfToken: document.querySelector('meta[name="csrf-token"]').content,
      formFields: {
        deckId: this.deck,
        question: {
          value: '',
          errorMessage: '',
        },
        answer: {
          value: '',
          errorMessage: '',
        },
        extraInformation: {
          value: '',
          errorMessage: '',
        },
        tags: {
          value: '',
          errorMessage: '',
        },
        appearOn: {
          value: '',
          errorMessage: '',
        },
      },
    };
  },

  methods: {
    submitForm(event) {
      event.preventDefault();

      const myForm = document.getElementById('cardForm');
      const formData = new FormData(myForm);
      formData.append('deck_id', this.formFields.deckId);

      fetch(this.formAction, {
        headers: {
          'X-CSRF-TOKEN': this.csrfToken,
          'X-Requested-With': 'XMLHttpRequest',
        },
        method: this.formMethod,
        body: formData,
      })
        .then((response) => {
          if (response.ok === false) {
            throw (response);
          }
          return response.json();
        })
        .then((result) => { //eslint-disable-line
          if (result.message === 'Card created successfully.') {
            Swal.fire(
              'Card has been created.',
              '',
              'success',
            ).then((value) => {
              if (value.isConfirmed) {
                window.location.reload();
              }
            });
          }
        })
        .catch((error) => {
          error.json().then((result) => {
            const errors = Object.entries(result.errors);
            errors.forEach((fieldError) => {
              const [field, message] = fieldError;
              const [errorMessage] = message;
              if (field === 'extra_information') {
                this.formFields.extraInformation.errorMessage = errorMessage;
              } else if (this.formFields[field]) {
                this.formFields[field].errorMessage = errorMessage;
              }
            });
          });
        });
    },
  },
};
</script>
