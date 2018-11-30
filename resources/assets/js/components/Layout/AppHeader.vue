<template>
    <div class="header">
        <div class="header_left_container">
            <div class="header_logo">
                <h2>MyTubeList</h2>
            </div>
            <player-ui/>
        </div>
        <add-video-input/>
    </div>
</template>

<script>
import AddVideoInput from '../Form/AddVideoInput.vue'
import PlayerUI from '../Form/PlayerUI.vue'

export default {
    components: {
        'add-video-input': AddVideoInput, 
        'player-ui': PlayerUI
    }, 
    methods: {
    }
}
</script>

<style lang="scss">
@import 'resources/assets/sass/variables';

.header {
    background-color: $color-header;
    width: 100%;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content:space-between;
    position:fixed;
    z-index: 10;
    top: 0px;
}

.header_left_container {
    display: flex;
}

.header_logo {
    width: 160px;
    margin: 0 24px 0 16px;
    display: flex;
    align-items: center;
    h2 {
        font-size: 24px;
        color: $color-text-light;
    }
}

</style>
