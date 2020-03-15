<template>
    <div>
        <!-- bidirectional data binding（双向数据绑定） -->
        <quill-editor
            ref="quillEditor"
            v-model="content"
            :options="editorOption"
            @ready="onEditorReady($event)"
            @blur="onEditorBlur($event)"
            @focus="onEditorFocus($event)"
            @change="onEditorChange($event)" />

        <!-- 图片上传组件辅助-->
        <el-upload
            ref="upload"
            name="image"
            class="quill-editor-image"
            action="/upload"
            accept="image/*"
            multiple
            list-type="picture"
            :show-file-list="false"
            :headers="headers"
            :before-upload="beforeUpload"
            :on-success="uoloadSuccess"
            :on-error="uoloadError">
        </el-upload>
    </div>
</template>

<script>
    // require styles
    import 'quill/dist/quill.core.css';
    import 'quill/dist/quill.snow.css';
    import 'quill/dist/quill.bubble.css';

    import {quillEditor} from 'vue-quill-editor';
    import * as Quill from 'quill' //引入编辑器
    import Video from './plugins/video.js'; // 插入h5 video视频
    Quill.register(Video, true);  // 注册video

    import {getToken, xsrf_token} from '@/utils/auth';

    export default {
        name: "QuillEditor",
        components: {quillEditor},
        props: {
            value: { // 编辑器的内容
                type: String,
                default: ''
            }
        },
        data () {
            return {
                content: this.value,
                quillUpdateImg: false, // 根据图片上传状态来确定是否显示loading动画，刚开始是false,不显示
                editorOption: {
                    modules: {
                        toolbar: {
                            //工具菜单栏配置
                            container: [
                                ["bold", "italic", "underline", "strike"], // 加粗 斜体 下划线 删除线
                                ["blockquote", "code-block"], // 引用  代码块

                                [{ header: 1 }, { header: 2 }], // 1、2 级标题
                                [{ list: "ordered" }, { list: "bullet" }], // 有序、无序列表
                                [{ script: "sub" }, { script: "super" }], // 上标/下标
                                [{ indent: "-1" }, { indent: "+1" }], // 缩进
                                [{'direction': 'rtl'}], // 文本方向

                                [{ size: ["small", false, "large", "huge"] }], // 字体大小
                                [{ header: [1, 2, 3, 4, 5, 6, false] }], // 标题

                                [{ color: [] }, { background: [] }], // 字体颜色、字体背景颜色
                                [{ font: [] }], // 字体种类
                                [{ align: [] }], // 对齐方式

                                ["clean"], // 清除文本格式
                                ["link", "image", "video"] // 链接、图片、视频
                            ],
                            handlers: {
                                image: function(value) {
                                    if (value) {
                                        // 触发input框选择图片文件
                                        document.querySelector(".quill-editor-image input").click();
                                    } else {
                                        this.quill.format("image", false);
                                    }
                                },
                                // link: function (value) {
                                //     if (value) {
                                //         let href = prompt('请输入url');
                                //         this.quill.format("link", href);
                                //     } else {
                                //         this.quill.format("link", false);
                                //     }
                                // },
                            }
                        }
                    },
                    placeholder: '请输入内容', //提示
                    readyOnly: false, //是否只读
                    theme: 'snow', //主题 snow/bubble
                    syntax: true, //语法检测
                },
                headers: {
                    'X-Token' : getToken(),
                    'X-XSRF-TOKEN' : xsrf_token(),
                }
            }
        },
        watch: {
            value: function(newValue, oldValue) {
                this.content = newValue;
                // console.log('watch value：', newValue, oldValue);
            }
        },
        computed: {
            quill() {
                return this.$refs.quillEditor.quill;
            }
        },
        methods: {
            // 开始
            onEditorReady(quill) {
                // console.log('onEditorReady：', quill);
                this.quill.enable(false);
            },
            // 失去焦点
            onEditorBlur(quill) {
                // console.log('onEditorBlur：', quill);
            },
            // 获得焦点
            onEditorFocus(quill) {
                // console.log('onEditorFocus：', quill);
                // 完美解决富文本自动获取焦点问题
                this.$nextTick(function() {
                    this.quill.enable(true);
                    this.quill.focus();
                });
            },
            // 值发生变化
            onEditorChange({quill, html, text}) {
                // console.log('onEditorChange：', quill, html, text);
                this.content = html;

                this.$emit('change', this.content);
            },
            // 富文本图片上传前
            beforeUpload(file) {
                // 显示loading动画
                this.quillUpdateImg = true;
            },
            // 富文本图片上传成功
            uoloadSuccess(response, file) {
                // response为图片服务器返回的数据
                // 如果上传成功
                if (1 === response.code) {
                    // 获取光标所在位置
                    let length = this.quill.getSelection().index;
                    // 插入图片  response.url为服务器返回的图片地址
                    this.quill.insertEmbed(length, "image", response.data.image);
                    // 调整光标到最后
                    this.quill.setSelection(length + 1);
                } else {
                    this.$message({
                        title: '失败',
                        message: response.message,
                        type: 'error',
                        duration: 2000
                    });
                }
                // loading动画消失
                this.quillUpdateImg = false;
            },
            // 富文本图片上传失败
            uoloadError() {
                // loading动画消失
                this.quillUpdateImg = false;
                this.$message({
                    title: '失败',
                    message: '图片插入失败',
                    type: 'error',
                    duration: 2000
                });
            }
        }
    }
