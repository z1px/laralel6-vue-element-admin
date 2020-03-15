<template>
    <el-form ref="form" :model="data" :rules="rules">
        <el-form-item label="头像" prop="avatar">
            <my-upload v-model="data.avatar" @upload="upload" />
        </el-form-item>
        <el-form-item label="账号" prop="username">
            <el-input v-model="data.username" disabled placeholder="请输入账号" />
        </el-form-item>
        <el-form-item label="昵称" prop="nickname">
            <el-input v-model="data.nickname" placeholder="请输入昵称" />
        </el-form-item>
        <el-form-item label="手机" prop="mobile">
            <el-input v-model="data.mobile" placeholder="请输入手机号" />
        </el-form-item>
        <el-form-item label="邮箱" prop="email">
            <el-input v-model="data.email" placeholder="请输入邮箱" />
        </el-form-item>
        <el-form-item label="新密码（不填不修改）" prop="password">
            <el-input v-model="data.password" show-password placeholder="请输入新密码 />
        </el-form-item>
        <el-form-item v-if="data.password" label="旧密码" prop="old_password">
            <el-input v-model="data.old_password" show-password placeholder="请输入旧密码" />
        </el-form-item>
        <el-form-item>
            <el-button :loading="loading" type="primary" @click="submit">修改</el-button>
        </el-form-item>
    </el-form>
</template>

<script>
    import {updateInfo} from '@/api/manage';
    import MyUpload from '@/components/Upload/UploadAvatar';

    export default {
        name: 'Account',
        components: {MyUpload},
        props: {
            value: {
                type: Object,
                default: () => {
                    return {
                        id: undefined,
                        file_id: '',
                        avatar: '',
                        nickname: '',
                        username: '',
                        old_password: '',
                        password: '',
                        mobile: '',
                        email: '',
                        status: 1,
                    }
                }
            }
        },
        data() {
            const validateRequire = (rule, value, callback) => {
                if (value === '') {
                    this.$message({
                        message: rule.field + '为必传项',
                        type: 'error'
                    });
                    callback(new Error(rule.field + '为必传项'));
                } else {
                    callback();
                }
            };
            return {
                data: this.value,
                loading: false,
                rules: {
                    nickname: [{validator: validateRequire}],
                    old_password: [{validator: validateRequire}]
                },
            };
        },
        watch: {
            value: function(newValue, oldValue) {
                this.data = newValue;
            }
        },
        methods: {
            upload(response) {
                this.data.file_id = response.data.id;
                updateInfo({file_id: response.data.id}).then(response => {
                    this.$message({
                        title: '成功',
                        message: response.message,
                        type: 'success',
                        duration: 2000
                    });
                    this.$store.dispatch('manage/getInfo');
                }).catch(error => {
                    console.log(error);
                });
            },
            submit() {
                this.$refs.form.validate(valid => {
                    if (valid) {
                        this.loading = true;
                        updateInfo(this.data).then(response => {
                            this.$message({
                                title: '成功',
                                message: response.message,
                                type: 'success',
                                duration: 2000
                            });
                            this.$store.dispatch('manage/getInfo');
                        }).catch(error => {
                            console.log(error);
                        });
                        this.loading = false;
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            }
        }
    }
</script>
