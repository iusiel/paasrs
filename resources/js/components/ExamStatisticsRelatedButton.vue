<template>
    <button
        :class="[
            'me-md-4',
            'mb-2',
            'mb-xl-0',
            'py-2',
            'd-block',
            'd-md-inline-block',
            'btn',
            'btn-primary',
        ]"
        @click="showRelatedStatistics"
    >
        View Related Statistics
    </button>
</template>

<script>
import Swal from "sweetalert2";

export default {
    name: "ExamStatisticsRelatedButton",

    props: ["deckName", "encodedExamStatistics"],

    data() {
        return {
            examStatistics: JSON.parse(atob(this.encodedExamStatistics)),
        };
    },

    computed: {
        averageNumberOfQuestions() {
            const filtered = this.examStatistics.filter(
                (element) => element.deck_name === this.deckName
            );

            let sum = 0;
            filtered.forEach((element) => {
                sum += element.number_of_questions;
            });

            return Math.round(sum / filtered.length);
        },

        averageNumberOfEasyAnswers() {
            const filtered = this.examStatistics.filter(
                (element) => element.deck_name === this.deckName
            );

            let sum = 0;
            filtered.forEach((element) => {
                sum += element.easy_answers;
            });

            return `${Math.round(sum / filtered.length)} `;
        },

        averageNumberOfEasyAnswersPercentage() {
            return Math.round(
                (this.averageNumberOfEasyAnswers /
                    this.averageNumberOfQuestions) *
                    100
            );
        },

        averageNumberOfGoodAnswers() {
            const filtered = this.examStatistics.filter(
                (element) => element.deck_name === this.deckName
            );

            let sum = 0;
            filtered.forEach((element) => {
                sum += element.good_answers;
            });

            return `${Math.round(sum / filtered.length)} `;
        },

        averageNumberOfGoodAnswersPercentage() {
            return Math.round(
                (this.averageNumberOfGoodAnswers /
                    this.averageNumberOfQuestions) *
                    100
            );
        },

        averageNumberOfHardAnswers() {
            const filtered = this.examStatistics.filter(
                (element) => element.deck_name === this.deckName
            );

            let sum = 0;
            filtered.forEach((element) => {
                sum += element.hard_answers;
            });

            return `${Math.round(sum / filtered.length)} `;
        },

        averageNumberOfHardAnswersPercentage() {
            return Math.round(
                (this.averageNumberOfHardAnswers /
                    this.averageNumberOfQuestions) *
                    100
            );
        },

        averageExamTime() {
            const filtered = this.examStatistics.filter(
                (element) => element.deck_name === this.deckName
            );

            let sum = 0;
            filtered.forEach((element) => {
                const date = new Date(
                    `2020-03-01 ${element.time_to_finish_exam}`
                );
                sum += date.getTime();
            });

            const averageTimestamp = Math.round(sum / filtered.length);
            const averageDate = new Date();
            averageDate.setTime(averageTimestamp);
            return `${averageDate
                .getHours()
                .toString()
                .padStart(2, "0")}:${averageDate
                .getMinutes()
                .toString()
                .padStart(2, "0")}:${averageDate
                .getSeconds()
                .toString()
                .padStart(2, "0")}`;
        },
    },

    methods: {
        showRelatedStatistics() {
            Swal.fire(
                `Related statistics for deck exam: ${this.deckName}`,
                `
                    Average number of questions: ${this.averageNumberOfQuestions}<br/>
                    Average number of easy answers:  ${this.averageNumberOfEasyAnswers} (${this.averageNumberOfEasyAnswersPercentage}%)<br/>
                    Average number of good answers: ${this.averageNumberOfGoodAnswers} (${this.averageNumberOfGoodAnswersPercentage}%) <br/>
                    Average number of hard answers: ${this.averageNumberOfHardAnswers} (${this.averageNumberOfHardAnswersPercentage}%) <br/>
                    Average exam time: ${this.averageExamTime}
                `,
                ""
            );
        },
    },
};
</script>
