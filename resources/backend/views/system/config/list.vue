<template>
    <div class="app-container">
        <div class="filter-container">
            <el-input
                v-model="params.title"
                placeholder="标题"
                class="filter-item"
                style="width: 200px;"
                clearable/>
            <el-button
                type="primary"
                class="filter-item"
                icon="el-icon-search"
                @click="search"
                v-waves>
                {{ $t('table.search') }}
            </el-button>
            <el-button
                v-if="checkPermission('admin.config.add')"
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
            <el-table-column type="expand">
                <template slot-scope="scope">
                    <el-form label-position="right" inline class="table-expand" label-width="100px">
                        <el-form-item label="ID">
                            <span>{{ scope.row.id }}</span>
                        </el-form-item>
                        <el-form-item label="标题">
                            <span>{{ scope.row.title }}</span>
                        </el-form-item>
                        <el-form-item label="配置值">
                            <span>{{ scope.row.value }}</span>
                        </el-form-item>
                        <el-form-item label="配置简介">
                            <span>{{ scope.row.brief }}</span>
                        </el-form-item>
                        <el-form-item label="表单操作类型">
                            <span>{{ scope.row.input_name }}</span>
                        </el-form-item>
                        <el-form-item label="默认可选值">
                            <span>{{ scope.row.values }}</span>
                        </el-form-item>
                        <el-form-item label="配置类型">
                            <span>{{ scope.row.type_name }}</span>
                        </el-form-item>
                        <el-form-item label="状态">
                            <el-switch
                                v-if="checkPermission('admin.config.update')"
                                v-model="scope.row.status"
                                active-text="正常"
                                inactive-text="禁用"
                                :active-value="1"
                                :inactive-value="2"
                                @change="switchStatus(scope.row)">
                            </el-switch>
                            <el-tag v-else-if="scope.row.status === 1" type="s
                        </el-form-item>
                        <el-form-item label="创建时间">
                            <span>{{ scope.row.created_at }}</span>
                        </el-form-item>
                    </el-form>
                </template>
            </el-table-column>
            <el-table-column prop="id" label="ID" width="80" align="center" />
            <el-table-column prop="title" label="标题" width="120" />
            <el-table-column prop="key" label="配置健" width="120" />
            <el-table-column prop="value" label="配置值" width="120" />
            <el-table-column prop="type_name" label="配置类型" width="120" />
            <el-table-column label="状态" width="180" align="center">
                <template slot-scope="scope">
                    <el-switch
                        v-if="checkPermission('admin.config.update')"
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
            <el-table-column label="操作" min-width="150" align="left" fixed="right">
                <template slot-scope="scope">
                    <el-link
                        v-if="checkPermission('admin.config.update')"
                        type="primary"
                        icon="el-icon-edit"
                        @click="update(scope.row)">
                        {{ $t('table.update') }}
                    </el-link>
                    <el-popconfirm
                        v-if="checkPermission('admin.config.delete')"
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
                label-width="120px">
                <el-form-item label="标题" prop="title">
                    <el-input v-model="dialogFormData.title" placeholder="请输入标题" />
                </el-form-item>
                <el-form-item label="配置键" prop="key">
                    <el-input v-model="dialogFormData.key" placeholder="请输入配置键" />
                </el-form-item>
                <el-form-item label="配置值" prop="value">
                    <el-input v-model="dialogFormData.value" placeholder="请输入配置值" />
                </el-form-item>
                <el-form-item label="配置简介" prop="brief">
                    <el-input
                        v-model="dialogFormData.brief"
                        type="textarea"
                        :autosize="{ minRows: 2, maxRows: 4}"
                        placeholder="请输入配置简介" />
                </el-form-item>
                <el-form-item label="表单操作类型" prop="input">
                    <el-select
                        v-model="dialogFormData.input"
                        placeholder="表单操作类型"
                        clearable
                        style="width: 200px">
                        <el-option
                            v-for="item in list_input"
                            :key="item.key"
                            :value="item.key"
                            :label="item.value" />
                    </el-select>
                </el-form-item>
                <el-form-item label="默认可选值" prop="values">
                    <el-input
                        v-model="dialogFormData.values"
                        type="textarea"
                        :autosize="{ minRows: 2, maxRows: 4}"
                        placeholder="请输入默认可选值，json类型" />
                </el-form-item>
                <el-form-item label="配置类型" prop="type">
                    <el-radio-group v-model="dialogFormData.type">
                        <el-radio
                            v-for="item in list_type"
                            :key="item.key"
                            :value="item.value"
                            :label="item.key">
                            {{ item.value }}
                        </el-radio>
                    </el-radio-group>
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

    </div>
</template>

<script>
    import {getList, add, update, del} from '@/api/config';
    import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
    import checkPermission from '@/utils/permission';
    import waves from '@/directive/waves'; // waves directive

    const defaultData = {
        id: undefined,
        title: '',
        key: '',
        value: '',
        brief: '',
        input: '',
        values: undefined,
        type: 1,
        type_name: '',
        status: 1,
        status_name: '',
        created_at: '',
    };

    export default {
        name: 'ConfigList',
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
                    title: undefined
                },
                list_input: [
                    {key: 'text', value: '文本'},
                    {key: 'select', value: '下拉框'},
                    {key: 'radio', value: '单选框'},
                    {key: 'checkbox', value: '复选框'},
                ],
                list_type: [
                    {key: 1, value: '系统配置'},
                ],
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
                        {required: true, message: '请输入标题'},
                        {min: 2, max: 20, message: '标题长度在 2 到 20 个字符'}
                    ],
                    key: [
                        {required: true, message: '请输入配置键'},
                        {min: 2, max: 20, message: '配置键长度在 2 到 30 个字符'}
                    ],
                    value: [
                        {max: 120, message: '配置值长度最多 120 个字符'}
                    ],
                    input: [
                        {required: true, message: '请选择表单操作类型'}
                    ]
                }
            }
        },
        created() {
            this.getList();
        },
        methods: {
            checkPermission,
            getList() {
                this.loading = true;
                getList(this.params).then(response => {
                    this.data = response.data.map(data => {
                        if(data.values && typeof data.values === 'object'){
                            data.values = JSON.stringify(data.values);
                        }
                        return data;
                    });
                    this.total = response.total;
                    this.loading = false;
                });
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
                this.dialogFormTitle = this.$t('route.config.add');
                this.dialogFormVisible = true;
                this.dialogFormData = Object.assign({}, defaultData);
                this.$nextTick(() => {
                    this.$refs.dialogForm.clearValidate()
                });
            },
            update(data) {
                this.isUpdate = true;
                this.dialogFormTitle = this.$t('route.config.update');
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
                if(checkPermission('admin.config.update')){
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
                        let formData = Object.assign({}, this.dialogFormData);
                        if(formData.values){
                            try {
                                formData.values = JSON.parse(formData.values);
                            }catch (e) {
                                this.dialogFormLoading = false;
                                this.$notify({
                                    title: '错误',
                                    message: '默认可选值格式错误！',
                                    type: 'error',
                                    duration: 2000
                                });
                                console.log('默认可选值格式错误，必须为json格式！');
                                return false;
                            }
                        }
                        let result;
                        if(this.isUpdate){
                            result = update(formData);
                        }else{
                            result = add(formData);
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
                            if(response.data.values && typeof response.data.values === 'object'){
                                response.data.values = JSON.stringify(response.data.values);
                            }
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
            }
        }
    }
</script>
