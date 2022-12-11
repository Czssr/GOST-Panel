import request from '@/utils/request';


/**
 * 获取用户分页列表
 *
 * @param queryParams
 */
export function getUserList(params: any = []) {
  return request({
    url: '/user',
    method: 'get',
    params
  });
}

/**
 * 获取用户表单详情
 *
 * @param userId
 */
export function getUserInfo(userId: number) {
  return request({
    url: '/user/' + userId,
    method: 'get'
  });
}

/**
 * 添加用户
 *
 * @param data
 */
export function createUser(data: any) {
  return request({
    url: '/user',
    method: 'post',
    data
  });
}

/**
 * 修改用户
 *
 * @param data
 */
export function updateUser(data: any) {
  return request({
    url: '/user/' + data.id,
    method: 'put',
    data
  });
}

/**
 * 删除用户
 *
 * @param ids
 */
export function deleteUsers(ids: string) {
  return request({
    url: '/user/' + ids,
    method: 'delete'
  });
}
