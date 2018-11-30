<template>
    <div class="login_wrapper">
        <div class="login_header">
            <h1>MyTubeList</h1>
        </div>
        <div class="form_container">
            <transition name="el-zoom-in-center">
                <form-login v-show="login_mode" @modeChange="login_mode = false" @loginSuccess="$emit('loginSuccess')"/>
            </transition>
            <transition name="el-zoom-in-center">
                <form-register v-show="!login_mode" @modeChange="login_mode=true" @loginSuccess="$emit('loginSuccess')"/>
            </transition>
        </div>
    </div>
</template>

<script>
import FormLogin from './FormLogin.vue';
import FormRegister from './FormRegister.vue';

export default {
    components: {
        'form-login': FormLogin, 
        'form-register': FormRegister
    }, 
    data: function() {
        return {
            login_mode: true
        }
    }, 
    methods: {
    }, 
    computed: {
    }
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.body {
    background-color: $color-background;
}
.login_wrapper {
    width: 100%;
}

.login_header {
    width: 100%;
    height: 200px;
    background: $color-theme;
    color: $color-text-light;
    display: flex;
    align-items: center;
    
    h1 {
        font-size: 44px;
        margin-left: 64px;
    }
}

.form_container {
    position: relative;
}



</style>
