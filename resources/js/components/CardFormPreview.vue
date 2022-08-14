<template>
    <div class="mb-5">
        <div class="fst-italic h6">{{ studyTags }}</div>
        <div v-html="studyQuestion" class="mb-3 fs-3"></div>
        <div
            v-html="studyAnswer"
            class="border-top border-bottom border-dark py-4 fs-4"
        ></div>
        <div v-if="extraInformation" class="my-3">
            <div
                v-html="studyExtraInformation"
                class="alert alert-info mb-0 fs-6 text-break"
            ></div>
        </div>
    </div>
</template>

<script>
const MarkdownIt = require("markdown-it");

import("../../plugins/prism/prism.js");

export default {
    name: "CardFormPreview",
    props: ["tags", "question", "answer", "extraInformation"],

    data() {
        return {};
    },

    mounted() {
        window.Prism.highlightAll();
    },

    computed: {
        studyTags() {
            return Array.isArray(this.tags) ? this.tags.join(",") : this.tags;
        },

        studyQuestion() {
            return this.convertMarkdownToHTML(this.question);
        },

        studyAnswer() {
            return this.convertMarkdownToHTML(this.answer);
        },

        studyExtraInformation() {
            return this.convertMarkdownToHTML(this.extraInformation);
        },
    },

    methods: {
        convertMarkdownToHTML(markdown) {
            const md = new MarkdownIt({
                html: true,
                linkify: true,
                breaks: true,
            });
            return md.render(markdown);
        },
    },
};
</script>
