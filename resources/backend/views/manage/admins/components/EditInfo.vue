<template>
    <div class="app-form-container">
        <el-form ref="form" :model="data" :rules="rules" label-width="80px" class="form-container">
            <div class="main-form-container">
                <el-row>
                    <el-col :span="24">
                        <div class="form-item-container">
                            <el-form-item label="头像" prop="file_id">
                                <my-upload v-model="data.avatar" @upload="response => {this.dialogFormData.file_id = response.data.id; this.dialogFormData.avatar = response.data.image;}" />
                            </el-form-item>
                            <el-form-item label="账号" prop="username" placeholder="请输入账号">
                                <el-input v-model="data.username" />
                            </el-form-item>
                            <el-form-item label="昵称" prop="nickname" placeholder="请输入昵称">
                                <el-input v-model="data.nickname" />
                            </el-form-item>
                            <el-form-item label="手机" prop="mobile" placeholder="请输入手机号">
                                <el-input v-model="data.mobile" />
                            </el-form-item>
                            <el-form-item label="邮箱" prop="email" placeholder="请输入路由名">
                                <el-input v-model="data.email" />
                            </el-form-item>
                            <el-form-item label="密码" prop="password">
                                <el-input v-model="data.password" show-password placeholder="请输入密码" />
                            </el-form-item>
                            <el-form-item label="状态" prop="status">
                                <el-radio-group v-model="data.status">
                                    <el-radio
                                        v-for="item in list_status"
                                        :key="item.key"
                                        :value="item.value"
                                        :label="item.key">
                                        {{ item.value }}
                                    </el-radio>
                                </el-radio-group>
                            </el-form-item>
                            <el-form-item>
                                <el-button :loading="loading" type="success" @click="submit">{{ $t('form.submit') }}</el-button>
                                <el-button>{{ $t('form.cancel') }}</el-button>
                            </el-form-item>
                        </div>
                    </el-col>
                </el-row>
            </div>
        </el-form>
    </div>
</template>

<script>
    import {getInfo, add, update} from '@/api/admins';
    import MyUpload from '@/components/Upload/UploadAvatar';
    import {mapGetters} from "vuex";

    const defaultData = {
        id: undefined,
        file_id: '',
        avatar: '',
        nickname: '',
        username: '',
        password: '',
        mobile: '',
        email: '',
        status: 1,
    };

    export default {
        name: 'AdminsEditInfo',
        components: {MyUpload},
        props: {
            isUpdate: {
                type: Boolean,
                default: false
            }
        },
        data() {
            const validatePassword = (rule, value, callback) => {
                if ((value === undefined || value === '') && !this.isUpdate) {
                    this.$message({
                        message: '请输入密码',
                        type: 'error'
                    });
                    callback(new Error('请输入密码'));
                } else if(value && (value.length < 6 || value.length > 20)) {
                    this.$message({
                        message: '密码长度在 6 到 20 个字符',
                        type: 'error'
                    });
                    callback(new Error('密码长度在 6 到 20 个字符'));
                } else {
                    callback();
                }
            };
            return {
                data: Object.assign({}, defaultData),
                loading: false,
                rules: {
                    nickname: [
                        {required: true, message: '请输入昵称'},
                        {min: 2, max: 20, message: '昵称长度在 2 到 20 个字符'}
                    ],
                    username: [
                        {required: true, message: '请输入账号'},
                        {min: 2, max: 20, message: '账号长度在 2 到 20 个字符'}
                    ],
                    mobile: [
                        {pattern: /^1[3456789]\d{9}$/, message: '请输入正确的手机号', trigger: ['blur', 'change'] }
                    ],
                    email: [
                        {type: 'email', message: '请输入正确的邮箱地址', trigger: ['blur', 'change'] }
                    ],
                    password: [{validator: validatePassword, trigger: 'blur'}]
                },
                list_status: [
                    {key: 1, value: '正常'},
                    {key: 2, value: '禁用'},
                ]
            };
        },
        created() {
            if (this.isUpdate) {
                const id = this.$route.query && this.$route.query.id;
                this.getInfo(id);
            }
        },
        computed: {
            ...mapGetters([
                'manageInfo'
            ])
        },
        methods: {
            getInfo(id) {
                getInfo({id: id}).then(response => {
                    this.data = response.data;
                }).catch(error => {
                    console.log(error);
                });
            },
            submit() {
                this.$refs.form.validate(valid => {
                    if (valid) {
                        this.loading = true;
                        let result;
                        if(this.isUpdate){
                            result = update(this.data);
                        }else{
                            result = add(this.data);
                        }
                        result.then(response => {
                            this.$notify({
                                title: '成功',
                                message: response.message,
                                type: 'success',
                                duration: 2000
                            });
                            if(response.data.id === this.manageInfo.id){
                                this.$store.dispatch('manage/getInfo');
                            }
                        }).catch(error => {
                            console.log(error);
                        });
                        this.loading = false;
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
        }
    }
</script>

<style lang="scss" scoped>
    @import "~@/styles/mixin.scss";

    .app-form-container {
        position: relative;

        .main-form-container {
            padding: 40px 45px 20px 50px;

            .form-item-container {
                position: relative;
                @include clearfix;
                margin-bottom: 10px;

                .form-item-container-item {
                    float: left;
                }
            }
        }
    }
</style>
