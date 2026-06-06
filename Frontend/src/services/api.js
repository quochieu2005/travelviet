import axios from "axios";

const api = axios.create({
  baseURL: "http://localhost:8000/api",
  headers: {
    "Content-Type": "application/json",
  },
});

export const get = async (endpoint) => {
  const res = await api.get(endpoint);
  return res.data;
};

export const post = async (endpoint, body) => {
  const res = await api.post(endpoint, body);
  return res.data;
};

export default api;