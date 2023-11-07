import api from "../utils/api.util";

export default class AuthService {
  static user(params = {}) {
    return api.get("/user", params);
  }

  static login(data = {}) {
    return api.post("/login", data);
  }

  static register(data = {}) {
    return api.post("/register", data);
  }

  static logout(data = {}) {
    return api.post("/logout", data);
  }
}
