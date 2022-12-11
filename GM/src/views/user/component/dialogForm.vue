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
        label-width="70px"
      >
        <el-form-item label="用户名" prop="username">
          <el-input v-model="infoForm.username" placeholder="请输入用户名" />
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input v-model="infoForm.password" placeholder="请输入密码，为空则不更改密码" />
        </el-form-item>
        <el-form-item label="昵称" prop="nickname">
          <el-input v-model="infoForm.nickname" placeholder="请输入昵称" />
        </el-form-item>
        <el-form-item label="邮箱" prop="email">
          <el-input v-model="infoForm.email" placeholder="请输入邮箱" />
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
import { createUser, updateUser } from '@/api/user'


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
    username: [{ required: true, message: '请输入用户名', trigger: 'blur' }],
    nickname: [{ required: true, message: '请输入昵称', trigger: 'blur' }],
    email: [{ required: true, message: '请输入邮箱', trigger: 'blur' }],
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
    updateUser(infoForm.value).then( () => {
      emit('success');
    })
  } else {
    createUser(infoForm.value).then( () => {
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
