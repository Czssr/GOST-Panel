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
      <el-form
        ref="dataFormRef"
        :rules="rules"
        :model="infoForm"
        label-width="90px"
      >
        <el-form-item label="IP" prop="ip">
          <el-input :disabled="infoForm.id" v-model="infoForm.ip" placeholder="请输入IP" />
        </el-form-item>
        <el-form-item label="数据路径" prop="metrics_prefix">
          <el-input v-model="infoForm.metrics_prefix" placeholder="请输入数据路径，默认: metrics" />
        </el-form-item>
        <el-form-item label="数据端口" prop="metrics_port">
          <el-input v-model="infoForm.metrics_port" placeholder="请输入数据端口，默认: 9000" />
        </el-form-item>
        <el-form-item label="面板路径" prop="panel_prefix">
          <el-input v-model="infoForm.panel_prefix" placeholder="请输入面板端口，默认: api" />
        </el-form-item>
        <el-form-item label="面板端口" prop="panel_port">
          <el-input v-model="infoForm.panel_port" placeholder="请输入面板端口，默认: 18080" />
        </el-form-item>
        <el-form-item label="面板认证" prop="auth">
          <el-input v-model="infoForm.auth" placeholder="请输入认证，例: user:pass" />
        </el-form-item>
        <el-form-item label="流量倍数" prop="multiple">
          <el-input v-model="infoForm.multiple" type="number" placeholder="请输入流量倍数，默认: 1" />
        </el-form-item>
        <el-form-item label="代理端口" prop="port">
          <el-input :disabled="infoForm.id" v-model="infoForm.port" placeholder="中转填写单个端口号 直连填写端口范围1-100" />
        </el-form-item>
        <el-form-item label="备注" prop="remark">
          <el-input v-model="infoForm.remark" placeholder="请输入备注" />
        </el-form-item>
        <el-form-item label="类型" prop="level">
          <el-select :disabled="infoForm.id" v-model="infoForm.type" placeholder="请选择类型">
            <el-option
              v-for="item in typeOptions"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="等级" prop="level">
          <el-select v-model="infoForm.level" placeholder="请选择等级">
            <el-option
              v-for="item in level"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="状态" prop="status">
          <el-select v-model="infoForm.status" placeholder="请选择状态">
            <el-option
              v-for="item in status"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
      </el-form>
    </div>
    <template #footer>
      <span class="dialog-footer">
        <el-button @click="emit('close');">取消</el-button>
        <el-button type="success" @click="save">保存</el-button>
      </span>
    </template>
  </el-dialog>
</template>

<script setup lang="ts">
import { computed, onMounted, reactive, watch, toRefs } from 'vue';
import { createNode, updateNode } from '@/api/node'


const emit = defineEmits(['close', 'success']);

const props = defineProps({
  options: {
    type: Object,
    default() {
      return {}
    }
  },
  data: {
    type: Object,
    default: () => {},
  },
});

const typeOptions = [{
  label: '中转',
  value: 1
},{
  label: '直连',
  value: 2
}]
const level = [{
  label: '1级',
  value: 1
},{
  label: '2级',
  value: 2
},{
  label: '3级',
  value: 3
},{
  label: '4级',
  value: 4
}]

const status = [{
  label: '禁用',
  value: 0
},{
  label: '正常',
  value: 1
}]

const state = reactive({
  rules: {
    ip: [{ required: true, message: '请输入IP', trigger: 'blur' }],
    port: [{ required: true, message: '请输入代理端口', trigger: 'blur' }],
    level: [{ required: true, message: '请选择等级', trigger: 'blur' }],
    status: [{ required: true, message: '请选择状态', trigger: 'blur' }]
  },
});
const { rules } = toRefs(state);

const infoForm: any = computed({
  get: () => props.data,
  set: (value) => {},
});

const save = () => {
  if (infoForm.value.id) {
    updateNode(infoForm.value).then( () => {
      emit('success');
    })
  } else {
    createNode(infoForm.value).then( () => {
      emit('success');
    })
  }
  // console.log(infoForm.value)
}

onMounted(() => {

});
</script>

<style scoped>

</style>
