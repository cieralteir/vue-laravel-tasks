import api from "../utils/api.util";

export default class FileService {
  static read(id) {
    return api.get(`/files/${id}`, { responseType: "blob" });
  }

  static delete(id) {
    return api.delete(`/files/${id}`);
  }
}
