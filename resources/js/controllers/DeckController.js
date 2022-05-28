import { createApp } from "vue";
import CreateDeckForm from "../components/CreateDeckForm.vue";
import DeckStudy from "../components/DeckStudy.vue";
import DeckSettingsForm from "../components/DeckSettingsForm.vue";

createApp({
    data() {
        return {
            deck: window.deck,
        };
    },
    components: {
        CreateDeckForm,
        DeckStudy,
        DeckSettingsForm,
    },
}).mount("#app");
