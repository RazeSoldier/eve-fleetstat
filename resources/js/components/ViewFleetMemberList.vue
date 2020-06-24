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
                        :default-sort = "{prop: 'size', order: 'descending'}">>
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
                }
                for (const key in corpMap) {
                    this.corpTableData.push({name: key, size: corpMap[key]});
                }
            });
        }
    }
</script>

<style scoped>

</style>
