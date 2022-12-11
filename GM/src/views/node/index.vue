<template>
  <div class="app-container">
    <el-card>
      <template #header>
        <div class="card-header">
          <el-form-item>
            <el-input v-model="queryParams.ip" placeholder="请输入IP" />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" :icon="Search" @click="handleSearch()">搜索</el-button>
          </el-form-item>
          <el-form-item>
            <el-button type="success" :icon="Plus" @click="handleCreate()">新增</el-button>
          </el-form-item>
        </div>
      </template>

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
          label="IP"
          prop="ip"
          align="center"
          width="130"
        />
        <el-table-column
          label="面板端口"
          prop="panel_port"
          align="center"
          width="120"
        />
        <el-table-column
          label="端口"
          prop="port"
          align="center"
          width="120"
        />
        <el-table-column
          label="认证"
          prop="auth"
          align="center"
          width="120"
        />
        <el-table-column
          label="已用端口"
          prop="has_many_port_count"
          align="center"
          width="120"
        >
          <template #default="scope">
            <el-tag type="success">{{ scope.row.has_many_port_count }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          label="等级"
          prop="level"
          align="center"
          width="80"
        >
          <template #default="scope">
            <el-tag type="success">{{ scope.row.level }}</el-tag>
          </template>
        </el-table-column>
<!--        <el-table-column-->
<!--          label="当前在线"-->
<!--          align="center"-->
<!--          width="80"-->
<!--        >-->
<!--          <template #default="scope">-->
<!--            <el-tag type="success">12</el-tag>-->
<!--          </template>-->
<!--        </el-table-column>-->
        <el-table-column
          label="备注"
          prop="remark"
          align="center"
        />
        <el-table-column
          label="类型"
          prop="type"
          align="center"
          width="80"
        >
          <template #default="scope">
            <el-tag type="success" v-if="scope.row.type === 1">中转</el-tag>
            <el-tag type="success" v-if="scope.row.type === 2">直连</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          label="状态"
          prop="status"
          align="center"
          width="80"
        >
          <template #default="scope">
            <el-tag type="info" v-if="scope.row.status === 0">禁用</el-tag>
            <el-tag type="success" v-if="scope.row.status === 1">正常</el-tag>
          </template>
        </el-table-column>
<!--        <el-table-column-->
<!--          label="更新时间"-->
<!--          prop="updated_at"-->
<!--          align="center"-->
<!--          width="180"-->
<!--        />-->
        <el-table-column
          label="创建时间"
          prop="created_at"
          align="center"
          width="180"
        />
        <el-table-column
          label="操作"
          align="center"
          width="200"
        >
          <template #default="scope">
            <el-button type="success" size="small" @click="handleEdit(scope.row)">编辑</el-button>
            <el-popover trigger="click" :ref="'popoverReloadRef_' + scope.row.id" placement="top" :width="160">
              <p>确认重启此节点?</p>
              <div style="text-align: right; margin: 0">
                <el-button size="small" text @click="handlePopoverReloadCancel(scope.row.id)">取消</el-button>
                <el-button size="small" type="primary" @click="handleReload(scope.row)">确认</el-button >
              </div>
              <template #reference>
                <el-button type="warning" size="small">重启</el-button>
              </template>
            </el-popover>
            <el-popover trigger="click" :ref="'popoverRef_' + scope.row.id" placement="top" :width="160">
              <p>确认删除此节点?</p>
              <div style="text-align: right; margin: 0">
                <el-button size="small" text @click="handlePopoverCancel(scope.row.id)">取消</el-button>
                <el-button size="small" type="primary" @click="handleDel(scope.row)">确认</el-button >
              </div>
              <template #reference>
                <el-button type="danger" size="small">删除</el-button>
              </template>
            </el-popover>
          </template>
        </el-table-column>
      </el-table>

      <Pagination
        layout="prev, pager, next"
        :page-sizes="[15, 30, 50, 100]"
        :total="total"
      ></Pagination>
    </el-card>

    <DialogForm :options="dialogFormOptions" :data="dialogFormData" @close="getListData" @success="getListData" />

  </div>
</template>

<script setup lang="ts">
import {onMounted, getCurrentInstance, ComponentInternalInstance, reactive, ref, toRefs} from "vue";
import {
  Search,
  Plus
} from '@element-plus/icons-vue';
import DialogForm from './component/dialogForm.vue'
import {getNodeList, deleteNode, reloadNode} from "@/api/node";

const { proxy } = getCurrentInstance() as any;

let dialogFormOptions = reactive({
  title: '节点',
  width: '500px',
  visible: false
})
let dialogFormData = ref({})

const state = reactive({
  // 遮罩层
  loading: true,
  total: 0,
  listData: [],
  queryParams: {
    page: 1,
    limit: 15,
    ip: '',
    status: '',
  }
});

const { loading, listData, total, queryParams } = toRefs(state);

const getListData = () => {
  dialogFormOptions.visible = false;
  state.loading = true;
  getNodeList(state.queryParams).then(response => {
    // console.log(response);
    state.total = response.data.total;
    state.listData = response.data.data;
    state.loading = false;
  })
}

const handleCreate = (info: any = {
  ip: '',
  metrics_prefix: '',
  metrics_port: '',
  panel_prefix: '',
  panel_port: '',
  port: '',
  auth: '',
  multiple: '',
  remark: '',
  type: 2,
  level: 1,
  status: 1,
}) => {
  dialogFormData.value = {...info};
  dialogFormOptions.visible = true;
}

const handleEdit = (info: any) => {
  dialogFormData.value = {...info};
  dialogFormOptions.visible = true;
}

const handlePopoverCancel = (id: number) => {
  proxy.$refs[`popoverRef_${id}`].hide();
}
const handlePopoverReloadCancel = (id: number) => {
  proxy.$refs[`popoverReloadRef_${id}`].hide();
}

const handleReload = (info: any) => {
  reloadNode(info.id).then(() => {
    handlePopoverReloadCancel(info.id);
    getListData();
  })
}

const handleDel = (info: any) => {
  deleteNode(info.id).then(() => {
    getListData()
  })
}

const handleSearch = () => {
  state.queryParams.page = 1;
  getListData();
}

onMounted(() => {
  getListData();
});
</script>

<style lang="scss" scoped>

</style>
