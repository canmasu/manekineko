<template>
    <div class="login_container">
        <div class="login_box">
        <el-form :model="loginForm" ref="loginFormRef" :rules="loginFormRules" class="login_form">
            <el-form-item prop="username" class="login_item">
                <el-input v-model="loginForm.username" prefix-icon="iconfont icon-user" placeholder="Username"></el-input>
            </el-form-item>
            <el-form-item prop="password" class="login_item">
                <el-input type="password" v-model="loginForm.password" prefix-icon="iconfont icon-lock" placeholder="Password"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button size="mini" type="primary" @click="login()">Submit</el-button>
                <el-button size="mini" @click="resetForm('loginFormRef')"> Reset </el-button>
            </el-form-item>
        </el-form>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            loginForm : {
                username : 'Eiichiro',
                password : '123456'
            },
            loginFormRules : {
                username: [
                    { required: true, message: 'insert username', trigger: 'blur' },
                    { min: 3, max: 8, message: 'required 3 to 5 charactors', trigger: 'blur' }
                ],
                password: [
                    { required: true, message: 'insert password', trigger: 'blur' },
                    { min: 6, max: 15, message: 'required minimun 6 charactors', trigger: 'blur' }
                ],
            }

        }
    },
    methods : {
        resetForm(loginFormRef) {
           this.$refs[loginFormRef].resetFields();
        },
        login(){
            this.$refs.loginFormRef.validate( async (valid)=>{
                if(!valid) return;
                const {data: res} = await this.$http.post("login",this.loginForm);
                console.log(res)
            })
        }
    }
}
</script>


<style lang="less" scoped>
.login_container { 
    height:100%;
    background-color:blanchedalmond;
}
.login_box {
    height:200px;
    width:300px;
    background-color: #FFF;
    border-radius: 10px;
    position :absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%,-50%);
    .login_form{
        padding: 10px;
    }
}
.login_form /deep/ .el-input__inner {
    height: 30px;
}

</style>