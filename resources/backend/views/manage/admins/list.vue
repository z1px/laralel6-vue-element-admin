<template>
    <div class="app-container">
        <div class="filter-container">
            <el-input
                v-model="params.keyword"
                placeholder="账号/昵称/手机号/邮箱"
                class="filter-item"
                style="width: 200px;"
                clearable/>
            <el-select
                v-model="params.status"
                :placeholder="$t('table.status')"
                clearable
                class="filter-item"
                style="width: 200px">
                <el-option
                    v-for="item in list_status"
                    :key="item.key"
                    :value="item.key"
                    :label="item.value" />
            </el-select>
            <el-button
                type="primary"
                class="filter-item"
                icon="el-icon-search"
                @click="search"
                v-waves>
                {{ $t('table.search') }}
            </el-button>
            <!--<router-link v-if="checkPermission('admin.admins.add')" :to="{path: '/manage/admins/add'}">
                <el-button
                    v-if="checkPermission('admin.admins.add')"
                    type="primary"
                    class="filter-item"
                    icon="el-icon-edit"
                    style="margin-left: 10px;">
                    {{ $t('table.add') }}
                </el-button>
            </router-link>-->
            <el-button
                v-if="checkPermission('admin.admins.add')"
                type="primary"
                class="filter-item"
                icon="el-icon-edit"
                @click="add"
                style="margin-left: 10px;">
                {{ $t('table.add') }}
            </el-button>
            <el-button
                class="filter-item"
                type="primary"
                icon="el-icon-download"
                :loading="downloadLoading"
                @click="download"
                v-waves>
                {{ $t('table.export') }}
            </el-button>
        </div>

        <el-table v-loading="loading" :data="data" fit highlight-current-row style="width: 100%">
            <el-table-column prop="id" label="ID" width="80" align="center" />
            <el-table-column label="头像" width="80" align="center">
                <template slot-scope="scope">
                    <img v-if="scope.row.avatar" :src="scope.row.avatar" @click="dialogImage(scope.row)" />
                    <i v-else class="el-icon-picture" />
                </template>
            </el-table-column>
            <el-table-column prop="nickname" label="昵称" width="120" />
            <el-table-column prop="username" label="账号" width="120" />
            <el-table-column prop="mobile" label="手机" width="100" />
            <el-table-column prop="email" label="邮箱" width="100" />
            <el-table-column label="状态" width="180" align="center">
                <template slot-scope="scope">
                    <el-switch
                        v-if="checkPermission('admin.admins.update')"
                        v-model="scope.row.status"
                        active-text="正常"
                        inactive-text="禁用"
                        :active-value="1"
                        :inactive-value="2"
                        :disabled="scope.row.id === 1 ? true : false"
                        @change="switchStatus(scope.row)">
                    </el-switch>
                    <el-tag v-else-if="scope.row.status === 1" type="success">正常</el-tag>
                    <el-tag v-else type="error">禁用</el-tag>
                </template>
            </el-table-column>
            <el-table-column prop="login_at" label="最后登录时间" width="155" align="center" />
            <el-table-column prop="created_at" label="创建时间" width="155" align="center" />
            <el-table-column label="操作" min-width="150" align="left" fixed="right">
                <template slot-scope="scope">
                    <!--<router-link v-if="checkPermission('admin.admins.update')" :to="{path: '/manage/admins/update/', query: {id: scope.row.id}}">
                        <el-button type="primary" icon="el-icon-edit">{{ $t('table.update') }}</el-button>
                    </router-link>-->
                    <el-link
                        v-if="checkPermission('admin.admins.update')"
                        type="primary"
                        icon="el-icon-edit"
                        @click="update(scope.row)">
                        {{ $t('table.update') }}
                    </el-link>
                    <el-link
                        v-if="checkPermission('admin.admins.setRoles') && scope.row.id !== 1"
                        type="warning"
                        icon="el-icon-lock"
                        @click="setRoles(scope.row)">
                        {{ $t('role') }}
                    </el-link>
                </template>
            </el-table-column>
        </el-table>

        <pagination
            v-show="total>0"
            :total="total"
            :page.sync="params.page"
            :limit.sync="params.limit"
            @pagination="getList"/>

        <el-dialog
            :title="dialogFormTitle"
            :visible.sync="dialogFormVisible"
            :loading="dialogFormLoading"
            width="480px"
            center>
            <el-form
                ref="dialogForm"
                :rules="dialogFormRules"
                :model="dialogFormData"
                label-width="80px">
                <el-form-item label="头像" prop="file_id">
                    <my-upload v-model="dialogFormData.avatar" @upload="response => {this.dialogFormData.file_id = response.data.id; this.dialogFormData.avatar = response.data.image;}" />
                </el-form-item>
                <el-form-item label="账号" prop="username">
                    <el-input v-model="dialogFormData.username" placeholder="请输入账号" />
                </el-form-item>
                <el-form-item label="昵称" prop="nickname">
                    <el-input v-model="dialogFormData.nickname" placeholder="请输入昵称" />
                </el-form-item>
                <el-form-item label="手机" prop="mobile">
                    <el-input v-model="dialogFormData.mobile" placeholder="请输入手机号" />
                </el-form-item>
                <el-form-item label="邮箱" prop="email">
                    <el-input v-model="dialogFormData.email" placeholder="请输入邮箱号" />
                </el-form-item>
                <el-form-item label="密码" prop="password">
                    <el-input v-model="dialogFormData.password" show-password placeholder="请输入密码" />
                </el-form-item>
                <el-form-item label="状态" prop="status">
                    <el-radio-group v-model="dialogFormData.status">
                        <el-radio
                            v-for="item in list_status"
                            :key="item.key"
                            :value="item.value"
                            :label="item.key"
                            :disabled="dialogFormData.id === 1 ? true : false">
                            {{ item.value }}
                        </el-radio>
                    </el-radio-group>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false">{{ $t('form.cancel') }}</el-button>
                <el-button type="primary" @click="dialogFormSubmit">{{ $t('form.submit') }}</el-button>
            </div>
        </el-dialog>

        <el-dialog
            title="角色设置"
            :visible.sync="dialogVisible"
            v-loading="dialogLoading"
            width="480px"
            center>
            <el-form
                ref="dialog"
                :model="dialogFormData"
                label-width="80px">
                <el-form-item label="账号" prop="title">
                    <el-input v-model="dialogFormData.username" disabled />
                </el-form-item>
                <el-form-item label="角色" prop="roles">
                    <el-checkbox
                        :indeterminate="dialogIndeterminate"
                        v-model="dialogCheckAll"
                        @change="dialogCheckAllChange">全选</el-checkbox>
                    <div style="margin: 15px 0;"></div>
                    <el-checkbox-group v-model="dialogChecked" @change="dialogCheckedChange">
                        <el-checkbox
                            v-for="item in list_roles"
                            :key="item.id"
                            :label="item.id"
                            :disabled="item.status !== 1">
                            {{ item.title }}
                        </el-checkbox>
                    </el-checkbox-group>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">{{ $t('form.cancel') }}</el-button>
                <el-button type="primary" @click="dialogSubmit">{{ $t('form.submit') }}</el-button>
            </div>
        </el-dialog>

        <el-dialog :visible.sync="dialogImageVisible" width="480px">
            <img width="100%" :src="dialogImageUrl">
        </el-dialog>
    </div>
