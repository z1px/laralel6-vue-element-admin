<template>
    <div class="app-container">
        <div class="filter-container">
            <el-input
                v-model="params.table"
                placeholder="表名"
                class="filter-item"
                style="width: 200px;"
                clearable/>
            <el-select
                v-model="params.operate"
                placeholder="操作类型"
                multiple
                clearable
                class="filter-item"
                style="width: 200px">
                <el-option
                    v-for="item in list_operate"
                    :key="item.key"
                    :value="item.key"
                    :label="item.value" />
            </el-select>
            <el-select
                v-model="params.user_type"
                placeholder="用户类型"
                clearable
                class="filter-item"
                style="width: 200px">
                <el-option
                    v-for="item in list_user_type"
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
            :cell-class-name="tableCellClassName"
            border
            fit
            highlight-current-row
            style="width: 100%">
            <el-table-column prop="id" label="ID" width="80" align="center" />
            <el-table-column prop="table" label="表名" width="120" />
            <el-table-column prop="table_comment" label="表说明" width="180" />
            <el-table-column prop="operate_name" label="操作类型" width="120" />
            <el-table-column v-if="checkPermission('admin.logs.tables_operated.info')" label="变动详情" width="80">
                <template slot-scope="scope">
                    <el-popover trigger="click" placement="right">
                        <el-table
                            :data="tableData(scope.row)"
                            :row-class-name="popoverTableRowClassName"
                            :cell-class-name="popoverTableCellClassName">
                            <el-table-column prop="field" label="字段" width="120" />
                            <el-table-column prop="before" label="旧值" width="180" />
                            <el-table-column prop="after" label="新值" width="180" />
                        </el-table>
                        <div slot="reference" class="name-wrapper">
                            <el-link type="success">详情</el-link>
                        </div>
                    </el-popover>
                </template>
            </el-table-column>
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
            <el-table-column prop="url" label="请求地址" width="320" />
            <el-table-column prop="ip" label="请求IP" width="100" />
            <el-table-column prop="area" label="IP区域" width="100" />
            <el-table-column prop="platform" label="客户端平台" width="100" />
            <el-table-column prop="created_at" label="请求时间" min-width="155" align="left" />
        </el-table>

        <pagination
            v-show="total>0"
            :total="total"
            :page.sync="params.page"
            :limit.sync="params.limit"
            @pagination="getList"/>

    </div>
</template>

<script>
    import {getListTablesOperated} from '@/api/logs';
    import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
    import checkPermission from '@/utils/permission';
    import waves from '@/directive/waves'; // waves directive
    import UserInfo from '@/components/Common/UserInfo';

    export default {
        name: 'TablesOperatedList',
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
                    table: undefined,
                    operate: undefined,
                    user_type: undefined,
                    date_range: undefined
                },
                list_operate: [
                    {key: 'create', value: '新增'},
                    {key: 'delete', value: '删除'},
                    {key: 'update', value: '修改'},
                    {key: 'select', value: '查找'},
                    {key: 'restore', value: '恢复'}
                ],
                list_user_type: [
                    {key: 1, value: '管理员'},
                    {key: 2, value: '平台用户'},
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
                getListTablesOperated(this.params).then(response => {
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
            tableData(data){
                let result = [];
                if(!data.before_attr || Array.isArray(data.before_attr)){
                    data.before_attr = {};
                }
                if(!data.after_attr || Array.isArray(data.after_attr)){
                    data.after_attr = {};
                }
                if(!data.change_attr || Array.isArray(data.change_attr)){
                    data.change_attr = {};
                }
                const list_attr = Object.assign({}, data.before_attr, data.after_attr, data.change_attr);
                for(let key in list_attr){
                    if(['password'].includes(key)){
                        delete list_attr[key];
                        continue;
                    }
                    result.push({
                        field: key,
                        operate: data.operate,
                        before: data.before_attr.hasOwnProperty(key) ? data.before_attr[key] : '--',
                        after: data.after_attr.hasOwnProperty(key) ? data.after_attr[key] : '--',
                        change: data.change_attr.hasOwnProperty(key) ? data.change_attr[key] : '--',
                    });
                }
                return result;
            },
            tableRowClassName({row, rowIndex}) {
                let class_name = '';
                if (row.operate === 'create') {
                    class_name = 'success-row success';
                } else if (row.operate === 'delete') {
                    class_name = 'danger-row danger';
                } else if (row.operate === 'update') {
                    class_name = 'warning-row warning';
                } else if (row.operate === 'select') {
                    class_name = 'info-row info';
                } else if (row.operate === 'restore') {
                    class_name = 'primary-row primary';
                } else {
                    class_name = '';
                }
                return class_name;
            },
            tableCellClassName({row, column, rowIndex, columnIndex}) {
                let class_name = '';
                if(columnIndex === 3){
                    if (row.operate === 'create') {
                        class_name = 'success';
                    } else if (row.operate === 'delete') {
                        class_name = 'danger';
                    } else if (row.operate === 'update') {
                        class_name = 'warning';
                    } else if (row.operate === 'select') {
                        class_name = 'info';
                    } else if (row.operate === 'restore') {
                        class_name = 'primary';
                    } else {
                        class_name = '';
                    }
                }
                return class_name;
            },
            popoverTableRowClassName({row, rowIndex}) {
                let class_name = '';
                if(!['create', 'delete'].includes(row.operate) && row.before !== row.after){
                    class_name = 'danger';
                }
                return class_name;
            },
            popoverTableCellClassName({row, column, rowIndex, columnIndex}) {
                let class_name = '';
                if(row.operate === 'create' && columnIndex === 2){
                    class_name = 'success';
                }else if(row.operate === 'delete' && columnIndex === 2){
                    class_name = 'danger';
                }
                return class_name;
            }
        }
    }
</script>
