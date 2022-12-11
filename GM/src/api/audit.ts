import request from '@/utils/request';


/**
 * @param params
 */
export function getAuditList(params: any = []) {
  return request({
    url: '/audit',
    method: 'get',
    params
  });
}

/**
 * @param id
 */
export function getAuditInfo(id: number) {
  return request({
    url: '/audit/' + id,
    method: 'get'
  });
}

/**
 * @param data
 */
export function createAudit(data: any) {
  return request({
    url: '/audit',
    method: 'post',
    data: data
  });
}

/**
 * @param data
 */
export function updateAudit(data: any) {
  return request({
    url: '/audit/' + data.id,
    method: 'put',
    data: data
  });
}

/**
 * @param id
 */
export function deleteAudit(id: number) {
  return request({
    url: '/audit/' + id,
    method: 'delete'
  });
}
