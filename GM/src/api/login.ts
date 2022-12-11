import request from '@/utils/request';

/**
 *
 * @param data {LoginForm}
 * @returns
 */
export function loginApi(data: any) {
  return request({
    url: '/login',
    method: 'post',
    params: data,
  });
}

/**
 * 注销
 */
export function logoutApi() {
  return request({
    url: '/logout',
    method: 'post'
  });
}


/**
 * 登录成功后获取用户信息（昵称、头像、权限集合和角色集合）
 */
export function getUserInfo() {
  return request({
    url: '/info',
    method: 'get'
  });
}
