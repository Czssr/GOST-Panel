import axios, { AxiosRequestConfig, AxiosResponse } from 'axios';
import { ElMessage, ElMessageBox } from 'element-plus';
import { localStorage } from '@/utils/storage';
import useStore from '@/store';

let VITE_APP_BASE_API = ''
if (import.meta.env.VITE_USER_NODE_ENV == 'development') {
  VITE_APP_BASE_API = import.meta.env.VITE_APP_BASE_API;
} else {
  VITE_APP_BASE_API = window.g.BASE_API;
}

// 创建 axios 实例
const service = axios.create({
  baseURL: VITE_APP_BASE_API,
  timeout: 50000,
  headers: { 'Content-Type': 'application/json;charset=utf-8' }
});

// 请求拦截器
service.interceptors.request.use(
  (config: AxiosRequestConfig) => {
    if (!config.headers) {
      throw new Error(
        `Expected 'config' and 'config.headers' not to be undefined`
      );
    }
    // console.log(config.baseURL)
    const { user } = useStore();
    if (user.token) {
      config.headers.Authorization = `${localStorage.get('token')}`;
    }
    return config;
  },
  (error: any) => {
    return Promise.reject(error);
  }
);

// 响应拦截器
service.interceptors.response.use(
  (response: AxiosResponse) => {
    const { code, msg } = response.data;
    if (code === 200) {
      return response.data;
    } else {
      // 响应数据为二进制流处理(Excel导出)
      if (response.data instanceof ArrayBuffer) {
        return response;
      }

      ElMessage({
        message: msg || '系统出错',
        type: 'error'
      });
      return Promise.reject(new Error(msg || 'Error'));
    }
  },
  (error: any) => {
    console.log('errorinfo', error);
    if (error.response.status === 401) {
      localStorage.clear();
      window.location.href = '/';
      return;
    }
    if (error.response.data) {
      const { code, message } = error.response.data;
      // console.log(code)
      // console.log(message)
      // token 过期,重新登录
      if (code === '401') {
        ElMessageBox.confirm('当前页面已失效，请重新登录', '提示', {
          confirmButtonText: 'OK',
          type: 'warning'
        }).then(() => {
          localStorage.clear();
          window.location.href = '/';
        });
      } else if(code === '400') {
        ElMessage({
          message: message || '系统出错',
          type: 'error'
        });
      } else {
        ElMessage({
          message: message || '系统出错',
          type: 'error'
        });
      }
    }
    return Promise.reject(error.message);
  }
);

// 导出 axios 实例
export default service;
