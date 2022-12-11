import request from '@/utils/request';


/**
 * @param params
 */
export function getNodeList(params: any = []) {
  return request({
    url: '/node',
    method: 'get',
    params
  });
}

/**
 * @param nodeId
 */
export function getNodeInfo(nodeId: number) {
  return request({
    url: '/node/' + nodeId,
    method: 'get'
  });
}

/**
 * @param data
 */
export function createNode(data: any) {
  return request({
    url: '/node',
    method: 'post',
    data: data
  });
}

/**
 * @param data
 */
export function updateNode(data: any) {
  return request({
    url: '/node/' + data.id,
    method: 'put',
    data: data
  });
}

/**
 * @param id
 */
export function deleteNode(id: number) {
  return request({
    url: '/node/' + id,
    method: 'delete'
  });
}

/**
 * @param id
 */
export function reloadNode(id: number) {
  return request({
    url: '/node/other/reload/' + id,
    method: 'get'
  });
}
