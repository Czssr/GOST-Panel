<template>
  <div class="app-container">
    <el-card>
      <template #header>
        <div class="card-header">
          <el-form-item>
            <el-input v-model="queryParams.url" placeholder="请输入链接" />
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
          label="URL"
          prop="url"
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
        <el-table-column
          label="操作"
          align="center"
          width="160"
        >
          <template #default="scope">
            <el-popover trigger="click" :ref="'popoverRef_' + scope.row.id" placement="top" :width="160">
              <p>确认删除此审计?</p>
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
import {onMounted, getCurrentInstance, reactive, ref, toRefs, ComponentInternalInstance} from "vue";
import {
  Search,
  Plus
} from '@element-plus/icons-vue';
import DialogForm from './component/dialogForm.vue'
import { getAuditList, deleteAudit } from "@/api/audit";

const { proxy } = getCurrentInstance() as any;

let dialogFormOptions = reactive({
  title: '审计',
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
    url: '',
  }
});

const { loading, listData, total, queryParams } = toRefs(state);

const getListData = () => {
  dialogFormOptions.visible = false;
  state.loading = true;
  getAuditList(state.queryParams).then(response => {
    // console.log(response);
    state.total = response.data.total;
    state.listData = response.data.data;
    state.loading = false;
  })
}

const handleCreate = (info: any = {
  url: '',
}) => {
  dialogFormData.value = {...info};
  dialogFormOptions.visible = true;
}


const handlePopoverCancel = (id: number) => {
  proxy.$refs[`popoverRef_${id}`].hide();
}

const handleDel = (info: any) => {
  deleteAudit(info.id).then(() => {
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
