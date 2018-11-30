<template>
    <div class="ui_container">
        <div class="prev_button" @click="prev" title="前の曲へ">
            <i class="material-icons">
                skip_previous
            </i>
        </div>
        <div class="play_button" @click="play" :title="playButtonIcon == 'pause' ? '一時停止' : '再生'">
            <i class="material-icons">
                {{ playButtonIcon }}
            </i>
        </div>
        <div class="next_button" @click="next" title="次の曲へ">
            <i class="material-icons">
                skip_next
            </i>
        </div>
        <el-popover placement="bottom" width="40" trigger="hover" visible-arrow="false">
            <el-slider v-model="volume" vertical height="100px"></el-slider>
            <div class="volume_button" slot="reference">
                <i class="material-icons">
                    volume_up
                </i>
            </div>
        </el-popover>
    </div>
</template>

<script>
import ls from 'local-storage'

export default {
    data() {
        return {

        }
    }, 
    created() {
        
    }, 
    methods: {
        play() {
            this.$store.dispatch('play')
        }, 
        next() {
            this.$store.dispatch('next')
        }, 
        prev() {
            this.$store.dispatch('prev')
        }, 
    }, 
    computed: {
        playButtonIcon() {
            return this.$store.state.system.playerState == 1 || 
            this.$store.state.system.playerState == 3 ? 'pause' : 'play_arrow'
        }, 
        volume: {
            get: function () {
                return this.$store.state.system.volume
            }, 
            set: function(value) {
                this.$store.commit('setVolume', { value: value })
                this.$store.state.system.player.setVolume(value)
            }
        }
    }
}
</script>

<style lang="scss">
@import 'resources/assets/sass/variables';

@mixin round_button($size) {
    width: $size;
    height: $size;
    margin: 6px;
    border-radius: 50%;
    background-color: $color-player-button;
    color: $color-text-dark;
    user-select: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition-duration: .4s;

    &:hover {
        background-color: lighten($color-player-button, 20%);
        transform: scale(1.1, 1.1);
    }

    &:active {
        background-color: darken($color-player-button, 10%);
    }
}

.ui_container {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content:center;
}

.play_button {
    @include round_button(40px);

    i {
        font-size: 32px;
    }
}

.prev_button {
    @include round_button(32px);

}

.next_button {
    @include round_button(32px);
}

.volume_button {
    margin: 10px 4px 6px 20px;
    user-select: none;
}

.el-popover {
    min-width: 0;
}
</style>
