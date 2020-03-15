<template>
    <el-form label-position="right" inline class="table-expand">
        <el-form-item label="账号ID" prop="id">
            <span>{{ data.id }}</span>
        </el-form-item>
        <el-form-item label="账号昵称" prop="title">
            <span>{{ data.nickname }}</span>
        </el-form-item>
        <el-form-item label="账号" prop="title">
            <span>{{ data.username }}</span>
        </el-form-item>
        <el-form-item label="手机号" prop="title">
            <span>{{ data.mobile }}</span>
        </el-form-item>
        <el-form-item label="邮箱号" prop="title">
            <span>{{ data.email }}</span>
        </el-form-item>
    </el-form>
</template>

<script>
    export default {
        name: "UserInfo",
        props: {
            data: {
                type: Object,
                default: {
                    id: undefined,
                    nickname: '',
                    username: '',
                    mobile: '',
                    email: ''
                }
            },
        },
    }
</script>

<style scoped>

</style>
