<template>
    <div class="add_video_container">
        <transition name="el-zoom-in-top">
            <div class="add_form" v-show="!loading">
                <input class="video_url_input" placeholder="YouTube動画のURL" @keyup.enter="submit" v-model="inputURL">
                <button class="add_video_button" @click="submit" title="動画を追加"><i class="el-icon-plus"></i></button>
            </div>
        </transition>
        <transition name="el-fade-in">
            <i class="el-icon-loading add_loading" v-show="loading"></i>
        </transition>
    </div>
</template>

<script>

export default {
    data() {
        return {
            inputURL: '', 
            loading: false
        }
    }, 
    methods: {
        async submit() {
            if (this.loading) {
                return
            }
            if (this.inputURL === '') {
                this.$notify({
                    title: 'エラー', type: 'error', offset: 50, customClass: 'custom_notify', 
                    message: 'URLが入力されていません'
                })
            }else if (this.inputURL.match(/(youtube.com\/watch\?v=|youtu.be\/)([a-zA-Z0-9_-]{11})/)) {
                this.loading = true
                await this.$store.dispatch('videos/add', this.inputURL)
                .then(response => {
                    this.$notify({
                        title: response.data.title, type: 'success', offset: 50, customClass: 'custom_notify', 
                        message: '動画が追加されました', 
                    })
                })
                .catch(error => {
                    var msg = '動画を追加できませんでした'
                    if (typeof error.response !== 'undefined' && typeof error.response.data !== 'undefined') {
                        if (error.response.data.error_validate) {
                            msg = '既に登録されている動画です'
                        }else if (error.response.data.error_is_live) {
                            msg = 'ライブ動画は登録できません'
                        }
                    }
                    this.$notify({
                        title: 'エラー', type: 'error', offset: 50, customClass: 'custom_notify', 
                        message: msg
                    })
                })
                this.inputURL = ''
                this.loading = false
            }else {
                this.$notify({
                    title: 'エラー', type: 'error', offset: 50, customClass: 'custom_notify', 
                    message: 'YouTube動画のURLではありません'
                })
                this.inputURL = ''
            }
        }
    }

}
</script>

<style lang="scss">
@import 'resources/assets/sass/variables';

.add_video_container {
    height: 100%;
    padding-right: 20px;
    display: flex;
    align-items: center;
    justify-content: right;
}

.add_form {
    display: flex;
    justify-content: end;
}

.video_url_input {
    width: 280px;
    height: 24px;
    padding: 4px 12px 4px 12px;
    color: darken($color-text-light, 5%);
    background-color: lighten($color-background, 10%);
    outline: none;
    box-shadow: none;
    border: none;
    border-radius: 6px 0 0 6px;
    transition-duration: .3s;

    &:focus {
        
        color: $color-text-light;
        background-color: lighten($color-background, 15%);
    }
}

.add_video_button {
    width: 32px;
    height: 32px;
    color: $color-text-light;
    background-color: $color-theme;
    outline: none;
    border: none;
    border-radius: 0 6px 6px 0;
    cursor: pointer;
    transition-duration: .1s;
    
    &:hover {
        background-color: lighten($color-theme, 10%);
    }

    &:active {
        background-color: darken($color-theme, 5%);
    }
}

.add_loading {
    position: absolute;
    right: 40px;
}

</style>
