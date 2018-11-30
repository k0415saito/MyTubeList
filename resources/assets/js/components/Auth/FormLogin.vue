<template>
    <div class="form_window">
        <div class="form_title">
            <h2>ログイン</h2>
        </div>
        <div class="form_content">
            <el-form :model="loginForm" ref="loginForm" :rules="rules">
                <el-form-item prop="email">
                    <el-input placeholder="メールアドレス" type="email" v-model="loginForm.email"></el-input>
                </el-form-item>
                <el-form-item prop="password" ref="password">
                    <el-input placeholder="パスワード" type="password" v-model="loginForm.password"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="submitForm('loginForm')" :loading="awaitLogin">ログイン</el-button>
                </el-form-item>
            </el-form>
            <div class="change_mode"><a @click="$emit('modeChange')">>>新規登録</a></div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            awaitLogin: false, 

            loginForm: {
                email: '', 
                password: ''
            }, 
            rules: {
                email: [
                    { required: true, message: 'メールアドレスを入力してください', trigger: 'blur' }, 
                    { type: 'email', message: '正しいメールアドレスを入力してください', trigger: 'change' }
                ], 
                password: [
                    { required: true, message: 'パスワードを入力してください', trigger: 'blur' }
                ]
            }
        }
    }, 
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate(valid => {
                if (valid) {
                    this.login()
                }else {
                    if (this.loginForm.password !== '') {
                        this.$refs['password'].resetField()
                    }
                }
            })
        }, 

        async login() {
            this.awaitLogin = true
            await axios.post('auth/login', {
                email: this.loginForm.email, 
                password: this.loginForm.password 
            })
            .then(response => {
                this.$emit('loginSuccess')
            })
            .catch(error => {
                this.$refs['password'].resetField()
                this.$message({ message: 'メールアドレスかパスワードが間違っています', type: 'error' })
            })
            this.awaitLogin = false
        }
    }
}
</script>

<style lang="scss">
@import 'resources/assets/sass/mixin';

.form_window {
    @include form_window();
}

.form_title {
    @include form_title();
}

.form_content {
    @include form_content();
}

.change_mode {
    @include form_change();
}
</style>