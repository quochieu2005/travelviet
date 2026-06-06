import axios from "axios";

const API_URL = "http://localhost:8000/api";

const axiosInstance = axios.create({
  baseURL: API_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  }
});

axiosInstance.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

axiosInstance.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export const googleAuth = (token) => {
  return axiosInstance.post('/google-login', { token });
};

export const facebookAuth = (accessToken) => {
  return axiosInstance.post('/facebook-login', { access_token: accessToken });
};

export const register = async (data) => {
  try {
    const response = await axiosInstance.post('/register', data);
    return response;
  } catch (error) {
    throw error;
  }
};

export const login = async (data) => {
  try {
    const response = await axiosInstance.post('/login', data);
    return response;
  } catch (error) {
    throw error;
  }
};

export const getProfile = async () => {
  try {
    const response = await axiosInstance.get('/profile');
    return response;
  } catch (error) {
    throw error;
  }
};

export const updateProfile = async (formData) => {
    try {
        const response = await axiosInstance.post('/profile/update', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });
        return response;
    } catch (error) {
        throw error;
    }
};

export const logout = async () => {
  try {
    const response = await axiosInstance.post('/logout');
    return response;
  } catch (error) {
    throw error;
  }
};

export default axiosInstance;