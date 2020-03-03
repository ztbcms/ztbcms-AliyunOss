<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <el-row>
                <el-col :sm="32" :md="28">
                    <div class="grid-content ">
                        <el-form ref="form" :model="form" label-width="120px">

                            <h3>必填参数</h3>

                            <el-form-item label="accesskey_id" required>
                                <el-input v-model="form.accesskey_id"></el-input>
                            </el-form-item>

                            <el-form-item label="accesskey_secret" required>
                                <el-input v-model="form.accesskey_secret"></el-input>
                            </el-form-item>

                            <el-form-item label="有效期（毫秒）" required>
                                <el-input v-model="form.validity"></el-input>
                            </el-form-item>

                            <el-form-item label="Bucket地址" required>
                                <div>
                                    <el-input v-model="host"  style="width: 300px;"  size="small"  placeholder="host"></el-input>
                                    <el-input v-model="bucket" style="width: 300px;" size="small" placeholder="bucket"></el-input>
                                    <el-input v-model="endpoint" style="width: 300px;" size="small" placeholder="endpoint"></el-input>
                                    <el-button type="primary" size="small" @click="getBucket()">确定</el-button>
                                </div>

                                <div style="margin-top: 5px;">
                                    <el-row style="margin-top: 5px;" v-for="(item,k) in form.bucket">
                                        <el-input v-model="item.host"  style="width: 300px;"  size="small"  placeholder="host"></el-input>
                                        <el-input v-model="item.bucket" style="width: 300px;" size="small" placeholder="bucket"></el-input>
                                        <el-input v-model="item.endpoint" style="width: 300px;" size="small" placeholder="endpoint"></el-input>
                                        <el-button class="el-button el-button--danger" size="small" @click="delBucket(k)">删除</el-button>
                                    </el-row>
                                </div>
                            </el-form-item>

                            <el-form-item>
                                <el-button type="primary" @click="onSubmit">保存设置</el-button>
                            </el-form-item>
                        </el-form>


                        <el-form ref="form" :model="simulation" label-width="120px">

                            <h3>模拟测试</h3>

                            <el-form-item label="url" required>
                                <el-input v-model="url"></el-input>
                                <br>
                                <span>需要使用url，非OSS的url直接返回</span>
                            </el-form-item>

                            <el-form-item label="style_id" required>
                                <el-input v-model="style_id"></el-input>
                                <br>
                                <span>样式id</span>
                            </el-form-item>

                            <el-form-item v-if="view_url" label="解密后的文件" required>
                                <el-input v-model="view_url"></el-input>
                            </el-form-item>

                            <el-form-item>
                                <el-button type="primary" @click="simulationSubmit">模拟测试</el-button>
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
                        accesskey_id : '',
                        accesskey_secret : '',
                        bucket : [],
                        validity :'3600'
                    },
                    host: '',
                    bucket : '',
                    endpoint : '',
                    url : 'https://shidiaoquan-online.oss-cn-shenzhen.aliyuncs.com/d/file/module_upload_images/2019/11/5dc61096bc821.jpg',
                    style_id : '0',
                    view_url : ''
                },
                watch: {},
                filters: {},
                methods: {
                    getBucket:function () {
                        var data = {
                            host : this.host,
                            bucket : this.bucket,
                            endpoint : this.endpoint
                        };
                        this.host = '';
                        this.bucket = '';
                        this.endpoint = '';
                        this.form.bucket.push(data);
                    },
                    delBucket:function (k) {
                        this.form.bucket.splice(k,1);
                    },
                    onSubmit: function () {
                        $.ajax({
                            url: "{:U('AliyunOss/Setting/addEditSetting')}",
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
                    simulationSubmit:function () {
                        var that = this;
                        that.view_url = '';
                        $.ajax({
                            url: "{:U('AliyunOss/Setting/urlDecryption')}",
                            method: 'post',
                            dataType: 'json',
                            data: {
                                url : that.url,
                                style_id : that.style_id
                            },
                            success: function (res) {
                                that.view_url = res.data;
                            }
                        })
                    },
                    onCancel: function () {
                        this.$message.error('已取消');
                    },
                    //获取水印配置
                    getDetails: function () {
                        var that = this;
                        $.ajax({
                            url: "{:U('AliyunOss/Setting/setting')}",
                            data: {
                                'id' : that.form.id
                            },
                            dataType: 'json',
                            type: 'get',
                            success: function (res) {
                                that.form = res.data;
                            }
                        })
                    }
                },
                mounted: function () {
                    this.getDetails()
                }
            })
        })
    </script>
</block>
