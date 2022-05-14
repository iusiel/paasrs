<script>
export default {
  data() {
    return {
      deckName: '',
    };
  },

  methods: {
    submitForm(event) {
      event.preventDefault();
    },
  },
};
</script>

<template>
    <div >
        <form @submit="submitForm">
            <div class="row">
                <div class="col col-8">
                    <input v-model="deckName" type="text" class="form-control" >
                </div>

                <div class="col col-4">
                    <button type="submit" class="btn btn-primary">Create new deck</button>
                </div>
            </div>
        </form>
    </div>
</template>
