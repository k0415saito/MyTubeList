<template>
    <div class="info_wrapper">
        <youtube-player/>
        <div class="info_container">
            <div class="info_top_container">
                <div class="info_left_container">
                    <playing-video-info/>
                </div>
                <div class="info_right_container">
                    <p>{{timeText}}</p>
                </div>
            </div>
            <div class="info_bottom_container" ref="info">
                <div class="seek_point" v-show="dragging" v-bind:style="{
                    transform: 'translateX(' + seekPoint + 'px)' 
                }"></div>
                <div class="seek_progress" v-bind:style="{
                    transform: 'scaleX(' + progressScaleX + ')'
                }"></div>
            </div>
        </div>
    </div>
</template>

<script>
import YouTubePlayer from '../Form/YouTubePlayer.vue';
import PlayingVideoInfo from '../Form/PlayingVideoInfo.vue'
import SeekBar from '../Form/SeekBar.vue'

export default {
    components: {
        'youtube-player': YouTubePlayer, 
        'playing-video-info': PlayingVideoInfo, 
        'seek-bar': SeekBar
    }, 
    data() {
        return {
            mouseX: 0, 
            mouseY: 0, 
            seekPoint: 0, 
            progressScaleX: 0, 
            dragging: false, 
            currentTime: 0, 
            updateTime: null, 
        }
    }, 
    mounted() {
        // 再生時間の更新用インターバル
        this.updateTime = setInterval(async () => {
            this.currentTime = Math.round(await this.$store.state.system.player.getCurrentTime())
            this.progressScaleX = this.currentTime / this.$store.getters['videos/currentDurationSeconds']
        }, 250)

        // シーク用マウスイベントの登録
        
        this.$refs.info.addEventListener('mousedown', e => {
            
            const info = this.$refs.info
            const rect = info.getBoundingClientRect()
            this.dragging = true
            this.seekPoint = Math.min(Math.max(0, e.x - rect.left), rect.width - 3)
            e.preventDefault ? e.preventDefault() : e.returnValue = false
        })

        document.addEventListener('mousemove', e => {
            const info = this.$refs.info
            const rect = info.getBoundingClientRect()
            if (!this.dragging) return
            this.seekPoint = Math.min(Math.max(0, e.x - rect.left), rect.width - 3)
        })

        document.addEventListener('mouseup', e => {
            const info = this.$refs.info
            const rect = info.getBoundingClientRect()
            if (this.dragging) {
                const m = this.video.duration.match(/(\d+)H(\d+)M(\d+)S/)
                const t = Number(m[1]) * 3600 + Number(m[2]) * 60 + Number(m[3])
                const s = t * (this.seekPoint / Math.max(1, rect.width - 3))
                this.$store.dispatch('seekTo', Number(s))
            }
            this.dragging = false
        })
    }, 
    methods: {

    }, 
    computed: {
        video() {
            return this.$store.getters['playingVideo']
        }, 
        timeText() {
            if (this.video && this.currentTime != null) {
                const h = Math.floor(this.currentTime / 3600)
                const m = Math.floor((this.currentTime - h * 3600) / 60)
                const s = this.currentTime - h * 3600 - m * 60
                const t = (h <= 0 ? '' : `${h}:`) + `${m}:${('00' + s).slice(-2)}`
                var d = '0:00'
                const match = this.video.duration.match(/(\d+)H(\d+)M(\d+)S/)
                if (match.length == 4) {
                    d = (match[1] == '0' ? '' : `${match[1]}:`) + `${match[2]}:${('00' + match[3]).slice(-2)}`
                }
                return `${t} / ${d}`
            }
            return '0:00 / 0:00'
        }
    }
}
</script>

<style lang="scss">
@import 'resources/assets/sass/variables';

.info_wrapper {
    width: 100%;
    height: $player-height;
    background-color: $color-theme;
    display: flex;
    position: fixed;
    top: $header-height;
    z-index: 10;
    box-shadow: 4px 4px 6px #00000060;
}

.info_container {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition-duration: .3s;

    &:hover {
        .info_bottom_container {
            height: 12px;
        }
    }
}

.info_top_container {
    height: 100%;
    display: flex;
    justify-content: space-between;
}

.info_left_container {
    width: calc(100vw - #{$side-bar-width} - 90px);
}

.info_right_container {
    height: 100%;
    display: flex;
    align-items: flex-end;
    justify-content: right;

    p {
        user-select: none;
        font-size: 14px;
        margin: 8px;
    }
}

.info_bottom_container {
    width: 100%;
    height: 4px;
    background: linear-gradient(to bottom, darken($color-theme, 30%), darken($color-theme, 20%));
    display: flex;
    transition-duration: .3s;
    overflow: hidden;
    position: relative;
}

.seek_progress {
    width: 100%;
    height: 100%;
    transform-origin: 0 0;
    transition-duration: .4s;
    background: linear-gradient(to bottom, darken($color-list, 20%), darken($color-list, 10%));
    
}

.seek_point {
    width: 3px;
    height: 12px;
    background-color: #fff;
    position: absolute;
    z-index: 20;
}
</style>
