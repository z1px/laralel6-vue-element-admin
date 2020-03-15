<template>
    <div class="app-form-container">
        <el-form ref="form" :model="data" :rules="rules" label-width="80px" class="form-container">
            <div class="main-form-container">
                <el-row>
                    <el-col :span="24">
                        <div class="form-item-container">
                            <el-form-item label="上级权限" prop="pid">
                                <el-select v-model="data.pid" clearable filterable placeholder="请选择上级权限">
                                    <el-option
                                        v-for="item in list_permission"
                                        :key="item.id"
                                        :value="item.id"
                                        :label="item.title"
                                        :disabled="item.id === data.id">
                                        <span v-if="item.pname">{{ item.pname }} => </span>{{ item.title }}
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="权限名称" prop="title">
                                <el-input v-model="data.title" placeholder="请输入权限名称" />
                            </el-form-item>
                            <el-form-item label="路由名" prop="route_name">
                                <el-input v-model="data.route_name" placeholder="请输入路由名" />
                            </el-form-item>
                            <el-form-item label="权限简介" prop="brief">
                                <el-input
                                    v-model="data.brief"
                                    type="textarea"
                                    :autosize="{ minRows: 2, maxRows: 4}"
                                    placeholder="请输入权限简介" />
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
    import {getAll, getInfo, add, update} from '@/api/permissions';

    const defaultData = {
        id: undefined,
        title: '',
        route_name: '',
        pname: '',
        pid: 0,
        status: 1,
    };

    export default {
        name: 'PermissionsEditInfo',
        props: {
            isUpdate: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                data: Object.assign({}, defaultData),
                loading: false,
                rules: {
                    title: [
                        {required: true, message: '请输入权限名称'},
                        {min: 2, max: 20, message: '权限名称长度在 2 到 20 个字符'}
                    ],
                    route_name: [
                        {required: true, message: '请输入路由名称'},
                        {min: 2, max: 50, message: '路由名称长度在 2 到 50 个字符'}
                    ]
                },
                list_status: [
                    {key: 1, value: '正常'},
                    {key: 2, value: '禁用'},
                ],
                list_permission: [],
            };
        },
        created() {
            if (this.isUpdate) {
                const id = this.$route.query && this.$route.query.id;
                this.getInfo(id);
            }
            this.getAllPermissions();
        },
        methods: {
            getInfo(id) {
                getInfo({id: id}).then(response => {
                    this.data = response.data;
                }).catch(error => {
                    console.log(error);
                });
            },
            getAllPermissions() {
                getAll().then(response => {
                    this.list_permission = response.data;
                    this.list_permission.unshift({
                        id: 0,
                        title: "请选择",
                        route_name: "",
                        status: 1,
                        pid: 0,
                        created_at: "",
                        updated_at: "",
                        status_name: "正常",
                        pname: ""
                    });
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
                            // this.data = response.data;
                        }).catch(error => {
                            console.log(error);
                        });
                        this.loading = false;
                        this.getAllPermissions();
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
