<template>
    <div class="app-container">
        <div class="filter-container">
            <el-input
                v-model="params.title"
                placeholder="角色名称"
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
            <!--<router-link v-if="checkPermission('admin.roles.add')" :to="{path: '/manage/roles/add'}">
                <el-button
                    v-if="checkPermission('admin.roles.add')"
                    type="primary"
                    class="filter-item"
                    icon="el-icon-edit"
                    style="margin-left: 10px;">
                    {{ $t('table.add') }}
                </el-button>
            </router-link>-->
            <el-button
                v-if="checkPermission('admin.roles.add')"
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
            <el-table-column prop="title" label="角色名称" width="120" />
            <el-table-column prop="brief" label="角色简介" width="320" />
            <el-table-column label="状态" width="180" align="center">
                <template slot-scope="scope">
                    <el-switch
                        v-if="checkPermission('admin.roles.update')"
                        v-model="scope.row.status"
                        active-text="正常"
                        inactive-text="禁用"
                        :active-value="1"
                        :inactive-value="2"
                        @change="switchStatus(scope.row)">
                    </el-switch>
                    <el-tag v-else-if="scope.row.status === 1" type="success">正常</el-tag>
                    <el-tag v-else type="error">禁用</el-tag>
                </template>
            </el-table-column>
            <el-table-column prop="created_at" label="创建时间" width="155" align="center" />
            <el-table-column label="操作" min-width="180" align="left" fixed="right">
                <template slot-scope="scope">
                    <!--<router-link :to="{path: '/manage/roles/update/', query: {id: scope.row.id}}">
                        <el-button type="primary" icon="el-icon-edit">修改</el-button>
                    </router-link>-->
                    <el-link
                        v-if="checkPermission('admin.roles.update')"
                        type="primary"
                        icon="el-icon-edit"
                        @click="update(scope.row)">
                        {{ $t('table.update') }}
                    </el-link>
                    <el-link
                        v-if="checkPermission('admin.roles.setPermissions')"
                        type="warning"
                        icon="el-icon-lock"
                        @click="setPermission(scope.row)">
                        {{ $t('permission') }}
                    </el-link>
                    <el-popconfirm
                        v-if="checkPermission('admin.roles.delete')"
                        confirmButtonText='确定'
                        cancelButtonText='取消'
                        icon="el-icon-info"
                        iconColor="red"
                        title="确定删除吗？"
                        @onConfirm="del(scope.row)">
                        <el-link
                            slot="reference"
                            type="danger"
                            icon="el-icon-delete"
                            style="margin-left: 10px;">
                            {{ $t('table.delete') }}
                        </el-link>
                    </el-popconfirm>
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
            v-loading="dialogFormLoading"
            width="480px"
            center>
            <el-form
                ref="dialogForm"
                :rules="dialogFormRules"
                :model="dialogFormData"
                label-width="80px">
                <el-form-item label="角色名称" prop="title">
                    <el-input v-model="dialogFormData.title" placeholder="请输入角色名称" />
                </el-form-item>
                <el-form-item label="角色简介" prop="brief">
                    <el-input
                        v-model="dialogFormData.brief"
                        type="textarea"
                        :autosize="{ minRows: 2, maxRows: 4}"
                        placeholder="请输入角色简介" />
                </el-form-item>
                <el-form-item label="状态" prop="status">
                    <el-radio-group v-model="dialogFormData.status">
                        <el-radio
                            v-for="item in list_status"
                            :key="item.key"
                            :value="item.value"
                            :label="item.key">
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
            title="角色权限"
            :visible.sync="dialogVisible"
            v-loading="dialogLoading"
            width="480px"
            center>
            <el-form
                ref="dialog"
                :model="dialogFormData"
                label-width="80px">
                <el-form-item label="角色名称" prop="title">
                    <el-input v-model="dialogFormData.title" disabled />
                </el-form-item>
                <el-form-item label="权限" prop="permissions">
                    <el-tree
                        ref="tree"
                        :data="list_permissions"
                        :props="props"
                        show-checkbox
                        node-key="id"
                        :expand-on-click-node="false"
                        :check-on-click-node="true"
                        :default-expand-all="true" />
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">{{ $t('form.cancel') }}</el-button>
                <el-button type="primary" @click="dialogSubmit">{{ $t('form.submit') }}</el-button>
            </div>
        </el-dialog>

    </div>
