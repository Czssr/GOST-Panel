import { defineStore } from 'pinia';
import { UserState } from './types';

import { localStorage } from '@/utils/storage';
import { loginApi, logoutApi, getUserInfo } from '@/api/login';
import { resetRouter } from '@/router';
import { LoginForm } from '@/api/auth/types';

const useUserStore = defineStore({
  id: 'user',
  state: (): UserState => ({
    token: localStorage.get('token') || '',
    nickname: '',
    avatar: ''
  }),
  actions: {
    async RESET_STATE() {
      this.$reset();
    },
    /**
     * 登录
     */
    login(data: LoginForm) {
      const { username, password } = data;
      return new Promise((resolve, reject) => {
        loginApi({
          username: username.trim(),
          password: password
        })
          .then(response => {
            const accessToken = response.data;
            localStorage.set('token', accessToken);
            this.token = accessToken;
            resolve(accessToken);
          })
          .catch(error => {
            reject(error);
          });
      });
    },
    /**
     *  获取用户信息（昵称、头像、角色集合、权限集合）
     */
    getUserInfo() {
      return new Promise((resolve, reject) => {
        getUserInfo()
          .then(({ data }) => {
            if (!data) {
              return reject('Verification failed, please Login again.');
            }
            const { nickname, avatar } = data;
            this.nickname = nickname;
            this.avatar = avatar;
            resolve(data);
          })
          .catch(error => {
            reject(error);
          });
      });
    },

    /**
     *  注销
     */
    logout() {
      return new Promise((resolve, reject) => {
        logoutApi()
          .then(() => {
            localStorage.remove('token');
            this.RESET_STATE();
            resetRouter();
            resolve(null);
          })
          .catch(error => {
            reject(error);
          });
      });
    },

    /**
     * 清除 Token
     */
    resetToken() {
      return new Promise(resolve => {
        localStorage.remove('token');
        this.RESET_STATE();
        resolve(null);
      });
    }
  }
});

export default useUserStore;
