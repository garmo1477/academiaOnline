<template>
    <table class="table table-dark table-striped">
        <slot name="topics-thead" />
        <slot name="topics-tbody" />
    </table>
</template>

<script>
    export default {
        name: "TopicsTable",
    }
</script>
