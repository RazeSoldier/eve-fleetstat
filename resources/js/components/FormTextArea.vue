<template>
    <div>
        <el-input
            type="textarea"
            :rows="30"
            placeholder="将舰队频道的所有成员粘贴至此"
            v-model="textarea">
        </el-input>
        <div style="margin: 20px 0;"></div>
        <el-row>
            <el-select v-model="server" placeholder="请选择服务器">
                <el-option
                    v-for="server in servers"
                    :key="server.value"
                    :label="server.label"
                    :value="server.value">
                </el-option>
            </el-select>
            <el-button type="primary" icon="el-icon-upload" @click="onClick"></el-button>
        </el-row>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                textarea: null,
                server: null,
                servers: [
                    {
                        value: 'serenity',
                        label: '晨曦（国服）'
                    },
                    {
                        value: 'tranquility',
                        label: '宁静（欧服）'
                    }
                ],
            };
        },
        methods: {
            onClick() {
                {
                    // 检查输入
                    if (this.textarea === null) {
                        this.$message({
                            message: '文本框没有内容',
                            type: 'warning'
                        });
                        return;
                    }
                    if (this.server === null) {
                        this.$message({
                            message: '未选择服务器',
                            type: 'warning'
                        });
                        return;
                    }
                }
                {
                    // POST代码逻辑块
                    fetch('/api/post-fleetmember-list', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            list: this.textarea,
                            server: this.server,
                        }),
                    }).then(res => res.json()).then(json => {
                        const hash = json.fleet_hash;
                        const link = window.location.origin + '/view/' + hash;
                        this.$alert(
                            '舰队成员列表已提交，请访问' + link.link(link),
                            '舰队成员列表已提交', {
                            dangerouslyUseHTMLString: true
                        });
                        this.textarea = null;
                        this.server = null;
                    });
                }
            }
        }
    }
</script>

<style scoped>

</style>
