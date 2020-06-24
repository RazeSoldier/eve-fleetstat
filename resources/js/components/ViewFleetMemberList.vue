<template>
    <el-row>
        <el-col class="col-md-8">
            <div class="card">
                <div class="card-header">舰队成员列表</div>
                <div class="card-body">
                    <el-table
                        :data="listTableData"
                        border
                        stripe
                        :default-sort = "{prop: 'name', order: 'ascending'}">
                        <el-table-column
                            prop="name"
                            label="角色"
                            sortable
                            width="220">
                        </el-table-column>
                        <el-table-column
                            prop="corpName"
                            label="军团"
                            sortable
                            width="180">
                        </el-table-column>
                        <el-table-column
                            prop="allianceName"
                            label="联盟"
                            sortable>
                        </el-table-column>
                    </el-table>
                </div>
            </div>
        </el-col>
        <el-col class="col-md-4 right">
            <el-card class="box-card">
                <div slot="header" class="clearfix">
                    <span>统计</span>
                </div>
                <div class="item">
                    <el-table
                        :data="corpTableData"
                        style="width: 100%"
                        :default-sort = "{prop: 'size', order: 'descending'}"
                        height="400">
                        <el-table-column
                            prop="name"
                            label="军团"
                            width="400"
                            sortable>
                        </el-table-column>
                        <el-table-column
                            prop="size"
                            label="人数"
                            sortable>
                        </el-table-column>
                    </el-table>
                </div>
                <div class="item">
                    <el-table
                        :data="allianceTableData"
                        style="width: 100%"
                        :default-sort = "{prop: 'size', order: 'descending'}"
                        height="300">
                        <el-table-column
                            prop="name"
                            label="联盟"
                            width="400"
                            sortable>
                        </el-table-column>
                        <el-table-column
                            prop="size"
                            label="人数"
                            sortable>
                        </el-table-column>
                    </el-table>
                </div>
            </el-card>
        </el-col>
    </el-row>
</template>

<script>
    export default {
        props: {
            isDone: Boolean,
            fleetHash: String,
        },
        data() {
            return {
                listTableData: [],
                corpTableData: [],
                allianceTableData: [],
            }
        },
        mounted() {
            if (!this.isDone) {
                this.$alert('此分析尚未完成，请稍后刷新页面', '尚未准备完成');
                return;
            }
            fetch('/api/get-fleetmember-list/' + this.fleetHash).then(res => res.json()).then(json => {
                this.listTableData = json.data;
                const corpMap = {};
                const allianceMap = {};
                for (const key in this.listTableData) {
                    if (!this.listTableData.hasOwnProperty(key)) {
                        continue;
                    }
                    const corp = this.listTableData[key].corpName;
                    if (corpMap.hasOwnProperty(corp)) {
                        corpMap[corp]++;
                    } else {
                        corpMap[corp] = 1;
                    }
                    const alliance = this.listTableData[key].allianceName;
                    if (allianceMap.hasOwnProperty(alliance)) {
                        allianceMap[alliance]++;
                    } else {
                        allianceMap[alliance] = 1;
                    }
                }
                for (const key in corpMap) {
                    this.corpTableData.push({name: key, size: corpMap[key]});
                }
                for (const key in allianceMap) {
                    this.allianceTableData.push({name: key, size: allianceMap[key]});
                }
            });
        }
    }
</script>

<style scoped>

</style>
