import axios from 'axios';

const API_BASE_URL = 'https://api.travelviet.duckdns.org/api'; 

export const tourService = {
    // CHỈ DÙNG 1 HÀM DUY NHẤT để lấy tất cả dữ liệu
    getHomeData: async () => {
        try {
            const response = await axios.get(`${API_BASE_URL}/home`);
            return response.data;
        } catch (error) {
            console.error('Error fetching home data:', error);
            throw error;
        }
    },

};