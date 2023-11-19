import api from "../utils/api.util";

export default class TaskService {
  static list(params = {}) {
    return api.get("/tasks", { params });
  }

  static create(data = {}) {
    return api.post("/tasks", data);
  }

  static read(id, params = {}) {
    return api.get(`/tasks/${id}`, { params });
  }

  static update(id, data = {}, params = {}) {
    return api.put(`/tasks/${id}`, data, { params });
  }

  static delete(id) {
    return api.delete(`/tasks/${id}`);
  }

  static complete(id, params = {}) {
    return api.put(`/tasks/${id}/complete`, null, { params });
  }

  static uncomplete(id, params = {}) {
    return api.put(`/tasks/${id}/uncomplete`, null, { params });
  }

  static archive(id, params = {}) {
    return api.put(`/tasks/${id}/archive`, null, { params });
  }

  static restore(id, params = {}) {
    return api.put(`/tasks/${id}/restore`, null, { params });
  }

  static createFile(id, data = {}, params = {}) {
    return api.post(`/tasks/${id}/files`, data, { params });
  }
}
