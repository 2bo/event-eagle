<template>
    <div class="navbar is-spaced navbar-header">
        <div class="navbar-brand">
            <div class="navbar-item">
                <span class="title is-4">Ivnet Gate</span>
            </div>
            <a class="navbar-burger" aria-label="menu" aria-expanded="false">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>
        <div class="navbar-menu">
            <div class="navbar-end">
                <div class="navbar-item">
                    <input class="input " type="text" placeholder="キーワード検索">
                </div>
                <a class="navbar-item" href="/register">
                    Sign up
                </a>
                <a class="navbar-item" href="/login">
                    login
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Header"
    }
</script>

<style scoped>
    .navbar-header {
    }
</style>
