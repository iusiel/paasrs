<template>
    <a
        :class="[
            'me-md-4',
            'mb-2',
            'mb-xl-0',
            'py-2',
            'd-block',
            'd-md-inline-block',
            'btn',
            'btn-primary',
            isDisabled ? 'pe-none disabled' : '',
        ]"
        v-bind:href="href"
        >Study</a
    >
</template>

<script>
export default {
    name: "StudyButton",

    props: ["href", "disabled", "activateOn"],

    data() {
        return {
            isDisabled: false,
        };
    },

    mounted() {
        this.isDisabled = !!this.disabled;

        setInterval(() => {
            if (Date.now() >= this.activateOn * 1000) {
                this.isDisabled = false;
            }
        }, 60000);
    },

    methods: {},
};
</script>
