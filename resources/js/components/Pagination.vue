<style lang="scss">
    .pagination {
        justify-content: flex-end !important;

    .page-stats {
        align-items: center;
    }

    i {
        color: #3273dc !important;
    }

    }
</style>

<template>
    <ul class="pagination">

        <li>
            <a v-if="pagination.prevPage" class="button is-small pagination-previous" @click="$emit('prev');">
                Prev
            </a>
            <a class="button is-small pagination-previous" v-else :disabled="true">
                Prev
            </a>
        </li>
        <li>
              <span class="page-stats">
            {{pagination.from}} - {{pagination.to}} of {{pagination.total}}
            </span>
        </li>

        <li>
            <a v-if="pagination.nextPage" class="button is-small pagination-next" @click="$emit('next');">
                Next
            </a>
            <a class="button is-small pagination-next" v-else :disabled="true">
                Next
            </a>
        </li>

    </ul>
</template>

<script>
    export default {
        props: ['pagination', 'filtered']
    }
</script>