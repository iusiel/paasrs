import { createApp } from "vue";
import CreateDeckForm from "../components/CreateDeckForm.vue";
import DeckStudy from "../components/DeckStudy.vue";
import DeckSettingsForm from "../components/DeckSettingsForm.vue";
import ImportCards from "../components/ImportCards.vue";
import StudyButton from "../components/StudyButton.vue";
import DeckExam from "../components/DeckExam.vue";

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
        ImportCards,
        StudyButton,
        DeckExam,
    },
}).mount("#app");
