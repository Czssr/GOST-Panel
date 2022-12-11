<template>
  <div class="component-container">
    <el-card class="project-card">
      <template #header>
        <span class="fw-b">GOST-Panel 项目简介</span>
      </template>
      <div class="project-card__main">
        <!-- 项目简介 -->
        <el-link target="_blank" type="primary" href="https://github.com/Czssr/GOST-Panel">
          GOST-Panel
        </el-link>
        是基于GOST V3、Vue3、Element-Plus、Laravel等主流技术栈构建的一整套全栈管理面板项目。

        <!-- 源码地址 -->
        <el-row :gutter="10">
          <el-col :span="6">
            <el-badge value="免费开源" class="fw-b"> 项目地址 </el-badge>
          </el-col>
          <el-col :span="6">
            <el-link
              target="_blank"
              type="warning"
              href="https://github.com/Czssr/GOST-Panel"
              >官方文档</el-link
            >
          </el-col>
          <el-col :span="6">
            <el-link
              target="_blank"
              type="primary"
              href="https://github.com/Czssr/GOST-Panel"
              >Github</el-link
            >
          </el-col>
        </el-row>
        <el-divider />
        <!-- 技术栈 -->
        <el-row :gutter="10">
          <el-col :span="6" class="fw-b"> 后端技术栈 </el-col>
          <el-col :span="18">
            Laravel、GOST V3
          </el-col>
        </el-row>
        <el-divider />
        <el-row :gutter="10">
          <el-col :span="6" class="fw-b"> 前端技术栈 </el-col>
          <el-col :span="18">
            Vue3、TypeScript、Element-Plus、vue3-element-admin
          </el-col>
        </el-row>
      </div>
    </el-card>
  </div>
</template>

<script lang="ts">
export default {
  name: 'index'
};
</script>

<style lang="scss" scoped>
.component-container {
  .project-card {
    font-size: 14px;

    &__main {
      line-height: 28px;
      height: 320px;
      overflow-y: auto;
      overflow-x: hidden;
    }
  }

  .fw-b {
    font-weight: bold;
  }
}
</style>
