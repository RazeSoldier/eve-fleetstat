<template>
    <div>
        <el-table
            :data="tableData"
            border
            stripe>
            <el-table-column
                prop="name"
                label="角色"
                width="220">
            </el-table-column>
            <el-table-column
                prop="corpName"
                label="军团"
                width="180">
            </el-table-column>
            <el-table-column
                prop="allianceName"
                label="联盟">
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    export default {
        props: {
            isDone: Boolean,
            fleetHash: String,
        },
        data() {
            return {
                tableData: [],
            }
        },
        mounted() {
            if (!this.isDone) {
                this.$alert('此分析尚未完成，请稍后刷新页面', '尚未准备完成');
                return;
            }
            fetch('/api/get-fleetmember-list/' + this.fleetHash).then(res => res.json()).then(json => {
                this.tableData = json.data;
            });
        }
    }
</script>

<style scoped>

</style>
