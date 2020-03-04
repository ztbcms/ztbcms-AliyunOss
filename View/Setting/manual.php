<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <el-row>
                <el-col :sm="28" :md="28">
                    <div class="grid-content ">
                        <el-form ref="form" :model="form" label-width="120px">

                            <h3>使用手册</h3>
                            <p>（一）获取到需要填写的 accesskey_id 和 accesskey_secret</p>

                            <img style="width: 100%" src="/app/Application/AliyunOss/Libs/Manual/manual1.png">

                            <p>（二）需要填写的Bucket，支持多Bucket处理</p>
                            <img style="width: 100%" src="/app/Application/AliyunOss/Libs/Manual/manual2.png">

                            <p>（三）设置好镜像回源，建议回源地址填写https</p>
                            <img style="width: 100%" src="/app/Application/AliyunOss/Libs/Manual/manual3.png">

                            <p>（四）设置完镜像回源后，请将外网访问的Bucket域名填写进入附件访问地址</p>
                            <img style="width: 100%" src="/app/Application/AliyunOss/Libs/Manual/manual4.png">

                            <p>（五）后台设置案例： </p>
                            <img style="width: 100%" src="/app/Application/AliyunOss/Libs/Manual/manual5.png">

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

                    }
                },
                watch: {},
                filters: {},
                methods: {

                },
                mounted: function () {

                }
            })
        })
    </script>
</block>
