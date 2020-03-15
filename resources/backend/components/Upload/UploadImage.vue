<template>
    <el-upload
        ref="upload"
        :name="name"
        action="/upload"
        accept="image/*"
        list-type="picture-card"
        :show-file-list="false"
        :headers="headers"
        :before-upload="beforeUpload"
        :on-progress="onProgress"
        :on-success="onSuccess">
        <el-progress v-if="loading" type="circle" :percentage="percentage" :width="96" />
        <img v-else-if="image" :src="image">
        <i v-else class="el-icon-plus" />
    </el-upload>
</template>

<script>
    import {getToken, xsrf_token} from '@/utils/auth';

    export default {
        name: "UploadImage",
        props: {
            value: {
                type: String,
                default: ''
            },
            name: {
                type: String,
                default: 'image'
            }
        },
        data() {
            return {
                image: this.value,
                headers: {
                    'X-Token' : getToken(),
                    'X-XSRF-TOKEN' : xsrf_token(),
                },
                loading: false,
                percentage: 0,
            };
        },
        watch: {
            value: function(newValue, oldValue) {
                this.image = newValue;
            }
        },
        methods: {
            beforeUpload(file) {
                // console.log('beforeUpload：', file);
                // const isJPG = file.type === 'image/jpeg';
                // const isLt2M = file.size / 1024 / 1024 < 2;
                // if (!isJPG) {
                //     this.$message.error('上传头像图片只能是 JPG 格式!');
                // }
                // if (!isLt2M) {
                //     this.$message.error('上传头像图片大小不能超过 2MB!');
                // }
                // return isJPG && isLt2M;
                return true;
            },
            onProgress(event, file) {
                // console.log('onProgress：', event, file);

                this.loading = true;
                this.percentage = parseFloat((event.percent || 0).toFixed(2));
            },
            onSuccess(response, file) {
                this.image = URL.createObjectURL(file.raw);

                if(1 !== response.code){
                    this.percentage = 0;

                    this.$message({
                        title: '失败',
                        message: response.message,
                        type: 'error',
                        duration: 2000
                    });
                }else {
                    // this.$message({
                    //     title: '成功',
                    //     message: response.message,
                    //     type: 'success',
                    //     duration: 2000
                    // });

                    this.loading = false;
                    this.percentage = 0;

                    this.$emit('upload', response);
                }
            }
        }
    }
</script>

<style scoped>
    .el-upload--picture-card {
        width: 148px;
        height: 148px;
        line-height: 148px;
    }

    .el-upload img {
        width: 100%;
        height: 100%;
        border-radius: 6px;
        display: block;
        object-fit: scale-down;
    }

    .el-progress {
        margin-top: 16px;
    }

</style>
