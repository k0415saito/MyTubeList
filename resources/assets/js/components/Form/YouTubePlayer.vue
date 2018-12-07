<template>
    <div class="player_wrapper">
        <div class="player_overlay"></div>
        <div id="player"></div>
    </div>
</template>

<script>
import YouTubePlayer from 'youtube-player'

export default {
    data() {
        return {
        }
    }, 
    methods: {
        onStateChange(event) {
            this.$store.commit('changeState', { playerState: event.data })
            switch(event.data) {
                case -1:    // 未開始
                break;
                case 0:     // 終了
                    this.$store.dispatch('next')
                break;
                case 1:     // 再生中
                break;
                case 2:     // 停止
                break;
                case 3:     // バッファリング中
                break;
                case 5:     // 頭出し済み
                break;
            }
        }, 
        onError(event) {

            this.$notify({
                title: this.$store.getters['playingVideo'].title, type: 'error', offset: 50, customClass: 'custom_notify', 
                message: '動画が再生できません'
            })
            this.$store.dispatch('next')
        }
    }, 
    mounted() {
        
        window.YTConfig = {
            host: 'https://www.youtube.com'
        }
        const player = YouTubePlayer('player', {
            playerVars: {
                cc_load_policy: 0, 
                controls: 0, 
                fs: 0, 
                iv_load_policy: 3, 
                rel: 0, 
                showinfo: 0, 
                autoplay: 1
            }
        })
        player.addEventListener('onStateChange', this.onStateChange)
        player.addEventListener('onError', this.onError)
        this.$store.commit('setPlayer', { player })
    }, 
    computed: {
       videoId() {
           if (this.$store.getters.playingVideo) {
               return this.$store.getters.playingVideo.video_id
           }
           return ''
       }
    }
}
</script>

<style lang="scss">

.player_wrapper {
    position: relative;
    min-width: 160px;
    width: 160px;
    height: 90px;

    iframe {
        width: 100%;
        height: 100%;
    }
}

.player_overlay {
    background-color: rgba(0, 0, 0, 0);
    position: absolute;
    width: 100%;
    height: 100%;
}

</style>
