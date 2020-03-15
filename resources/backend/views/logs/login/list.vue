<template>
    <div class="app-container">
        <div class="filter-container">
            <el-select
                v-model="params.admin_id"
                placeholder="账号/昵称/手机号/邮箱"
                filterable
                remote
                clearable
                :remote-method="remoteMethod"
                :loading="selectLoading"
                class="filter-item">
                <el-option
                    v-for="item in selectData"
                    :key="item.id"
                    :value="item.id"
                    :label="item.username">
                    <span style="float: left">{{ item.username }}</span>
                    <span style="float: right; color: #8492a6; font-size: 13px">{{ item.nickname }}</span>
                </el-option>
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

        <el-table v-loading="loading" :data="data" fit highlight-current-row style="width: 100%">
            <el-table-column prop="id" label="ID" width="80" align="center" />
            <el-table-column prop="nickname" label="昵称" width="120" />
            <el-table-column prop="username" label="账号" width="120" />
            <el-table-column prop="mobile" label="手机" width="100" />
            <el-table-column prop="email" label="邮箱" width="100" />
            <el-table-column prop="ip" label="登录IP" width="100" />
            <el-table-column prop="area" label="IP区域" width="100" />
            <el-table-column prop="platform" label="客户端平台" width="150" />
            <el-table-column prop="model" label="设备型号" width="100" />
            <el-table-column prop="created_at" label="登录时间" min-width="155" align="left" />
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
    import {getList as getAdminsList} from '@/api/admins';
    import {getAdminsLoginList} from '@/api/logs';
    import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
    import waves from '@/directive/waves'; // waves directive

    export default {
        name: 'LoginList',
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
                    admin_id: undefined,
                    date_range: undefined
                },
                selectLoading: false,
                selectData: [],
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
            getList() {
                this.loading = true;
                getAdminsLoginList(this.params).then(response => {
                    this.data = response.data;
                    this.total = response.total;
                    this.loading = false;
                });
            },
            remoteMethod(value){
                if (value !== '') {
                    this.selectLoading = true;
                    getAdminsList({keyword: value}).then(response => {
                        this.selectData = response.data;
                        this.selectLoading = false;
                    });
                } else {
                    this.selectData = [];
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
            }
        }
    }
</script>

