<template>
    <div class="app-container">
        <el-table v-loading="loading" :data="data">
            <el-table-column prop="title" label="APP" width="80" align="center" />
            <el-table-column prop="version" label="版本号" width="80" align="center" />
        </el-table>
    </div>
</template>

<script>

    export default {
        name: 'Dashboard',
        data() {
            return {
                data: [
                    {title: 'php', version: '7.5'},
                    {title: 'mysql', version: '8.0'},
                    {title: 'vue', version: '2.6.10'},
                    {title: 'laravel', version: '6.10.1'},
                ],
                loading: true,
            }
        },
        created() {
            this.hideLoading()
        },
        methods: {
            hideLoading() {
                this.loading = false;
            }
        }
    }
</script>
