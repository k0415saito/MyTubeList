<template>
    <div class="playing_info_container">
        <div class="playing_info_title">
            <h1>{{ title }}</h1>
        </div>
        <div class="playing_info_artist">
            <p>{{ artist }}</p>
        </div>
        <div class="playing_info_usertag">
            <p>{{ usertag }}</p>
        </div>
    </div>
</template>

<script>
export default {
    computed: {
        title() {
            return this.video ? this.video.title : ''
        }, 
        artist() {
            return this.video ? this.video.artist : ''
        }, 
        usertag() {
            return this.video ? this.video.user_tag : ''
        }, 
        video() {
            return this.$store.getters.playingVideo
        }
    }
}
</script>

<style lang="scss">
@import 'resources/assets/sass/variables';

.playing_info_container {
    width: 100%;
    height: 100%;
    color: $color-text-light;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    user-select: none;

    h1, p {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
}

.playing_info_title {
    height: 50%;
    display: flex;
    align-items: center;
    

    h1 {
        width: 100%;
        font-size: 22px;
        padding-left: 16px;
    }
}

.playing_info_artist {
    height: 25%;
    display: flex;
    align-items: center;

    p {
        font-size: 16px;
        padding-left: 32px;
    }
}

.playing_info_usertag {
    height: 25%;
    display: flex;
    align-items: center;

    p {
        font-size: 16px;
        padding-left: 48px;
    }
}

</style>
