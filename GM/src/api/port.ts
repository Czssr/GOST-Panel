import request from '@/utils/request';


/**
 * @param params
 */
export function getPortList(params: any = []) {
  return request({
    url: '/port',
    method: 'get',
    params
  });
}

/**
 * @param portId
 */
export function getPortInfo(portId: number) {
  return request({
    url: '/port/' + portId,
    method: 'get'
  });
}

/**
 * @param data
 */
export function createPort(data: any) {
  return request({
    url: '/port',
    method: 'post',
    data: data
  });
}

/**
 * @param data
 */
export function updatePort(data: any) {
  return request({
    url: '/port/' + data.id,
    method: 'put',
    data: data
  });
}

/**
 * @param id
 */
export function deletePort(id: number) {
  return request({
    url: '/port/' + id,
    method: 'delete'
  });
}

