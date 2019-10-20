<template>
    <div class="container container-events">
        <article class="media" v-for="workshop in workshops" v-bind:key="workshops.id">
            <div class="media-content">
                <div class="content">
                    <p>
                        <strong>
                            <a :href="workshop.event_url" target="_blank" rel="noopener">{{workshop.title}}</a>
                        </strong>
                        <br>
                        <small>{{workshop.address}}</small>
                    </p>
                </div>
            </div>
        </article>
    </div>
</template>

<script>
    export default {
        name: "EventsIndex",
        props: {
            workshops: {
                type: Array
            }
        }
    }
</script>

<style scoped>
.container-events {
    padding: 3rem;
    border: 1px;
}

</style>
