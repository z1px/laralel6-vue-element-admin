<template>
    <div class="uploader">
        <my-upload
            ref="upload"
            :name="name"
            url="/upload"
            img-format="png"
            v-model="show"
            :headers="headers"
            :width="300"
            :height="300"
            @crop-success="cropSuccess"
            @crop-upload-success="cropUploadSuccess"
            @crop-upload-fail="cropUploadFail" />
        <div class="upload" @click="toggleShow">
            <img v-if="image" :src="image" >
            <i v-else class="el-icon-plus uploader-icon"></i>
        </div>
    </div>
</template>

<script>
    import {getToken, xsrf_token} from '@/utils/auth';
    import MyUpload from 'vue-image-crop-upload';

    export default {
        name: "UploadAvatar",
        components: {MyUpload},
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
                show: false,
                headers: {
                    'X-Token' : getToken(),
                    'X-XSRF-TOKEN' : xsrf_token(),
                }
            }
        },
        watch: {
            value: function(newValue, oldValue) {
                this.image = newValue;
            }
        },
        methods: {
            toggleShow() {
                this.show = !this.show;
            },
            cropSuccess(image, field){
                // console.log('cropSuccess：', image, field);
                this.image = image;
            },
            /**
             * upload success
             *
             * [param] jsonData  server api return data, already json encode
             * [param] field
             */
            cropUploadSuccess(response, field){
                if(1 !== response.code){
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

                    this.image = response.data.image;
                    this.$emit('upload', response);
                }
            },
            /**
             * upload fail
             *
             * [param] status    server api return error status, like 500
             * [param] field
             */
            cropUploadFail(status, field){
                // console.log('cropUploadFail：', status, field);
            },
        }
    }
</script>

<style scoped>
    .uploader .upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        display: inline-block;
        text-align: center;
        outline: none;
    }
    .uploader .upload:hover {
        border-color: #409EFF;
    }
    .uploader .upload img {
        width: 128px;
        height: 128px;
        display: block;
        cursor: pointer;
    }
    .uploader .upload .uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 128px;
        height: 128px;
        line-height: 128px;
        text-align: center;
    }
</style>
