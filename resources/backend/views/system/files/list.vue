<template>
    <div class="app-container">
        <div class="filter-container">
            <el-select
                v-model="params.file_type"
                placeholder="文件类型"
                multiple
                clearable
                class="filter-item"
                style="width: 200px">
                <el-option
                    v-for="item in list_file_type"
                    :key="item.key"
                    :value="item.key"
                    :label="item.value" />
            </el-select>
            <el-select
                v-model="params.visibility"
                placeholder="文件可见性"
                clearable
                class="filter-item"
                style="width: 200px">
                <el-option
                    v-for="item in list_visibility"
                    :key="item.key"
                    :value="item.key"
                    :label="item.value" />
            </el-select>
            <el-date-picker
                v-model="params.date_range"
                type="daterange"
                class="filter-item"
                align="right"
                unlink-panels
                range-separator="至"
                start-placeholder="开始日期"
                end-placeholder="结束日期"
                value-format="yyyy-MM-dd"
                :picker-options="pickerOptions">
            </el-date-picker>
            <el-button
                type="primary"
                class="filter-item"
                icon="el-icon-search"
                @click="search"
                v-waves>
                {{ $t('table.search') }}
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

        <el-table
            v-loading="loading"
            :data="data"
            :row-class-name="tableRowClassName"
            fit
            highlight-current-row
            style="width: 100%">
            <el-table-column type="expand">
                <template slot-scope="scope">
                    <el-form label-position="right" inline class="table-expand" label-width="100px">
                        <el-form-item label="ID">
                            <span>{{ scope.row.id }}</span>
                        </el-form-item>
                        <el-form-item label="文件预览">
                            <img v-if="scope.row.file_type === 1 && scope.row.base64" :src="scope.row.base64" @click="dialogImage(scope.row)" />
                            <i v-else-if="scope.row.file_type === 1 && !scope.row.base64" class="el-icon-picture" />
                            <i v-else-if="scope.row.file_type === 2" class="el-icon-headset" />
                            <i v-else-if="scope.row.file_type === 3" class="el-icon-video-camera" />
                            <i v-else-if="scope.row.file_type === 4" class="el-icon-document" />
                            <i v-else-if="scope.row.file_type === 5" class="el-icon-menu" />
                            <i v-else-if="scope.row.file_type === 6" class="el-icon-folder" />
                            <i v-else class="el-icon-question" />
                        </el-form-item>
                        <el-form-item label="原始文件名">
                            <span>{{ scope.row.original_name }}</span>
                        </el-form-item>
                        <el-form-item label="文件可见性">
                            <el-switch
                                v-if="(checkPermission('admin.files.visible') && scope.row.visibility === 'private') || (checkPermission('admin.files.invisible') && scope.row.visibility === 'public')"
                                v-model="scope.row.visibility"
                                active-text="可见"
                                inactive-text="不可见"
                                active-value="public"
                                inactive-value="private"
                                :disabled="scope.row.deleted_at ? true : false"
                                @change="setVisibility(scope.row)">
                            </el-switch>
                            <el-tag v-else-if="scope.row.visibility === 'public'" type="success">可见</el-tag>
                            <el-tag v-else type="error">不可见</el-tag>
                        </el-form-item>
                        <el-form-item label="文件大小">
                            <span>{{ scope.row.size_format }}</span>
                        </el-form-item>
                        <el-form-item label="文件类型">
                            <span>{{ scope.row.file_type_name }}</span>
                        </el-form-item>
                        <el-form-item label="HASH">
                            <span>{{ scope.row.sha1 }}</span>
                        </el-form-item>
                        <el-form-item label="MD5">
                            <span>{{ scope.row.md5 }}</span>
                        </el-form-item>
                        <el-form-item label="用户类型">
                            <span>{{ scope.row.user_type_name }}</span>
                        </el-form-item>
                        <el-form-item label="账号">
                            <el-popover v-if="scope.row.user" trigger="hover" placement="right" width="200">
                                <user-info :data="scope.row.user" />
                                <div slot="reference" class="name-wrapper">
                                    <el-tag>{{ scope.row.user.username }}</el-tag>
                                </div>
                            </el-popover>
                        </el-form-item>
                        <el-form-item label="管理员">
                            <el-popover v-if="scope.row.admin" trigger="hover" placement="right" width="200">
                                <user-info :data="scope.row.admin" />
                                <div slot="reference" class="name-wrapper">
                                    <el-tag>{{ scope.row.admin.username }}</el-tag>
                                </div>
                            </el-popover>
                        </el-form-item>
                        <el-form-item label="上传时间">
                            <span>{{ scope.row.created_at }}</span>
                        </el-form-item>
                    </el-form>
                </template>
            </el-table-column>
            <el-table-column prop="id" label="ID" width="80" align="center" />
            <el-table-column label="文件预览" width="80" align="center">
                <template slot-scope="scope">
                    <img v-if="scope.row.file_type === 1 && scope.row.base64" :src="scope.row.base64" @click="dialogImage(scope.row)" />
                    <i v-else-if="scope.row.file_type === 1 && !scope.row.base64" class="el-icon-picture" />
                    <i v-else-if="scope.row.file_type === 2" class="el-icon-headset" />
                    <i v-else-if="scope.row.file_type === 3" class="el-icon-video-camera" />
                    <i v-else-if="scope.row.file_type === 4" class="el-icon-document" />
                    <i v-else-if="scope.row.file_type === 5" class="el-icon-menu" />
                    <i v-else-if="scope.row.file_type === 6" class="el-icon-folder" />
                    <i v-else class="el-icon-question" />
                </template>
            </el-table-column>
            <el-table-column label="文件可见性" width="180">
                <template slot-scope="scope">
                    <el-switch
                        v-if="(checkPermission('admin.files.visible') && scope.row.visibility === 'private') || (checkPermission('admin.files.invisible') && scope.row.visibility === 'public')"
                        v-model="scope.row.visibility"
                        active-text="可见"
                        inactive-text="不可见"
                        active-value="public"
                        inactive-value="private"
                        :disabled="scope.row.deleted_at ? true : false"
                        @change="setVisibility(scope.row)">
                    </el-switch>
                    <el-tag v-else-if="scope.row.visibility === 'public'" type="success">可见</el-tag>
                    <el-tag v-else type="error">不可见</el-tag>
                </template>
            </el-table-column>
            <el-table-column prop="file_type_name" label="文件类型" width="120" />
            <el-table-column prop="user_type_name" label="用户类型" width="120" />
            <el-table-column label="账号" width="120">
                <template slot-scope="scope">
                    <el-popover v-if="scope.row.user" trigger="hover" placement="right" width="200">
                        <user-info :data="scope.row.user" />
                        <div slot="reference" class="name-wrapper">
                            <el-tag>{{ scope.row.user.username }}</el-tag>
                        </div>
                    </el-popover>
                    <span v-else></span>
                </template>
            </el-table-column>
            <el-table-column prop="created_at" label="上传时间" width="155" align="center" />
            <el-table-column label="操作" min-width="120" align="left" fixed="right">
                <template slot-scope="scope">
                    <span v-if="scope.row.deleted_at">已删除</span>
                    <el-popconfirm
                        v-else-if="checkPermission('admin.files.delete')"
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

        <el-dialog :visible.sync="dialogImageVisible" width="480px">
            <img width="100%" :src="dialogImageUrl">
        </el-dialog>

    </div>
