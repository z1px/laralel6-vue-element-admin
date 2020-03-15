<template>
    <div class="uploader">
        <!-- 多图片上传 -->
        <el-upload
            ref="upload"
            :name="name"
            action="/upload"
            accept="image/*"
            list-type="picture-card"
            :headers="headers"
            :on-preview="onPreview"
            :on-remove="onRemove"
            :on-success="onSuccess"
            :file-list="value"
            :multiple="multiple">
            <i class="el-icon-plus" />
        </el-upload>
        <!-- 图片预览弹窗 -->
        <el-dialog :visible.sync="dialogVisible">
            <img width="100%" :src="dialogImageUrl">
        </el-dialog>
    </div>
</template>

<script>
    import {getToken, xsrf_token} from '@/utils/auth';

    export default {
        name: "UploadImages",
        props: {
            value: {
                type: Array,
                default: []
            },
            name: {
                type: String,
                default: 'images'
            },
            multiple: {
                type: Boolean,
                default: true
            }
        },
        data() {
            return {
                headers: {
                    'X-Token' : getToken(),
                    'X-XSRF-TOKEN' : xsrf_token(),
                },
                dialogVisible: false, // 展示弹窗开关
                dialogImageUrl: '', // 弹窗内图片链接
            };
        },
        methods: {
            onPreview (file) {
                // 点击进行图片展示
                this.dialogImageUrl = file.url;
                this.dialogVisible = true;
            },
            onRemove (file, fileList) {
                // 删除图片后更新图片文件列表并通知父级变化
                this.$emit('upload', fileList.map(data => {
                    if(!data.id && data.response){
                        data.id = data.response.data.id;
                    }
                    return data;
                }));
            },
            onSuccess (response, file, fileList) {
                this.$emit('upload', fileList.map(data => {
                    if(!data.id && data.response){
                        data.id = data.response.data.id;
                    }
                    return data;
                }));
            }
        }
    }
</script>

<style>
    .el-upload-list--picture-card .el-upload-list__item-thumbnail {
         object-fit: scale-down;
     }
</style>