</template>

<script>
    import {getList, add, update, del, getPermissions, setPermissions} from '@/api/roles';
    import {getAll} from '@/api/permissions';
    import {listToTree} from '@/utils/index';
    import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
    import checkPermission from '@/utils/permission';
    import waves from '@/directive/waves'; // waves directive

    const defaultData = {
        id: undefined,
        title: '',
        brief: '',
        pname: '',
        pid: 0,
        status: 1,
    };

    export default {
        name: 'RolesList',
        components: {Pagination},
        directives: {waves},
        data() {
            return {
                data: [],
                total: 0,
                loading: false,
                downloadLoading: false,
                params: {
                    page: 1,
                    limit: 20,
                    title: undefined,
                    status: undefined
                },
                list_status: [
                    {key: 1, value: '正常'},
                    {key: 2, value: '禁用'},
                ],
                isUpdate: false,
                dialogFormTitle: '',
                dialogFormLoading: false,
                dialogFormVisible: false,
                dialogFormData: Object.assign({}, defaultData),
                dialogFormRules: {
                    title: [
                        {required: true, message: '请输入角色名称'},
                        {min: 2, max: 20, message: '角色名称长度在 2 到 20 个字符'}
                    ]
                },
                dialogLoading: false,
                dialogVisible: false,
                list_permissions: [],
                props: {
                    label: 'title',
                    children: 'children',
                    disabled(data, node) {
                        return 1 !== data.status;
                    }
                },
            }
        },
        created() {
            this.getList();
            this.getListPermissions();
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
            getListPermissions() {
                if(this.checkPermission('admin.permissions.all')) {
                    getAll().then(response => {
                        this.list_permissions = listToTree(response.data);
                    }).catch(error => {
                        console.log(error);
                    });
                }
            },
            getListRolePermissions() {
                if(this.checkPermission('admin.roles.getPermissions')) {
                    getPermissions(this.dialogFormData).then(response => {
                        let list_pid = response.data.map(data => data.pid);
                        list_pid = Array.from(new Set(list_pid)); // 去重
                        this.$refs.tree.setCheckedNodes(response.data.filter(data => !list_pid.includes(data.id)));
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
                this.dialogFormTitle = this.$t('route.roles.add');
                this.dialogFormVisible = true;
                this.dialogFormData = Object.assign({}, defaultData);
                this.$nextTick(() => {
                    this.$refs.dialogForm.clearValidate()
                });
            },
            update(data) {
                this.isUpdate = true;
                this.dialogFormTitle = this.$t('route.roles.update');
                this.dialogFormVisible = true;
                this.dialogFormData = Object.assign({}, data);
                this.$nextTick(() => {
                    this.$refs.dialogForm.clearValidate()
                });
            },
            del(data) {
                del(data).then(response => {
                    this.data.splice(this.data.indexOf(data), 1);
                    this.$notify({
                        title: '成功',
                        message: response.message,
                        type: 'success',
                        duration: 2000
                    });
                    this.total -= 1;
                }).catch(error => {
                    console.log(error);
                });
            },
            switchStatus(data){
                if(checkPermission('admin.roles.update')){
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
            setPermission(data) {
                this.dialogVisible = true;
                this.dialogFormData = Object.assign({}, data);
                this.getListRolePermissions();
            },
            dialogSubmit() {
                let permission_ids = this.$refs.tree.getHalfCheckedKeys();
                permission_ids = permission_ids.concat(this.$refs.tree.getCheckedKeys());
                this.dialogLoading = true;
                setPermissions(Object.assign({}, this.dialogFormData, {permission_ids: permission_ids})).then(response => {
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

