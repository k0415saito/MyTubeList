<template>
    <div class="seek_container" ref="seek">
        <div class="seek_bar">
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
        }
    }, 
    mounted() {
    }
}
</script>

<style lang="scss">
@import 'resources/assets/sass/variables';

.seek_container {
    width: 100%;
    height: 12px;
    background: linear-gradient(to bottom, darken($color-theme, 30%), darken($color-theme, 20%));
}
</style>
