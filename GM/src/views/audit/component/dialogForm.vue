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
        <el-form-item label="URL" prop="url">
          <el-input v-model="infoForm.url" placeholder="请输URL，支持*号泛解析" />
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
import { createAudit, updateAudit } from '@/api/audit'


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


const state = reactive({
  rules: {
    url: [{ required: true, message: '请输入URL', trigger: 'blur' }]
  },
});
const { rules } = toRefs(state);

const infoForm: any = computed({
  get: () => props.data,
  set: (value) => {},
});

const save = () => {
  if (infoForm.value.id) {
    updateAudit(infoForm.value).then( () => {
      emit('success');
    })
  } else {
    createAudit(infoForm.value).then( () => {
      emit('success');
    })
  }
}

onMounted(() => {

});
</script>

<style scoped>

</style>
