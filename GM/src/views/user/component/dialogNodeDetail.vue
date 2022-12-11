<template>
  <el-dialog
    :title="options.title"
    :width="options.width"
    :top="options.top"
    :append-to-body="options.inBody ?? true"
    v-model="options.visible"
    draggable
  >
    <div class="app-container">
        <el-table
            v-loading="loading"
            :data="listData"
            stripe
        >
            <el-table-column
                label="ID"
                prop="id"
                align="center"
                width="80"
            />
            <el-table-column
                label="节点ID"
                prop="node_id"
                align="center"
                width="120"
            />
            <el-table-column
                label="节点端口"
                prop="node_port"
                align="center"
                width="120"
            />
            <el-table-column
                label="服务端口"
                prop="server_port"
                align="center"
            />
            <el-table-column
                label="共用流量"
                prop="total_dosage"
                align="center"
            />
            <el-table-column
                label="更新时间"
                prop="updated_at"
                align="center"
                width="180"
            />
            <el-table-column
                label="创建时间"
                prop="created_at"
                align="center"
                width="180"
            />
        </el-table>

        <Pagination
            layout="prev, pager, next"
            :page-sizes="[15, 30, 50, 100]"
            :total="total"
        ></Pagination>
    </div>
    <template #footer>
      <span class="dialog-footer">
        <el-button @click="emit('close');">关闭</el-button>
      </span>
    </template>
  </el-dialog>
</template>

<script setup lang="ts">
import { reactive, watch, toRefs } from 'vue';
import { getPortList } from '@/api/port'


const emit = defineEmits(['close', 'success']);

const props = defineProps({
  options: {
    type: Object,
    default: () => {}
  },
  id: {
    type: Number,
    default: 0,
  },
});

const state = reactive({
    loading: false,
    id: 0,
    total: 0,
    listData: []
});

const { loading, total, listData } = toRefs(state);

const nodeDetail = () => {
    getPortList({user_id: state.id}).then((response:any) => {
        state.total = response.data.total;
        state.listData = response.data.data;
        state.loading = false;
    })
}


watch(() => props.id, (newValue:any) => {
    state.id = newValue;
    nodeDetail();
})
</script>

<style scoped>

</style>