</script>

<style>
    .quill-editor-image {
        display: none;
    }

    .ql-editor {
        min-height: 200px;
    }
    .ql-toolbar.ql-snow .ql-formats {
        margin-right: 5px;
    }

    .ql-snow .ql-tooltip[data-mode=link]::before {
        content: "请输入链接地址:";
    }
    .ql-snow .ql-tooltip.ql-editing a.ql-action::after {
        border-right: 0;
        content: '保存';
        padding-right: 0;
    }

    .ql-snow .ql-tooltip[data-mode=video]::before {
        content: "请输入视频地址:";
    }

    .ql-snow .ql-picker {
        line-height: 24px;
    }

    .ql-snow .ql-picker.ql-size {
        width: 60px;
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item::before {
        content: '14px';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value=small]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value=small]::before {
        content: '10px';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value=large]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value=large]::before {
        content: '18px';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value=huge]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value=huge]::before {
        content: '32px';
    }

    .ql-snow .ql-picker.ql-header {
        width: 56px;
    }
    .ql-snow .ql-picker.ql-header .ql-picker-label::before,
    .ql-snow .ql-picker.ql-header .ql-picker-item::before {
        content: '文本';
    }
    .ql-snow .ql-picker.ql-header .ql-picker-label[data-value="1"]::before,
    .ql-snow .ql-picker.ql-header .ql-picker-item[data-value="1"]::before {
        content: '标题1';
    }
    .ql-snow .ql-picker.ql-header .ql-picker-label[data-value="2"]::before,
    .ql-snow .ql-picker.ql-header .ql-picker-item[data-value="2"]::before {
        content: '标题2';
    }
    .ql-snow .ql-picker.ql-header .ql-picker-label[data-value="3"]::before,
    .ql-snow .ql-picker.ql-header .ql-picker-item[data-value="3"]::before {
        content: '标题3';
    }
    .ql-snow .ql-picker.ql-header .ql-picker-label[data-value="4"]::before,
    .ql-snow .ql-picker.ql-header .ql-picker-item[data-value="4"]::before {
        content: '标题4';
    }
    .ql-snow .ql-picker.ql-header .ql-picker-label[data-value="5"]::before,
    .ql-snow .ql-picker.ql-header .ql-picker-item[data-value="5"]::before {
        content: '标题5';
    }
    .ql-snow .ql-picker.ql-header .ql-picker-label[data-value="6"]::before,
    .ql-snow .ql-picker.ql-header .ql-picker-item[data-value="6"]::before {
        content: '标题6';
    }

    .ql-snow .ql-picker.ql-font {
        width: 85px;
    }
    .ql-snow .ql-picker.ql-font .ql-picker-label::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item::before {
        content: '标准字体';
    }
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value=serif]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value=serif]::before {
        content: '衬线字体';
    }
    .ql-snow .ql-picker.ql-font .ql-picker-label[data-value=monospace]::before,
    .ql-snow .ql-picker.ql-font .ql-picker-item[data-value=monospace]::before {
        content: '等宽字体';
    }
</style>
