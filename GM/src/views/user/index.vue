<template>
  <div class="app-container">
    <el-card>
      <template #header>
        <div class="card-header">
          <el-form-item>
            <el-input v-model="queryParams.user_id" placeholder="请输入用户ID" />
          </el-form-item>
          <el-form-item>
            <el-input v-model="queryParams.username" placeholder="请输入用户名" />
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
          label="头像"
          prop="avatar"
          align="center"
          width="100"
        >
          <template #default="scope">
            <el-avatar :size="60" :src="scope.row.avatar_url">
              <img src="https://cube.elemecdn.com/e/fd/0fc7d20532fdaf769a25683617711png.png" />
            </el-avatar>
          </template>
        </el-table-column>
        <el-table-column
          label="用户名"
          prop="username"
          align="center"
          width="120"
        />
        <el-table-column
          label="昵称"
          prop="nickname"
          align="center"
          width="120"
        />
        <el-table-column
          label="邮箱"
          prop="email"
          align="center"
        />
        <el-table-column
          label="凭证"
          prop="auth"
          align="center"
        />
        <el-table-column
          label="本月流量"
          align="center"
          width="160"
        >
          <template #default="scope">
            <el-tag type="success">{{ scope.row.traffic ?? 0 }}GB / {{ scope.row.flow_sum ?? 0 }}GB</el-tag>
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
            <el-button type="success" size="small" @click="handleEdit(scope.row)">编辑</el-button>
            <el-button type="primary" size="small" @Click="handleNodeDetail(scope.row.id)">节点详情</el-button>
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
    <DialogNodeDetail :options="dialogNodeDetailOptions" :id="dialogNodeDetailData" @close="getListData" @success="getListData" />

  </div>
</template>

<script setup lang="ts">
import {onMounted, reactive, ref, toRefs} from "vue";
import { Search, Plus } from '@element-plus/icons-vue';
import DialogForm from './component/dialogForm.vue'
import DialogNodeDetail from './component/dialogNodeDetail.vue'
import { getUserList } from "@/api/user";

let dialogFormOptions = reactive({
  title: '用户',
  width: '500px',
  visible: false
})
let dialogFormData = ref({})

let dialogNodeDetailOptions = reactive({
  title: '节点详情',
  width: '900px',
  visible: false
})
let dialogNodeDetailData = ref(0)


const state = reactive({
  // 遮罩层
  loading: true,
  total: 0,
  listData: [],
  queryParams: {
    page: 1,
    limit: 15,
    user_id: '',
    username: '',
    level: '',
    status: '',
  }
});

const { loading, listData, total, queryParams } = toRefs(state);

const getListData = () => {
  dialogFormOptions.visible = false;
  dialogNodeDetailOptions.visible = false;
  state.loading = true;
  getUserList(state.queryParams).then((response:any) => {
    state.total = response.data.total;
    state.listData = response.data.data;
    state.loading = false;
  })
}



const handleCreate = (info: any = {
  username: '',
  password: '',
  nickname: '',
  email: '',
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

const handleNodeDetail = (id: number) => {
  dialogNodeDetailData.value = id;
  dialogNodeDetailOptions.visible = true;
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
