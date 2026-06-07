import axios from 'axios'

const API_URL = 'http://localhost:8000/api'

export const getTours = (filters = {}) => {
    return axios.get(`${API_URL}/tours`, { params: filters })
}

export const getTourBySlug = (slug) => {
    return axios.get(`${API_URL}/tours/${slug}`)
}