</template>

<script>
    import {getList, add, update, getRoles, setRoles} from '@/api/admins';
    import {getAll} from '@/api/roles';
    import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
    import MyUpload from '@/components/Upload/UploadAvatar';
    import checkPermission from '@/utils/permission';
    import waves from '@/directive/waves'; // waves directive
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
        name: 'AdminsList',
        components: {Pagination, MyUpload},
        directives: {waves},
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
                data: [],
                total: 0,
                loading: true,
                downloadLoading: false,
                params: {
                    page: 1,
                    limit: 20,
                    keyword: undefined,
                    status: undefined,
                },
                list_status: [
                    {key: 1, value: '正常'},
                    {key: 2, value: '禁用'},
                ],
                dialogImageVisible: false, // 展示弹窗开关
                dialogImageUrl: '',
                isUpdate: false,
                dialogFormTitle: '',
                dialogFormLoading: false,
                dialogFormVisible: false,
                dialogFormData: Object.assign({}, defaultData),
                dialogFormRules: {
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
                dialogLoading: false,
                dialogVisible: false,
                dialogIndeterminate: false,
                dialogCheckAll: false,
                dialogChecked: [],
                list_roles: []
            }
        },
        created() {
            this.getList();
            this.getListRoles();
        },
        computed: {
            ...mapGetters([
                'manageInfo'
            ])
        },
        methods: {
            checkPermission,
            getList() {
                this.loading = true;
                getList(this.params).then(response => {
                    this.data = response.data;
                    this.total = response.total;
                    this.loading = false;
                })
            },
            getListRoles() {
                if(this.checkPermission('admin.roles.all')){
                    getAll().then(response => {
                        this.list_roles = response.data;
                    }).catch(error => {
                        console.log(error);
                    });
                }
            },
            getListAdminRoles() {
                if(this.checkPermission('admin.admins.getRoles')) {
                    getRoles(this.dialogFormData).then(response => {
                        this.dialogChecked = response.data.map(data => data.id);
                    }).catch(error => {
                        console.log(error);
                    });
                }
            },
            search() {
                if(!this.loading) {
                    this.params.page = 1;
                    this.getList();
                }
            },
            download() {
                this.downloadLoading = true;
                setTimeout(() => {
                    this.downloadLoading = false;
                    this.$message({
                        message: '导出失败',
                        type: 'error'
                    });
                }, 1.5 * 1000);
            },
            add() {
                this.isUpdate = false;
                this.dialogFormTitle = this.$t('route.admins.add');
                this.dialogFormVisible = true;
                this.dialogFormData = Object.assign({}, defaultData);
                this.$nextTick(() => {
                    this.$refs.dialogForm.clearValidate()
                });
            },
            update(data) {
                this.isUpdate = true;
                this.dialogFormTitle = this.$t('route.admins.update');
                this.dialogFormVisible = true;
                this.dialogFormData = Object.assign({}, data);
                this.$nextTick(() => {
                    this.$refs.dialogForm.clearValidate()
                });
            },
            switchStatus(data){
                if(checkPermission('admin.admins.update')){
                    update(data).then(response => {
                        this.$notify({
                            title: '成功',
                            message: response.message,
                            type: 'success',
                            duration: 2000
                        });
                    }).catch(error => {
                        data.status = data.status === 1 ? 2 : 1;
                        console.log(error);
                    });
                }else{
                    data.status = data.status === 1 ? 2 : 1;
                    this.$message({
                        title: '错误',
                        message: '无权限',
                        type: 'error',
                        duration: 2000
                    });
                }
                return true;
            },
            dialogImage(data) {
                this.dialogImageUrl = data.avatar;
                this.dialogImageVisible = true;
            },
            dialogFormSubmit() {
                this.$refs.dialogForm.validate((valid) => {
                    if (valid) {
                        this.dialogFormLoading = true;
                        let result;
                        if(this.isUpdate){
                            result = update(this.dialogFormData);
                        }else{
                            result = add(this.dialogFormData);
                        }
                        result.then(response => {
                            this.dialogFormLoading = false;
                            this.dialogFormVisible = false;
                            this.$notify({
                                title: '成功',
                                message: response.message,
                                type: 'success',
                                duration: 2000
                            });
                            if(this.isUpdate){
                                const index = this.data.findIndex((data) => {
                                    return this.dialogFormData.id === data.id;
                                });
                                if(index >= 0){
                                    this.$set(this.data, index, response.data);
                                }
                                if(response.data.id === this.manageInfo.id){
                                    this.$store.dispatch('manage/getInfo');
                                }
                            }else{
                                this.data.unshift(response.data);
                                this.total += 1;
                            }
                        }).catch(error => {
                            this.dialogFormLoading = false;
                            console.log(error);
                        });
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            setRoles(data) {
                this.dialogVisible = true;
                this.dialogFormData = Object.assign({}, data);
                this.getListAdminRoles();
            },
            dialogCheckAllChange(value) {
                this.dialogChecked = value ? this.list_roles.map(data => data.id) : [];
                this.dialogIndeterminate = false;
            },
            dialogCheckedChange(value) {
                let checkedCount = value.length;
                this.dialogCheckAll = checkedCount === this.list_roles.length;
                this.dialogIndeterminate = checkedCount > 0 && checkedCount < this.list_roles.length;
            },
            dialogSubmit() {
                this.dialogLoading = true;
                setRoles(Object.assign({}, this.dialogFormData, {role_ids: this.dialogChecked})).then(response => {
                    this.dialogLoading = false;
                    this.dialogVisible = false;
                    this.$notify({
                        title: '成功',
                        message: response.message,
                        type: 'success',
                        duration: 2000
                    });
                }).catch(error => {
                    this.dialogLoading = false;
                    console.log(error);
                });
            }
        }
    }
</script>
