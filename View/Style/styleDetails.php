<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <el-row>
                <el-col :sm="16" :md="8">
                    <div class="grid-content ">
                        <el-form ref="form" :model="form" label-width="120px">

                            <h3>基础信息</h3>
                            <el-form-item label="标题">
                                <el-input v-model="form.title"></el-input>
                            </el-form-item>

                            <el-form-item label="排序">
                                <el-input type="number" v-model="form.listorder"></el-input>
                            </el-form-item>

                            <el-form-item label="功能开关">
                                <el-radio v-model="form.watermarkenable" label="1">开启</el-radio>
                                <el-radio v-model="form.watermarkenable" label="0">关闭</el-radio>
                            </el-form-item>

                            <h3>水印配置</h3>

                            <el-form-item label="水印尺寸" style="display: none;">
                                <el-input v-model="form.watermarkminwidth" style="width:100px" placeholder="宽度"></el-input>
                                x
                                <el-input v-model="form.watermarkminheight" style="width:100px" placeholder="高度"></el-input>
                                px
                            </el-form-item>

                            <el-form-item label="水印图片">
                                <p>
                                <p><img :src="form.watermarkimg" alt="" style="height: 80px;background: #f1f0f09e;"></p>
                                <p>
                                    <el-button type="primary" @click="gotoUploadFile">上传</el-button>
                                    <el-button type="primary" @click="previewWatermark">查看原图</el-button>
                                </p>
                                </p>
                            </el-form-item>

                            <el-form-item label="水印透明度">
                                <el-input v-model="form.watermarkpct"></el-input>
                            </el-form-item>

                            <el-form-item label="水印质量" style="display: none;">
                                <el-input v-model="form.watermarkquality"></el-input>
                            </el-form-item>

                            <el-form-item label="水印位置">
                                <table class="pos_container">
                                    <tbody >
                                    <tr>
                                        <td class="item" :class="{'current': form.watermarkpos == 1}" @click="selectPos(1)">左上</td>
                                        <td class="item" :class="{'current': form.watermarkpos == 2}" @click="selectPos(2)">中上</td>
                                        <td class="item" :class="{'current': form.watermarkpos == 3}" @click="selectPos(3)">右上</td>
                                    </tr>
                                    <tr>
                                        <td class="item" :class="{'current': form.watermarkpos == 4}" @click="selectPos(4)">左中</td>
                                        <td class="item" :class="{'current': form.watermarkpos == 5}" @click="selectPos(5)">中心</td>
                                        <td class="item" :class="{'current': form.watermarkpos == 6}" @click="selectPos(6)">右中</td>
                                    </tr>
                                    <tr>
                                        <td class="item" :class="{'current': form.watermarkpos == 7}" @click="selectPos(7)">左下</td>
                                        <td class="item" :class="{'current': form.watermarkpos == 8}" @click="selectPos(8)">中下</td>
                                        <td class="item" :class="{'current': form.watermarkpos == 9}" @click="selectPos(9)">右下</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </el-form-item>

                            <h3>原图配置</h3>

                            <el-form-item label="原图长度控制">
                                <el-input type="number" v-model="form.pictures_length"></el-input>
                                <br>
                                <span>为0不进行处理</span>
                            </el-form-item>

                            <el-form-item label="原图宽度控制">
                                <el-input type="number" v-model="form.pictures_width"></el-input>
                                <br>
                                <span>为0不进行处理</span>
                            </el-form-item>

                            <el-form-item label="原图质量控制">
                                <el-radio v-model="form.quality" label="0">不压缩</el-radio>
                                <el-radio v-model="form.quality" label="1">相对质量</el-radio>
                                <el-radio v-model="form.quality" label="2">绝对质量</el-radio>
                            </el-form-item>

                            <el-form-item label="原图质量百分比" v-if="form.quality !== '0'">
                                <el-input type="number" v-model="form.quality_num"></el-input>
                            </el-form-item>

                            <el-form-item>
                                <el-button type="primary" @click="onSubmit">确认</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-col>
                <el-col :sm="8" :md="16">
                    <div class="grid-content"></div>
                </el-col>
            </el-row>

        </el-card>
    </div>

    <style>
        .pos_container {
            width: 220px;
            background: #e9e9e9;
            text-align: center;
        }

        .pos_container td.item {
            background: white;
            height: 60px;
            cursor: pointer;
        }

        .pos_container td.item:hover{
            background: gainsboro;
        }

        .pos_container td.item.current{
            background: #409EFF;
        }

    </style>

    <script>
        $(document).ready(function () {
            new Vue({
                el: '#app',
                data: {
                    form: {
                        id: "{$_GET['id']}",
                        watermarkenable: '0',
                        watermarkminwidth: '300',
                        watermarkminheight: '100',
                        watermarkimg: '',// 系统默认：/statics/images/mark_bai.png
                        watermarkpct: '100',
                        watermarkquality: '100',
                        watermarkpos: '1',
                        pictures_length : '0',
                        pictures_width : '0',
                        title : '',
                        listorder : '50',
                        quality : 0,
                        quality_num : 100
                    }
                },
                watch: {},
                filters: {},
                methods: {
                    onSubmit: function () {
                        $.ajax({
                            url: "{:U('AliyunOss/Style/addEditStyle')}",
                            method: 'post',
                            dataType: 'json',
                            data: this.form,
                            success: function (res) {
                                if (!res.status) {
                                    layer.msg(res.msg)
                                } else {
                                    layer.msg(res.msg)
                                }
                            }
                        })
                    },
                    onCancel: function () {
                        this.$message.error('已取消');
                    },
                    //获取水印配置
                    getWatermarkConfig: function () {
                        var that = this;
                        $.ajax({
                            url: "{:U('AliyunOss/Style/styleDetails')}",
                            data: {
                                'id' : that.form.id
                            },
                            dataType: 'json',
                            type: 'get',
                            success: function (res) {
                                that.form = res.data;
                                that.form.watermarkenable = that.form.watermarkenable + ''
                            }
                        })
                    },
                    selectPos: function(pos){
                        this.form.watermarkpos = pos
                    },
                    gotoUploadFile: function () {
                        layer.open({
                            type: 2,
                            title: '上传图片',
                            content: "{:U('Upload/UploadCenter/imageUploadPanel', ['max_upload' => 1])}",
                            area: ['60%', '50%'],
                        })
                    },
                    onUploadedFile: function (event) {
                        var files = event.detail.files;
                        if (files && files.length > 0) {
                            this.form.watermarkimg = files[0]['url']
                        }
                    },
                    previewWatermark: function (){
                        window.open(this.form.watermarkimg)
                    }
                },
                mounted: function () {
                    window.addEventListener('ZTBCMS_UPLOAD_FILE', this.onUploadedFile.bind(this));
                    if(this.form.id) this.getWatermarkConfig()
                }
            })
        })
    </script>
</block>