</template>

<script>
    import {getList, setVisible, setInvisible, del} from '@/api/files';
    import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
    import checkPermission from '@/utils/permission';
    import waves from '@/directive/waves'; // waves directive
    import UserInfo from '@/components/Common/UserInfo';

    export default {
        name: 'FilesList',
        components: {Pagination, UserInfo},
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
                    visibility: undefined,
                    file_type: undefined,
                    date_range: undefined
                },
                list_file_type: [
                    {key: 1, value: '图片'},
                    {key: 2, value: '音频'},
                    {key: 3, value: '视频'},
                    {key: 4, value: '文本'},
                    {key: 5, value: '应用'},
                    {key: 6, value: '归档'},
                ],
                list_visibility: [
                    {key: 'public', value: '可见'},
                    {key: 'private', value: '不可见'},
                ],
                pickerOptions: {
                    shortcuts: [{
                        text: '最近一周',
                        onClick(picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                            picker.$emit('pick', [start, end]);
                        }
                    }, {
                        text: '最近一个月',
                        onClick(picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                            picker.$emit('pick', [start, end]);
                        }
                    }, {
                        text: '最近三个月',
                        onClick(picker) {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                            picker.$emit('pick', [start, end]);
                        }
                    }]
                },
                dialogImageVisible: false, // 展示弹窗开关
                dialogImageUrl: '',
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
                    this.data = response.data;
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
            del(data) {
                del(data).then(response => {
                    this.$set(this.data, this.data.indexOf(data), response.data);
                    this.$notify({
                        title: '成功',
                        message: response.message,
                        type: 'success',
                        duration: 2000
                    });
                }).catch(error => {
                    console.log(error);
                });
            },
            setVisibility(data){
                if(data.visibility === 'public'){
                    if(checkPermission('admin.files.visible')){
                        setVisible(data).then(response => {
                            this.$notify({
                                title: '成功',
                                message: response.message,
                                type: 'success',
                                duration: 2000
                            });
                        }).catch(error => {
                            data.visibility = 'private';
                            console.log(error);
                        });
                    }else{
                        data.visibility = 'private';
                        this.$message({
                            title: '错误',
                            message: '无权限',
                            type: 'error',
                            duration: 2000
                        });
                    }
                }else{
                    if(checkPermission('admin.files.invisible')){
                        setInvisible(data).then(response => {
                            this.$notify({
                                title: '成功',
                                message: response.message,
                                type: 'success',
                                duration: 2000
                            });
                        }).catch(error => {
                            data.visibility = 'public';
                            console.log(error);
                        });
                    }else{
                        data.visibility = 'public';
                        this.$message({
                            title: '错误',
                            message: '无权限',
                            type: 'error',
                            duration: 2000
                        });
                    }
                }
                return true;
            },
            tableRowClassName({row, rowIndex}) {
                let class_name = '';
                if(row.deleted_at){
                    class_name = 'danger delete';
                }
                return class_name;
            },
            dialogImage(data) {
                this.dialogImageUrl = data.base64;
                this.dialogImageVisible = true;
            },
        }
    }
</script>
